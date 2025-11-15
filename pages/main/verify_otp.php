<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../../config.php');

if (isset($_POST['verify_otp'])) {
    // Kiểm tra session reset
    if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_step']) || $_SESSION['reset_step'] != 2) {
        $_SESSION['thongbao'] = "Phiên làm việc không hợp lệ. Vui lòng bắt đầu lại.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
        exit();
    }

    $email = $_SESSION['reset_email'];
    $otp = mysqli_real_escape_string($mysqli, $_POST['otp']);

    // Validate OTP input
    if (empty($otp) || !preg_match('/^\d{6}$/', $otp)) {
        $_SESSION['thongbao'] = "Mã OTP phải gồm 6 chữ số.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
        exit();
    }

    // Kiểm tra email và OTP
    $sql = "SELECT id_dangki, otp_code, otp_expires_at FROM tbl_dangki WHERE email = ?";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Kiểm tra OTP
        if ($row['otp_code'] == $otp && strtotime($row['otp_expires_at']) > time()) {
            // OTP hợp lệ, chuyển đến bước 3
            $_SESSION['reset_step'] = 3;
            $_SESSION['thongbao'] = "Xác nhận OTP thành công! Vui lòng đặt mật khẩu mới.";
            $_SESSION['thongbao_type'] = 'success';
            header('Location: quenmatkhau.php');
            exit();
        } else {
            $_SESSION['thongbao'] = "Mã OTP không hợp lệ hoặc đã hết hạn.";
            $_SESSION['thongbao_type'] = 'danger';
        }
    } else {
        $_SESSION['thongbao'] = "Email này chưa được đăng ký trong hệ thống.";
        $_SESSION['thongbao_type'] = 'danger';
    }

    header('Location: quenmatkhau.php');
    exit();
} else {
    // Nếu không phải POST request, chuyển về trang chủ
    header('Location: ../../index.php');
    exit();
}
?>
