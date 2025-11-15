<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = '';

try {
    $configPath = __DIR__ . '/../../src/Config/config.php';
    if (!file_exists($configPath)) throw new Exception("Không tìm thấy file config.php tại: $configPath");
    require_once $configPath;

    if (!isset($mysqli)) throw new Exception("Biến \$mysqli không được khởi tạo trong config.php");
    if ($mysqli->connect_error) throw new Exception("Lỗi kết nối database: " . $mysqli->connect_error);

    $authControllerPath = __DIR__ . '/../../includes/controllers/AuthController.php';
    if (!file_exists($authControllerPath)) throw new Exception("Không tìm thấy AuthController.php tại: $authControllerPath");
    require_once $authControllerPath;

    $authController = new AuthController($mysqli);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dangki'])) {
        $name = isset($_POST['hovaten']) ? htmlspecialchars(trim($_POST['hovaten']), ENT_QUOTES, 'UTF-8') : '';
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $phone = trim(filter_input(INPUT_POST, 'dienthoai', FILTER_SANITIZE_NUMBER_INT));
        $address = isset($_POST['diachi']) ? htmlspecialchars(trim($_POST['diachi']), ENT_QUOTES, 'UTF-8') : '';
        $password = trim($_POST['matkhau'] ?? '');

        // Validate dữ liệu
        if (!$name) $error = "Vui lòng nhập họ và tên.";
        elseif (!$email) $error = "Email không hợp lệ.";
        elseif (!$phone) $error = "Vui lòng nhập số điện thoại.";
        elseif (!$address) $error = "Vui lòng nhập địa chỉ.";
        elseif (empty($password) || strlen($password) < 6) $error = "Mật khẩu phải có ít nhất 6 ký tự.";
        else {
            try {
                $result = $authController->register([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address,
                    'password' => $password
                ]);

                if ($result['success']) {
                    $_SESSION['verify_user_id'] = $result['userId'];
                    $_SESSION['register_email'] = $email;
                    $success = $result['message'];

                    echo "<script>
                        setTimeout(function(){
                            window.location.href = 'index.php?quanly=xacnhanotp';
                        }, 2000);
                    </script>";
                } else {
                    $error = $result['message'];
                }
            } catch (Exception $e) {
                $error = "Lỗi khi đăng ký: " . $e->getMessage();
            }
        }
    }

} catch (Exception $e) {
    $error = "Lỗi hệ thống: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký thành viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
            body {
            background: #ffffff !important;
            min-height: auto;
            display: block;
            padding: 0;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-header h3 {
            margin: 0;
            font-weight: 600;
        }
        .register-body {
            padding: 30px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="register-card">
                    <div class="register-header">
                        <i class="fas fa-user-plus fa-3x mb-3"></i>
                        <h3>Đăng ký tài khoản</h3>
                        <p class="mb-0">Tạo tài khoản mới để bắt đầu mua sắm</p>
                    </div>

                    <div class="register-body">
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
                                    <small>Đang chuyển hướng đến trang xác thực...</small>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if (!$success): ?>
                            <form method="post" action="" id="registerForm">
                                <div class="mb-3">
                                    <label for="hovaten" class="form-label">
                                        <i class="fas fa-user me-1"></i>Họ và tên
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="hovaten" id="hovaten" class="form-control" 
                                               placeholder="Nguyễn Văn A" required 
                                               value="<?php echo htmlspecialchars($_POST['hovaten'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Email
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" id="email" class="form-control" 
                                               placeholder="email@example.com" required
                                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="dienthoai" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Số điện thoại
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" name="dienthoai" id="dienthoai" class="form-control" 
                                               placeholder="0987654321" required pattern="[0-9]{10,11}"
                                               value="<?php echo htmlspecialchars($_POST['dienthoai'] ?? ''); ?>">
                                    </div>
                                    <small class="text-muted">Nhập 10-11 chữ số</small>
                                </div>

                                <div class="mb-3">
                                    <label for="diachi" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Địa chỉ
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" name="diachi" id="diachi" class="form-control" 
                                               placeholder="123 Đường ABC, Quận XYZ" required
                                               value="<?php echo htmlspecialchars($_POST['diachi'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="matkhau" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Mật khẩu
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="matkhau" id="matkhau" class="form-control" 
                                               placeholder="Ít nhất 6 ký tự" required minlength="6">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
                                </div>

                                <button type="submit" name="dangki" class="btn btn-primary btn-register w-100">
                                    <i class="fas fa-user-plus me-2"></i>Đăng ký
                                </button>
                            </form>

                            <hr class="my-4">

                            <p class="text-center mb-0">
                                Đã có tài khoản? 
                                <a href="index.php?quanly=dangnhap" class="text-decoration-none fw-bold">
                                    Đăng nhập ngay
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <p class="text-center mt-3 text-white">
                    <small>&copy; 2025 Your Company. All rights reserved.</small>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('matkhau');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Form validation
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            const phone = document.getElementById('dienthoai').value;
            if (!/^[0-9]{10,11}$/.test(phone)) {
                e.preventDefault();
                alert('Số điện thoại phải có 10-11 chữ số!');
                return false;
            }
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>