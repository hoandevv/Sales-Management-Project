<?php
abstract class BaseModel {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }
    
    public function findBy($column, $value) {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        );
    }
    
    public function findAll($conditions = '', $params = [], $orderBy = '') {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . $conditions;
        }
        
        if (!empty($orderBy)) {
            $sql .= " ORDER BY " . $orderBy;
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function create($data) {
        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }
        if (isset($data['updated_at'])) {
            unset($data['updated_at']);
        }
        
        return $this->db->insert($this->table, $data);
    }
    
    public function update($id, $data) {
        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }
        if (isset($data['updated_at'])) {
            unset($data['updated_at']);
        }
        
        return $this->db->update(
            $this->table,
            $data,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }
    
    public function delete($id) {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }
    
    public function paginate($page = 1, $perPage = 10, $conditions = '', $params = [], $orderBy = '') {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($conditions)) {
            $sql .= " WHERE " . $conditions;
        }
        if (!empty($orderBy)) {
            $sql .= " ORDER BY " . $orderBy;
        }
        $sql .= " LIMIT ?, ?";
        
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        if (!empty($conditions)) {
            $countSql .= " WHERE " . $conditions;
        }
        
        $items = $this->db->fetchAll($sql, array_merge($params, [$offset, $perPage]));
        $total = $this->db->fetchOne($countSql, $params)['total'];
        
        return [
            'items' => $items,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'current_page' => $page,
            'per_page' => $perPage
        ];
    }
}