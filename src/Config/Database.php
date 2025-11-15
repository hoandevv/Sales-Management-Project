<?php
namespace Config;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = getenv('DB_HOST') ?: 'mysql';
        $user = getenv('DB_USER') ?: 'hoan';
        $pass = getenv('DB_PASS') ?: '123456';
        $dbname = getenv('DB_NAME') ?: 'project1_db';

        try {
            $this->connection = new \mysqli($host, $user, $pass, $dbname);
            $this->connection->set_charset("utf8mb4");
        } catch (\Exception $e) {
            die("Không thể kết nối đến cơ sở dữ liệu: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}