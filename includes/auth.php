<?php
/**
 * EventPro – Authentication Helpers
 */

// ─── Session Helpers ──────────────────────────────────────────────────────────
function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

function get_auth_user(PDO $pdo): ?array {
    if (!is_logged_in()) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND status = 'active'");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function require_login(): void {
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect_with_message(APP_URL . '/login.php', 'Please login to continue.', 'warning');
    }
}

function require_admin(): void {
    require_login();
    if (($_SESSION['user_role'] ?? '') !== 'admin') {
        redirect_with_message(APP_URL . '/login.php', 'Access denied.', 'danger');
    }
}

function require_organizer(): void {
    require_login();
    if (!in_array($_SESSION['user_role'] ?? '', ['organizer', 'admin'])) {
        redirect_with_message(APP_URL . '/login.php', 'Access denied. Organizer account required.', 'danger');
    }
}

// ─── Login ────────────────────────────────────────────────────────────────────
function login_user(PDO $pdo, string $email, string $password): array {
    $result = ['success' => false, 'message' => '', 'user' => null];

    if (!validate_email($email)) {
        $result['message'] = 'Invalid email address.';
        return $result;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([strtolower(trim($email))]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $result['message'] = 'Invalid email or password.';
        return $result;
    }

    if ($user['status'] === 'banned') {
        $result['message'] = 'Your account has been banned. Contact support.';
        return $result;
    }

    if ($user['status'] === 'inactive') {
        $result['message'] = 'Your account is inactive.';
        return $result;
    }

    // Set session
    $_SESSION['user_id']    = $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role']  = $user['role'];
    $_SESSION['user_avatar']= $user['avatar'];
    session_regenerate_id(true);

    $result['success'] = true;
    $result['user']    = $user;
    return $result;
}

// ─── Logout ───────────────────────────────────────────────────────────────────
function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

// ─── Register ─────────────────────────────────────────────────────────────────
function register_user(PDO $pdo, array $data): array {
    $result = ['success' => false, 'message' => ''];

    // Check email duplicate
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([strtolower($data['email'])]);
    if ($stmt->fetch()) {
        $result['message'] = 'Email already registered.';
        return $result;
    }

    $password_errors = validate_password($data['password']);
    if (!empty($password_errors)) {
        $result['message'] = 'Password must have: ' . implode(', ', $password_errors);
        return $result;
    }

    $token = bin2hex(random_bytes(32));
    $hash  = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role, verification_token, status, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())");
        $stmt->execute([
            sanitize_input($data['name']),
            strtolower($data['email']),
            $hash,
            $data['phone'] ?? '',
            in_array($data['role'] ?? '', ['user','organizer']) ? $data['role'] : 'user',
            $token
        ]);
        $user_id = $pdo->lastInsertId();

        // If organizer, create organizers record
        if (($data['role'] ?? '') === 'organizer') {
            $stmt2 = $pdo->prepare("INSERT INTO organizers (user_id, org_name, bio, created_at) VALUES (?, ?, ?, NOW())");
            $stmt2->execute([$user_id, sanitize_input($data['org_name'] ?? $data['name']), sanitize_input($data['bio'] ?? '')]);
        }

        $pdo->commit();
        $result['success']  = true;
        $result['message']  = 'Registration successful! Welcome to EventPro.';
        $result['user_id']  = $user_id;
        $result['token']    = $token;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $result['message'] = 'Registration failed. Please try again.';
    }
    return $result;
}

// ─── Password Reset ───────────────────────────────────────────────────────────
function request_password_reset(PDO $pdo, string $email): bool {
    $stmt = $pdo->prepare("SELECT id, name FROM users WHERE email = ? AND status = 'active'");
    $stmt->execute([strtolower(trim($email))]);
    $user = $stmt->fetch();
    if (!$user) return true; // Always return true (security: don't reveal)

    $token  = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', time() + 3600);
    $stmt   = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
    $stmt->execute([$token, $expiry, $user['id']]);

    // Send email
    require_once ROOT_PATH . 'includes/mailer.php';
    send_password_reset_email($email, $token, $user['name']);
    return true;
}

function reset_password(PDO $pdo, string $token, string $new_password): array {
    $result = ['success' => false, 'message' => ''];
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    if (!$user) {
        $result['message'] = 'Invalid or expired reset link.';
        return $result;
    }
    $errors = validate_password($new_password);
    if (!empty($errors)) {
        $result['message'] = 'Password must have: ' . implode(', ', $errors);
        return $result;
    }
    $hash = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
    $stmt->execute([$hash, $user['id']]);
    $result['success'] = true;
    $result['message'] = 'Password reset successful! You can now login.';
    return $result;
}

// ─── Role Helpers ─────────────────────────────────────────────────────────────
function is_admin(): bool    { return ($_SESSION['user_role'] ?? '') === 'admin'; }
function is_organizer(): bool{ return in_array($_SESSION['user_role'] ?? '', ['organizer','admin']); }
function is_user(): bool     { return is_logged_in(); }

function get_dashboard_url(): string {
    return match($_SESSION['user_role'] ?? '') {
        'admin'     => APP_URL . '/admin/',
        'organizer' => APP_URL . '/organizer/dashboard.php',
        default     => APP_URL . '/user/dashboard.php',
    };
}
