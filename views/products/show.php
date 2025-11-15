<?php
$title = htmlspecialchars($product['tensanpham']);
$description = htmlspecialchars($product['tomtat']);
?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <?php foreach ($breadcrumbs as $breadcrumb): ?>
                <li class="breadcrumb-item">
                    <a href="/products.php?category=<?= $breadcrumb['id_danhmuc'] ?>">
                        <?= htmlspecialchars($breadcrumb['tendanhmuc']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?= htmlspecialchars($product['tensanpham']) ?>
            </li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product images -->
        <div class="col-md-6 mb-4">
            <div class="product-gallery">
                <div class="main-image mb-3">
                    <img src="/uploads/products/<?= htmlspecialchars($product['hinhanh']) ?>" 
                         class="img-fluid" 
                         alt="<?= htmlspecialchars($product['tensanpham']) ?>"
                         id="main-product-image">
                </div>
                
                <?php if (!empty($product['gallery'])): ?>
                    <div class="thumbnails row g-2">
                        <div class="col-3">
                            <img src="/uploads/products/<?= htmlspecialchars($product['hinhanh']) ?>" 
                                 class="img-thumbnail active"
                                 onclick="changeMainImage(this.src)"
                                 alt="<?= htmlspecialchars($product['tensanpham']) ?>">
                        </div>
                        <?php foreach (json_decode($product['gallery'], true) as $image): ?>
                            <div class="col-3">
                                <img src="/uploads/products/<?= htmlspecialchars($image) ?>" 
                                     class="img-thumbnail"
                                     onclick="changeMainImage(this.src)"
                                     alt="<?= htmlspecialchars($product['tensanpham']) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product info -->
        <div class="col-md-6">
            <h1 class="mb-3"><?= htmlspecialchars($product['tensanpham']) ?></h1>
            
            <div class="mb-3">
                <span class="text-danger h3 fw-bold">
                    <?= Utility::formatMoney($product['giasp']) ?>
                </span>
                <?php if ($product['giaspcu'] > $product['giasp']): ?>
                    <del class="text-muted ms-2">
                        <?= Utility::formatMoney($product['giaspcu']) ?>
                    </del>
                    <span class="badge bg-danger ms-2">
                        Giảm <?= round(($product['giaspcu'] - $product['giasp']) / $product['giaspcu'] * 100) ?>%
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($product['rating_count'] > 0): ?>
                <div class="mb-3">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= round($product['rating_avg'])): ?>
                            <i class="fas fa-star text-warning"></i>
                        <?php else: ?>
                            <i class="far fa-star text-warning"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <span class="text-muted ms-1">
                        <?= number_format($product['rating_avg'], 1) ?> 
                        (<?= $product['rating_count'] ?> đánh giá)
                    </span>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <p><?= nl2br(htmlspecialchars($product['tomtat'])) ?></p>
            </div>

            <?php if ($product['soluong'] > 0): ?>
                <form action="/cart/add.php" method="post" class="mb-3">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="hidden" name="product_id" value="<?= $product['id_sanpham'] ?>">
                    
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <label class="form-label">Số lượng:</label>
                            <input type="number" name="quantity" value="1" min="1" 
                                   max="<?= $product['soluong'] ?>" class="form-control" required>
                        </div>
                        <div class="col-auto">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                                
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button type="submit" formaction="/wishlist/add.php" 
                                            class="btn btn-outline-danger">
                                        <i class="far fa-heart"></i> Yêu thích
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    Sản phẩm tạm hết hàng
                </div>
            <?php endif; ?>

            <?php if (!empty($product['thong_so_ky_thuat'])): ?>
                <div class="mb-4">
                    <h4>Thông số kỹ thuật</h4>
                    <table class="table">
                        <tbody>
                            <?php foreach (json_decode($product['thong_so_ky_thuat'], true) as $key => $value): ?>
                                <tr>
                                    <th><?= htmlspecialchars($key) ?></th>
                                    <td><?= htmlspecialchars($value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Product description -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                       href="#description" role="tab">Mô tả sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                       href="#reviews" role="tab">Đánh giá (<?= $product['rating_count'] ?>)</a>
                </li>
            </ul>

            <div class="tab-content py-4" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <?= $product['noidung'] ?>
                </div>

                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="/products/rate.php" method="post" class="mb-4">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="product_id" value="<?= $product['id_sanpham'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Đánh giá của bạn</label>
                                <div class="rating">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" value="<?= $i ?>" 
                                               id="rating<?= $i ?>" required>
                                        <label for="rating<?= $i ?>">☆</label>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nhận xét</label>
                                <textarea name="comment" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    <?php else: ?>
                        <p>Vui lòng <a href="/login.php">đăng nhập</a> để đánh giá sản phẩm.</p>
                    <?php endif; ?>

                    <div class="reviews">
                        <?php foreach ($ratings['items'] as $rating): ?>
                            <div class="review border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?= htmlspecialchars($rating['tenkhachhang']) ?></strong>
                                        <div class="text-warning">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $rating['rating']): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= Utility::formatDate($rating['created_at']) ?>
                                    </small>
                                </div>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($rating['nhan_xet'])) ?></p>
                            </div>
                        <?php endforeach; ?>

                        <?php if ($ratings['last_page'] > 1): ?>
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $ratings['last_page']; $i++): ?>
                                        <li class="page-item <?= $i === $ratings['current_page'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?id=<?= $product['id_sanpham'] ?>&page=<?= $i ?>">
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
        </div>
    </div>

    <!-- Related products -->
    <?php if (!empty($related_products)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Sản phẩm liên quan</h3>
                
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach ($related_products as $related): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="/uploads/products/<?= htmlspecialchars($related['hinhanh']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($related['tensanpham']) ?>">
                                     
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="/product.php?id=<?= $related['id_sanpham'] ?>" 
                                           class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($related['tensanpham']) ?>
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-danger fw-bold">
                                        <?= Utility::formatMoney($related['giasp']) ?>
                                    </p>
                                    
                                    <button class="btn btn-primary btn-sm w-100" 
                                            onclick="addToCart(<?= $related['id_sanpham'] ?>)">
                                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function changeMainImage(src) {
    document.getElementById('main-product-image').src = src;
    document.querySelectorAll('.thumbnails img').forEach(img => {
        img.classList.remove('active');
        if (img.src === src) {
            img.classList.add('active');
        }
    });
}

function addToCart(productId) {
    fetch('/cart/add.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `product_id=${productId}&csrf_token=<?= $csrf_token ?>`
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            document.querySelector('#cart-count').textContent = data.cart_count;
            const toast = new bootstrap.Toast(document.querySelector('.toast'));
            document.querySelector('.toast-body').textContent = 'Đã thêm sản phẩm vào giỏ hàng';
            toast.show();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra, vui lòng thử lại');
    });
}

// Initialize rating system
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('.rating input');
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            ratingInputs.forEach(inp => {
                if (inp.value <= this.value) {
                    inp.parentElement.classList.add('checked');
                } else {
                    inp.parentElement.classList.remove('checked');
                }
            });
        });
    });
});
</script>

<style>
.product-gallery .thumbnails img {
    cursor: pointer;
    transition: all 0.3s;
}

.product-gallery .thumbnails img.active {
    border-color: #0d6efd;
}

.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating input {
    display: none;
}

.rating label {
    cursor: pointer;
    font-size: 30px;
    color: #ddd;
    transition: color 0.3s;
}

.rating input:checked ~ label,
.rating label:hover,
.rating label:hover ~ label {
    color: #ffd700;
}

.rating input:checked + label:hover,
.rating input:checked ~ label:hover,
.rating label:hover ~ input:checked ~ label,
.rating input:checked ~ label:hover ~ label {
    color: #ffc107;
}
</style>