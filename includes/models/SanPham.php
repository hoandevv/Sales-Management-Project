<?php
require_once 'BaseModel.php';

class SanPham extends BaseModel {
    protected $table = 'tbl_sanpham';
    protected $primaryKey = 'id_sanpham';
    
    public function getFeatureProducts($limit = 8) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
            WHERE tinhtrang = 1 AND is_featured = 1 
            ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
    
    public function getNewProducts($limit = 8) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
            WHERE tinhtrang = 1 AND is_new = 1 
            ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
    
    public function getBestSellers($limit = 8) {
        return $this->db->fetchAll(
            "SELECT p.*, COUNT(cd.id_sanpham) as so_luong_ban 
            FROM {$this->table} p 
            LEFT JOIN tbl_cart_details cd ON p.id_sanpham = cd.id_sanpham 
            LEFT JOIN tbl_cart c ON cd.code_cart = c.code_cart 
            WHERE p.tinhtrang = 1 AND c.cart_status = 1 
            GROUP BY p.id_sanpham 
            ORDER BY so_luong_ban DESC LIMIT ?",
            [$limit]
        );
    }
    
    public function searchProducts($keyword, $page = 1, $perPage = 12) {
        $conditions = "MATCH(tensanpham, tomtat, noidung) AGAINST(? IN BOOLEAN MODE) AND tinhtrang = 1";
        return $this->paginate($page, $perPage, $conditions, [$keyword], 'created_at DESC');
    }
    
    public function getByCategory($categoryId, $page = 1, $perPage = 12) {
        return $this->paginate(
            $page,
            $perPage,
            "id_danhmuc = ? AND tinhtrang = 1",
            [$categoryId],
            'created_at DESC'
        );
    }
    
    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
            WHERE id_danhmuc = ? AND id_sanpham != ? AND tinhtrang = 1 
            ORDER BY RAND() LIMIT ?",
            [$categoryId, $productId, $limit]
        );
    }
    
    public function updateViews($productId) {
        $this->db->query(
            "UPDATE {$this->table} SET views = views + 1 WHERE id_sanpham = ?",
            [$productId]
        );
    }
    
    public function create($data) {
        if (!isset($data['slug'])) {
            $data['slug'] = $this->createSlug($data['tensanpham']);
        }
        return parent::create($data);
    }
    
    public function update($id, $data) {
        if (isset($data['tensanpham']) && !isset($data['slug'])) {
            $data['slug'] = $this->createSlug($data['tensanpham']);
        }
        return parent::update($id, $data);
    }
    
    private function createSlug($str) {
        $str = mb_strtolower(trim($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}