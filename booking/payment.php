<?php
require_once '../config/config.php';
require_login();

$booking_id = (int)($_GET['booking_id'] ?? 0);
$booking = get_booking($pdo, $booking_id);

if (!$booking || $booking['user_id'] != $_SESSION['user_id'] || $booking['payment_status'] === 'paid') {
    secure_redirect(APP_URL . '/user/bookings.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = sanitize_input($_POST['payment_method'] ?? 'card');
    $txn_id = 'TXN' . strtoupper(substr(md5(uniqid()), 0, 10));

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, user_id, payment_method, transaction_id, amount, status, created_at) VALUES (?, ?, ?, ?, ?, 'completed', NOW())");
        $stmt->execute([$booking_id, $_SESSION['user_id'], $method, $txn_id, $booking['total']]);

        $up = $pdo->prepare("UPDATE bookings SET status='confirmed', payment_status='paid' WHERE id=?");
        $up->execute([$booking_id]);

        $inv = generate_invoice_no();
        $stmt_inv = $pdo->prepare("INSERT INTO invoices (booking_id, invoice_no, subtotal, tax, total, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt_inv->execute([$booking_id, $inv, $booking['subtotal'], ($booking['total'] - $booking['subtotal']), $booking['total']]);

        $pdo->commit();

        send_notification($pdo, $_SESSION['user_id'], 'Booking Confirmed', 'Your booking #' . $booking['booking_ref'] . ' is confirmed!', 'success');

        secure_redirect(APP_URL . '/booking/success.php?booking_id=' . $booking_id);
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

$page_title = "Payment Gateway";
include '../includes/header.php';
?>

<div class="container section-padding">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 text-center">
                <h3 class="fw-bold mb-2">Simulated Payment Gateway</h3>
                <p class="text-muted mb-4">Select payment method to complete booking <strong>#<?= $booking['booking_ref'] ?></strong></p>

                <div class="alert alert-light border p-3 mb-4 text-center">
                    <span class="fs-4 fw-bold text-success"><?= format_currency($booking['total']) ?></span>
                </div>

                <form method="POST" action="">
                    <div class="mb-4 text-start">
                        <label class="form-label font-semibold">Payment Method</label>
                        <select name="payment_method" class="form-select form-select-lg">
                            <option value="upi">UPI / Google Pay / PhonePe</option>
                            <option value="card">Credit / Debit Card</option>
                            <option value="netbanking">Net Banking</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold"><i class="fas fa-lock me-2"></i>Pay Now (Mock Payment)</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
