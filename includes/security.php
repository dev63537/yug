<?php
/**
 * EventPro – Security Helpers
 */

// ─── CSRF ─────────────────────────────────────────────────────────────────────
function generate_csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . generate_csrf_token() . '">';
}

// ─── Sanitization ─────────────────────────────────────────────────────────────
function sanitize_input(mixed $data): string {
    if (is_array($data)) return '';
    return trim(stripslashes(htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8')));
}

function xss_clean(string $data): string {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function sanitize_email(string $email): string|false {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

// ─── Validation ───────────────────────────────────────────────────────────────
function validate_email(string $email): bool {
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_phone(string $phone): bool {
    return (bool) preg_match('/^[\+]?[0-9]{10,15}$/', preg_replace('/[\s\-\(\)]/', '', $phone));
}

function validate_password(string $password): array {
    $errors = [];
    if (strlen($password) < 8)               $errors[] = 'At least 8 characters';
    if (!preg_match('/[A-Z]/', $password))   $errors[] = 'At least one uppercase letter';
    if (!preg_match('/[0-9]/', $password))   $errors[] = 'At least one number';
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) $errors[] = 'At least one special character';
    return $errors;
}

// ─── Redirect ─────────────────────────────────────────────────────────────────
function secure_redirect(string $url): never {
    $url = filter_var($url, FILTER_SANITIZE_URL);
    header('Location: ' . $url);
    exit;
}

function redirect_with_message(string $url, string $message, string $type = 'success'): never {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type']    = $type;
    secure_redirect($url);
}

// ─── Flash Messages ───────────────────────────────────────────────────────────
function get_flash_message(): array {
    if (!empty($_SESSION['flash_message'])) {
        $msg  = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return ['message' => $msg, 'type' => $type];
    }
    return [];
}

function show_flash(): string {
    $flash = get_flash_message();
    if (empty($flash)) return '';
    $icons = ['success'=>'check-circle','danger'=>'times-circle','warning'=>'exclamation-triangle','info'=>'info-circle'];
    $icon  = $icons[$flash['type']] ?? 'info-circle';
    return '<div class="alert alert-' . $flash['type'] . ' alert-dismissible fade show alert-custom" role="alert">
        <i class="fas fa-' . $icon . ' me-2"></i>' . xss_clean($flash['message']) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}

// ─── File Upload Security ─────────────────────────────────────────────────────
function validate_image_upload(array $file): array {
    $errors = [];
    if ($file['error'] !== UPLOAD_ERR_OK)          $errors[] = 'Upload error code: ' . $file['error'];
    if ($file['size'] > MAX_FILE_SIZE)              $errors[] = 'File too large (max 5MB)';
    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mimeType, ALLOWED_IMG_TYPES))    $errors[] = 'Invalid file type. Only JPG, PNG, GIF, WEBP allowed';
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_IMG_EXTS))          $errors[] = 'Invalid file extension';
    return $errors;
}

function save_uploaded_image(array $file, string $subfolder = 'events'): string|false {
    $dir = UPLOAD_PATH . $subfolder . '/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('img_', true) . '.' . $ext;
    $dest     = $dir . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return 'uploads/' . $subfolder . '/' . $filename;
    }
    return false;
}

// ─── Audit Log ────────────────────────────────────────────────────────────────
function log_audit(PDO $pdo, ?int $user_id, string $action, string $table = '', int $record_id = 0, mixed $old = null, mixed $new = null): void {
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent, created_at)
                               VALUES (?,?,?,?,?,?,?,?,NOW())");
        $stmt->execute([
            $user_id,
            $action,
            $table,
            $record_id,
            $old ? json_encode($old) : null,
            $new  ? json_encode($new)  : null,
            $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    } catch (PDOException $e) {
        // Silently fail audit logging
    }
}

// ─── Rate Limiting ────────────────────────────────────────────────────────────
function check_rate_limit(string $key, int $max_attempts = 5, int $window_minutes = 15): bool {
    $session_key = 'rate_limit_' . $key;
    $now = time();
    if (!isset($_SESSION[$session_key])) {
        $_SESSION[$session_key] = ['count' => 0, 'start' => $now];
    }
    $rl = &$_SESSION[$session_key];
    if ($now - $rl['start'] > $window_minutes * 60) {
        $rl = ['count' => 0, 'start' => $now];
    }
    $rl['count']++;
    return $rl['count'] <= $max_attempts;
}
