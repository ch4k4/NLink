<?php
/**
 * Main Entry Point (Front Controller)
 * 
 * All requests are routed through this file
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoloader for PSR-4
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR;
    
    // Check if class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Not our namespace, try global classes
        $globalClass = BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR;
        
        // Convert namespace separators to directory separators
        $relativeClass = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        $file = $globalClass . $relativeClass . '.php';
        
        if (file_exists($file)) {
            require $file;
            return;
        }
    } else {
        // Get the relative class name
        $relativeClass = substr($class, $len);
        
        // Replace namespace separators with directory separators
        $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
    }
});

// Load environment variables
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'EnvLoader.php';
$envLoader = new EnvLoader(BASE_PATH);
$envLoader->load();

// Load configuration
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Config.php';
$config = Config::getInstance();

// Set error reporting based on environment
if ($config->get('app.debug')) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    $sessionConfig = $config->get('session');
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.gc_maxlifetime', $sessionConfig['lifetime'] * 60);
    session_start();
}

// Initialize router
$router = new Router('/nerslink');

// Register routes
require_once BASE_PATH . DIRECTORY_SEPARATOR . 'routes' . DIRECTORY_SEPARATOR . 'web.php';

// Get request URI and method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
    // Dispatch the request
    $response = $router->dispatch($requestMethod, $requestUri);
    
    // If response is a string, output it (view content)
    if (is_string($response)) {
        echo $response;
    }
} catch (NotFoundException $e) {
    http_response_code(404);
    require_once BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . '404.php';
} catch (AppException $e) {
    http_response_code($e->getHttpStatus());
    
    if ($config->get('app.debug')) {
        echo "<h1>Error {$e->getHttpStatus()}</h1>";
        echo "<p><strong>{$e->getMessage()}</strong></p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        require_once BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . '500.php';
    }
} catch (Throwable $e) {
    http_response_code(500);
    
    if ($config->get('app.debug')) {
        echo "<h1>Internal Server Error</h1>";
        echo "<p><strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        require_once BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . '500.php';
    }
}
