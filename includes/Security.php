<?php
class Security {
    public static function checkCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception('CSRF token không hợp lệ');
            }
        }
    }

    public static function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function sanitizeInput($data) {
        return Utility::sanitize($data);
    }

    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePhone($phone) {
        return preg_match('/^[0-9]{10,11}$/', $phone);
    }

    public static function validatePassword($password) {
        // Ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
    }

    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    public static function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['return_to'] = Utility::getCurrentUrl();
            Utility::redirect('/login.php');
        }
    }

    public static function requireAdmin() {
        if (!isset($_SESSION['admin_id'])) {
            Utility::redirect('/admin/login.php');
        }
    }

    public static function generateRandomString($length = 10) {
        return bin2hex(random_bytes($length));
    }

    public static function rateLimit($key, $limit = 5, $seconds = 60) {
        $attempts = isset($_SESSION[$key]) ? $_SESSION[$key] : [];
        $attempts = array_filter($attempts, function($time) use ($seconds) {
            return $time > time() - $seconds;
        });
        
        if (count($attempts) >= $limit) {
            throw new Exception('Quá nhiều yêu cầu, vui lòng thử lại sau');
        }
        
        $attempts[] = time();
        $_SESSION[$key] = $attempts;
    }

    public static function validateFile($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'], $maxSize = 5000000) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            throw new Exception('Không có file được upload');
        }

        $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Loại file không được phép');
        }

        if ($file['size'] > $maxSize) {
            throw new Exception('File quá lớn');
        }

        if (!getimagesize($file['tmp_name'])) {
            throw new Exception('File không phải là ảnh');
        }

        return true;
    }
}