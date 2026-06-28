<?php
require_once 'config/config.php';
$page_title = "404 Not Found";
include 'includes/header.php';
?>

<div class="container section-padding text-center py-5">
    <h1 class="display-1 fw-bold text-accent">404</h1>
    <h2 class="fw-bold mb-3">Oops! Page Not Found</h2>
    <p class="text-secondary mb-4">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
    <a href="<?= APP_URL ?>/" class="btn btn-accent btn-lg px-4 py-2 rounded-pill"><i class="fas fa-home me-2"></i>Back to Homepage</a>
</div>

<?php include 'includes/footer.php'; ?>
