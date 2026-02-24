<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../utils/auth_helper.php';
require_once __DIR__ . '/../utils/db_helper.php';

// Handle CORS (for React/Flutter cross-origin)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    exit(0);
}
header('Access-Control-Allow-Origin: *');

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'register':
        // Email registration (user only)
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        if (!$email || !$password) {
            echo json_encode(['error' => 'Missing email or password']);
            exit;
        }
        if (get_user_by_email($pdo, $email)) {
            echo json_encode(['error' => 'Email already exists']);
            exit;
        }
        $hash = hash_password($password);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, provider, role) VALUES (?, ?, 'email', 'user')");
        $stmt->execute([$email, $hash]);
        $user_id = $pdo->lastInsertId();
        $jwt = generate_jwt($user_id, $email, 'user');
        echo json_encode(['token' => $jwt]);
        break;

    case 'login':
        // Email login (user or admin)
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        if (!$email || !$password) {
            echo json_encode(['error' => 'Missing email or password']);
            exit;
        }
        $user = get_user_by_email($pdo, $email);
        if (!$user || !verify_password($password, $user['password_hash'])) {
            echo json_encode(['error' => 'Invalid credentials']);
            exit;
        }
        $jwt = generate_jwt($user['id'], $email, $user['role']);
        echo json_encode(['token' => $jwt, 'role' => $user['role']]);
        break;

    case 'google_login':
        // Google login for apps (user only, auto-create if not exists)
        $id_token = $input['id_token'] ?? '';
        if (!$id_token) {
            echo json_encode(['error' => 'Missing ID token']);
            exit;
        }
        $verified = verify_firebase_token($id_token);
        if (!$verified) {
            echo json_encode(['error' => 'Invalid ID token']);
            exit;
        }
        $email = $verified['email'];
        $firebase_uid = $verified['firebase_uid'];
        $user = get_user_by_email($pdo, $email);
        if (!$user) {
            // Auto-create user
            $stmt = $pdo->prepare("INSERT INTO users (email, provider, role, firebase_uid) VALUES (?, 'google', 'user', ?)");
            $stmt->execute([$email, $firebase_uid]);
            $user_id = $pdo->lastInsertId();
        } else {
            $user_id = $user['id'];
            // Update firebase_uid if needed
            if ($user['firebase_uid'] !== $firebase_uid) {
                $stmt = $pdo->prepare("UPDATE users SET firebase_uid = ? WHERE id = ?");
                $stmt->execute([$firebase_uid, $user_id]);
            }
        }
        $jwt = generate_jwt($user_id, $email, 'user');
        echo json_encode(['token' => $jwt]);
        break;

    case 'admin_google_login':
        // Google login for admin (must exist with role=admin)
        $id_token = $input['id_token'] ?? '';
        if (!$id_token) {
            echo json_encode(['error' => 'Missing ID token']);
            exit;
        }
        $verified = verify_firebase_token($id_token);
        if (!$verified) {
            echo json_encode(['error' => 'Invalid ID token']);
            exit;
        }
        $email = $verified['email'];
        $firebase_uid = $verified['firebase_uid'];
        $user = get_user_by_email($pdo, $email);
        if (!$user || $user['role'] !== 'admin') {
            echo json_encode(['error' => 'Not an admin account']);
            exit;
        }
        // Update firebase_uid if needed
        if ($user['firebase_uid'] !== $firebase_uid) {
            $stmt = $pdo->prepare("UPDATE users SET firebase_uid = ? WHERE id = ?");
            $stmt->execute([$firebase_uid, $user['id']]);
        }
        $jwt = generate_jwt($user['id'], $email, 'admin');
        echo json_encode(['token' => $jwt, 'role' => 'admin']);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
