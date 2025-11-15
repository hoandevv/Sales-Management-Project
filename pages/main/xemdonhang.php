<?php

// Kiểm tra sự tồn tại của tham số 'code' trong URL
if (isset($_GET['code'])) {
    $code_cart = $_GET['code'];  // Lấy giá trị 'code' từ URL

    // Truy vấn chi tiết đơn hàng từ bảng 'tbl_cart_details' và 'tbl_sanpham'
    $sql_details = "SELECT tbl_cart_details.*, tbl_sanpham.*, tbl_cart.cart_payment 
    FROM tbl_cart_details 
    JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham 
    JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart 
    WHERE tbl_cart_details.code_cart = '$code_cart' 
    ORDER BY tbl_cart_details.id_cart_details DESC";
$query_details = mysqli_query($mysqli, $sql_details);
} else {
    echo "Không có mã đơn hàng!";
    exit;
}
?>

<p>Danh sách sản phẩm trong đơn hàng: <?php echo ($code_cart); ?></p>
<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã đơn hàng</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
            <th>Hình thức thanh toán </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        // Lặp qua các chi tiết đơn hàng
        while ($row = mysqli_fetch_array($query_details)) {
            $i++;
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo ($row['code_cart']); ?></td>
                <td><?php echo ($row['tensanpham']); ?></td>
                <td><?php echo ($row['soluongmua']); ?></td>
                <td><?php echo number_format($row['giasp']); ?></td>
                <td><?php echo number_format($row['giasp'] * $row['soluongmua']); ?></td>
                <td><?php echo ($row['cart_payment']); ?></td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>