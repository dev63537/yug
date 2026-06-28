<?php
/**
 * EventPro – Mailer Helper Functions
 */

function send_html_email(string $to, string $subject, string $body): bool {
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . APP_NAME . " <" . APP_EMAIL . ">\r\n";
    $headers .= "Reply-To: " . APP_EMAIL . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $template = '
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
            .email-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
            .header { background: #1a1a2e; color: #ffffff; padding: 25px; text-align: center; }
            .header h1 { margin: 0; font-size: 24px; }
            .content { padding: 30px; color: #333333; line-height: 1.6; }
            .footer { background: #f8fafc; color: #64748b; padding: 20px; text-align: center; font-size: 12px; border-top: 1px solid #e2e8f0; }
            .btn { display: inline-block; padding: 12px 25px; background: #6c63ff; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
        </style>
    </head>
    <body>
        <div class="email-card">
            <div class="header"><h1>' . APP_NAME . '</h1></div>
            <div class="content">' . $body . '</div>
            <div class="footer">&copy; ' . date('Y') . ' ' . APP_NAME . '. All rights reserved.<br>' . APP_ADDRESS . '</div>
        </div>
    </body>
    </html>';

    // In local dev without SMTP server, mail() may return false, so we log it to a text file for testing
    if (DEBUG_MODE) {
        $log = LOG_PATH . 'mail.log';
        if (!is_dir(LOG_PATH)) mkdir(LOG_PATH, 0755, true);
        file_put_contents($log, "[" . date('Y-m-d H:i:s') . "] TO: $to | SUBJECT: $subject\n$body\n\n-------------------\n", FILE_APPEND);
    }

    return @mail($to, $subject, $template, $headers);
}

function send_booking_confirmation(array $booking, array $event, array $user): bool {
    $subject = "Booking Confirmation - " . $booking['booking_ref'];
    $body = "<h2>Hello " . htmlspecialchars($user['name']) . ",</h2>
             <p>Thank you for booking with EventPro! Your ticket for <strong>" . htmlspecialchars($event['title']) . "</strong> has been confirmed.</p>
             <div style='background:#f8fafc; padding:15px; border-radius:6px; margin:20px 0;'>
                <p><strong>Booking Ref:</strong> " . $booking['booking_ref'] . "</p>
                <p><strong>Event Date:</strong> " . format_date($event['start_date']) . "</p>
                <p><strong>Venue:</strong> " . htmlspecialchars($event['venue']) . ", " . htmlspecialchars($event['city']) . "</p>
                <p><strong>Seats Booked:</strong> " . $booking['seats'] . "</p>
                <p><strong>Total Paid:</strong> " . format_currency($booking['total']) . "</p>
             </div>
             <p>You can view your ticket and invoice in your account dashboard.</p>
             <a href='" . APP_URL . "/user/bookings.php' class='btn'>View My Bookings</a>";
    return send_html_email($user['email'], $subject, $body);
}

function send_password_reset_email(string $email, string $token, string $name): bool {
    $subject = "Reset Your Password - " . APP_NAME;
    $reset_url = APP_URL . "/reset-password.php?token=" . $token;
    $body = "<h2>Hi " . htmlspecialchars($name) . ",</h2>
             <p>We received a request to reset your password. Click the button below to choose a new password. This link is valid for 1 hour.</p>
             <a href='" . $reset_url . "' class='btn'>Reset Password</a>
             <p style='margin-top:20px; font-size:12px; color:#777;'>If you didn't request this, you can safely ignore this email.</p>";
    return send_html_email($email, $subject, $body);
}

function send_registration_email(string $email, string $name): bool {
    $subject = "Welcome to " . APP_NAME . "!";
    $body = "<h2>Welcome, " . htmlspecialchars($name) . "!</h2>
             <p>Thank you for creating an account with EventPro. You can now browse events, book tickets, and manage your bookings seamlessly.</p>
             <a href='" . APP_URL . "/login.php' class='btn'>Login to Your Account</a>";
    return send_html_email($email, $subject, $body);
}
