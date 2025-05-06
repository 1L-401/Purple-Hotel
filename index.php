<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Define base path
define('BASE_PATH', __DIR__);

// Include necessary files
require_once 'config/database.php';

// Get URL from the .htaccess rewrite
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : ['home'];

// Get controller, method, and parameters
$controllerName = ucfirst($url[0] ?? 'Home') . 'Controller';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

// Default to HomeController if the controller doesn't exist
if (!file_exists('controllers/' . $controllerName . '.php')) {
    $controllerName = 'HomeController';
    $method = 'index';
    $params = [];
}

// Include controller file
require_once 'controllers/' . $controllerName . '.php';

// Create controller instance
$controller = new $controllerName();

// Check if method exists
if (!method_exists($controller, $method)) {
    $method = 'index';
}

// Call the method with parameters
call_user_func_array([$controller, $method], $params);
?>