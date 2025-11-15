<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load required files
$userModelPath = __DIR__ . '/../models/User.php';
$authHelperPath = __DIR__ . '/../../src/Helpers/Auth.php';

if (!file_exists($userModelPath)) {
    throw new Exception("Không tìm thấy file User.php tại: " . $userModelPath);
}
if (!file_exists($authHelperPath)) {
    throw new Exception("Không tìm thấy file Auth.php tại: " . $authHelperPath);
}

require_once $userModelPath;
require_once $authHelperPath;

// Load PHPMailer
$mailerPath = __DIR__ . '/../../mail/Mailer.php';
if (!file_exists($mailerPath)) {
    throw new Exception("Không tìm thấy file Mailer.php tại: " . $mailerPath);
}
require_once $mailerPath;

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function register($data) {
        try {
            // Validate required fields
            $required = ['name', 'email', 'phone', 'address', 'password'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Vui lòng điền đầy đủ thông tin bắt buộc");
                }
            }

            // Register user
            $result = $this->userModel->register(
                trim($data['name']),
                trim($data['email']),
                trim($data['phone']),
                trim($data['address']),
                $data['password']
            );

            // Prepare OTP email
            $subject = "Xác thực email của bạn";
            $body = $this->getOTPEmailTemplate($data['name'], $result['otp']);
            
            // Send OTP email
            try {
                $mailer = new Mailer();
                $mailer->dathangmail($subject, $body, $data['email'], $data['name']);
                
                // Log successful email sending
                error_log("OTP email sent to: " . $data['email']);
                
            } catch (Exception $e) {
                // Log the error but don't fail registration
                error_log("Failed to send OTP email to " . $data['email'] . ": " . $e->getMessage());
                
                // You can choose to continue registration even if email fails
                // or uncomment the line below to make email sending mandatory
                // throw new Exception("Lỗi khi gửi email xác thực: " . $e->getMessage());
            }

            return [
                'success' => true,
                'userId' => $result['id'],
                'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function verifyOTP($userId, $otp) {
        try {
            if ($this->userModel->verifyOTP($userId, $otp)) {
                return [
                    'success' => true,
                    'message' => 'Xác thực thành công! Bạn có thể đăng nhập ngay bây giờ.'
                ];
            }
            throw new Exception('Mã OTP không hợp lệ hoặc đã hết hạn');
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function getOTPEmailTemplate($name, $otp) {
        return "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2>Xác thực tài khoản</h2>
                <p>Xin chào $name,</p>
                <p>Mã xác thực của bạn là: <strong>$otp</strong></p>
                <p>Mã có hiệu lực trong vòng 10 phút.</p>
                <p>Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.</p>
                <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
            </div>
        ";
    }
}