<?php
require_once '../config/config.php';
$page_title = "Event Participants";
include '../includes/organizer/header.php';

$event_id = (int)($_GET['event_id'] ?? 0);

$stmt = $pdo->prepare("SELECT b.*, e.title as event_title FROM bookings b JOIN events e ON b.event_id = e.id WHERE e.organizer_id = ? AND (? = 0 OR b.event_id = ?) ORDER BY b.created_at DESC");
$stmt->execute([$_SESSION['user_id'], $event_id, $event_id]);
$participants = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Participant List</h5>
    <?php if (empty($participants)): ?>
        <p class="text-muted mb-0">No participant bookings found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Booking Ref</th>
                        <th>Event</th>
                        <th>Participant Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Seats</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($participants as $p): ?>
                        <tr>
                            <td><strong><?= $p['booking_ref'] ?></strong></td>
                            <td><?= xss_clean($p['event_title']) ?></td>
                            <td><?= xss_clean($p['participant_name']) ?></td>
                            <td><?= xss_clean($p['participant_email']) ?></td>
                            <td><?= xss_clean($p['participant_phone']) ?></td>
                            <td><?= $p['seats'] ?></td>
                            <td><span class="badge bg-<?= $p['status'] === 'confirmed' ? 'success' : 'warning' ?>"><?= ucfirst($p['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/organizer/footer.php'; ?>
