<?php
require_once '../config/config.php';
$page_title = "Organizer Dashboard";
include '../includes/organizer/header.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM events WHERE organizer_id = ?");
$stmt->execute([$user_id]);
$total_events = $stmt->fetch()['cnt'];

$stmt = $pdo->prepare("SELECT SUM(b.seats) as seats, SUM(b.total) as revenue FROM bookings b JOIN events e ON b.event_id = e.id WHERE e.organizer_id = ? AND b.status='confirmed'");
$stmt->execute([$user_id]);
$res = $stmt->fetch();
$total_seats = $res['seats'] ?? 0;
$total_revenue = $res['revenue'] ?? 0;
?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-primary text-white me-3"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <h6 class="text-muted mb-1">Created Events</h6>
                <h3 class="fw-bold mb-0"><?= $total_events ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-success text-white me-3"><i class="fas fa-users"></i></div>
            <div>
                <h6 class="text-muted mb-1">Tickets Sold</h6>
                <h3 class="fw-bold mb-0"><?= $total_seats ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-warning text-white me-3"><i class="fas fa-coins"></i></div>
            <div>
                <h6 class="text-muted mb-1">Total Revenue</h6>
                <h3 class="fw-bold mb-0"><?= format_currency($total_revenue) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Quick Management</h5>
        <a href="<?= APP_URL ?>/organizer/create-event.php" class="btn btn-accent btn-sm"><i class="fas fa-plus me-1"></i>New Event</a>
    </div>
    <p class="text-muted">Use the navigation menu on the left to manage your hosted events, check participant lists, or generate revenue reports.</p>
</div>

<?php include '../includes/organizer/footer.php'; ?>
