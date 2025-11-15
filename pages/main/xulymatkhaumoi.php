<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../../config.php');

if (isset($_POST['reset_password'])) {
    // Kiểm tra session reset
    if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_step']) || $_SESSION['reset_step'] != 3) {
        $_SESSION['thongbao'] = "Phiên làm việc không hợp lệ. Vui lòng bắt đầu lại.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
        exit();
    }

    $email = $_SESSION['reset_email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate mật khẩu
    if (empty($new_password) || empty($confirm_password)) {
        $_SESSION['thongbao'] = "Vui lòng nhập đầy đủ mật khẩu.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
        exit();
    }

    // Kiểm tra mật khẩu xác nhận
    if ($new_password !== $confirm_password) {
        $_SESSION['thongbao'] = "Mật khẩu xác nhận không khớp.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
        exit();
    }

    // Kiểm tra độ mạnh mật khẩu
    if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $_SESSION['thongbao'] = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
        exit();
    }

    // Mã hóa mật khẩu mới
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Cập nhật mật khẩu và xóa OTP
    $sql = "UPDATE tbl_dangki SET matkhau = ?, otp_code = NULL, otp_expires_at = NULL WHERE email = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $email);

    if (mysqli_stmt_execute($stmt)) {
        // Xóa session reset
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_step']);
        $_SESSION['thongbao'] = "Đặt lại mật khẩu thành công! Bạn có thể đăng nhập bằng mật khẩu mới.";
        $_SESSION['thongbao_type'] = 'success';
        header('Location: ../../index.php');
    } else {
        $_SESSION['thongbao'] = "Có lỗi xảy ra. Vui lòng thử lại sau.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
    }
    exit();
} else {
    header('Location: ../../index.php');
    exit();
}
?>
