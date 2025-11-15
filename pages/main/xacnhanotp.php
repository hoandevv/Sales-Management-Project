<?php
// Bắt đầu output buffering nếu chưa được bắt đầu
if (ob_get_level() == 0) {
    ob_start();
}

// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bắt đầu session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Xử lý redirect trước bất kỳ output nào
function safe_redirect($url) {
    if (!headers_sent()) {
        header('Location: ' . $url);
    } else {
        echo '<script>window.location.href="' . $url . '";</script>';
    }
    exit();
}

// Kiểm tra xem người dùng đã đăng ký chưa
if (!isset($_SESSION['verify_user_id']) || !isset($_SESSION['register_email'])) {
    safe_redirect('dangki.php');
}

// Khởi tạo biến
$error = '';
$success = '';

// Kết nối database và xử lý OTP
try {
    // Kết nối database
    $configPath = __DIR__ . '/../../src/Config/config.php';
    if (!file_exists($configPath)) {
        throw new Exception("Không tìm thấy file cấu hình");
    }
    require_once $configPath;

    if (!isset($mysqli) || $mysqli->connect_error) {
        throw new Exception("Lỗi kết nối database");
    }

    // Include AuthController
    $authControllerPath = __DIR__ . '/../../includes/controllers/AuthController.php';
    if (!file_exists($authControllerPath)) {
        throw new Exception("Không tìm thấy AuthController.php");
    }
    require_once $authControllerPath;
    $authController = new AuthController($mysqli);

    // Xử lý khi người dùng gửi mã OTP
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
        $otp = trim($_POST['otp'] ?? '');
        $userId = $_SESSION['verify_user_id'];

        if (empty($otp)) {
            $error = 'Vui lòng nhập mã OTP';
        } else {
            $result = $authController->verifyOTP($userId, $otp);
            if ($result['success']) {
                $success = $result['message'];
                // Lưu thông báo thành công vào session
                $_SESSION['success_message'] = $result['message'];
                
                // Xóa session xác thực
                unset($_SESSION['verify_user_id']);
                unset($_SESSION['register_email']);
                
                // Chuyển hướng đến trang đăng nhập
                safe_redirect('index.php?quanly=dangnhap');
            } else {
                $error = $result['message'];
            }
        }
    }
} catch (Exception $e) {
    $error = 'Lỗi hệ thống: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .otp-card {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .otp-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .otp-body {
            padding: 30px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-verify {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }
        .btn-verify:hover {
            opacity: 0.9;
        }
        .otp-input {
            letter-spacing: 5px;
            font-size: 24px;
            text-align: center;
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="otp-card">
            <div class="otp-header">
                <i class="fas fa-shield-alt fa-3x mb-3"></i>
                <h3>Xác thực OTP</h3>
                <p class="mb-0">Nhập mã xác thực đã gửi đến email của bạn</p>
                <p class="fw-bold mb-0"><?php echo isset($_SESSION['register_email']) ? htmlspecialchars($_SESSION['register_email']) : ''; ?></p>
            </div>

            <div class="otp-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($success); ?>
                        <p class="mb-0 mt-2">
                            <small>Đang chuyển hướng đến trang đăng nhập...</small>
                        </p>
                    </div>
                <?php else: ?>
                    <form method="post" action="">
                        <div class="mb-4">
                            <label for="otp" class="form-label">Mã xác thực (OTP)</label>
                            <input type="text" 
                                   class="form-control form-control-lg otp-input" 
                                   id="otp" 
                                   name="otp" 
                                   placeholder="Nhập mã OTP" 
                                   maxlength="6"
                                   pattern="\d{6}"
                                   required>
                            <div class="form-text">Vui lòng nhập mã 6 số đã được gửi đến email của bạn</div>
                        </div>

                        <button type="submit" name="verify_otp" class="btn btn-primary btn-verify">
                            <i class="fas fa-check-circle me-2"></i>Xác nhận
                        </button>

                        <div class="text-center mt-3">
                            <p class="mb-0">Không nhận được mã? 
                                <a href="#" id="resendOtp">Gửi lại mã</a>
                            </p>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus OTP input
        document.addEventListener('DOMContentLoaded', function() {
            const otpInput = document.getElementById('otp');
            if (otpInput) otpInput.focus();

            // Handle resend OTP
            document.getElementById('resendOtp')?.addEventListener('click', function(e) {
                e.preventDefault();
                // Gửi yêu cầu gửi lại mã OTP
                fetch('resend_otp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'email=<?php echo urlencode($_SESSION['register_email'] ?? '') ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Mã OTP mới đã được gửi đến email của bạn');
                    } else {
                        alert('Có lỗi xảy ra: ' + (data.message || 'Không thể gửi lại mã OTP'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi gửi lại mã OTP');
                });
            });
        });
    </script>
</body>
</html>
<?php
// Kết thúc output buffering nếu đã bắt đầu
if (ob_get_level() > 0) {
    ob_end_flush();
}
