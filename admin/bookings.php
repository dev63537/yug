<?php
require_once '../config/config.php';
$page_title = "Manage Bookings";
include '../includes/admin/header.php';

$bookings = $pdo->query("SELECT b.*, e.title as event_title, u.name as user_name FROM bookings b JOIN events e ON b.event_id = e.id JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC")->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Master Booking Ledger</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Seats</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $b): ?>
                    <tr>
                        <td><strong><?= $b['booking_ref'] ?></strong></td>
                        <td><?= xss_clean($b['user_name']) ?></td>
                        <td><?= xss_clean($b['event_title']) ?></td>
                        <td><?= $b['seats'] ?></td>
                        <td><?= format_currency($b['total']) ?></td>
                        <td><span class="badge bg-<?= $b['status'] === 'confirmed' ? 'success' : 'warning' ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td><span class="badge bg-<?= $b['payment_status'] === 'paid' ? 'success' : 'danger' ?>"><?= ucfirst($b['payment_status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
