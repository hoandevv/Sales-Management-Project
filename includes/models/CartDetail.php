<?php
require_once 'BaseModel.php';

class CartDetail extends BaseModel {
    protected $table = 'tbl_cart_details';
    protected $primaryKey = 'id_cart_details';
    
    public function getByOrderCode($orderCode) {
        return $this->db->fetchAll(
            "SELECT cd.*, sp.tensanpham, sp.hinhanh, sp.masp 
            FROM {$this->table} cd 
            JOIN tbl_sanpham sp ON cd.id_sanpham = sp.id_sanpham 
            WHERE cd.code_cart = ?",
            [$orderCode]
        );
    }
    
    public function updateQuantity($id, $quantity) {
        return $this->update($id, ['soluongmua' => $quantity]);
    }
}