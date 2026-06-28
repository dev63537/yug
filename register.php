<?php
require_once 'config/config.php';

if (is_logged_in()) {
    secure_redirect(get_dashboard_url());
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token.';
    } else {
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? 'user',
            'org_name' => $_POST['org_name'] ?? '',
            'bio' => $_POST['bio'] ?? ''
        ];

        $res = register_user($pdo, $data);
        if ($res['success']) {
            redirect_with_message(APP_URL . '/login.php', $res['message'], 'success');
        } else {
            $error = $res['message'];
        }
    }
}

$page_title = "Register";
include 'includes/header.php';
?>

<div class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Create Account</h3>
                    <p class="text-muted">Join EventPro to book or organize events</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-custom mb-4"><?= xss_clean($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label font-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 9876543210">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-semibold">Account Type</label>
                        <select name="role" class="form-select" id="roleSelect" onchange="document.getElementById('orgFields').style.display = this.value === 'organizer' ? 'block' : 'none';">
                            <option value="user">Regular User (Book Events)</option>
                            <option value="organizer">Event Organizer (Host & Manage Events)</option>
                        </select>
                    </div>

                    <div id="orgFields" style="display: none;" class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="mb-3">
                            <label class="form-label font-semibold">Organization Name</label>
                            <input type="text" name="org_name" class="form-control" placeholder="Grand Events Pvt Ltd">
                        </div>
                        <div>
                            <label class="form-label font-semibold">Short Bio / Company Intro</label>
                            <textarea name="bio" class="form-control" rows="2" placeholder="Tell attendees about your organization..."></textarea>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label font-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="At least 8 chars, 1 uppercase, 1 number" required>
                    </div>

                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold">Create Account</button>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted mb-0">Already have an account? <a href="<?= APP_URL ?>/login.php" class="text-accent fw-bold text-decoration-none">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
