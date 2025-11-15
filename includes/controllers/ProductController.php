<?php
class ProductController extends BaseController {
    private $productModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = new SanPham();
    }
    
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $this->data['products'] = $this->productModel->paginate(
            $page, 
            12, 
            'tinhtrang = 1'
        );
        $this->render('products/index');
    }
    
    public function show($id) {
        $product = $this->productModel->find($id);
        if (!$product) {
            $this->redirect('/404.php');
        }
        
        // Tăng lượt xem
        $this->productModel->updateViews($id);
        
        // Lấy sản phẩm liên quan
        $relatedProducts = $this->productModel->getRelatedProducts(
            $id,
            $product['id_danhmuc']
        );
        
        // Lấy đánh giá sản phẩm
        $ratingModel = new DanhGia();
        $ratings = $ratingModel->getByProduct($id);
        
        $this->data['product'] = $product;
        $this->data['related_products'] = $relatedProducts;
        $this->data['ratings'] = $ratings;
        
        $this->render('products/show');
    }
    
    public function category($id) {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $category = (new DanhMuc())->find($id);
        
        if (!$category) {
            $this->redirect('/404.php');
        }
        
        $products = $this->productModel->getByCategory($id, $page);
        
        $this->data['category'] = $category;
        $this->data['products'] = $products;
        $this->render('products/category');
    }
    
    public function search() {
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        if (empty($keyword)) {
            $this->redirect('/');
        }
        
        $products = $this->productModel->searchProducts($keyword, $page);
        
        $this->data['keyword'] = $keyword;
        $this->data['products'] = $products;
        $this->render('products/search');
    }
    
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }
        
        Security::checkCSRF();
        
        $productId = (int)$_POST['product_id'];
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        $product = $this->productModel->find($productId);
        if (!$product || $product['tinhtrang'] != 1) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh']);
            }
            $_SESSION['error'] = 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh';
            $this->redirect('/cart.php');
        }
        
        if ($quantity > $product['soluong']) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Số lượng sản phẩm trong kho không đủ']);
            }
            $_SESSION['error'] = 'Số lượng sản phẩm trong kho không đủ';
            $this->redirect('/cart.php');
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['soluong'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id_sanpham' => $productId,
                'tensanpham' => $product['tensanpham'],
                'hinhanh' => $product['hinhanh'],
                'giasp' => $product['giasp'],
                'soluong' => $quantity
            ];
        }
        
        if ($this->isAjax()) {
            $this->json([
                'success' => true,
                'cart_count' => count($_SESSION['cart'])
            ]);
        }
        
        $_SESSION['success'] = 'Thêm vào giỏ hàng thành công';
        $this->redirect('/cart.php');
    }
    
    public function addToWishlist() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }
        
        Security::checkCSRF();
        
        $productId = (int)$_POST['product_id'];
        $userId = $_SESSION['user_id'];
        
        try {
            $userModel = new User();
            $result = $userModel->addToWishlist($userId, $productId);
            
            if ($this->isAjax()) {
                $this->json(['success' => true]);
            }
            
            $_SESSION['success'] = 'Thêm vào danh sách yêu thích thành công';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            
        } catch (Exception $e) {
            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()]);
            }
            $_SESSION['error'] = $e->getMessage();
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
    }
    
    public function rate() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }
        
        Security::checkCSRF();
        
        $productId = (int)$_POST['product_id'];
        $rating = (int)$_POST['rating'];
        $comment = trim($_POST['comment'] ?? '');
        
        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = 'Đánh giá không hợp lệ';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }
        
        try {
            $ratingModel = new DanhGia();
            $ratingModel->create([
                'id_sanpham' => $productId,
                'id_khachhang' => $_SESSION['user_id'],
                'rating' => $rating,
                'nhan_xet' => $comment
            ]);
            
            // Cập nhật rating trung bình của sản phẩm
            $avgRating = $ratingModel->getAverageRating($productId);
            $this->productModel->update($productId, [
                'rating_avg' => $avgRating['avg'],
                'rating_count' => $avgRating['count']
            ]);
            
            $_SESSION['success'] = 'Gửi đánh giá thành công';
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
    }
}