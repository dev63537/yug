<?php
require_once '../config/config.php';
$page_title = "Manage Events";
include '../includes/admin/header.php';

$events = $pdo->query("SELECT e.*, c.name as category_name, u.name as organizer_name FROM events e LEFT JOIN categories c ON e.category_id = c.id LEFT JOIN users u ON e.organizer_id = u.id ORDER BY e.created_at DESC")->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">All System Events</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Organizer</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $e): ?>
                    <tr>
                        <td><strong><?= xss_clean($e['title']) ?></strong></td>
                        <td><?= xss_clean($e['organizer_name']) ?></td>
                        <td><?= xss_clean($e['category_name']) ?></td>
                        <td><?= format_date($e['start_date']) ?></td>
                        <td><?= format_currency($e['price']) ?></td>
                        <td><span class="badge bg-<?= $e['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($e['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
