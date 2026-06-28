<?php
require_once '../config/config.php';
$id = (int)($_GET['id'] ?? 0);
$booking = get_booking($pdo, $id);

if (!$booking || $booking['user_id'] != $_SESSION['user_id']) {
    secure_redirect(APP_URL . '/user/bookings.php');
}

$page_title = "Ticket Details";
include '../includes/user/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold mb-1"><?= xss_clean($booking['event_title']) ?></h4>
                    <span class="badge bg-success fs-6">Confirmed Ticket</span>
                </div>
                <div class="text-end">
                    <span class="text-muted d-block small">Booking Reference</span>
                    <strong class="fs-5 text-accent"><?= $booking['booking_ref'] ?></strong>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <p class="text-muted mb-1 small">Participant Name</p>
                    <strong><?= xss_clean($booking['participant_name']) ?></strong>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 small">Email Address</p>
                    <strong><?= xss_clean($booking['participant_email']) ?></strong>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 small">Event Date & Time</p>
                    <strong><?= format_date($booking['start_date']) ?> at <?= date('h:i A', strtotime($booking['start_time'])) ?></strong>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 small">Venue & Location</p>
                    <strong><?= xss_clean($booking['venue']) ?>, <?= xss_clean($booking['city']) ?></strong>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 small">Seats Booked</p>
                    <strong><?= $booking['seats'] ?> Ticket(s)</strong>
                </div>
                <div class="col-md-6">
                    <p class="text-muted mb-1 small">Total Paid</p>
                    <strong class="fs-5 text-success"><?= format_currency($booking['total']) ?></strong>
                </div>
            </div>

            <div class="alert alert-light border text-center p-4 rounded-3 mb-4">
                <i class="fas fa-qrcode fa-5x text-dark mb-3"></i>
                <p class="mb-0 text-muted small">Present this QR Code or Booking Ref at the venue entrance for verification.</p>
            </div>

            <div class="text-center">
                <button onclick="window.print()" class="btn btn-accent px-4 py-2 rounded-pill"><i class="fas fa-print me-2"></i>Print Ticket</button>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/user/footer.php'; ?>
