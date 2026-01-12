<?php
/**
 * Rashid Backend API - Pure PHP Version
 * Main entry point for the API
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/phponly', '', $path); // Remove phponly prefix if present

// Remove query string from path
$path = explode('?', $path)[0];

// API Routes
switch ($path) {
    // User Authentication
    case '/api/user/google-login':
        require_once __DIR__ . '/controllers/UserApi/UserApiLogin.php';
        break;
    
    case '/api/user/subscriptions':
        if ($method === 'GET') {
            require_once __DIR__ . '/controllers/UserApi/SubscriptionController.php';
        } elseif ($method === 'POST') {
            require_once __DIR__ . '/controllers/UserApi/SubscriptionController.php';
        }
        break;
    
    // Superstar Authentication
    case '/api/superstar/login':
        require_once __DIR__ . '/controllers/SuperStar/SuperStarAuth.php';
        break;
    
    case '/api/superstar/logout':
        require_once __DIR__ . '/controllers/SuperStar/SuperStarAuth.php';
        break;
    
    case '/api/superstar/me':
        require_once __DIR__ . '/controllers/SuperStar/SuperStarAuth.php';
        break;
    
    case '/api/superstar/profile':
        require_once __DIR__ . '/controllers/SuperStar/SuperStarAuth.php';
        break;
    
    case '/api/superstar/change-password':
        require_once __DIR__ . '/controllers/SuperStar/SuperStarAuth.php';
        break;
    
    // Superstar Posts
    case '/api/superstar/posts':
        if ($method === 'GET') {
            require_once __DIR__ . '/controllers/SuperStar/SuperstarPostController.php';
        } elseif ($method === 'POST') {
            require_once __DIR__ . '/controllers/SuperStar/SuperstarPostController.php';
        }
        break;
    
    // User Superstars
    case strpos($path, '/api/user/superstars') === 0:
        require_once __DIR__ . '/controllers/UserApi/UserSuperStarController.php';
        break;
    
    // User Payments
    case '/api/user/payments/process':
        require_once __DIR__ . '/controllers/UserApi/PaymentController.php';
        break;
    
    case '/api/user/payments/history':
        require_once __DIR__ . '/controllers/UserApi/PaymentController.php';
        break;
    
    // User Payment History
    case strpos($path, '/api/user/payment-history') === 0:
        require_once __DIR__ . '/controllers/UserApi/PaymentHistoryController.php';
        break;
    
    // User Chat
    case strpos($path, '/api/user/chat') === 0:
        require_once __DIR__ . '/controllers/UserApi/ChatController.php';
        break;
    
    // Admin routes
case strpos($path, '/admin/') === 0:
    $adminPath = str_replace('/admin/', '', $path);
    if (empty($adminPath) || $adminPath === 'index') {
        require_once __DIR__ . '/public/admin/index.php';
    } elseif ($adminPath === 'users') {
        require_once __DIR__ . '/public/admin/users.php';
    } elseif ($adminPath === 'superstars') {
        require_once __DIR__ . '/public/admin/superstars.php';
    } elseif ($adminPath === 'posts') {
        require_once __DIR__ . '/public/admin/posts.php';
    } elseif ($adminPath === 'payments') {
        require_once __DIR__ . '/public/admin/payments.php';
    } elseif ($adminPath === 'subscriptions') {
        require_once __DIR__ . '/public/admin/subscriptions.php';
    } elseif ($adminPath === 'chats') {
        require_once __DIR__ . '/public/admin/chats.php';
    } elseif ($adminPath === 'settings') {
        require_once __DIR__ . '/public/admin/settings.php';
    } elseif ($adminPath === 'logout') {
        // Handle admin logout
        session_start();
        session_destroy();
        header('Location: /admin/');
        exit;
    }
    break;

// Default response
    default:
        http_response_code(404);
        echo json_encode([
            'error' => 'Endpoint not found',
            'path' => $path,
            'method' => $method
        ]);
        break;
}
