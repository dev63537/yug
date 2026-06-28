<?php
require_once '../config/config.php';
$page_title = "Change Password";
include '../includes/user/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!password_verify($current, $user['password'])) {
        $error = 'Current password is incorrect.';
    } else {
        $errs = validate_password($new);
        if (!empty($errs)) {
            $error = 'New password must have: ' . implode(', ', $errs);
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
            $u_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $u_stmt->execute([$hash, $_SESSION['user_id']]);
            $success = 'Password updated successfully!';
        }
    }
}
?>

<div class="card border-0 shadow-sm rounded-4 p-4 col-lg-6">
    <h5 class="fw-bold mb-4">Security Settings</h5>
    <?php if ($error): ?><div class="alert alert-danger alert-custom mb-4"><?= xss_clean($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success alert-custom mb-4"><?= xss_clean($success) ?></div><?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label font-semibold">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-4">
            <label class="form-label font-semibold">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-accent px-4 py-2 rounded-pill font-semibold">Update Password</button>
    </form>
</div>

<?php include '../includes/user/footer.php'; ?>
