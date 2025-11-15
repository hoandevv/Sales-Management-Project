<?php
// Kết nối database
include('../config.php');

// Thống kê tổng quan
// 1. Tổng số đơn hàng
$sql_total_orders = "SELECT COUNT(*) as total_orders FROM tbl_cart";
$result_total_orders = mysqli_query($mysqli, $sql_total_orders);
$row_total_orders = mysqli_fetch_assoc($result_total_orders);
$total_orders = $row_total_orders['total_orders'];

// 2. Tổng doanh thu
$sql_total_revenue = "SELECT SUM(tbl_cart_details.soluongmua * tbl_sanpham.giasp) as total_revenue
                      FROM tbl_cart_details
                      INNER JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham
                      INNER JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart
                      WHERE tbl_cart.cart_status = 3";
$result_total_revenue = mysqli_query($mysqli, $sql_total_revenue);
$row_total_revenue = mysqli_fetch_assoc($result_total_revenue);
$total_revenue = $row_total_revenue['total_revenue'] ? $row_total_revenue['total_revenue'] : 0;

// 3. Tổng số sản phẩm
$sql_total_products = "SELECT COUNT(*) as total_products FROM tbl_sanpham";
$result_total_products = mysqli_query($mysqli, $sql_total_products);
$row_total_products = mysqli_fetch_assoc($result_total_products);
$total_products = $row_total_products['total_products'];

// 4. Tổng số khách hàng
$sql_total_customers = "SELECT COUNT(*) as total_customers FROM tbl_dangki";
$result_total_customers = mysqli_query($mysqli, $sql_total_customers);
$row_total_customers = mysqli_fetch_assoc($result_total_customers);
$total_customers = $row_total_customers['total_customers'];

// 5. Đơn hàng trong tháng này
$current_month = date('Y-m');
$sql_monthly_orders = "SELECT COUNT(*) as monthly_orders FROM tbl_cart
                       WHERE DATE_FORMAT(created_at, '%Y-%m') = '$current_month'";
$result_monthly_orders = mysqli_query($mysqli, $sql_monthly_orders);
$row_monthly_orders = mysqli_fetch_assoc($result_monthly_orders);
$monthly_orders = $row_monthly_orders['monthly_orders'];

// 6. Doanh thu tháng này
$sql_monthly_revenue = "SELECT SUM(tbl_cart_details.soluongmua * tbl_sanpham.giasp) as monthly_revenue
                        FROM tbl_cart_details
                        INNER JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham
                        INNER JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart
                        WHERE tbl_cart.cart_status = 3 AND DATE_FORMAT(tbl_cart.created_at, '%Y-%m') = '$current_month'";
$result_monthly_revenue = mysqli_query($mysqli, $sql_monthly_revenue);
$row_monthly_revenue = mysqli_fetch_assoc($result_monthly_revenue);
$monthly_revenue = $row_monthly_revenue['monthly_revenue'] ? $row_monthly_revenue['monthly_revenue'] : 0;

// 7. Top 5 sản phẩm bán chạy nhất
$sql_top_products = "SELECT tbl_sanpham.tensanpham, SUM(tbl_cart_details.soluongmua) as total_sold
                     FROM tbl_cart_details
                     INNER JOIN tbl_sanpham ON tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham
                     INNER JOIN tbl_cart ON tbl_cart_details.code_cart = tbl_cart.code_cart
                     WHERE tbl_cart.cart_status = 3
                     GROUP BY tbl_cart_details.id_sanpham
                     ORDER BY total_sold DESC
                     LIMIT 5";
$result_top_products = mysqli_query($mysqli, $sql_top_products);

// 8. Thống kê đơn hàng theo trạng thái
$sql_order_status = "SELECT cart_status, COUNT(*) as count FROM tbl_cart GROUP BY cart_status";
$result_order_status = mysqli_query($mysqli, $sql_order_status);
$order_status_data = [];
while ($row = mysqli_fetch_assoc($result_order_status)) {
    switch ($row['cart_status']) {
        case 0:
            $status_text = 'Chưa xử lý';
            break;
        case 1:
            $status_text = 'Đã xác nhận';
            break;
        case 2:
            $status_text = 'Đang vận chuyển';
            break;
        case 3:
            $status_text = 'Đã giao hàng';
            break;
        default:
            $status_text = 'Không xác định';
    }
    $order_status_data[$status_text] = $row['count'];
}
?>

<div class="dashboard-container">
    <h2 class="dashboard-title">Dashboard Tổng Quan</h2>

    <!-- Thống kê chính -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($total_orders); ?></h3>
                <p>Tổng đơn hàng</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($total_revenue, 0, ',', '.'); ?> ₫</h3>
                <p>Tổng doanh thu</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($total_products); ?></h3>
                <p>Tổng sản phẩm</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($total_customers); ?></h3>
                <p>Tổng khách hàng</p>
            </div>
        </div>
    </div>

    <!-- Thống kê tháng này -->
    <div class="monthly-stats">
        <h3>Thống kê tháng <?php echo date('m/Y'); ?></h3>
        <div class="monthly-grid">
            <div class="monthly-card">
                <h4><?php echo number_format($monthly_orders); ?></h4>
                <p>Đơn hàng mới</p>
            </div>
            <div class="monthly-card">
                <h4><?php echo number_format($monthly_revenue, 0, ',', '.'); ?> ₫</h4>
                <p>Doanh thu</p>
            </div>
        </div>
    </div>

    <!-- Top sản phẩm bán chạy -->
    <div class="top-products">
        <h3>Top 5 sản phẩm bán chạy</h3>
        <table class="top-products-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng bán</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($result_top_products)) {
                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . htmlspecialchars($row['tensanpham']) . "</td>";
                    echo "<td>" . number_format($row['total_sold']) . "</td>";
                    echo "</tr>";
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Trạng thái đơn hàng -->
    <div class="order-status">
        <h3>Trạng thái đơn hàng</h3>
        <div class="status-chart">
            <?php foreach ($order_status_data as $status => $count): ?>
                <div class="status-item">
                    <span class="status-label"><?php echo $status; ?>:</span>
                    <span class="status-count"><?php echo number_format($count); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.dashboard-title {
    color: #333;
    margin-bottom: 30px;
    font-size: 28px;
    font-weight: bold;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    color: white;
    font-size: 24px;
}

.stat-content h3 {
    margin: 0;
    font-size: 32px;
    font-weight: bold;
    color: #333;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 14px;
}

.monthly-stats {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.monthly-stats h3 {
    margin-top: 0;
    color: #333;
    margin-bottom: 20px;
}

.monthly-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.monthly-card {
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.monthly-card h4 {
    margin: 0;
    font-size: 28px;
    color: #667eea;
    font-weight: bold;
}

.monthly-card p {
    margin: 5px 0 0 0;
    color: #666;
}

.top-products, .order-status {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.top-products h3, .order-status h3 {
    margin-top: 0;
    color: #333;
    margin-bottom: 20px;
}

.top-products-table {
    width: 100%;
    border-collapse: collapse;
}

.top-products-table th, .top-products-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.top-products-table th {
    background: #f8f9fa;
    font-weight: bold;
}

.status-chart {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.status-item {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 8px;
    min-width: 150px;
}

.status-label {
    font-weight: bold;
    color: #333;
    margin-right: 10px;
}

.status-count {
    font-size: 18px;
    color: #667eea;
    font-weight: bold;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .monthly-grid {
        grid-template-columns: 1fr;
    }

    .stat-card {
        flex-direction: column;
        text-align: center;
    }

    .stat-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
}
</style>
