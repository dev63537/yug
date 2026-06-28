<?php
require_once 'config/config.php';
$page_title = "Event Categories";
include 'includes/header.php';

$categories = get_categories($pdo);
?>

<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">Event Categories</h1>
        <p class="mb-0 text-white-50">Browse events by your favourite category</p>
    </div>
</div>

<div class="container section-padding">
    <div class="row g-4">
        <?php foreach($categories as $cat): ?>
            <div class="col-lg-4 col-md-6">
                <a href="<?= APP_URL ?>/events.php?category=<?= $cat['slug'] ?>" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 transition-all hover-lift">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 text-white me-3" style="background-color: <?= $cat['color'] ?>;">
                                <i class="fas <?= $cat['icon'] ?> fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-0"><?= xss_clean($cat['name']) ?></h4>
                                <span class="text-muted small"><?= $cat['event_count'] ?> Active Events</span>
                            </div>
                        </div>
                        <p class="text-secondary mb-0"><?= xss_clean($cat['description'] ?: 'Explore top events in this category.') ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
