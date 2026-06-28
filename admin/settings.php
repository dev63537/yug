<?php
require_once '../config/config.php';
$page_title = "System Settings";
include '../includes/admin/header.php';
?>

<div class="card border-0 shadow-sm rounded-4 p-4 col-lg-8">
    <h5 class="fw-bold mb-4">Portal Settings</h5>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label font-semibold">Platform Name</label>
            <input type="text" class="form-control" value="<?= APP_NAME ?>" readonly disabled>
        </div>
        <div class="mb-3">
            <label class="form-label font-semibold">System Email Address</label>
            <input type="email" class="form-control" value="<?= APP_EMAIL ?>" readonly disabled>
        </div>
        <div class="mb-3">
            <label class="form-label font-semibold">Currency Code</label>
            <input type="text" class="form-control" value="<?= CURRENCY ?> (<?= CURRENCY_CODE ?>)" readonly disabled>
        </div>
        <div class="mb-3">
            <label class="form-label font-semibold">Tax Percentage (%)</label>
            <input type="text" class="form-control" value="<?= TAX_RATE ?>%" readonly disabled>
        </div>
    </form>
</div>

<?php include '../includes/admin/footer.php'; ?>
