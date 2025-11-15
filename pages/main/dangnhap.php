<?php
// Bắt đầu output buffering
ob_start();

// Bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['user'])) {
    echo '<script>window.location.href = "index.php";</script>';
    exit();
}

// Khởi tạo biến thông báo lỗi
$error = '';
$redirect = '';

// Xử lý khi người dùng nhấn nút đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dangnhap'])) {
    // Lấy và xử lý dữ liệu đầu vào
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Kiểm tra dữ liệu đầu vào
    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ email và mật khẩu';
    } else {
        try {
            // Sử dụng prepared statement để tránh SQL Injection
            $stmt = $mysqli->prepare("SELECT * FROM tbl_dangki WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($user = $result->fetch_assoc()) {
                // Kiểm tra mật khẩu
                if (password_verify($password, $user['matkhau'])) {
                    // Đăng nhập thành công, lưu thông tin vào session
                    $_SESSION['user'] = [
                        'id' => $user['id_dangki'],
                        'name' => $user['tenkhachhang'],
                        'email' => $user['email']
                    ];
                    
                    // Đặt biến redirect để chuyển hướng sau khi hiển thị thông báo
                    $redirect = 'index.php';
                    
                    // Làm sạp bộ đệm đầu ra
                    if (ob_get_level() > 0) {
                        ob_end_clean();
                    }
                    
                    // Sử dụng JavaScript để chuyển hướng sau khi đăng nhập thành công
                    echo '<script>window.location.href = "index.php";</script>';
                    exit();
                } else {
                    $error = 'Mật khẩu không chính xác';
                }
            } else {
                $error = 'Email không tồn tại trong hệ thống';
            }
        } catch (Exception $e) {
            // Ghi log lỗi và hiển thị thông báo chung
            error_log('Lỗi đăng nhập: ' . $e->getMessage());
            $error = 'Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại sau.';
        }
    }
}
// Kết thúc output buffering và lấy nội dung
$output = ob_get_clean();
?>

    <form action="" method="POST" class="login-form" autocomplete="off">
        <h3 class="text-center mb-4">Đăng nhập tài khoản</h3>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                       placeholder="Nhập email của bạn" 
                       required>
            </div>
        </div>
        
        <div class="form-group mb-4">
            <label for="password" class="form-label">Mật khẩu</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       placeholder="Nhập mật khẩu" 
                       required>
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        
        <div class="d-grid gap-2 mb-3">
            <button type="submit" name="dangnhap" class="btn btn-primary btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
            </button>
        </div>
        
        <div class="text-center">
            <a href="index.php?quanly=quenmatkhau" class="text-decoration-none">Quên mật khẩu?</a>
            <span class="mx-2">|</span>
            <a href="index.php?quanly=dangki" class="text-decoration-none">Đăng ký tài khoản mới</a>
        </div>
    </form>
    
    <style>
    .login-form {
        max-width: 400px;
        margin: 2rem auto;
        padding: 2rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .toggle-password {
        cursor: pointer;
    }
    </style>
    
    <script>
    // Hiển thị/ẩn mật khẩu
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('.toggle-password');
        const password = document.querySelector('#password');
        const icon = togglePassword.querySelector('i');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
    </script>

