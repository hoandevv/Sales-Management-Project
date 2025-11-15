<?php
// Khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo '<script>alert("Vui lòng đăng nhập để xác nhận đơn hàng"); window.location.href = "index.php?quanly=dangnhap";</script>';
    exit();
}

// Kiểm tra mã đơn hàng
if (!isset($_GET['code'])) {
    echo '<script>alert("Thiếu mã đơn hàng"); window.location.href = "index.php?quanly=lichsudonhang";</script>';
    exit();
}

$code_cart = $_GET['code'];
$id_khachhang = $_SESSION['user']['id'];

// Kết nối database
include_once '../../config.php';
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
    die("Kết nối database thất bại: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");

// Kiểm tra đơn hàng có tồn tại và thuộc về khách hàng này không
$sql_check = "SELECT * FROM tbl_cart WHERE code_cart = ? AND id_khachhang = ? AND cart_status = 2";
$stmt_check = $mysqli->prepare($sql_check);
$stmt_check->bind_param("si", $code_cart, $id_khachhang);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    echo '<script>alert("Đơn hàng không tồn tại hoặc không thể xác nhận"); window.location.href = "index.php?quanly=lichsudonhang";</script>';
    exit();
}

// Cập nhật trạng thái đơn hàng thành "Đã giao hàng" (status = 3) và updated_at
$sql_update = "UPDATE tbl_cart SET cart_status = 3, updated_at = NOW() WHERE code_cart = ? AND id_khachhang = ?";
$stmt_update = $mysqli->prepare($sql_update);
$stmt_update->bind_param("si", $code_cart, $id_khachhang);

if ($stmt_update->execute()) {
    echo '<script>alert("Đã xác nhận nhận hàng thành công!"); window.location.href = "index.php?quanly=lichsudonhang";</script>';
} else {
    echo '<script>alert("Có lỗi xảy ra, vui lòng thử lại"); window.location.href = "index.php?quanly=lichsudonhang";</script>';
}

$stmt_check->close();
$stmt_update->close();
$mysqli->close();
?>
