<?php
require_once '../config/config.php';
$page_title = "System Reports";
include '../includes/admin/header.php';

$stmt = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as mth, COUNT(id) as total_bookings, SUM(total) as revenue FROM bookings WHERE payment_status='paid' GROUP BY mth ORDER BY mth DESC");
$monthly = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Monthly Revenue & Sales Report</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Successful Bookings</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($monthly as $m): ?>
                    <tr>
                        <td><strong><?= date('F Y', strtotime($m['mth'] . '-01')) ?></strong></td>
                        <td><?= $m['total_bookings'] ?></td>
                        <td class="fw-bold text-success"><?= format_currency($m['revenue']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
