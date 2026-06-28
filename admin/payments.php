<?php
require_once '../config/config.php';
$page_title = "Manage Payments";
include '../includes/admin/header.php';

$stmt = $pdo->query("SELECT p.*, b.booking_ref, u.name as user_name, u.email as user_email, e.title as event_title 
                     FROM payments p 
                     JOIN bookings b ON p.booking_id = b.id 
                     JOIN users u ON p.user_id = u.id 
                     JOIN events e ON b.event_id = e.id 
                     ORDER BY p.created_at DESC");
$payments = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Transaction & Payment Records</h5>
    </div>

    <?php if (empty($payments)): ?>
        <p class="text-muted mb-0">No payment transactions recorded yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Booking Ref</th>
                        <th>User</th>
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
                            <td><strong><?= $p['booking_ref'] ?></strong></td>
                            <td><?= xss_clean($p['user_name']) ?><br><small class="text-muted"><?= xss_clean($p['user_email']) ?></small></td>
                            <td><?= xss_clean($p['event_title']) ?></td>
                            <td class="fw-bold text-success"><?= format_currency($p['amount']) ?></td>
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

<?php include '../includes/admin/footer.php'; ?>
