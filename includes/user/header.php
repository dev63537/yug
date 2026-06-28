<?php
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../config/config.php';
}
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? xss_clean($page_title) : 'User Dashboard' ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/admin.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-user-circle me-2 text-accent"></i>User Dashboard
        </div>
        <nav class="sidebar-nav">
            <a class="nav-link" href="<?= APP_URL ?>/user/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link" href="<?= APP_URL ?>/user/profile.php"><i class="fas fa-user-edit"></i> Profile Management</a>
            <a class="nav-link" href="<?= APP_URL ?>/user/bookings.php"><i class="fas fa-ticket-alt"></i> My Bookings</a>
            <a class="nav-link" href="<?= APP_URL ?>/user/payments.php"><i class="fas fa-history"></i> Payment History</a>
            <a class="nav-link" href="<?= APP_URL ?>/user/change-password.php"><i class="fas fa-key"></i> Change Password</a>
            <hr class="text-secondary mx-3 my-2">
            <a class="nav-link" href="<?= APP_URL ?>/"><i class="fas fa-home"></i> Back to Website</a>
            <a class="nav-link text-danger" href="<?= APP_URL ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    <div class="admin-content">
        <header class="admin-topbar">
            <button class="btn btn-sm btn-light sidebar-toggle me-3"><i class="fas fa-bars"></i></button>
            <h4 class="mb-0 fw-bold"><?= isset($page_title) ? xss_clean($page_title) : 'Dashboard' ?></h4>
            <div class="ms-auto d-flex align-items-center">
                <span class="fw-semibold me-2"><?= xss_clean($_SESSION['user_name']) ?></span>
            </div>
        </header>
        <main class="admin-main">
            <?php echo show_flash(); ?>
