<?php
session_start();
include('../../admincp/config/config.php');
require('../../mail/sendmail.php');

// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header('Location: ../../index.php?quanly=giohang&error=empty');
    exit();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header('Location: ../../index.php?quanly=dangnhap&error=login_required');
    exit();
}

// Lấy thông tin khách hàng từ session
$user_id = (int)$_SESSION['user']['id'];
$code_order = rand(1000, 9999); 
$cart_payment = mysqli_real_escape_string($mysqli, $_POST['payment']);

// Lấy thông tin vận chuyển
$sql_vanchuyen = "SELECT * FROM tbl_vanchuyen WHERE id_dangki = ? LIMIT 1";
$stmt = mysqli_prepare($mysqli, $sql_vanchuyen);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result_vanchuyen = mysqli_stmt_get_result($stmt);
$row_vanchuyen = mysqli_fetch_assoc($result_vanchuyen);

if (!$row_vanchuyen) {
    header('Location: ../../index.php?quanly=vanchuyen&error=shipping_required');
    exit();
}

$id_shipping = (int)$row_vanchuyen['id_shipping'];

// Bắt đầu transaction
mysqli_begin_transaction($mysqli);

try {
    // Thêm đơn hàng vào bảng tbl_cart
    $insert_cart = "INSERT INTO tbl_cart (id_khachhang, code_cart, cart_status, cart_payment, cart_shipping)
                    VALUES (?, ?, 0, ?, ?)";
    $stmt = mysqli_prepare($mysqli, $insert_cart);
    
    if (!$stmt) {
        throw new Exception("Lỗi chuẩn bị câu lệnh: " . mysqli_error($mysqli));
    }
    
    mysqli_stmt_bind_param($stmt, "issi", $user_id, $code_order, $cart_payment, $id_shipping);
    $cart_query = mysqli_stmt_execute($stmt);
    
    if (!$cart_query) {
        throw new Exception("Lỗi khi thêm đơn hàng: " . mysqli_error($mysqli));
    }

    // Thêm chi tiết đơn hàng
    foreach ($_SESSION['cart'] as $key => $value) {
        $id_sanpham = (int)$value['id'];
        $soluong = (int)$value['soluong'];

        // Lấy giá sản phẩm từ bảng sản phẩm
        $sql_get_price = "SELECT giasp FROM tbl_sanpham WHERE id_sanpham = ? LIMIT 1";
        $stmt_price = mysqli_prepare($mysqli, $sql_get_price);
        mysqli_stmt_bind_param($stmt_price, 'i', $id_sanpham);
        mysqli_stmt_execute($stmt_price);
        $result_price = mysqli_stmt_get_result($stmt_price);
        $row_price = mysqli_fetch_assoc($result_price);

        if (!$row_price) {
            throw new Exception("Không tìm thấy sản phẩm có ID: $id_sanpham");
        }

        // Thêm chi tiết đơn hàng vào bảng tbl_cart_details
        $insert_order_details = "INSERT INTO tbl_cart_details (id_sanpham, code_cart, soluongmua)
                                 VALUES (?, ?, ?)";
        $stmt_details = mysqli_prepare($mysqli, $insert_order_details);
        mysqli_stmt_bind_param($stmt_details, 'isi', $id_sanpham, $code_order, $soluong);

        if (!mysqli_stmt_execute($stmt_details)) {
            throw new Exception("Lỗi khi chèn chi tiết đơn hàng: " . mysqli_error($mysqli));
        }
    }

    // Lấy thông tin khách hàng để gửi email
    $sql_khachhang = "SELECT * FROM tbl_dangki WHERE id_dangki = ? LIMIT 1";
    $stmt_khachhang = mysqli_prepare($mysqli, $sql_khachhang);
    mysqli_stmt_bind_param($stmt_khachhang, 'i', $user_id);
    mysqli_stmt_execute($stmt_khachhang);
    $result_khachhang = mysqli_stmt_get_result($stmt_khachhang);
    $row_khachhang = mysqli_fetch_assoc($result_khachhang);

    if (!$row_khachhang) {
        throw new Exception("Không tìm thấy thông tin khách hàng");
    }

    $tenkhachhang = $row_khachhang['tenkhachhang'];
    $email = $row_khachhang['email'];
    $tieude = "Xác nhận đơn hàng #$code_order";

    // Tạo nội dung email gửi cho khách hàng
    $noidung = "<div><p>Xin chào $tenkhachhang,</p><p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi với mã đơn hàng #$code_order</p><p>Chi tiết đơn hàng của bạn:</p>";

    // Lặp qua các sản phẩm trong giỏ hàng để hiển thị chi tiết
    foreach ($_SESSION['cart'] as $key => $value) {
        $noidung .= "<p>Sản phẩm: " . htmlspecialchars($value['tensanpham']) . "</p>";
        $noidung .= "<p>Số lượng: " . (int)$value['soluong'] . "</p>";
        $noidung .= "<p>Giá: " . number_format($value['giasp'], 0, ',', '.') . " ₫</p>";
        $noidung .= "<p>Tổng tiền: " . number_format($value['giasp'] * $value['soluong'], 0, ',', '.') . " ₫</p>";
    }
    $noidung .= "<p>Chúng tôi sẽ liên hệ bạn trong thời gian sớm nhất!</p><p>Chúc bạn có một ngày tuyệt vời!</p></div>";

    // Gửi email
    ob_start(); // Bắt đầu output buffering
    try {
        $mail = new Mailer();
        // Gửi email xác nhận cho khách hàng
        $mail->dathangmail($tieude, $noidung, $email, $tenkhachhang);

        // Gửi email thông báo cho người bán (nếu có email admin)
        if (defined('ADMIN_EMAIL')) {
            $tieudeseller = "Đơn hàng mới #$code_order";
            $noidungseller = "<p>Bạn có đơn hàng mới từ khách hàng: $tenkhachhang</p><p>Mã đơn hàng: #$code_order</p><p>Vui lòng kiểm tra và xử lý đơn hàng.</p>";
            $mail->dathangmailSeller($tieudeseller, $noidungseller, ADMIN_EMAIL, $tenkhachhang);
        }
    } catch (Exception $e) {
        error_log("Lỗi khi gửi email: " . $e->getMessage());
        // Tiếp tục xử lý dù có lỗi gửi email
    }
    ob_end_clean(); // Xóa output buffer

    // Commit transaction
    mysqli_commit($mysqli);

    // Xóa giỏ hàng sau khi thanh toán thành công
    unset($_SESSION['cart']);

    // Lưu thông báo thành công vào session
    $_SESSION['order_success'] = true;

    // Chuyển hướng với mã đơn hàng
    header('Location: ../../index.php?quanly=camon&code=' . $code_order);
    exit();
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    mysqli_rollback($mysqli);
    
    // Ghi log lỗi
    error_log("Lỗi xử lý đơn hàng: " . $e->getMessage());
    
    // Hiển thị thông báo lỗi
    echo "Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.";
    // Hoặc chuyển hướng về trang lỗi
    // header('Location: ../../index.php?quanly=loi');
    exit();
}
?>
