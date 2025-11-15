<?php
if (!class_exists('DatabaseConfig')) {
class DatabaseConfig {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $host = getenv('MYSQL_HOST') ?: 'mysql_php_app';  // Container name from docker-compose
        $user = getenv('MYSQL_USER') ?: 'root';
        $pass = getenv('MYSQL_PASSWORD') ?: 'rootpassword';
        $db = getenv('MYSQL_DATABASE') ?: 'web1';
        $port = getenv('MYSQL_PORT') ?: 3306;
        
        $this->connection = new mysqli($host, $user, $pass, $db, $port);
        
        if ($this->connection->connect_error) {
            $error_msg = sprintf(
                "Database Connection Error: %s\nHost: %s\nUser: %s\nDB: %s\nPort: %s", 
                $this->connection->connect_error,
                $host,
                $user,
                $db,
                $port
            );
            error_log($error_msg);
            throw new Exception($error_msg);
        }
        
        $this->connection->set_charset("utf8mb4");
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
    
    public static function init() {
        try {
            $instance = self::getInstance();
            return $instance->getConnection();
        } catch (Exception $e) {
            error_log("Database Initialization Error: " . $e->getMessage());
            throw $e;
        }
    }
}
} // End of class_exists check

// Khởi tạo kết nối
try {
    $mysqli = DatabaseConfig::init();
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}
