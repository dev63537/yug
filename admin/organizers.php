<?php
require_once '../config/config.php';
$page_title = "Manage Organizers";
include '../includes/admin/header.php';

// Handle Action (Toggle Verify / Toggle Status)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $org_id = (int)$_GET['id'];
    
    if ($action === 'toggle_verify') {
        $stmt = $pdo->prepare("UPDATE organizers SET verified = IF(verified=1, 0, 1) WHERE id = ?");
        $stmt->execute([$org_id]);
        redirect_with_message(APP_URL . '/admin/organizers.php', 'Organizer verification status updated.', 'success');
    }
}

$stmt = $pdo->query("SELECT o.*, u.name as user_name, u.email, u.status as user_status, COUNT(e.id) as total_events 
                     FROM organizers o 
                     JOIN users u ON o.user_id = u.id 
                     LEFT JOIN events e ON u.id = e.organizer_id 
                     GROUP BY o.id ORDER BY o.created_at DESC");
$organizers = $stmt->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Event Organizers Directory</h5>
    </div>
    
    <?php if (empty($organizers)): ?>
        <p class="text-muted mb-0">No registered organizers found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Organization Name</th>
                        <th>Contact Person</th>
                        <th>Email / Phone</th>
                        <th>Events Hosted</th>
                        <th>Verified</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($organizers as $o): ?>
                        <tr>
                            <td><?= $o['id'] ?></td>
                            <td>
                                <strong><?= xss_clean($o['org_name']) ?></strong>
                                <?php if($o['website']): ?><br><a href="<?= xss_clean($o['website']) ?>" target="_blank" class="small text-accent"><i class="fas fa-external-link-alt me-1"></i>Website</a><?php endif; ?>
                            </td>
                            <td><?= xss_clean($o['user_name']) ?></td>
                            <td><?= xss_clean($o['email']) ?><br><small class="text-muted"><?= xss_clean($o['phone'] ?: 'N/A') ?></small></td>
                            <td><span class="badge bg-info text-dark"><?= $o['total_events'] ?> Events</span></td>
                            <td>
                                <?php if($o['verified']): ?>
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= APP_URL ?>/admin/organizers.php?action=toggle_verify&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-<?= $o['verified'] ? 'warning' : 'success' ?>">
                                    <?= $o['verified'] ? 'Unverify / Stop' : 'Approve & Verify' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/admin/footer.php'; ?>
