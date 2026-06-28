<?php
require_once 'config/config.php';

$slug = sanitize_input($_GET['slug'] ?? '');
$event = get_event_by_slug($pdo, $slug);

if (!$event) {
    secure_redirect(APP_URL . '/404.php');
}

$page_title = $event['title'];
include 'includes/header.php';

$available_seats = $event['seats_total'] - $event['seats_booked'];
?>

<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-accent mb-2"><?= xss_clean($event['category_name']) ?></span>
                <h1 class="fw-bold mb-3"><?= xss_clean($event['title']) ?></h1>
                <p class="mb-2"><i class="far fa-calendar-alt me-2 text-warning"></i><?= format_date($event['start_date']) ?> at <?= date('h:i A', strtotime($event['start_time'])) ?></p>
                <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-warning"></i><?= xss_clean($event['venue']) ?>, <?= xss_clean($event['city']) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container section-padding">
    <div class="row g-5">
        <div class="col-lg-8">
            <img src="<?= APP_URL ?>/<?= $event['image'] ?>" class="img-fluid rounded-4 mb-4 shadow w-100" alt="<?= xss_clean($event['title']) ?>" onerror="this.src='https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80';">
            
            <h4 class="fw-bold mb-3">About This Event</h4>
            <div class="lh-lg text-secondary mb-5">
                <?= nl2br(xss_clean($event['description'])) ?>
            </div>

            <?php if (!empty($event['rules'])): ?>
                <h4 class="fw-bold mb-3">Event Rules & Guidelines</h4>
                <div class="alert alert-warning border-0 shadow-sm mb-5">
                    <?= nl2br(xss_clean($event['rules'])) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 100px;">
                <h4 class="fw-bold mb-3">Ticket Information</h4>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="text-muted">Price Per Seat</span>
                    <span class="fs-3 fw-bold text-accent"><?= $event['price'] > 0 ? format_currency($event['price']) : 'FREE' ?></span>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Availability</span>
                        <span><?= $available_seats ?> / <?= $event['seats_total'] ?> Seats Left</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-accent" style="width: <?= ($event['seats_booked'] / $event['seats_total']) * 100 ?>%;"></div>
                    </div>
                </div>

                <?php if ($available_seats > 0): ?>
                    <a href="<?= APP_URL ?>/booking/checkout.php?event_id=<?= $event['id'] ?>" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold mb-3"><i class="fas fa-ticket-alt me-2"></i>Book Seats Now</a>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg w-100 py-3 rounded-pill fw-bold mb-3" disabled>Sold Out</button>
                <?php endif; ?>

                <hr class="my-4">

                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-light p-3 me-3"><i class="fas fa-building text-primary fa-lg"></i></div>
                    <div>
                        <small class="text-muted d-block">Organized by</small>
                        <strong class="text-dark"><?= xss_clean($event['org_name'] ?: $event['organizer_name']) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
