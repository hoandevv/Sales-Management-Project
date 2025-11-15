<?php
abstract class BaseController {
    protected $db;
    protected $view;
    protected $data = [];
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->data['csrf_token'] = Security::generateCSRFToken();
        $this->data['current_url'] = Utility::getCurrentUrl();
        $this->loadCommonData();
    }
    
    protected function loadCommonData() {
        // Load categories for menu
        $danhmucModel = new DanhMuc();
        $this->data['categories'] = $danhmucModel->findAll('is_active = 1', [], 'thutu ASC');
        
        // Load cart info if user is logged in
        if (isset($_SESSION['user_id'])) {
            $this->data['cart_count'] = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
            $userModel = new User();
            $this->data['user_info'] = $userModel->find($_SESSION['user_id']);
        }
    }
    
    protected function render($view, $data = []) {
        $viewPath = __DIR__ . "/../views/{$view}.php";
        if (!file_exists($viewPath)) {
            throw new Exception("View {$view} not found");
        }
        
        $data = array_merge($this->data, $data);
        extract($data);
        
        include __DIR__ . '/../views/layouts/header.php';
        include $viewPath;
        include __DIR__ . '/../views/layouts/footer.php';
    }
    
    protected function renderPartial($view, $data = []) {
        $viewPath = __DIR__ . "/../views/{$view}.php";
        if (!file_exists($viewPath)) {
            throw new Exception("View {$view} not found");
        }
        
        extract(array_merge($this->data, $data));
        include $viewPath;
    }
    
    protected function redirect($url) {
        Utility::redirect($url);
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    protected function requireLogin() {
        Security::requireLogin();
    }
    
    protected function requireAdmin() {
        Security::requireAdmin();
    }
}