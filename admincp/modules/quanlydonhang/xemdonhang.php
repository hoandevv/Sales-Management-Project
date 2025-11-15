<?php
// Sử dụng kết nối từ file cấu hình chung
require_once(__DIR__ . '/../../config/config.php');

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    // Lưu mã đơn hàng vào session và chuyển hướng
    $_SESSION['last_order_code'] = $_POST['code'];
    header('Location: ' . $_SERVER['PHP_SELF'] . '?code=' . urlencode($_POST['code']));
    exit();
}

// Lấy mã đơn hàng từ URL hoặc từ session
$code_cart = '';
if (isset($_GET['code'])) {
    $code_cart = $_GET['code'];
    // Lưu vào session để giữ lại khi refresh
    $_SESSION['last_order_code'] = $code_cart;
} elseif (isset($_SESSION['last_order_code'])) {
    $code_cart = $_SESSION['last_order_code'];
    // Chuyển hướng để có URL sạch
    if (!isset($_GET['code'])) {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?code=' . urlencode($code_cart));
        exit();
    }
}

// Nút quay lại
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'quanlydonhang') !== false) {
    $back_url = $_SERVER['HTTP_REFERER'];
} else {
    $back_url = 'index.php?action=quanlydonhang&query=lietke';
}

echo '<div style="margin-bottom: 20px;">';
echo '<a href="' . $back_url . '" class="btn btn-primary">← Quay lại danh sách đơn hàng</a>';
echo '</div>';

if (!empty($code_cart)) {
    // Bảo vệ chống SQL Injection
    $code_cart = mysqli_real_escape_string($mysqli, $code_cart);
    
    $sql_details = "SELECT * FROM tbl_cart_details, tbl_sanpham 
                   WHERE tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham 
                   AND tbl_cart_details.code_cart = '$code_cart' 
                   ORDER BY tbl_cart_details.id_cart_details DESC";
    $query_details = mysqli_query($mysqli, $sql_details);
    if (!$query_details) {
        die("Query failed: " . mysqli_error($mysqli));
    }
} else {
    echo "Không có mã đơn hàng!";
    exit;
}
?>

<?php if (isset($_SESSION['order_success'])): ?>
    <div class="alert alert-success">
        Đơn hàng đã được xử lý thành công!
    </div>
    <?php unset($_SESSION['order_success']); ?>
<?php endif; ?>

<p>Danh sách sản phẩm trong đơn hàng: <?php echo htmlspecialchars($code_cart); ?></p>
<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã đơn hàng</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        $total = 0; // Biến để tính tổng tiền
        // Lặp qua các chi tiết đơn hàng
        while ($row = mysqli_fetch_array($query_details)) {
            $i++;
            $thanhtien = $row['giasp'] * $row['soluongmua'];
            $total += $thanhtien; // Cộng dồn vào tổng tiền
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $row['code_cart']; ?></td>
                <td><?php echo $row['tensanpham']; ?></td>
                <td><?php echo $row['soluongmua']; ?></td>
                <td><?php echo number_format($row['giasp'], 0, ',', '.'); ?> ₫</td>
                <td><?php echo number_format($thanhtien, 0, ',', '.'); ?> ₫</td>
            </tr>
        <?php
        }
        // Hiển thị tổng tiền
        if($i > 0) {
        ?>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">Tổng tiền đơn hàng:</td>
                <td style="font-weight: bold;"><?php echo number_format($total, 0, ',', '.'); ?> ₫</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>