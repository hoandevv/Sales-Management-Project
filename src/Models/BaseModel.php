<?php
namespace Models;

class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = \Config\Database::getInstance()->getConnection();
    }

    public function findAll() {
        $query = "SELECT * FROM {$this->table}";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($id) {
        $id = $this->db->real_escape_string($id);
        $query = "SELECT * FROM {$this->table} WHERE id = '$id' LIMIT 1";
        $result = $this->db->query($query);
        return $result->fetch_assoc();
    }

    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$this->db, 'real_escape_string'], $data)) . "'";
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($values)";
        return $this->db->query($query);
    }

    public function update($id, $data) {
        $id = $this->db->real_escape_string($id);
        $updates = [];
        foreach ($data as $key => $value) {
            $updates[] = "$key = '" . $this->db->real_escape_string($value) . "'";
        }
        $updates = implode(', ', $updates);
        $query = "UPDATE {$this->table} SET $updates WHERE id = '$id'";
        return $this->db->query($query);
    }

    public function delete($id) {
        $id = $this->db->real_escape_string($id);
        $query = "DELETE FROM {$this->table} WHERE id = '$id'";
        return $this->db->query($query);
    }
}