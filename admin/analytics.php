<?php
require_once '../config/config.php';
$page_title = "System Analytics";
include '../includes/admin/header.php';
?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-4">Platform Growth & Analytics</h5>
    <p class="text-muted">Analytics data and performance overview for EventPro portal.</p>
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="p-4 border rounded-3 bg-light text-center">
                <h6 class="text-muted">Booking Completion Rate</h6>
                <h2 class="fw-bold text-success mb-0">94.2%</h2>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 border rounded-3 bg-light text-center">
                <h6 class="text-muted">Average Ticket Value</h6>
                <h2 class="fw-bold text-accent mb-0">₹ 849.00</h2>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin/footer.php'; ?>
