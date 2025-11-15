<?php
$sql_lietke_dh = "SELECT * FROM tbl_cart 
                  INNER JOIN tbl_dangki ON tbl_cart.id_khachhang = tbl_dangki.id_dangki 
                  ORDER BY tbl_cart.id_cart DESC";
$query_lietke_dh = mysqli_query($mysqli, $sql_lietke_dh);
?>

<p>Liệt kê danh mục đơn hàng</p>

<!-- Form tìm kiếm -->
<form action="" method="POST">
    <input type="text" name="search_name" placeholder="Nhập tên khách hàng">
    <input type="submit" name="search" value="Tìm kiếm">
</form>

<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã đơn hàng</th>
            <th>Tên khách hàng</th>
            <th>Địa chỉ</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Quản lý</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;

    // Kiểm tra nếu form tìm kiếm được gửi
    if (isset($_POST['search'])) {
        $search_name = $_POST['search_name'];
        $sql_search = "SELECT * FROM tbl_cart, tbl_dangki 
                       WHERE tbl_cart.id_khachhang = tbl_dangki.id_dangki 
                       AND tbl_dangki.tenkhachhang LIKE '%$search_name%' 
                       ORDER BY tbl_cart.id_cart DESC";
        $query_lietke_dh = mysqli_query($mysqli, $sql_search);
    }

    while ($row = mysqli_fetch_array($query_lietke_dh)) {
        $i++;
    ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['code_cart']; ?></td>
            <td><?php echo $row['tenkhachhang']; ?></td>
            <td><?php echo $row['diachi']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['dienthoai']; ?></td>
            <td>
                <?php
                switch ($row['cart_status']) {
                    case 0:
                        echo '<a href="modules/quanlydonhang/xuly.php?code=' . $row['code_cart'] . '&status=1">Xác nhận đơn hàng</a>';
                        break;
                    case 1:
                        echo '<a href="modules/quanlydonhang/xuly.php?code=' . $row['code_cart'] . '&status=2">Đang vận chuyển</a>';
                        break;
                    case 2:
                        echo '<a href="modules/quanlydonhang/xuly.php?code=' . $row['code_cart'] . '&status=3">Đã giao hàng</a>';
                        break;
                    case 3:
                        echo 'Đã giao hàng';
                        break;
                    default:
                        echo 'Trạng thái không xác định';
                }
                ?>
            </td>
            <td>
                <a href="index.php?action=donhang&query=xemdonhang&code=<?php echo urlencode($row['code_cart']); ?>">Xem đơn hàng</a>
            </td>
        </tr>
    <?php
    }
    ?>
    </tbody>
</table>