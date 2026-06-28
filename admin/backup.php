<?php
require_once '../config/config.php';
$page_title = "Database Backup";
include '../includes/admin/header.php';
?>

<div class="card border-0 shadow-sm rounded-4 p-4 col-lg-8">
    <h5 class="fw-bold mb-3">Database Backup & Maintenance</h5>
    <p class="text-muted mb-4">Export master database SQL dumps or restore database snapshots for backup purposes.</p>
    
    <div class="p-4 border rounded-3 bg-light mb-4">
        <h6>Current Database: <strong><?= DB_NAME ?></strong></h6>
        <p class="small text-muted mb-0">Host: <?= DB_HOST ?> | Charset: <?= DB_CHARSET ?></p>
    </div>

    <a href="<?= APP_URL ?>/database/eventpro.sql" download class="btn btn-accent px-4 py-2 rounded-pill"><i class="fas fa-download me-2"></i>Download Master SQL Backup File</a>
</div>

<?php include '../includes/admin/footer.php'; ?>
