<?php
require_once 'BaseModel.php';

class Cart extends BaseModel {
    protected $table = 'tbl_cart';
    protected $primaryKey = 'id_cart';
    
    public function createOrder($userId, $shippingInfo, $paymentMethod) {
        try {
            $this->db->beginTransaction();
            
            // Tạo mã đơn hàng
            $orderCode = $this->generateOrderCode();
            
            // Lấy thông tin giỏ hàng hiện tại
            $cartItems = $_SESSION['cart'] ?? [];
            $totalAmount = 0;
            
            // Tính tổng tiền
            foreach ($cartItems as $item) {
                $totalAmount += $item['giasp'] * $item['soluong'];
            }
            
            // Tạo đơn hàng
            $orderId = $this->create([
                'code_cart' => $orderCode,
                'id_khachhang' => $userId,
                'cart_status' => 0,
                'cart_payment' => $paymentMethod,
                'cart_shipping' => json_encode($shippingInfo),
                'total_amount' => $totalAmount
            ]);
            
            // Thêm chi tiết đơn hàng
            $cartDetailModel = new CartDetail();
            foreach ($cartItems as $item) {
                $cartDetailModel->create([
                    'code_cart' => $orderCode,
                    'id_sanpham' => $item['id_sanpham'],
                    'soluongmua' => $item['soluong'],
                    'gia_tai_thoi_diem_mua' => $item['giasp']
                ]);
                
                // Cập nhật số lượng sản phẩm
                $this->db->query(
                    "UPDATE tbl_sanpham 
                    SET soluong = soluong - ? 
                    WHERE id_sanpham = ? AND soluong >= ?",
                    [$item['soluong'], $item['id_sanpham'], $item['soluong']]
                );
            }
            
            $this->db->commit();
            unset($_SESSION['cart']);
            
            return $orderCode;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
    
    public function getOrdersByUser($userId, $page = 1, $perPage = 10) {
        return $this->paginate(
            $page,
            $perPage,
            "id_khachhang = ?",
            [$userId],
            'created_at DESC'
        );
    }
    
    public function getOrderDetails($orderCode) {
        return $this->db->fetchAll(
            "SELECT cd.*, sp.tensanpham, sp.hinhanh 
            FROM tbl_cart_details cd 
            JOIN tbl_sanpham sp ON cd.id_sanpham = sp.id_sanpham 
            WHERE cd.code_cart = ?",
            [$orderCode]
        );
    }
    
    public function updateOrderStatus($orderCode, $status) {
        return $this->db->update(
            $this->table,
            ['cart_status' => $status],
            "code_cart = ?",
            [$orderCode]
        );
    }
    
    private function generateOrderCode() {
        return 'ORDER' . date('YmdHis') . rand(1000, 9999);
    }
}