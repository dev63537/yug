<?php
require_once 'config/config.php';
$page_title = "Browse Events";
include 'includes/header.php';

$search = sanitize_input($_GET['q'] ?? '');
$category_slug = sanitize_input($_GET['category'] ?? '');
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = EVENTS_PER_PAGE;
$offset = ($page - 1) * $limit;

// Build SQL
$sql = "SELECT e.*, c.name as category_name, c.color as category_color 
        FROM events e 
        LEFT JOIN categories c ON e.category_id = c.id 
        WHERE e.status = 'active'";
$params = [];

if ($search !== '') {
    $sql .= " AND (e.title LIKE ? OR e.description LIKE ? OR e.city LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_slug !== '') {
    $sql .= " AND c.slug = ?";
    $params[] = $category_slug;
}

// Total count
$count_stmt = $pdo->prepare(str_replace("e.*, c.name as category_name, c.color as category_color", "COUNT(*) as total", $sql));
$count_stmt->execute($params);
$total_events = $count_stmt->fetch()['total'];

$sql .= " ORDER BY e.start_date ASC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll();
?>

<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">Explore Events</h1>
        <p class="mb-0 text-white-50">Discover all upcoming concerts, hackathons, seminars and workshops</p>
    </div>
</div>

<div class="container section-padding">
    <div class="row g-4">
        <?php if (empty($events)): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <h3>No Events Found</h3>
                <p class="text-muted">Try searching with different keywords or clearing filters.</p>
                <a href="<?= APP_URL ?>/events.php" class="btn btn-accent">View All Events</a>
            </div>
        <?php else: ?>
            <?php foreach($events as $event): ?>
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

    <div class="mt-5">
        <?= paginate($total_events, $limit, $page, APP_URL . "/events.php?q=" . urlencode($search) . "&category=" . urlencode($category_slug) . "&page=%d") ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
