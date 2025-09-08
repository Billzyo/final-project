<?php
require_once 'init.php';
header('Content-Type: application/json');

// Parse URL
$request_uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$endpoint = isset($request_uri[3]) ? $request_uri[3] : null;

// Routes
switch ($endpoint) {
    case 'login':
        require_once 'login.php';
        break;
    case 'logout': 
        require_once 'logout.php';
        break;
    case 'users':
        require_once 'users.php';
        break;
    case 'products':
        require_once 'products.php';
        break;
    case 'sales':
        require_once 'sales.php';
        break;
    default:
        echo json_encode(['error' => 'Endpoint not found']);
        http_response_code(404);
        break;
}
