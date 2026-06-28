<?php
/**
 * EventPro – Helper Functions
 */

// ─── Formatting ───────────────────────────────────────────────────────────────
function format_currency(float|int|string $amount): string {
    return CURRENCY . ' ' . number_format((float)$amount, 2);
}

function format_date(string $date, string $format = 'd M Y'): string {
    if (empty($date) || $date === '0000-00-00') return 'N/A';
    return date($format, strtotime($date));
}

function format_datetime(string $datetime, string $format = 'd M Y, h:i A'): string {
    if (empty($datetime) || $datetime === '0000-00-00 00:00:00') return 'N/A';
    return date($format, strtotime($datetime));
}

function time_ago(string $datetime): string {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' mins ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hrs ago';
    if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
    return date('d M Y', $time);
}

function truncate_text(string $text, int $length = 100): string {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

// ─── Generators ───────────────────────────────────────────────────────────────
function generate_booking_ref(): string {
    return 'EP' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 8));
}

function generate_invoice_no(): string {
    return 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
}

function generate_qr_data(array $booking): string {
    return json_encode([
        'ref' => $booking['booking_ref'],
        'event' => $booking['event_title'] ?? '',
        'user' => $booking['participant_name'] ?? '',
        'seats' => $booking['seats'] ?? 1,
        'status' => $booking['status'] ?? 'pending'
    ]);
}

// ─── Event Queries ────────────────────────────────────────────────────────────
function get_featured_events(PDO $pdo, int $limit = 6): array {
    $stmt = $pdo->prepare("SELECT e.*, c.name as category_name, c.color as category_color 
                           FROM events e 
                           LEFT JOIN categories c ON e.category_id = c.id 
                           WHERE e.is_featured = 1 AND e.status = 'active' AND e.start_date >= CURDATE()
                           ORDER BY e.start_date ASC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_upcoming_events(PDO $pdo, int $limit = 4): array {
    $stmt = $pdo->prepare("SELECT e.*, c.name as category_name, c.icon as category_icon
                           FROM events e 
                           LEFT JOIN categories c ON e.category_id = c.id 
                           WHERE e.status = 'active' AND e.start_date >= CURDATE()
                           ORDER BY e.start_date ASC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_event_by_id(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT e.*, c.name as category_name, c.slug as category_slug, u.name as organizer_name, o.org_name
                           FROM events e 
                           LEFT JOIN categories c ON e.category_id = c.id 
                           LEFT JOIN users u ON e.organizer_id = u.id
                           LEFT JOIN organizers o ON u.id = o.user_id
                           WHERE e.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function get_event_by_slug(PDO $pdo, string $slug): ?array {
    $stmt = $pdo->prepare("SELECT e.*, c.name as category_name, c.slug as category_slug, u.name as organizer_name, u.email as organizer_email, o.org_name, o.bio as org_bio
                           FROM events e 
                           LEFT JOIN categories c ON e.category_id = c.id 
                           LEFT JOIN users u ON e.organizer_id = u.id
                           LEFT JOIN organizers o ON u.id = o.user_id
                           WHERE e.slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function get_categories(PDO $pdo): array {
    $stmt = $pdo->query("SELECT c.*, COUNT(e.id) as event_count 
                         FROM categories c 
                         LEFT JOIN events e ON c.id = e.category_id AND e.status = 'active'
                         WHERE c.status = 'active'
                         GROUP BY c.id ORDER BY c.sort_order ASC");
    return $stmt->fetchAll();
}

function get_user_bookings(PDO $pdo, int $user_id): array {
    $stmt = $pdo->prepare("SELECT b.*, e.title as event_title, e.image as event_image, e.start_date, e.venue, e.city
                           FROM bookings b 
                           JOIN events e ON b.event_id = e.id 
                           WHERE b.user_id = ? ORDER BY b.created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function get_booking(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT b.*, e.title as event_title, e.image as event_image, e.start_date, e.start_time, e.venue, e.address, e.city, p.payment_method, p.transaction_id
                           FROM bookings b 
                           JOIN events e ON b.event_id = e.id 
                           LEFT JOIN payments p ON b.id = p.booking_id
                           WHERE b.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function update_seats(PDO $pdo, int $event_id, int $qty): bool {
    $stmt = $pdo->prepare("UPDATE events SET seats_booked = seats_booked + ? WHERE id = ? AND (seats_total - seats_booked) >= ?");
    return $stmt->execute([$qty, $event_id, $qty]);
}

function send_notification(PDO $pdo, int $user_id, string $title, string $message, string $type = 'info'): void {
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $title, $message, $type]);
    } catch (PDOException $e) {}
}

function get_settings(PDO $pdo): array {
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

function paginate(int $total, int $per_page, int $current_page, string $url_pattern): string {
    $total_pages = ceil($total / $per_page);
    if ($total_pages <= 1) return '';

    $html = '<nav><ul class="pagination justify-content-center">';
    
    // Prev
    if ($current_page > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page - 1) . '"><i class="fas fa-chevron-left"></i></a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-left"></i></span></li>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $i) . '">' . $i . '</a></li>';
        }
    }

    // Next
    if ($current_page < $total_pages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . sprintf($url_pattern, $current_page + 1) . '"><i class="fas fa-chevron-right"></i></a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link"><i class="fas fa-chevron-right"></i></span></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}
