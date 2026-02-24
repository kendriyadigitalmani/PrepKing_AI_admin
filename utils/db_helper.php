<?php
// Placeholder for DB query helpers (expanded in future phases)
// Example: function to get user by email
function get_user_by_email($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}
