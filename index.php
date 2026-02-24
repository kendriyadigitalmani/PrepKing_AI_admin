<?php
header('Content-Type: application/json');

// API Router
$route = $_GET['route'] ?? '';
switch ($route) {
    case 'auth':
        require_once 'api/auth.php';
        break;
    case '':
    case null:
        // Root endpoint - show API info
        echo json_encode([
            'message' => 'PrepKing AI Admin API',
            'version' => '1.0.0',
            'endpoints' => [
                'POST /auth' => 'Authentication endpoints (register, login, google_login, admin_google_login)'
            ]
        ]);
        break;
    default:
        echo json_encode(['error' => 'Invalid route']);
}
