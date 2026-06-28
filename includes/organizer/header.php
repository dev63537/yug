<?php
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../config/config.php';
}
require_organizer();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? xss_clean($page_title) : 'Organizer Portal' ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-calendar-check me-2 text-teal"></i>Organizer Panel
        </div>
        <nav class="sidebar-nav">
            <a class="nav-link" href="<?= APP_URL ?>/organizer/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a class="nav-link" href="<?= APP_URL ?>/organizer/create-event.php"><i class="fas fa-plus-circle"></i> Create Event</a>
            <a class="nav-link" href="<?= APP_URL ?>/organizer/manage-events.php"><i class="fas fa-calendar-alt"></i> Manage Events</a>
            <a class="nav-link" href="<?= APP_URL ?>/organizer/participants.php"><i class="fas fa-users"></i> Participants</a>
            <a class="nav-link" href="<?= APP_URL ?>/organizer/reports.php"><i class="fas fa-chart-bar"></i> Analytics & Reports</a>
            <hr class="text-secondary mx-3 my-2">
            <a class="nav-link" href="<?= APP_URL ?>/"><i class="fas fa-globe"></i> View Website</a>
            <a class="nav-link text-danger" href="<?= APP_URL ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    <div class="admin-content">
        <header class="admin-topbar">
            <button class="btn btn-sm btn-light sidebar-toggle me-3"><i class="fas fa-bars"></i></button>
            <h4 class="mb-0 fw-bold"><?= isset($page_title) ? xss_clean($page_title) : 'Organizer Dashboard' ?></h4>
            <div class="ms-auto d-flex align-items-center">
                <span class="badge bg-success me-3">Verified Organizer</span>
                <span class="fw-semibold me-2"><?= xss_clean($_SESSION['user_name']) ?></span>
            </div>
        </header>
        <main class="admin-main">
            <?php echo show_flash(); ?>
