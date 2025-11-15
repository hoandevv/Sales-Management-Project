<?php
require_once __DIR__ . '/../../src/Config/config.php';
require_once __DIR__ . '/../../includes/controllers/AuthController.php';

header('Content-Type: application/json');

// Check if user is logged in and has a verification ID
if (!isset($_SESSION['verify_user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$user_id = $_SESSION['verify_user_id'];

// Get user details
$stmt = $mysqli->prepare("SELECT email, tenkhachhang FROM tbl_dangki WHERE id_dangki = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit();
}

$user = $result->fetch_assoc();

// Generate new OTP
$otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$otp_expires = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// Update OTP in database
$update = $mysqli->prepare("UPDATE tbl_dangki SET otp_code = ?, otp_expires_at = ? WHERE id_dangki = ?");
$update->bind_param('ssi', $otp, $otp_expires, $user_id);

if ($update->execute()) {
    // Send OTP via email
    $authController = new AuthController($mysqli);
    $emailSent = $authController->sendOTPEmail($user['email'], $user['tenkhachhang'], $otp);
    
    if ($emailSent) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send email']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update OTP']);
}
?>
