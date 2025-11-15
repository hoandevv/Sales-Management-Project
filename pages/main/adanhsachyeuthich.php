<?php
// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo "<p class='alert alert-warning'>Vui lòng đăng nhập để sử dụng tính năng này.</p>";
    exit();
}

// Lấy ID người dùng từ session
$user_id = $_SESSION['user']['id'];
$user_name = $_SESSION['user']['name'] ?? 'Khách';
?>

<div class="wishlist-container">
    <div class="wishlist-header">
        <h2 class="wishlist-title">Danh sách sản phẩm yêu thích</h2>
        <p class="user-info">
            Xin chào: <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
            <span class="user-id">(ID: <?php echo $user_id; ?>)</span>
        </p>
    </div>

    <div class="product-grid">
        <?php
        $sql = "SELECT tbl_sanpham.*, tbl_sanphamyeuthich.id as favorite_id 
                FROM tbl_sanphamyeuthich 
                INNER JOIN tbl_sanpham ON tbl_sanphamyeuthich.product_id = tbl_sanpham.id_sanpham 
                WHERE tbl_sanphamyeuthich.user_id = '$user_id'
                ORDER BY tbl_sanphamyeuthich.added_at DESC";
        
        $query = mysqli_query($mysqli, $sql);

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_array($query)) {
                $product_id = $row['id_sanpham'];
                ?>
                <div class="product-card" id="product-<?php echo $product_id; ?>">
                    <a href="index.php?quanly=sanpham&id=<?php echo $product_id; ?>" class="product-link">
                        <div class="product-image">
                            <img src="admincp/modules/quanlysp/uploads/<?php echo $row['hinhanh']; ?>" 
                                 alt="<?php echo $row['tensanpham']; ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-title"><?php echo $row['tensanpham']; ?></h3>  
                            <p class="product-price"><?php echo number_format($row['giasp'], 0, ',', '.'); ?>đ</p>
                        </div>
                    </a>
                   
                    <div class="product-actions">
                        <?php if (isset($_SESSION['user'])) { ?>
                            <!-- Form yêu thích -->
                            <form action="pages/main/axulysanphamyeuthich.php" method="POST" class="favorite-form">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <?php
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

                            <!-- Form giỏ hàng -->
                            <form action="pages/main/themgiohang.php?idsanpham=<?php echo $product_id; ?>" 
                                  method="POST" class="cart-form">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <button type="submit" name="add_to_cart" class="btn-action btn-cart">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <span>Thêm vào giỏ</span>
                                </button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            <?php
            }
        } else {
            echo "<div class='empty-wishlist'>
                    <i class='far fa-heart'></i>
                    <p>Chưa có sản phẩm yêu thích nào</p>
                  </div>";
        }
        ?>
    </div>
</div>
