<?php
require_once '../config/config.php';
$page_title = "Create New Event";
include '../includes/organizer/header.php';

$categories = get_categories($pdo);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $short_desc = sanitize_input($_POST['short_desc']);
    $description = sanitize_input($_POST['description']);
    $venue = sanitize_input($_POST['venue']);
    $city = sanitize_input($_POST['city']);
    $start_date = $_POST['start_date'];
    $start_time = $_POST['start_time'];
    $price = (float)$_POST['price'];
    $seats_total = (int)$_POST['seats_total'];
    $slug = slugify($title) . '-' . rand(100, 999);

    $image = 'assets/images/default-event.jpg';
    if (!empty($_FILES['image']['name'])) {
        $img_res = save_uploaded_image($_FILES['image'], 'events');
        if ($img_res) $image = $img_res;
    }

    $stmt = $pdo->prepare("INSERT INTO events (organizer_id, category_id, title, slug, description, short_desc, venue, city, start_date, start_time, price, seats_total, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW())");
    if ($stmt->execute([$_SESSION['user_id'], $category_id, $title, $slug, $description, $short_desc, $venue, $city, $start_date, $start_time, $price, $seats_total, $image])) {
        redirect_with_message(APP_URL . '/organizer/manage-events.php', 'Event created successfully!', 'success');
    } else {
        $error = 'Failed to create event.';
    }
}
?>

<div class="card border-0 shadow-sm rounded-4 p-4 col-lg-9">
    <h5 class="fw-bold mb-4">Event Details</h5>
    <?php if ($error): ?><div class="alert alert-danger alert-custom mb-4"><?= xss_clean($error) ?></div><?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label font-semibold">Event Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Annual Tech Conference 2026" required>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label font-semibold">Category</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach($categories as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= xss_clean($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Event Image</label>
                <input type="file" name="image" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label font-semibold">Short Summary</label>
            <input type="text" name="short_desc" class="form-control" placeholder="Brief 1-line description" required>
        </div>
        <div class="mb-3">
            <label class="form-label font-semibold">Full Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label font-semibold">Venue Name</label>
                <input type="text" name="venue" class="form-control" placeholder="Convention Center" required>
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">City</label>
                <input type="text" name="city" class="form-control" placeholder="Bangalore" required>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label font-semibold">Start Date</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Start Time</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label font-semibold">Ticket Price (₹)</label>
                <input type="number" name="price" step="0.01" class="form-control" value="0.00" required>
            </div>
            <div class="col-md-6">
                <label class="form-label font-semibold">Total Seats Available</label>
                <input type="number" name="seats_total" class="form-control" value="100" required>
            </div>
        </div>
        <button type="submit" class="btn btn-accent px-4 py-2 rounded-pill font-semibold">Publish Event</button>
    </form>
</div>

<?php include '../includes/organizer/footer.php'; ?>
