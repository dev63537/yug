<?php
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../config/config.php';
}
require_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? xss_clean($page_title) : 'Admin Dashboard' ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-user-shield me-2 text-warning"></i>EventPro Admin
        </div>
        <nav class="sidebar-nav">
            <a class="nav-link" href="<?= APP_URL ?>/admin/"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/organizers.php"><i class="fas fa-building"></i> Organizers</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/events.php"><i class="fas fa-calendar-alt"></i> Events</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/categories.php"><i class="fas fa-list"></i> Categories</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/bookings.php"><i class="fas fa-ticket-alt"></i> Bookings</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/payments.php"><i class="fas fa-money-bill-wave"></i> Payments</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/coupons.php"><i class="fas fa-tags"></i> Coupons</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/reports.php"><i class="fas fa-file-invoice"></i> Reports</a>
            <a class="nav-link" href="<?= APP_URL ?>/admin/settings.php"><i class="fas fa-cog"></i> Settings</a>
            <hr class="text-secondary mx-3 my-2">
            <a class="nav-link" href="<?= APP_URL ?>/" target="_blank"><i class="fas fa-globe"></i> View Website</a>
            <a class="nav-link text-danger" href="<?= APP_URL ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    <div class="admin-content">
        <header class="admin-topbar">
            <button class="btn btn-sm btn-light sidebar-toggle me-3"><i class="fas fa-bars"></i></button>
            <h4 class="mb-0 fw-bold"><?= isset($page_title) ? xss_clean($page_title) : 'Dashboard' ?></h4>
            <div class="ms-auto d-flex align-items-center">
                <span class="badge bg-danger me-3">Admin Portal</span>
                <span class="fw-semibold me-2"><?= xss_clean($_SESSION['user_name']) ?></span>
            </div>
        </header>
        <main class="admin-main">
            <?php echo show_flash(); ?>
