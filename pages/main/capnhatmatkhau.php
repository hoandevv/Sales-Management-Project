<?php
session_start();
include('../../admincp/config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = isset($_POST['token']) ? mysqli_real_escape_string($mysqli, $_POST['token']) : '';
    $matkhau = $_POST['matkhau'];
    $xacnhan_matkhau = $_POST['xacnhan_matkhau'];
    
    // Kiểm tra mật khẩu có khớp không
    if ($matkhau !== $xacnhan_matkhau) {
        $_SESSION['thongbao'] = "Mật khẩu xác nhận không khớp.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: doimatkhau.php?token=' . urlencode($token));
        exit();
    }
    
    // Kiểm tra độ mạnh mật khẩu (tối thiểu 8 ký tự, có chữ hoa, chữ thường và số)
    if (strlen($matkhau) < 8 || !preg_match('/[A-Z]/', $matkhau) || !preg_match('/[a-z]/', $matkhau) || !preg_match('/[0-9]/', $matkhau)) {
        $_SESSION['thongbao'] = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: doimatkhau.php?token=' . urlencode($token));
        exit();
    }
    
    // Kiểm tra token hợp lệ và chưa hết hạn
    $sql = "SELECT id_dangki FROM tbl_dangki WHERE reset_token = ? AND reset_token_expires > NOW()";
    $stmt = mysqli_prepare($mysqli, $sql);
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id_dangki'];
        
        // Mã hóa mật khẩu mới
        $hashed_password = password_hash($matkhau, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu và xóa token
        $sql = "UPDATE tbl_dangki SET matkhau = ?, reset_token = NULL, reset_token_expires = NULL WHERE id_dangki = ?";
        $stmt = mysqli_prepare($mysqli, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $hashed_password, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['thongbao'] = "Đặt lại mật khẩu thành công. Bạn có thể đăng nhập bằng mật khẩu mới.";
            $_SESSION['thongbao_type'] = 'success';
            header('Location: ../../index.php?quanly=dangnhap');
        } else {
            $_SESSION['thongbao'] = "Có lỗi xảy ra. Vui lòng thử lại sau.";
            $_SESSION['thongbao_type'] = 'danger';
            header('Location: doimatkhau.php?token=' . urlencode($token));
        }
    } else {
        $_SESSION['thongbao'] = "Liên kết không hợp lệ hoặc đã hết hạn. Vui lòng yêu cầu lại.";
        $_SESSION['thongbao_type'] = 'danger';
        header('Location: quenmatkhau.php');
    }
} else {
    header('Location: ../../index.php');
}

exit();
?>
