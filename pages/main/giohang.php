<?php
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
?>
    <table style="width:100%; text-align:center; border-collapse: collapse;" border="1">
        <tr>
            <th>ID</th>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Hình ảnh</th>
            <th>Số lượng</th>
            <th>Giá sản phẩm</th>
            <th>Thành tiền</th>
            <th>Quản lý</th>
        </tr>

        <?php
        $i = 0;
        $tongtien = 0;

        foreach ($_SESSION['cart'] as $cart_item) {
            $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
            $tongtien += $thanhtien;
            $i++;
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $cart_item['masp']; ?></td>
                <td><?php echo $cart_item['tensanpham']; ?></td>
                <td><img src="admincp/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh']; ?>" width="150px" /></td>
                <td>
                    <a href=" pages/main/themgiohang.php?tru=<?php echo $cart_item['id']; ?>"><i class="fa-solid fa-minus"></i></a>
                    <?php echo $cart_item['soluong']; ?>
                    <a href=" pages/main/themgiohang.php?cong=<?php echo $cart_item['id']; ?>"><i class="fa-solid fa-plus"></i></a>
                </td>
                <td><?php echo number_format($cart_item['giasp'], 0, ',', '.') . ' ₫'; ?></td>
                <td><?php echo number_format($thanhtien, 0, ',', '.') . ' ₫'; ?></td>
                <td><a href=" pages/main/themgiohang.php?xoa=<?php echo $cart_item['id']; ?>">Xóa</a></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="8">
                <p style="float: left;">Tổng tiền: <?php echo number_format($tongtien, 0, ',', '.') . ' ₫'; ?></p>
                <p style="float: right;"><a href=" pages/main/themgiohang.php?xoatatca=1">Xóa tất cả</a> </p>
                <div style="clear: both;">
                    <?php
                    if (isset($_SESSION['user'])) {
                        ?>
                        <a href="index.php?quanly=vanchuyen" class="btn btn-primary">Hình thức vận chuyển</a>
                        <?php
                    } else {
                        ?>
                        <p>Bạn cần <a href="index.php?quanly=dangnhap" class="btn btn-primary">Đăng nhập</a> hoặc <a href="index.php?quanly=dangki" class="btn btn-secondary">Đăng ký</a> để thanh toán</p>
                    <?php
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
<?php
} else {
    echo 'Giỏ hàng trống';
}
?>
