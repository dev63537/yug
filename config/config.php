<?php
/**
 * EventPro – Event Management Portal
 * Main Configuration File
 * @version 1.0.0
 */

// ─── Error Reporting ─────────────────────────────────────────────────────────
define('DEBUG_MODE', true); // Set false in production
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ─── Application Settings ─────────────────────────────────────────────────────
define('APP_NAME',    'EventPro');
define('APP_TAGLINE', 'Discover Amazing Events Near You');
define('APP_VERSION', '1.0.0');
define('APP_URL',     'http://localhost/YUG');
define('APP_EMAIL',   'admin@eventpro.com');
define('APP_PHONE',   '+91 98765 43210');
define('APP_ADDRESS', '123, MG Road, Bangalore, Karnataka 560001');
define('TIMEZONE',    'Asia/Kolkata');
define('CURRENCY',    '₹');
define('CURRENCY_CODE', 'INR');
define('TAX_RATE',    5); // Percentage

// ─── Database Settings ────────────────────────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'eventpro');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// ─── Path Constants ───────────────────────────────────────────────────────────
define('ROOT_PATH',    dirname(__DIR__) . '/');
define('CONFIG_PATH',  __DIR__ . '/');
define('UPLOAD_PATH',  ROOT_PATH . 'uploads/');
define('LOG_PATH',     ROOT_PATH . 'logs/');
define('UPLOAD_URL',   APP_URL . '/uploads/');

// ─── Upload Settings ──────────────────────────────────────────────────────────
define('MAX_FILE_SIZE',    5 * 1024 * 1024); // 5MB
define('ALLOWED_IMG_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_IMG_EXTS',  ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// ─── Pagination ───────────────────────────────────────────────────────────────
define('EVENTS_PER_PAGE',   9);
define('ADMIN_PER_PAGE',    15);
define('BLOG_PER_PAGE',     6);

// ─── Booking Settings ─────────────────────────────────────────────────────────
define('MAX_SEATS_PER_BOOKING',  10);
define('CANCELLATION_HOURS',     24); // Hours before event start
define('BOOKING_EXPIRY_MINUTES', 30); // Session timeout for checkout

// ─── Timezone ─────────────────────────────────────────────────────────────────
date_default_timezone_set(TIMEZONE);

// ─── Session ──────────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    ini_set('session.gc_maxlifetime', 3600);
    session_start();
}

// ─── Database Connection ──────────────────────────────────────────────────────
require_once CONFIG_PATH . 'database.php';
$pdo = Database::getInstance()->getConnection();

// ─── Load Helpers ─────────────────────────────────────────────────────────────
require_once ROOT_PATH . 'includes/security.php';
require_once ROOT_PATH . 'includes/functions.php';
require_once ROOT_PATH . 'includes/auth.php';
