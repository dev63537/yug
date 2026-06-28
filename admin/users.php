<?php
require_once '../config/config.php';
$page_title = "Manage Users";
include '../includes/admin/header.php';

// Handle Action (Ban/Unban User)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $u_id = (int)$_GET['id'];
    if ($_GET['action'] === 'toggle_status') {
        $pdo->prepare("UPDATE users SET status = IF(status='active', 'banned', 'active') WHERE id = ? AND role != 'admin'")->execute([$u_id]);
        redirect_with_message(APP_URL . '/admin/users.php', 'User account status updated.', 'success');
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Registered Users & Account Control</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><strong><?= xss_clean($u['name']) ?></strong></td>
                        <td><?= xss_clean($u['email']) ?></td>
                        <td><?= xss_clean($u['phone'] ?: 'N/A') ?></td>
                        <td><span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : ($u['role'] === 'organizer' ? 'primary' : 'secondary') ?>"><?= ucfirst($u['role']) ?></span></td>
                        <td><span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($u['status']) ?></span></td>
                        <td>
                            <?php if ($u['role'] !== 'admin'): ?>
                                <a href="<?= APP_URL ?>/admin/users.php?action=toggle_status&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-<?= $u['status'] === 'active' ? 'danger' : 'success' ?>">
                                    <?= $u['status'] === 'active' ? 'Ban / Stop User' : 'Unban User' ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
