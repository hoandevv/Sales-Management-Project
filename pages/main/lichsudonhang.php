<?php
// Khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo '<script>alert("Vui lòng đăng nhập để xem lịch sử đơn hàng"); window.location.href = "index.php?quanly=dangnhap";</script>';
    exit();
}

// Lấy id khách hàng từ session
$id_khachhang = $_SESSION['user']['id'];

// Kết nối database nếu chưa có
if (!isset($mysqli)) {
    include_once '../../config.php';
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        die("Kết nối database thất bại: " . $mysqli->connect_error);
    }
    $mysqli->set_charset("utf8");
}

// Truy vấn liệt kê đơn hàng
$sql_lietke_dh = "SELECT c.*, d.tenkhachhang, d.diachi, d.email, d.dienthoai,
                         c.cart_status as trangthai_donhang
                  FROM tbl_cart c
                  INNER JOIN tbl_dangki d ON c.id_khachhang = d.id_dangki 
                  WHERE c.id_khachhang = ?
                  GROUP BY c.id_cart
                  ORDER BY c.id_cart DESC";

// Sử dụng prepared statement để tránh SQL Injection
$stmt = $mysqli->prepare($sql_lietke_dh);
if ($stmt === false) {
    die("Lỗi chuẩn bị truy vấn: " . $mysqli->error);
}

$stmt->bind_param("i", $id_khachhang);

if (!$stmt->execute()) {
    die("Lỗi thực thi truy vấn: " . $stmt->error);
}

$query_lietke_dh = $stmt->get_result();

if ($query_lietke_dh === false) {
    die("Lỗi lấy kết quả truy vấn: " . $mysqli->error);
}
?>

<div class="container mt-4">
    <h3 class="mb-4"><i class="fas fa-history me-2"></i>Lịch sử đơn hàng</h3>

<table style="width: 100%; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã đơn hàng</th>
            <th>Tên khách hàng</th>
            <th>Địa chỉ</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
            <th>Phương thức thanh toán </th>
            <th>Quản lý</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;

    // Hiển thị danh sách đơn hàng
    while ($row = mysqli_fetch_array($query_lietke_dh)) {
        $i++;
    ?>
        <tr>
            <td class="text-center"><?php echo $i; ?></td>
            <td class="text-center">
                <a href="index.php?quanly=xemdonhang&code=<?php echo $row['code_cart']; ?>" class="text-primary">
                    <?php echo htmlspecialchars($row['code_cart']); ?>
                </a>
            </td>
            <td><?php echo htmlspecialchars($row['tenkhachhang']); ?></td>
            <td><?php echo htmlspecialchars($row['diachi']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['dienthoai']); ?></td>
            

            <td class="text-center">
                <?php
                $status_class = '';
                $status_text = '';
                $can_confirm_delivery = false;

                switch($row['cart_status']) {
                    case 0:
                        $status_class = 'text-secondary';
                        $status_text = 'Chưa xử lý';
                        break;
                    case 1:
                        $status_class = 'text-warning';
                        $status_text = 'Đã xác nhận';
                        break;
                    case 2:
                        $status_class = 'text-primary';
                        $status_text = 'Đang vận chuyển';
                        $can_confirm_delivery = true;
                        break;
                    case 3:
                        $status_class = 'text-success';
                        $status_text = 'Đã giao hàng';
                        break;
                    default:
                        $status_class = 'text-secondary';
                        $status_text = 'Không xác định';
                }
                echo '<span class="' . $status_class . '">' . $status_text . '</span>';
                ?>
            </td>
            <td class="text-center">
                <?php 
                $payment_method = isset($row['cart_payment']) ? $row['cart_payment'] : 'Chưa chọn';
                echo $payment_method == 'vnpay' ? 'VNPay' : 'Thanh toán khi nhận hàng';
                ?>
            </td>
            <td class="text-center">
                <a href="index.php?quanly=xemdonhang&code=<?php echo $row['code_cart']; ?>"
                   class="btn btn-sm btn-info"
                   title="Xem chi tiết">
                    <i class="fas fa-eye"></i> Xem
                </a>
                <?php if ($can_confirm_delivery): ?>
                    <br><br>
                    <a href="index.php?quanly=xacnhanhoanthanh&code=<?php echo $row['code_cart']; ?>"
                       class="btn btn-sm btn-success"
                       title="Xác nhận đã nhận hàng"
                       onclick="return confirm('Bạn đã nhận được hàng?')">
                        <i class="fas fa-check"></i> Đã nhận hàng
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php
    }
    ?>
        </tbody>
</table>
</div>
