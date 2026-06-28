<?php
require_once '../config/config.php';
$page_title = "Categories Management";
include '../includes/admin/header.php';

$categories = get_categories($pdo);
?>

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
                    <th>Color</th>
                    <th>Event Count</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($categories as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><i class="fas <?= $c['icon'] ?> fa-lg" style="color: <?= $c['color'] ?>;"></i></td>
                        <td><strong><?= xss_clean($c['name']) ?></strong></td>
                        <td><code><?= $c['slug'] ?></code></td>
                        <td><span class="badge" style="background-color: <?= $c['color'] ?>;"><?= $c['color'] ?></span></td>
                        <td><?= $c['event_count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
