<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'reset_step') {
        // Reset về bước 1
        unset($_SESSION['reset_step']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['thongbao']);
        unset($_SESSION['thongbao_type']);
    }
}

echo json_encode(['success' => true]);
?>
