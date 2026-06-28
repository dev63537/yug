<?php
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? xss_clean($page_title) . ' - ' . APP_NAME : APP_NAME . ' | ' . APP_TAGLINE ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= APP_URL ?>/assets/css/responsive.css" rel="stylesheet">
</head>
<body>
    <div id="preloader"><div class="spinner"></div></div>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="main-content">
        <?php echo show_flash(); ?>
