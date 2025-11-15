<?php
// Database configuration
define('DB_HOST', 'mysql');  // Use the service name from docker-compose.yml
define('DB_USER', 'root');
define('DB_PASS', 'rootpassword');
define('DB_NAME', 'web1');

// Create connection
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to utf8mb4
$mysqli->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
