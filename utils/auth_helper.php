<?php
// Core PHP JWT implementation (no libraries)
define('JWT_SECRET', 'your-jwt-secret-key'); // Replace with a strong secret
define('JWT_EXPIRY', 604800); // 7 days in seconds

function generate_jwt($user_id, $email, $role) {
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode([
        'user_id' => $user_id,
        'email' => $email,
        'role' => $role,
        'exp' => time() + JWT_EXPIRY
    ]));
    $signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$signature";
}

function validate_jwt($jwt) {
    if (!$jwt) return false;
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;
    list($header, $payload, $signature) = $parts;
    $valid_signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    if ($signature !== $valid_signature) return false;
    $decoded_payload = json_decode(base64_decode($payload), true);
    if ($decoded_payload['exp'] < time()) return false;
    return $decoded_payload;
}

// Password functions
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Firebase ID token verification using cURL (no Guzzle)
function verify_firebase_token($id_token) {
    $ch = curl_init("https://oauth2.googleapis.com/tokeninfo?id_token=$id_token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) return false;
    $data = json_decode($response, true);
    if (!$data || !isset($data['email']) || !isset($data['sub'])) return false;
    return ['email' => $data['email'], 'firebase_uid' => $data['sub']];
}
