<?php
require_once '../config/config.php';
$page_title = "Manage Events";
include '../includes/organizer/header.php';

$stmt = $pdo->prepare("SELECT e.*, c.name as category_name FROM events e LEFT JOIN categories c ON e.category_id = c.id WHERE e.organizer_id = ? ORDER BY e.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">My Events List</h5>
        <a href="<?= APP_URL ?>/organizer/create-event.php" class="btn btn-accent btn-sm"><i class="fas fa-plus me-1"></i>Create Event</a>
    </div>
    <?php if (empty($events)): ?>
        <p class="text-muted mb-0">You haven't created any events yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Price</th>
                        <th>Booked / Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($events as $e): ?>
                        <tr>
                            <td><strong><?= xss_clean($e['title']) ?></strong></td>
                            <td><?= xss_clean($e['category_name']) ?></td>
                            <td><?= format_date($e['start_date']) ?></td>
                            <td><?= format_currency($e['price']) ?></td>
                            <td><?= $e['seats_booked'] ?> / <?= $e['seats_total'] ?></td>
                            <td><span class="badge bg-<?= $e['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($e['status']) ?></span></td>
                            <td>
                                <a href="<?= APP_URL ?>/event-details.php?slug=<?= $e['slug'] ?>" target="_blank" class="btn btn-sm btn-light me-1"><i class="fas fa-globe"></i></a>
                                <a href="<?= APP_URL ?>/organizer/participants.php?event_id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-users me-1"></i>Participants</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/organizer/footer.php'; ?>
