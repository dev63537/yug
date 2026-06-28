<?php
require_once '../config/config.php';
require_login();

$booking_id = (int)($_GET['booking_id'] ?? 0);
$booking = get_booking($pdo, $booking_id);

if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    secure_redirect(APP_URL . '/user/bookings.php');
}

$page_title = "Booking Confirmed!";
include '../includes/header.php';
?>

<div class="container section-padding text-center">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <div class="text-success mb-3"><i class="fas fa-check-circle fa-5x"></i></div>
                <h2 class="fw-bold mb-2">Booking Confirmed!</h2>
                <p class="text-muted mb-4">Your tickets have been successfully booked for <strong><?= xss_clean($booking['event_title']) ?></strong>.</p>
                
                <div class="alert alert-light border p-3 rounded-3 mb-4">
                    <span class="text-muted d-block small">Booking Reference</span>
                    <strong class="fs-4 text-accent"><?= $booking['booking_ref'] ?></strong>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="<?= APP_URL ?>/user/booking-detail.php?id=<?= $booking['id'] ?>" class="btn btn-accent btn-lg rounded-pill px-4"><i class="fas fa-ticket-alt me-2"></i>View Ticket</a>
                    <a href="<?= APP_URL ?>/user/dashboard.php" class="btn btn-outline-secondary btn-lg rounded-pill px-4">My Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
