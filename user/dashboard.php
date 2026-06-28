<?php
require_once '../config/config.php';
$page_title = "User Dashboard";
include '../includes/user/header.php';

$user_id = $_SESSION['user_id'];
$bookings = get_user_bookings($pdo, $user_id);
$total_bookings = count($bookings);

$stmt = $pdo->prepare("SELECT SUM(total) as spent FROM bookings WHERE user_id = ? AND payment_status = 'paid'");
$stmt->execute([$user_id]);
$total_spent = $stmt->fetch()['spent'] ?? 0;
?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-primary text-white me-3"><i class="fas fa-ticket-alt"></i></div>
            <div>
                <h6 class="text-muted mb-1">Total Bookings</h6>
                <h3 class="fw-bold mb-0"><?= $total_bookings ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="dash-card d-flex align-items-center">
            <div class="dash-card-icon bg-success text-white me-3"><i class="fas fa-wallet"></i></div>
            <div>
                <h6 class="text-muted mb-1">Total Spent</h6>
                <h3 class="fw-bold mb-0"><?= format_currency($total_spent) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <h5 class="fw-bold mb-3">Recent Bookings</h5>
    <?php if (empty($bookings)): ?>
        <p class="text-muted mb-0">You haven't made any bookings yet. <a href="<?= APP_URL ?>/events.php">Browse events</a> to get started!</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Seats</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach(array_slice($bookings, 0, 5) as $b): ?>
                        <tr>
                            <td><strong><?= $b['booking_ref'] ?></strong></td>
                            <td><?= xss_clean($b['event_title']) ?></td>
                            <td><?= format_date($b['start_date']) ?></td>
                            <td><?= $b['seats'] ?></td>
                            <td><?= format_currency($b['total']) ?></td>
                            <td><span class="badge bg-<?= $b['status'] === 'confirmed' ? 'success' : 'warning' ?>"><?= ucfirst($b['status']) ?></span></td>
                            <td><a href="<?= APP_URL ?>/user/booking-detail.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-light"><i class="fas fa-eye me-1"></i>View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/user/footer.php'; ?>
