<?php
require_once 'config/config.php';
$page_title = "Home";
include 'includes/header.php';

$featured_events = get_featured_events($pdo, 6);
$upcoming_events = get_upcoming_events($pdo, 4);
$categories = get_categories($pdo);
?>

<!-- Hero Section -->
<section class="hero-section text-center text-lg-start">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title">Experience Unforgettable Events & Experiences</h1>
                <p class="hero-subtitle">Discover concerts, tech summits, sports events, and networking meetups near you. Book tickets instantly with EventPro.</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                    <a href="<?= APP_URL ?>/events.php" class="btn btn-accent btn-lg"><i class="fas fa-compass me-2"></i>Browse Events</a>
                    <a href="<?= APP_URL ?>/register.php" class="btn btn-outline-light btn-lg rounded-pill"><i class="fas fa-plus-circle me-2"></i>Create Event</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Bar Overlay -->
<div class="container">
    <div class="search-box-card">
        <form action="<?= APP_URL ?>/events.php" method="GET" class="row g-3 align-items-center">
            <div class="col-lg-5 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Search events by keyword...">
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['slug'] ?>"><?= xss_clean($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-4">
                <button type="submit" class="btn btn-accent w-100"><i class="fas fa-search me-2"></i>Search Now</button>
            </div>
        </form>
    </div>
</div>

<!-- Featured Events -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Events</h2>
            <p class="section-subtitle">Handpicked top events you don't want to miss</p>
        </div>
        <div class="row g-4">
            <?php if (empty($featured_events)): ?>
                <div class="col-12 text-center text-muted"><p>No featured events currently available.</p></div>
            <?php else: ?>
                <?php foreach($featured_events as $event): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="event-card">
                            <div class="position-relative">
                                <img src="<?= APP_URL ?>/<?= $event['image'] ?>" class="event-card-img" alt="<?= xss_clean($event['title']) ?>" onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=600&q=80';">
                                <span class="event-badge"><?= xss_clean($event['category_name']) ?></span>
                                <span class="event-price"><?= $event['price'] > 0 ? format_currency($event['price']) : 'FREE' ?></span>
                            </div>
                            <div class="event-card-body">
                                <div class="text-muted small mb-2"><i class="far fa-calendar-alt me-1 text-accent"></i><?= format_date($event['start_date']) ?> | <i class="fas fa-map-marker-alt ms-1 me-1 text-accent"></i><?= xss_clean($event['city']) ?></div>
                                <h5 class="fw-bold mb-2"><?= xss_clean($event['title']) ?></h5>
                                <p class="text-muted small flex-grow-1"><?= truncate_text($event['short_desc'] ?: $event['description'], 90) ?></p>
                                <hr class="my-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="small text-muted"><i class="fas fa-users me-1"></i><?= $event['seats_total'] - $event['seats_booked'] ?> seats left</span>
                                    <a href="<?= APP_URL ?>/event-details.php?slug=<?= $event['slug'] ?>" class="btn btn-sm btn-accent">Book Ticket</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Explore by Category</h2>
            <p class="section-subtitle">Find events tailored to your interests and hobbies</p>
        </div>
        <div class="row g-4">
            <?php foreach($categories as $cat): ?>
                <div class="col-lg-2 col-md-4 col-6">
                    <a href="<?= APP_URL ?>/events.php?category=<?= $cat['slug'] ?>" class="text-decoration-none">
                        <div class="stat-card h-100">
                            <div class="stat-icon" style="color: <?= $cat['color'] ?>;"><i class="fas <?= $cat['icon'] ?>"></i></div>
                            <h6 class="fw-bold text-dark mb-1"><?= xss_clean($cat['name']) ?></h6>
                            <span class="small text-muted"><?= $cat['event_count'] ?> Events</span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
