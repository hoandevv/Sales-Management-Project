<?php
    // Include database config if not already included
    if (!isset($mysqli)) {
        require_once __DIR__ . '/../../admincp/config/config.php';
    }

    // Xử lý phân trang
    if (isset($_GET['trang'])) {
        $page = $_GET['trang'];
    } else {
        $page = 1;
    }

    if ($page == 1) {
        $begin = 0;
    } else {
        $begin = ($page - 1) * 8;
    }

    // Câu lệnh SQL để lấy sản phẩm theo phân trang
    $sql_pro = "SELECT * FROM tbl_sanpham, tbl_danhmuc WHERE tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc ORDER BY tbl_sanpham.id_sanpham DESC LIMIT $begin, 8";
    $query_pro = mysqli_query($mysqli, $sql_pro);
?>

<div class="product">
    <?php
    while ($row = mysqli_fetch_array($query_pro)) {
    ?>
        <li class="product_item">
            <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham'] ?>" class="product_link">
                <div class="product_details">
                    <p class="category_name">Thương hiệu: <?php echo ($row['tendanhmuc']); ?></p>
                    <p class="product_title"><?php echo ($row['tensanpham']); ?></p>
                    <div class="product_image">
                        <img src="admincp/modules/quanlysp/uploads/<?php echo ($row['hinhanh']); ?>" alt="Hình ảnh sản phẩm">
                    </div>
                    <p class="product_price_cu"><?php echo number_format($row['giaspcu'], 0, ',', '.'); ?> ₫</p>
                    <p class="product_price" style="margin-left: 12px;">Giá: <?php echo number_format($row['giasp'], 0, ',', '.'); ?> ₫</p>
                </div>
            </a>
        <div class="product_actions">
            <?php if (isset($_SESSION['user'])) { ?>
                <!-- Form thêm yêu thích -->
                <form action="pages/main/axulysanphamyeuthich.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id_sanpham']; ?>">
                    <?php
                    $user_id = $_SESSION['user']['id'];
                    $product_id = $row['id_sanpham'];
                    $sql_check = "SELECT * FROM tbl_sanphamyeuthich WHERE user_id='$user_id' AND product_id='$product_id'";
                    $query_check = mysqli_query($mysqli, $sql_check);

                    if (mysqli_num_rows($query_check) > 0) {
                        echo '<button type="submit" name="remove_favorite" class="btn-action btn-favorite active">
                                <i class="fas fa-heart"></i>
                                <span>Đã yêu thích</span>
                            </button>';
                    } else {
                        echo '<button type="submit" name="add_favorite" class="btn-action btn-favorite">
                                <i class="far fa-heart"></i>
                                <span>Thêm yêu thích</span>
                            </button>';
                    }
                    ?>
                </form>

                <!-- Form thêm vào giỏ hàng -->
                <form action="pages/main/themgiohang.php?idsanpham=<?php echo $product_id; ?>" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <button type="submit" name="add_to_cart" class="btn-action btn-cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Thêm vào giỏ</span>
                    </button>
                </form>
            <?php } ?>
            </div>
        </li>
    <?php
    }
    ?>
</div>

<div style="clear: both;"></div>

<?php
    // Lấy tổng số sản phẩm
    $sql_trang = mysqli_query($mysqli, "SELECT * FROM tbl_sanpham");
    $row_count = mysqli_num_rows($sql_trang); 
    $trang = ceil($row_count / 8); 
?>

<p>Trang hiện tại: <?php echo $page; ?> / <?php echo $trang; ?></p>

<ul class="list_trang">

    <?php 
    if ($page > 1) {
        echo '<li><a href="index.php?trang=1">Trang đầu</a></li>';
        echo '<li><a href="index.php?trang=' . ($page - 1) . '">Trang trước</a></li>';
    }

    // Hiển thị các trang giữa
    for ($i = 1; $i <= $trang; $i++) {
        echo '<li ' . ($i == $page ? 'style="background-color: #0275d8;"' : '') . '><a href="index.php?trang=' . $i . '">' . $i . '</a></li>';
    }

    // Hiển thị trang tiếp theo và trang cuối
    if ($page < $trang) {
        echo '<li><a href="index.php?trang=' . ($page + 1) . '">Trang tiếp theo</a></li>';
        echo '<li><a href="index.php?trang=' . $trang . '">Trang cuối</a></li>';
    }
    ?>
</ul>
