<?php
class AdminController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index() {
        // Thống kê tổng quan
        $stats = $this->getStats();
        
        // Lấy đơn hàng mới
        $cartModel = new Cart();
        $newOrders = $cartModel->findAll(
            'cart_status = 0',
            [],
            'created_at DESC',
            5
        );
        
        // Lấy sản phẩm sắp hết hàng
        $productModel = new SanPham();
        $lowStock = $productModel->findAll(
            'soluong <= soluong_canh_bao AND tinhtrang = 1',
            [],
            'soluong ASC',
            5
        );
        
        $this->data['stats'] = $stats;
        $this->data['new_orders'] = $newOrders;
        $this->data['low_stock'] = $lowStock;
        $this->render('admin/dashboard');
    }
    
    private function getStats() {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        
        // Doanh thu hôm nay
        $todayRevenue = $this->db->fetchOne(
            "SELECT SUM(total_amount) as total 
            FROM tbl_cart 
            WHERE DATE(created_at) = ? AND cart_status = 1",
            [$today]
        )['total'] ?? 0;
        
        // Doanh thu tháng này
        $monthRevenue = $this->db->fetchOne(
            "SELECT SUM(total_amount) as total 
            FROM tbl_cart 
            WHERE DATE_FORMAT(created_at, '%Y-%m') = ? AND cart_status = 1",
            [$thisMonth]
        )['total'] ?? 0;
        
        // Số đơn hàng mới
        $newOrders = $this->db->fetchOne(
            "SELECT COUNT(*) as total 
            FROM tbl_cart 
            WHERE cart_status = 0"
        )['total'] ?? 0;
        
        // Số sản phẩm sắp hết hàng
        $lowStock = $this->db->fetchOne(
            "SELECT COUNT(*) as total 
            FROM tbl_sanpham 
            WHERE soluong <= soluong_canh_bao AND tinhtrang = 1"
        )['total'] ?? 0;
        
        return [
            'today_revenue' => $todayRevenue,
            'month_revenue' => $monthRevenue,
            'new_orders' => $newOrders,
            'low_stock' => $lowStock
        ];
    }
    
    public function products() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $_GET['search'] ?? '';
        $category = isset($_GET['category']) ? (int)$_GET['category'] : null;
        
        $conditions = [];
        $params = [];
        
        if (!empty($search)) {
            $conditions[] = "tensanpham LIKE ?";
            $params[] = "%{$search}%";
        }
        
        if ($category) {
            $conditions[] = "id_danhmuc = ?";
            $params[] = $category;
        }
        
        $where = !empty($conditions) ? implode(' AND ', $conditions) : '';
        
        $productModel = new SanPham();
        $products = $productModel->paginate($page, 20, $where, $params);
        
        $this->data['products'] = $products;
        $this->data['search'] = $search;
        $this->data['category'] = $category;
        $this->render('admin/products/index');
    }
    
    public function editProduct($id = null) {
        $productModel = new SanPham();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::checkCSRF();
            
            try {
                $data = [
                    'tensanpham' => $_POST['tensanpham'],
                    'masp' => $_POST['masp'],
                    'giasp' => $_POST['giasp'],
                    'giaspcu' => $_POST['giaspcu'] ?? 0,
                    'soluong' => $_POST['soluong'],
                    'id_danhmuc' => $_POST['id_danhmuc'],
                    'tomtat' => $_POST['tomtat'],
                    'noidung' => $_POST['noidung'],
                    'tinhtrang' => $_POST['tinhtrang'],
                    'thong_so_ky_thuat' => json_encode($_POST['specs'] ?? []),
                    'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                    'is_new' => isset($_POST['is_new']) ? 1 : 0,
                    'updated_by' => $_SESSION['admin_id']
                ];
                
                // Upload hình ảnh
                if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === 0) {
                    Security::validateFile($_FILES['hinhanh']);
                    $data['hinhanh'] = Utility::uploadImage($_FILES['hinhanh'], 'uploads/products');
                }
                
                // Upload gallery
                $gallery = [];
                if (isset($_FILES['gallery'])) {
                    foreach ($_FILES['gallery']['tmp_name'] as $key => $tmp_name) {
                        if ($_FILES['gallery']['error'][$key] === 0) {
                            $_FILES['current'] = [
                                'name' => $_FILES['gallery']['name'][$key],
                                'type' => $_FILES['gallery']['type'][$key],
                                'tmp_name' => $tmp_name,
                                'error' => $_FILES['gallery']['error'][$key],
                                'size' => $_FILES['gallery']['size'][$key]
                            ];
                            Security::validateFile($_FILES['current']);
                            $gallery[] = Utility::uploadImage($_FILES['current'], 'uploads/products');
                        }
                    }
                    if (!empty($gallery)) {
                        $data['gallery'] = json_encode($gallery);
                    }
                }
                
                if ($id) {
                    $productModel->update($id, $data);
                    $_SESSION['success'] = 'Cập nhật sản phẩm thành công';
                } else {
                    $data['created_by'] = $_SESSION['admin_id'];
                    $productModel->create($data);
                    $_SESSION['success'] = 'Thêm sản phẩm thành công';
                }
                
                $this->redirect('/admin/products.php');
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                if ($id) {
                    $this->redirect("/admin/products.php?action=edit&id={$id}");
                } else {
                    $this->redirect('/admin/products.php?action=create');
                }
            }
        }
        
        if ($id) {
            $product = $productModel->find($id);
            if (!$product) {
                $this->redirect('/admin/products.php');
            }
            $this->data['product'] = $product;
        }
        
        $this->data['categories'] = (new DanhMuc())->findAll('is_active = 1');
        $this->render('admin/products/form');
    }
    
    public function deleteProduct($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/products.php');
        }
        
        Security::checkCSRF();
        
        try {
            $productModel = new SanPham();
            $product = $productModel->find($id);
            
            if ($product) {
                // Xóa hình ảnh
                if (!empty($product['hinhanh'])) {
                    Utility::deleteImage($product['hinhanh'], 'uploads/products');
                }
                
                // Xóa gallery
                if (!empty($product['gallery'])) {
                    $gallery = json_decode($product['gallery'], true);
                    foreach ($gallery as $image) {
                        Utility::deleteImage($image, 'uploads/products');
                    }
                }
                
                $productModel->delete($id);
                $_SESSION['success'] = 'Xóa sản phẩm thành công';
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        $this->redirect('/admin/products.php');
    }
}