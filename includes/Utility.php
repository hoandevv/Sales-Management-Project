<?php
class Utility {
    public static function generateSlug($str) {
        $str = mb_strtolower(trim($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

    public static function formatMoney($amount) {
        return number_format($amount, 0, ',', '.') . 'đ';
    }

    public static function formatDate($date) {
        return date('d/m/Y H:i', strtotime($date));
    }

    public static function uploadImage($file, $folder = 'uploads') {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            return null;
        }

        $targetDir = __DIR__ . "/../{$folder}/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;
        
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // Kiểm tra file có phải là ảnh
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            throw new Exception('File không phải là ảnh');
        }
        
        // Kiểm tra định dạng file
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new Exception('Chỉ chấp nhận file JPG, JPEG, PNG & GIF');
        }
        
        // Kiểm tra kích thước file
        if ($file['size'] > 5000000) {
            throw new Exception('File không được lớn hơn 5MB');
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $fileName;
        }
        
        throw new Exception('Có lỗi khi upload file');
    }

    public static function deleteImage($fileName, $folder = 'uploads') {
        if (empty($fileName)) {
            return true;
        }

        $filePath = __DIR__ . "/../{$folder}/" . $fileName;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return true;
    }

    public static function sendMail($to, $subject, $content) {
        require_once __DIR__ . '/../mail/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../mail/PHPMailer/src/SMTP.php';
        require_once __DIR__ . '/../mail/PHPMailer/src/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'] ?? '';
            $mail->Password = $_ENV['SMTP_PASS'] ?? '';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $_ENV['SMTP_PORT'] ?? 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($_ENV['MAIL_FROM'] ?? '', $_ENV['MAIL_FROM_NAME'] ?? '');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $content;

            return $mail->send();
        } catch (Exception $e) {
            throw new Exception('Lỗi gửi mail: ' . $mail->ErrorInfo);
        }
    }

    public static function generateToken() {
        return bin2hex(random_bytes(32));
    }

    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags($data));
    }

    public static function redirect($url) {
        header("Location: $url");
        exit;
    }

    public static function getCurrentUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return "$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}