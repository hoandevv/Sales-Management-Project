<?php
session_start();
include('config/config.php');

if (isset($_POST['dangnhap'])) {
    try {
        // Khởi tạo kết nối database
        $mysqli = DatabaseConfig::init();
        
        // Lấy dữ liệu từ form và làm sạch input
        $taikhoan = $mysqli->real_escape_string($_POST['username']);
        $matkhau = md5($_POST['password']);

        $sql = "SELECT * FROM tbl_admin WHERE username = '$taikhoan' AND password = '$matkhau' LIMIT 1";

        // Thực thi truy vấn
        $result = $mysqli->query($sql);

        // Kiểm tra kết quả truy vấn
        if ($result && $result->num_rows > 0) {
            $_SESSION['dangnhap'] = $taikhoan;
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['login_error'] = "Tài khoản hoặc mật khẩu không đúng!";
            header('Location: login.php');
            exit();
        }
    } catch (Exception $e) {
        error_log("Lỗi đăng nhập: " . $e->getMessage());
        $_SESSION['login_error'] = "Có lỗi xảy ra, vui lòng thử lại sau!";
        header('Location: login.php');
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'dangxuat') {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="https://png.pngtree.com/element_our/20190528/ourlarge/pngtree-flat-keyboard-image_1174880.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome (đã dùng icon mắt) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Đăng Nhập ADMIN</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            background-image: url('https://banghieuviet.org/wp-content/uploads/2024/01/background-dep-4k.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .login-form {
            width: 340px;
            background-color: #faf3f3;
            padding: 30px;
            border-radius: 20px;
            border: 2px solid white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        label {
            text-align: left;
            display: block;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px 40px 10px 10px; /* để chừa chỗ cho icon */
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        button {
            border: none;
            border-radius: 5px;
            background-color: rgb(184, 177, 181);
            color: #000;
            padding: 10px 15px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e0cda8;
        }

        h3 {
            color: rgb(43, 9, 110);
            margin-bottom: 20px;
        }

        .form_user,
        .form_pass {
            display: flex;
            align-items: center;
            position: relative;
        }

        .icon {
            padding-right: 15px;
            font-size: 20px;
        }

        /* Icon mắt hiển thị trong trường mật khẩu (bên phải) */
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
            color: #333;
        }

        .toggle-password:focus {
            outline: none;
        }

        /* căn input và icon user */
        .form_user .icon {
            position: absolute;
            left: 8px;
        }
        .form_user input {
            padding-left: 36px;
        }
        .form_pass .icon {
            position: absolute;
            left: 8px;
        }
        .form_pass input {
            padding-left: 36px;
        }
        /* Đảm bảo vùng chứa icon không che chữ */
        .form_user, .form_pass {
            position: relative;
        }
    </style>
</head>

<body>
    <form action="" autocomplete="off" method="POST" class="login-form">
        <h3>Đăng Nhập Tài Khoản Admin</h3>
        <?php if(isset($_SESSION['login_error'])): ?>
            <div style="color: red; margin-bottom: 10px;">
                <?php 
                    echo $_SESSION['login_error'];
                    unset($_SESSION['login_error']);
                ?>
            </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="username">Nhập Tài Khoản</label>
            <div class="form_user">
                <i class="icon fa-solid fa-user"></i>
                <input type="text" id="username" name="username" placeholder="Email" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Nhập Mật Khẩu</label>
            <div class="form_pass">
                <i class="icon fa-solid fa-key"></i>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <!-- Nút chuyển hiển thị/ẩn mật khẩu -->
                <button type="button" id="togglePassword" class="toggle-password" aria-label="Hiện mật khẩu">
                    <i class="fa-solid fa-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>
        <div class="button-group">
            <button type="submit" name="dangnhap">Đăng Nhập</button>
            <button type="reset">Nhập Lại</button>
        </div>
    </form>

    <script>
        // Toggle hiển thị mật khẩu
        (function() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // đổi icon và aria-label
                if (type === 'text') {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                    toggleBtn.setAttribute('aria-label', 'Ẩn mật khẩu');
                } else {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                    toggleBtn.setAttribute('aria-label', 'Hiện mật khẩu');
                }

                // đặt lại con trỏ focus về trường mật khẩu để UX tốt hơn
                passwordInput.focus();
            });

            // Không submit form khi bấm nút toggle (đã là type="button", nhưng để an toàn)
            toggleBtn.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        })();
    </script>
</body>

</html>
