<?php
require_once 'config/config.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');

    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $subject, $message]);
    $success = 'Thank you! Your message has been received. We will get back to you soon.';
}

$page_title = "Contact Us";
include 'includes/header.php';
?>

<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">Contact Us</h1>
        <p class="mb-0 text-white-50">Have questions? We are here to help you</p>
    </div>
</div>

<div class="container section-padding">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <h4 class="fw-bold mb-4">Send Us a Message</h4>
                <?php if ($success): ?>
                    <div class="alert alert-success alert-custom mb-4"><?= xss_clean($success) ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label font-semibold">Your Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label font-semibold">Message</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold">Send Message</button>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <h4 class="fw-bold mb-4">Get In Touch</h4>
            <p class="text-secondary mb-4">Reach out to our support team for any queries regarding bookings, ticket cancellations or hosting your own event.</p>
            <div class="d-flex align-items-center mb-4">
                <div class="rounded-circle bg-light p-3 me-3 text-accent"><i class="fas fa-map-marker-alt fa-lg"></i></div>
                <div><strong>Address:</strong><br><span class="text-secondary"><?= APP_ADDRESS ?></span></div>
            </div>
            <div class="d-flex align-items-center mb-4">
                <div class="rounded-circle bg-light p-3 me-3 text-accent"><i class="fas fa-envelope fa-lg"></i></div>
                <div><strong>Email:</strong><br><span class="text-secondary"><?= APP_EMAIL ?></span></div>
            </div>
            <div class="d-flex align-items-center mb-4">
                <div class="rounded-circle bg-light p-3 me-3 text-accent"><i class="fas fa-phone fa-lg"></i></div>
                <div><strong>Phone:</strong><br><span class="text-secondary"><?= APP_PHONE ?></span></div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
