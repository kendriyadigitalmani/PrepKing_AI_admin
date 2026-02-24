<?php
// Auto-detect environment: localhost vs live server
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if (strpos($host, 'localhost') !== false || $host === '127.0.0.1') {
    $db_host = '127.0.0.1';
    $db_name = 'prepking_ai';
    $db_user = 'root';
    $db_pass = '';
} else {
    // Replace with live server credentials
    $db_host = 'your_live_host'; // e.g., 'mysql.yourhost.com'
    $db_name = 'your_live_db_name';
    $db_user = 'your_live_db_user';
    $db_pass = 'your_live_db_pass';
}

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}
