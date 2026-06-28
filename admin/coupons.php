<?php
require_once '../config/config.php';
$page_title = "Manage Coupons";
include '../includes/admin/header.php';

$error = '';
$success = '';

// Handle Add Coupon
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_coupon'])) {
    $code = strtoupper(sanitize_input($_POST['code']));
    $desc = sanitize_input($_POST['description']);
    $type = $_POST['type'];
    $value = (float)$_POST['value'];
    $min_amount = (float)$_POST['min_amount'];

    try {
        $stmt = $pdo->prepare("INSERT INTO coupons (code, description, type, value, min_amount, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
        $stmt->execute([$code, $desc, $type, $value, $min_amount]);
        $success = "Coupon '$code' created successfully!";
    } catch (Exception $e) {
        $error = "Failed to add coupon. Code may already exist.";
    }
}

// Handle Action (Toggle / Delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $c_id = (int)$_GET['id'];
    if ($_GET['action'] === 'toggle') {
        $pdo->prepare("UPDATE coupons SET status = IF(status='active', 'inactive', 'active') WHERE id = ?")->execute([$c_id]);
        redirect_with_message(APP_URL . '/admin/coupons.php', 'Coupon status updated.', 'success');
    } elseif ($_GET['action'] === 'delete') {
        $pdo->prepare("DELETE FROM coupons WHERE id = ?")->execute([$c_id]);
        redirect_with_message(APP_URL . '/admin/coupons.php', 'Coupon deleted.', 'info');
    }
}

$coupons = $pdo->query("SELECT * FROM coupons ORDER BY created_at DESC")->fetchAll();
?>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3">Add New Discount Coupon</h5>
            <?php if ($error): ?><div class="alert alert-danger alert-custom mb-3"><?= xss_clean($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success alert-custom mb-3"><?= xss_clean($success) ?></div><?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="add_coupon" value="1">
                <div class="mb-3">
                    <label class="form-label font-semibold">Coupon Code</label>
                    <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g. SUMMER50" required>
                </div>
                <div class="mb-3">
                    <label class="form-label font-semibold">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Flat discount on tickets" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label font-semibold">Discount Type</label>
                        <select name="type" class="form-select">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (₹)</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label font-semibold">Value</label>
                        <input type="number" name="value" step="0.01" class="form-control" placeholder="10 or 100" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label font-semibold">Minimum Booking (₹)</label>
                    <input type="number" name="min_amount" step="0.01" class="form-control" value="0.00">
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2 rounded-pill font-semibold">Create Coupon</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-4">Active Coupons</h5>
            <?php if (empty($coupons)): ?>
                <p class="text-muted mb-0">No discount coupons generated yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Discount</th>
                                <th>Min Purchase</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($coupons as $c): ?>
                                <tr>
                                    <td><strong class="text-accent"><?= $c['code'] ?></strong><br><small class="text-muted"><?= xss_clean($c['description']) ?></small></td>
                                    <td><?= $c['type'] === 'percentage' ? $c['value'] . '%' : format_currency($c['value']) ?></td>
                                    <td><?= format_currency($c['min_amount']) ?></td>
                                    <td><span class="badge bg-<?= $c['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($c['status']) ?></span></td>
                                    <td>
                                        <a href="<?= APP_URL ?>/admin/coupons.php?action=toggle&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-<?= $c['status'] === 'active' ? 'warning' : 'success' ?> me-1"><?= $c['status'] === 'active' ? 'Disable' : 'Enable' ?></a>
                                        <a href="<?= APP_URL ?>/admin/coupons.php?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this coupon?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
