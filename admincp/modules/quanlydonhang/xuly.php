<?php
include('../../config/config.php');

if (isset($_GET['code']) && isset($_GET['status'])) {
    $code_cart = $_GET['code'];
    $status = (int)$_GET['status'];

    // Kiểm tra trạng thái hợp lệ (0-3)
    if ($status >= 0 && $status <= 3) {
        // Cập nhật trạng thái đơn hàng và updated_at
        $sql = "UPDATE tbl_cart SET cart_status = $status, updated_at = NOW() WHERE code_cart = '$code_cart'";
        $query = mysqli_query($mysqli, $sql);

        // Kiểm tra cập nhật thành công và chuyển hướng
        if ($query) {
            header('Location: ../../index.php?action=quanlydonhang&query=lietke');
        } else {
            echo "Lỗi: Không thể cập nhật trạng thái đơn hàng!";
        }
    } else {
        echo "Trạng thái không hợp lệ!";
    }
} else {
    echo "Thiếu tham số cần thiết!";
}
?>