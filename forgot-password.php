<?php
require_once 'config/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    request_password_reset($pdo, $email);
    $message = 'If an account exists for that email, password reset instructions have been logged/sent.';
}

$page_title = "Forgot Password";
include 'includes/header.php';
?>

<div class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 text-center">
                <h3 class="fw-bold mb-2">Forgot Password</h3>
                <p class="text-muted mb-4">Enter your email address to receive reset instructions</p>

                <?php if ($message): ?>
                    <div class="alert alert-info alert-custom mb-4"><?= xss_clean($message) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <?= csrf_field() ?>
                    <div class="mb-4 text-start">
                        <label class="form-label font-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com" required>
                    </div>
                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold">Reset Password</button>
                </form>

                <div class="mt-4">
                    <a href="<?= APP_URL ?>/login.php" class="text-accent fw-bold text-decoration-none"><i class="fas fa-arrow-left me-2"></i>Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
