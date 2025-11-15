<?php
class CartController extends BaseController {
    private $cartModel;
    
    public function __construct() {
        parent::__construct();
        $this->cartModel = new Cart();
    }
    
    public function index() {
        $this->data['cart_items'] = $_SESSION['cart'] ?? [];
        $this->render('cart/index');
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/cart.php');
        }
        
        Security::checkCSRF();
        
        $quantities = $_POST['quantity'] ?? [];
        $productModel = new SanPham();
        
        foreach ($quantities as $productId => $quantity) {
            $productId = (int)$productId;
            $quantity = (int)$quantity;
            
            if ($quantity < 1) {
                unset($_SESSION['cart'][$productId]);
                continue;
            }
            
            $product = $productModel->find($productId);
            if (!$product || $quantity > $product['soluong']) {
                $_SESSION['error'] = 'Số lượng sản phẩm không hợp lệ';
                $this->redirect('/cart.php');
            }
            
            $_SESSION['cart'][$productId]['soluong'] = $quantity;
        }
        
        $_SESSION['success'] = 'Cập nhật giỏ hàng thành công';
        $this->redirect('/cart.php');
    }
    
    public function checkout() {
        $this->requireLogin();
        
        if (empty($_SESSION['cart'])) {
            $this->redirect('/cart.php');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::checkCSRF();
            
            try {
                $shippingInfo = [
                    'name' => $_POST['name'],
                    'phone' => $_POST['phone'],
                    'address' => $_POST['address'],
                    'note' => $_POST['note'] ?? ''
                ];
                
                $paymentMethod = $_POST['payment_method'];
                
                $orderCode = $this->cartModel->createOrder(
                    $_SESSION['user_id'],
                    $shippingInfo,
                    $paymentMethod
                );
                
                // Xử lý thanh toán online nếu cần
                if ($paymentMethod === 'online') {
                    $this->processOnlinePayment($orderCode);
                }
                
                $_SESSION['success'] = 'Đặt hàng thành công';
                $this->redirect('/order-success.php?code=' . $orderCode);
                
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('/checkout.php');
            }
        }
        
        $this->data['cart_items'] = $_SESSION['cart'];
        $this->render('cart/checkout');
    }
    
    public function remove($productId) {
        $productId = (int)$productId;
        
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['success'] = 'Xóa sản phẩm thành công';
        }
        
        if ($this->isAjax()) {
            $this->json([
                'success' => true,
                'cart_count' => count($_SESSION['cart'])
            ]);
        }
        
        $this->redirect('/cart.php');
    }
    
    public function clear() {
        unset($_SESSION['cart']);
        
        if ($this->isAjax()) {
            $this->json(['success' => true]);
        }
        
        $_SESSION['success'] = 'Xóa giỏ hàng thành công';
        $this->redirect('/cart.php');
    }
    
    private function processOnlinePayment($orderCode) {
        // TODO: Implement payment gateway integration
        throw new Exception('Phương thức thanh toán này đang được phát triển');
    }
    
    public function orderSuccess() {
        $orderCode = $_GET['code'] ?? '';
        
        if (empty($orderCode)) {
            $this->redirect('/');
        }
        
        $order = $this->cartModel->findBy('code_cart', $orderCode);
        if (!$order || $order['id_khachhang'] !== $_SESSION['user_id']) {
            $this->redirect('/');
        }
        
        $orderDetails = $this->cartModel->getOrderDetails($orderCode);
        
        $this->data['order'] = $order;
        $this->data['order_details'] = $orderDetails;
        $this->render('cart/success');
    }
}