<?php
include ('config/config.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['dangnhap'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit'])) {
    $taikhoan = $_SESSION['dangnhap'];
    $matkhau_cu = md5($_POST['matkhau_cu']);
    $matkhau_moi = md5($_POST['matkhau_moi']);
    
    if ($_POST['matkhau_moi'] != $_POST['matkhau_moi2']) {
        echo '<script>alert("Mật khẩu mới không khớp!");</script>';
    } else {
        // Kiểm tra mật khẩu cũ
        $sql = "SELECT * FROM tbl_admin WHERE username = '$taikhoan' AND password = '$matkhau_cu' LIMIT 1";
        $result = $mysqli->query($sql);
        
        if ($result->num_rows > 0) {
            // Cập nhật mật khẩu mới
            $sql_update = "UPDATE tbl_admin SET password = '$matkhau_moi' WHERE username = '$taikhoan'";
            if ($mysqli->query($sql_update)) {
                echo '<script>alert("Đổi mật khẩu thành công!"); window.location.href="index.php";</script>';
            } else {
                echo '<script>alert("Lỗi cập nhật mật khẩu!");</script>';
            }
        } else {
            echo '<script>alert("Mật khẩu hiện tại không đúng!");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="https://png.pngtree.com/element_our/20190528/ourlarge/pngtree-flat-keyboard-image_1174880.jpg" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thay Đổi Mật Khẩu</title>
</head>
<body>
    <form action="" method="POST" class="change-password-form">
        <h3>Thay Đổi Mật Khẩu</h3>
        <div class="form-group">
            <label for="matkhau_cu">Mật Khẩu Hiện Tại</label>
            <input type="password" id="matkhau_cu" name="matkhau_cu" required>
        </div>
        <div class="form-group">
            <label for="matkhau_moi">Mật Khẩu Mới</label>
            <input type="password" id="matkhau_moi" name="matkhau_moi" required>
        </div>
        <div class="form-group">
            <label for="matkhau_moi2">Xác Nhận Mật Khẩu Mới</label>
            <input type="password" id="matkhau_moi2" name="matkhau_moi2" required>
        </div>
        <button type="submit" name="submit">Cập Nhật</button>
    </form>
</body>
</html>
