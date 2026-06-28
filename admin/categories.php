<?php
require_once '../config/config.php';
$page_title = "Manage Categories";
include '../includes/admin/header.php';

$error = '';
$success = '';

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = sanitize_input($_POST['name']);
    $icon = sanitize_input($_POST['icon'] ?: 'fa-calendar');
    $color = sanitize_input($_POST['color'] ?: '#6c63ff');
    $description = sanitize_input($_POST['description']);
    $slug = slugify($name);

    try {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug, icon, color, description, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', NOW())");
        $stmt->execute([$name, $slug, $icon, $color, $description]);
        $success = "Category '$name' created successfully!";
    } catch (Exception $e) {
        $error = "Failed to add category. Name/Slug might already exist.";
    }
}

// Handle Delete/Toggle
if (isset($_GET['action']) && isset($_GET['id'])) {
    $cat_id = (int)$_GET['id'];
    if ($_GET['action'] === 'delete') {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$cat_id]);
        redirect_with_message(APP_URL . '/admin/categories.php', 'Category deleted.', 'info');
    }
}

$categories = get_categories($pdo);
?>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3">Add New Category</h5>
            <?php if ($error): ?><div class="alert alert-danger alert-custom mb-3"><?= xss_clean($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success alert-custom mb-3"><?= xss_clean($success) ?></div><?php endif; ?>

            <form method="POST" action="">
                <input type="hidden" name="add_category" value="1">
                <div class="mb-3">
                    <label class="form-label font-semibold">Category Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Gaming & Esports" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label font-semibold">FontAwesome Icon</label>
                        <input type="text" name="icon" class="form-control" placeholder="fa-gamepad" value="fa-calendar">
                    </div>
                    <div class="col-6">
                        <label class="form-label font-semibold">Accent Color</label>
                        <input type="color" name="color" class="form-control form-control-color w-100" value="#6c63ff">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label font-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief description of category..."></textarea>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2 rounded-pill font-semibold">Create Category</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-4">Event Categories</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Events</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $c): ?>
                            <tr>
                                <td><?= $c['id'] ?></td>
                                <td><i class="fas <?= $c['icon'] ?> fa-lg" style="color: <?= $c['color'] ?>;"></i></td>
                                <td><strong><?= xss_clean($c['name']) ?></strong></td>
                                <td><code><?= $c['slug'] ?></code></td>
                                <td><span class="badge bg-light text-dark border"><?= $c['event_count'] ?> Events</span></td>
                                <td>
                                    <a href="<?= APP_URL ?>/admin/categories.php?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?');"><i class="fas fa-trash me-1"></i>Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
