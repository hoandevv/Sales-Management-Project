<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../../config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ']);
    exit();
}

$email = mysqli_real_escape_string($mysqli, $_POST['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email không được để trống']);
    exit();
}

// Kiểm tra email có tồn tại không
$sql = "SELECT id_dangki, tenkhachhang FROM tbl_dangki WHERE email = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(['success' => false, 'message' => 'Email này chưa được đăng ký trong hệ thống']);
    exit();
}

$row = mysqli_fetch_assoc($result);
$user_id = $row['id_dangki'];
$user_name = $row['tenkhachhang'];

// Tạo mã OTP ngẫu nhiên
$otp = rand(100000, 999999);
$expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

// Lưu OTP vào database
$sql = "UPDATE tbl_dangki SET otp_code = ?, otp_expires_at = ? WHERE id_dangki = ?";
$stmt = mysqli_prepare($mysqli, $sql);
mysqli_stmt_bind_param($stmt, 'ssi', $otp, $expires_at, $user_id);

if (mysqli_stmt_execute($stmt)) {
    // Gửi email với OTP thật
    try {
        require_once '../../mail/Mailer.php';
        $mailer = new Mailer();

        $subject = "Mã OTP đặt lại mật khẩu - GearShop";
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #333;'>Đặt lại mật khẩu</h2>
                <p>Xin chào <strong>$user_name</strong>,</p>
                <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản GearShop.</p>
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px; text-align: center; margin: 20px 0;'>
                    <h3 style='color: #007bff; margin: 0;'>Mã OTP của bạn: <strong>$otp</strong></h3>
                </div>
                <p><strong>Lưu ý:</strong></p>
                <ul>
                    <li>Mã OTP có hiệu lực trong vòng 5 phút</li>
                    <li>Không chia sẻ mã này với bất kỳ ai</li>
                    <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này</li>
                </ul>
                <p>Trân trọng,<br><strong>Đội ngũ GearShop</strong></p>
            </div>
        ";

        $mailer->dathangmail($subject, $body, $email, $user_name);

        // Lưu email vào session để sử dụng ở bước tiếp theo
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_step'] = 2; // Chuyển sang bước 2

        echo json_encode(['success' => true, 'message' => 'Mã OTP đã được gửi đến email của bạn']);
    } catch (Exception $e) {
        error_log("Lỗi gửi email OTP: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi tạo mã OTP']);
}
?>
