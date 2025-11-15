<?php
// Lấy từ khóa và kết quả từ session
$tukhoa = isset($_SESSION['search_keyword']) ? $_SESSION['search_keyword'] : '';
$query_pro = isset($_SESSION['search_results']) ? $_SESSION['search_results'] : array();
?>

<h3 class="tilte_product">Từ khóa tìm kiếm: <?php echo ($tukhoa); ?> </h3>

<div class="product">
    <?php
    if (!empty($query_pro)) {
        foreach ($query_pro as $row) {
    ?>
        <li class="product_item">
            <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham']; ?>" class="product_link">
                <div class="product_details">
                    <p class="category_name">Thương hiệu: <?php echo ($row['tendanhmuc']); ?></p>
                    <p class="product_title"><?php echo ($row['tensanpham']); ?></p>
                    <div class="product_image">
                        <img src="admincp/modules/quanlysp/uploads/<?php echo ($row['hinhanh']); ?>" alt="Hình ảnh sản phẩm">
                    </div>
                    <p class="product_price_cu"><?php echo number_format($row['giaspcu'], 0, ',', '.'); ?> ₫</p>
                    <p class="product_price">Giá: <?php echo number_format($row['giasp'], 0, ',', '.'); ?> ₫</p>
                </div>
            </a> 

            <div class="product_actions">
            <?php if (isset($_SESSION['dangki'])) { ?>
                <!-- Form thêm yêu thích -->
                <form action="pages/main/axulysanphamyeuthich.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id_sanpham']; ?>">
                    <?php
                    $user_id = $_SESSION['id_khachhang'];
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
    } else {
        echo "<p>Không tìm thấy sản phẩm nào với từ khóa '" . ($tukhoa) . "'.</p>";
    }
    ?>
</div>
