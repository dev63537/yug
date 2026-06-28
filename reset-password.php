<?php
require_once 'config/config.php';

$token = sanitize_input($_GET['token'] ?? '');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $res = reset_password($pdo, $token, $password);
    if ($res['success']) {
        redirect_with_message(APP_URL . '/login.php', $res['message'], 'success');
    } else {
        $error = $res['message'];
    }
}

$page_title = "Reset Password";
include 'includes/header.php';
?>

<div class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <h3 class="fw-bold mb-2 text-center">Set New Password</h3>
                <p class="text-muted mb-4 text-center">Enter your new password below</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-custom mb-4"><?= xss_clean($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <?= csrf_field() ?>
                    <div class="mb-4">
                        <label class="form-label font-semibold">New Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="At least 8 chars" required>
                    </div>
                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
