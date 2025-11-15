<?php
$title = 'Sản phẩm';
$description = 'Danh sách sản phẩm của cửa hàng';
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar filters -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Danh mục</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($categories as $category): ?>
                            <li class="mb-2">
                                <a href="/products.php?category=<?= $category['id_danhmuc'] ?>" 
                                   class="text-decoration-none <?= isset($_GET['category']) && $_GET['category'] == $category['id_danhmuc'] ? 'fw-bold' : '' ?>">
                                    <?= htmlspecialchars($category['tendanhmuc']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lọc theo giá</h5>
                </div>
                <div class="card-body">
                    <form action="" method="get">
                        <?php if (isset($_GET['category'])): ?>
                            <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Giá từ</label>
                            <input type="number" name="price_from" class="form-control" 
                                   value="<?= htmlspecialchars($_GET['price_from'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Đến</label>
                            <input type="number" name="price_to" class="form-control"
                                   value="<?= htmlspecialchars($_GET['price_to'] ?? '') ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product list -->
        <div class="col-lg-9">
            <!-- Sort options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="text-muted">Hiển thị <?= count($products['items']) ?> trong số <?= $products['total'] ?> sản phẩm</span>
                </div>
                <div>
                    <select class="form-select" onchange="location = this.value;">
                        <option value="?sort=new" <?= ($_GET['sort'] ?? '') === 'new' ? 'selected' : '' ?>>
                            Mới nhất
                        </option>
                        <option value="?sort=price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>
                            Giá tăng dần
                        </option>
                        <option value="?sort=price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>
                            Giá giảm dần
                        </option>
                        <option value="?sort=name" <?= ($_GET['sort'] ?? '') === 'name' ? 'selected' : '' ?>>
                            Tên A-Z
                        </option>
                    </select>
                </div>
            </div>

            <!-- Products grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($products['items'] as $product): ?>
                    <div class="col">
                        <div class="card h-100">
                            <?php if ($product['giaspcu'] > $product['giasp']): ?>
                                <div class="badge bg-danger position-absolute top-0 end-0 m-2">
                                    Giảm <?= round(($product['giaspcu'] - $product['giasp']) / $product['giaspcu'] * 100) ?>%
                                </div>
                            <?php endif; ?>
                            
                            <img src="/uploads/products/<?= htmlspecialchars($product['hinhanh']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($product['tensanpham']) ?>">
                                 
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/product.php?id=<?= $product['id_sanpham'] ?>" 
                                       class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($product['tensanpham']) ?>
                                    </a>
                                </h5>
                                
                                <div class="mb-2">
                                    <?php if ($product['giaspcu'] > $product['giasp']): ?>
                                        <del class="text-muted me-2">
                                            <?= Utility::formatMoney($product['giaspcu']) ?>
                                        </del>
                                    <?php endif; ?>
                                    <span class="text-danger fw-bold">
                                        <?= Utility::formatMoney($product['giasp']) ?>
                                    </span>
                                </div>

                                <?php if ($product['rating_count'] > 0): ?>
                                    <div class="mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= round($product['rating_avg'])): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="text-muted ms-1">(<?= $product['rating_count'] ?>)</span>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center">
                                    <form action="/cart/add.php" method="post" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                        <input type="hidden" name="product_id" value="<?= $product['id_sanpham'] ?>">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                    
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <form action="/wishlist/add.php" method="post" class="d-inline">
                                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                            <input type="hidden" name="product_id" value="<?= $product['id_sanpham'] ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="far fa-heart"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($products['last_page'] > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $products['last_page']; $i++): ?>
                            <li class="page-item <?= $i === $products['current_page'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?><?= isset($_GET['category']) ? '&category=' . $_GET['category'] : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý thêm vào giỏ hàng bằng Ajax
    document.querySelectorAll('form[action="/cart/add.php"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Cập nhật số lượng trong giỏ hàng
                    document.querySelector('#cart-count').textContent = data.cart_count;
                    
                    // Hiển thị thông báo
                    const toast = new bootstrap.Toast(document.querySelector('.toast'));
                    document.querySelector('.toast-body').textContent = 'Đã thêm sản phẩm vào giỏ hàng';
                    toast.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại');
            });
        });
    });
    
    // Xử lý thêm vào yêu thích bằng Ajax
    document.querySelectorAll('form[action="/wishlist/add.php"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    const button = this.querySelector('button');
                    button.innerHTML = '<i class="fas fa-heart"></i>';
                    button.classList.remove('btn-outline-danger');
                    button.classList.add('btn-danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại');
            });
        });
    });
});
</script>