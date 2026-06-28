<?php
require_once '../config/config.php';
$page_title = "Payment History";
include '../includes/user/header.php';

$stmt = $pdo->prepare("SELECT p.*, e.title as event_title, b.booking_ref 
                       FROM payments p 
                       JOIN bookings b ON p.booking_id = b.id 
                       JOIN events e ON b.event_id = e.id 
                       WHERE p.user_id = ? ORDER BY p.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$payments = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Transaction History</h5>
    <?php if (empty($payments)): ?>
        <p class="text-muted mb-0">No payment history found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Booking Ref</th>
                        <th>Event</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($payments as $p): ?>
                        <tr>
                            <td><code><?= $p['transaction_id'] ?></code></td>
                            <td><?= $p['booking_ref'] ?></td>
                            <td><?= xss_clean($p['event_title']) ?></td>
                            <td><?= format_currency($p['amount']) ?></td>
                            <td><span class="badge bg-secondary"><?= strtoupper($p['payment_method']) ?></span></td>
                            <td><?= format_date($p['created_at']) ?></td>
                            <td><span class="badge bg-success"><?= ucfirst($p['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/user/footer.php'; ?>
