<?php
require_once '../config/config.php';
$page_title = "My Profile";
include '../includes/user/header.php';

$user = get_current_user($pdo);
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');

    $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
    $stmt->execute([$name, $phone, $user['id']]);
    $_SESSION['user_name'] = $name;
    $success = 'Profile updated successfully!';
    $user['name'] = $name;
    $user['phone'] = $phone;
}
?>

<div class="card border-0 shadow-sm rounded-4 p-4 col-lg-8">
    <h5 class="fw-bold mb-4">Profile Details</h5>
    <?php if ($success): ?>
        <div class="alert alert-success alert-custom mb-4"><?= xss_clean($success) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label font-semibold">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= xss_clean($user['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label font-semibold">Email Address (Read Only)</label>
            <input type="email" class="form-control" value="<?= xss_clean($user['email']) ?>" readonly disabled>
        </div>
        <div class="mb-4">
            <label class="form-label font-semibold">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="<?= xss_clean($user['phone']) ?>">
        </div>
        <button type="submit" class="btn btn-accent px-4 py-2 rounded-pill font-semibold">Save Changes</button>
    </form>
</div>

<?php include '../includes/user/footer.php'; ?>
