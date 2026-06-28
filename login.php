<?php
require_once 'config/config.php';

if (is_logged_in()) {
    secure_redirect(get_dashboard_url());
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $email = sanitize_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $res = login_user($pdo, $email, $password);
        if ($res['success']) {
            $redirect = $_SESSION['redirect_after_login'] ?? get_dashboard_url();
            unset($_SESSION['redirect_after_login']);
            redirect_with_message($redirect, 'Welcome back, ' . $res['user']['name'] . '!', 'success');
        } else {
            $error = $res['message'];
        }
    }
}

$page_title = "Login";
include 'includes/header.php';
?>

<div class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Welcome Back</h3>
                    <p class="text-muted">Sign in to manage your bookings and events</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-custom mb-4"><?= xss_clean($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="name@example.com" required>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label font-semibold mb-0">Password</label>
                            <a href="<?= APP_URL ?>/forgot-password.php" class="small text-accent text-decoration-none">Forgot?</a>
                        </div>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold mt-3">Sign In</button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">Don't have an account? <a href="<?= APP_URL ?>/register.php" class="text-accent fw-bold text-decoration-none">Register Now</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
