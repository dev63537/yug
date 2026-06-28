<?php
require_once '../config/config.php';
require_login();

$event_id = (int)($_GET['event_id'] ?? 0);
$event = get_event_by_id($pdo, $event_id);

if (!$event || $event['status'] !== 'active') {
    secure_redirect(APP_URL . '/events.php');
}

$available_seats = $event['seats_total'] - $event['seats_booked'];
if ($available_seats <= 0) {
    redirect_with_message(APP_URL . '/event-details.php?slug=' . $event['slug'], 'Sorry, this event is sold out.', 'danger');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid token.';
    } else {
        $seats = max(1, min((int)$_POST['seats'], $available_seats));
        $name = sanitize_input($_POST['participant_name']);
        $email = sanitize_input($_POST['participant_email']);
        $phone = sanitize_input($_POST['participant_phone']);

        $unit_price = $event['price'];
        $subtotal = $unit_price * $seats;
        $tax = ($subtotal * TAX_RATE) / 100;
        $total = $subtotal + $tax;

        $booking_ref = generate_booking_ref();

        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, event_id, booking_ref, seats, unit_price, subtotal, total, status, payment_status, participant_name, participant_email, participant_phone, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', ?, ?, ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $event_id, $booking_ref, $seats, $unit_price, $subtotal, $total, $name, $email, $phone]);
            $booking_id = $pdo->lastInsertId();

            update_seats($pdo, $event_id, $seats);

            $pdo->commit();

            if ($total == 0) {
                // Free event
                $up = $pdo->prepare("UPDATE bookings SET status='confirmed', payment_status='paid' WHERE id=?");
                $up->execute([$booking_id]);
                secure_redirect(APP_URL . '/booking/success.php?booking_id=' . $booking_id);
            } else {
                secure_redirect(APP_URL . '/booking/payment.php?booking_id=' . $booking_id);
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Booking failed. Please try again.';
        }
    }
}

$page_title = "Checkout";
include '../includes/header.php';
?>

<div class="container section-padding">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
                <h3 class="fw-bold mb-4">Checkout & Participant Details</h3>

                <?php if ($error): ?><div class="alert alert-danger alert-custom mb-4"><?= xss_clean($error) ?></div><?php endif; ?>

                <form method="POST" action="">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Event</label>
                        <input type="text" class="form-control" value="<?= xss_clean($event['title']) ?>" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-semibold">Number of Seats</label>
                        <input type="number" id="seats_count" name="seats" class="form-control" value="1" min="1" max="<?= min(10, $available_seats) ?>" required>
                        <input type="hidden" id="unit_price_val" value="<?= $event['price'] ?>">
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Participant Information</h5>
                    <div class="mb-3">
                        <label class="form-label font-semibold">Full Name</label>
                        <input type="text" name="participant_name" class="form-control" value="<?= xss_clean($_SESSION['user_name']) ?>" required>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label font-semibold">Email</label>
                            <input type="email" name="participant_email" class="form-control" value="<?= xss_clean($_SESSION['user_email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-semibold">Phone</label>
                            <input type="text" name="participant_phone" class="form-control" placeholder="+91 9876543210" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 rounded-pill fw-bold">Proceed to Payment</button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
                <h4 class="fw-bold mb-3">Order Summary</h4>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Ticket Price</span>
                    <span><?= format_currency($event['price']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tax (<?= TAX_RATE ?>%)</span>
                    <span>Calculated at checkout</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5 text-accent">
                    <span>Estimated Total</span>
                    <span><?= CURRENCY ?> <span id="total_price_val"><?= number_format($event['price'], 2) ?></span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= APP_URL ?>/assets/js/booking.js"></script>
<?php include '../includes/footer.php'; ?>
