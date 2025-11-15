<?php
session_start();
include('../../admincp/config/config.php');
require ('../../mail/sendmail.php');

// Kiểm tra nếu giỏ hàng trống
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('Location:../../index.php?quanly=giohang&error=empty');
    exit();
}

$id_khachhang = $_SESSION['id_khachhang'];
$code_order = rand(0, 9999);



$insert_cart = "INSERT INTO tbl_cart (id_khachhang, code_cart, cart_status) VALUES ('$id_khachhang', '$code_order', 1)";
$cart_query = mysqli_query($mysqli, $insert_cart);

if ($cart_query) {
    foreach ($_SESSION['cart'] as $key => $value) {
        $id_sanpham = $value['id'];
        $soluong = $value['soluong'];
        $insert_order_details = "INSERT INTO tbl_cart_details (id_sanpham, code_cart, soluongmua) VALUES ('$id_sanpham', '$code_order', '$soluong')";
        if (!mysqli_query($mysqli, $insert_order_details)) {
            echo "Lỗi khi chèn chi tiết đơn hàng: " . mysqli_error($mysqli);
            exit();
        }
    }

    // Tiêu đề và nội dung email
    $noidung = "<div><p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi với mã đơn hàng $code_order</p><p>Chi tiết đơn hàng của bạn:</p>";
    
    foreach ($_SESSION['cart'] as $key => $value) {
        // Hiển thị thông tin sản phẩm
        $noidung .= "<p>Sản phẩm: " . $value['tensanpham'] . "</p>";
        $noidung .= "<p>Số lượng: " . $value['soluong'] . "</p>";
        $noidung .= "<p>Giá: " . number_format($value['giasp'], 0, ',', '.') . " VNĐ</p>";
        $noidung .= "<p>Tổng tiền: " . number_format($value['giasp'] * $value['soluong'], 0, ',', '.') . " VNĐ</p>";
    }
    $noidung .= "<p>Chúng tôi sẽ liên hệ bạn trong thời gian sớm nhất!</p></div>";
    $noidung .= "<p>Chúc bạn có một ngày tuyệt vời!</p></div>";

    // Tiêu đề email
    $tieude = 'Đơn hàng của bạn đã đặt hàng thành công tại web shop';

    // Lấy email từ session và gửi email
    $maildathang = $_SESSION['email'];
    $mail = new Mailer();
    $mail->dathangmail($tieude, $noidung, $maildathang,$tenkhachhang);


    // Xóa giỏ hàng sau khi thanh toán
    unset($_SESSION['cart']);

    // Chuyển hướng đến trang cảm ơn
    header('Location:../../index.php?quanly=camon');
    exit();
} else {
    echo "Lỗi khi chèn đơn hàng vào tbl_cart: " . mysqli_error($mysqli);
    exit();
}
?>
