<?php
require_once '../config/config.php';
$page_title = "Admin Dashboard";
include '../includes/admin/header.php';

$u_cnt = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$e_cnt = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$b_cnt = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$r_cnt = $pdo->query("SELECT SUM(total) FROM bookings WHERE payment_status='paid'")->fetchColumn() ?? 0;
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-primary text-white me-3"><i class="fas fa-users"></i></div>
            <div>
                <h6 class="text-muted mb-1">Total Users</h6>
                <h3 class="fw-bold mb-0"><?= $u_cnt ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-success text-white me-3"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <h6 class="text-muted mb-1">Total Events</h6>
                <h3 class="fw-bold mb-0"><?= $e_cnt ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-warning text-white me-3"><i class="fas fa-ticket-alt"></i></div>
            <div>
                <h6 class="text-muted mb-1">Total Bookings</h6>
                <h3 class="fw-bold mb-0"><?= $b_cnt ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-danger text-white me-3"><i class="fas fa-coins"></i></div>
            <div>
                <h6 class="text-muted mb-1">Total Revenue</h6>
                <h3 class="fw-bold mb-0"><?= format_currency($r_cnt) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-3">System Overview</h5>
    <p class="text-muted">Welcome to the EventPro System Administration dashboard. Manage registered users, review organizer submissions, track platform bookings and monitor overall revenue performance.</p>
</div>

<?php include '../includes/admin/footer.php'; ?>
