<?php
require_once '../config/config.php';
$page_title = "My Bookings";
include '../includes/user/header.php';

$bookings = get_user_bookings($pdo, $_SESSION['user_id']);
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">All Booking Records</h5>
    <?php if (empty($bookings)): ?>
        <p class="text-muted mb-0">No bookings found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Seats</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $b): ?>
                        <tr>
                            <td><strong><?= $b['booking_ref'] ?></strong></td>
                            <td><?= xss_clean($b['event_title']) ?></td>
                            <td><?= format_date($b['start_date']) ?></td>
                            <td><?= $b['seats'] ?></td>
                            <td><?= format_currency($b['total']) ?></td>
                            <td><span class="badge bg-<?= $b['status'] === 'confirmed' ? 'success' : 'warning' ?>"><?= ucfirst($b['status']) ?></span></td>
                            <td><a href="<?= APP_URL ?>/user/booking-detail.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-light"><i class="fas fa-eye me-1"></i>View Ticket</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/user/footer.php'; ?>
