<?php
// Define routes
$routes = [
    // Public routes
    '/' => ['HomeController', 'index'],
    '/shop' => ['ProductController', 'index'],
    '/product/{id}' => ['ProductController', 'show'],
    '/cart' => ['CartController', 'index'],
    '/checkout' => ['CheckoutController', 'index'],
    '/login' => ['AuthController', 'login'],
    '/register' => ['AuthController', 'register'],
    
    // Admin routes
    '/admin' => ['Admin\DashboardController', 'index'],
    '/admin/products' => ['Admin\ProductController', 'index'],
    '/admin/orders' => ['Admin\OrderController', 'index'],
    '/admin/users' => ['Admin\UserController', 'index'],
];

// Get current URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove trailing slash
$uri = rtrim($uri, '/');

// If URI is empty, set it to /
if (empty($uri)) {
    $uri = '/';
}

// Find matching route
$matchedRoute = false;
foreach ($routes as $pattern => $handler) {
    // Convert route pattern to regex
    $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $pattern);
    $pattern = str_replace('/', '\/', $pattern);
    $pattern = '/^' . $pattern . '$/';

    if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches); // Remove full match
        $controllerName = "Controllers\\" . $handler[0];
        $methodName = $handler[1];

        // Instantiate controller
        $controller = new $controllerName();
        
        // Call method with parameters
        call_user_func_array([$controller, $methodName], $matches);
        
        $matchedRoute = true;
        break;
    }
}

// If no route matches, show 404
if (!$matchedRoute) {
    header("HTTP/1.0 404 Not Found");
    require __DIR__ . '/../Views/errors/404.php';
    exit;
}