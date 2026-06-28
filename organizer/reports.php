<?php
require_once '../config/config.php';
$page_title = "Revenue Reports";
include '../includes/organizer/header.php';

$stmt = $pdo->prepare("SELECT e.title, COUNT(b.id) as total_bookings, SUM(b.seats) as total_seats, SUM(b.total) as total_revenue 
                       FROM events e 
                       LEFT JOIN bookings b ON e.id = b.event_id AND b.status = 'confirmed' 
                       WHERE e.organizer_id = ? 
                       GROUP BY e.id");
$stmt->execute([$_SESSION['user_id']]);
$reports = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Event Revenue & Ticket Summary</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Total Bookings</th>
                    <th>Tickets Sold</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reports as $r): ?>
                    <tr>
                        <td><strong><?= xss_clean($r['title']) ?></strong></td>
                        <td><?= $r['total_bookings'] ?></td>
                        <td><?= $r['total_seats'] ?: 0 ?></td>
                        <td class="fw-bold text-success"><?= format_currency($r['total_revenue'] ?: 0) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/organizer/footer.php'; ?>
