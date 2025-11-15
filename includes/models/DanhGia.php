<?php
class DanhGia extends BaseModel {
    protected $table = 'tbl_danh_gia';
    protected $primaryKey = 'id';
    
    public function getByProduct($productId, $page = 1, $perPage = 10) {
        return $this->paginate(
            $page,
            $perPage,
            "id_sanpham = ?",
            [$productId],
            'created_at DESC'
        );
    }
    
    public function getAverageRating($productId) {
        $result = $this->db->fetchOne(
            "SELECT AVG(rating) as avg, COUNT(*) as count 
            FROM {$this->table} 
            WHERE id_sanpham = ?",
            [$productId]
        );
        
        return [
            'avg' => round($result['avg'] ?? 0, 2),
            'count' => $result['count'] ?? 0
        ];
    }
    
    public function hasUserRated($userId, $productId) {
        $result = $this->db->fetchOne(
            "SELECT id FROM {$this->table} 
            WHERE id_khachhang = ? AND id_sanpham = ?",
            [$userId, $productId]
        );
        
        return !empty($result);
    }
}