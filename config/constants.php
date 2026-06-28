<?php
/**
 * EventPro - Application Constants
 * All role identifiers, status codes, limits, and type lists.
 */

// ============================================================
// USER ROLES
// ============================================================
define('USER_ROLE',      'user');
define('ORGANIZER_ROLE', 'organizer');
define('ADMIN_ROLE',     'admin');

define('ALL_ROLES', [USER_ROLE, ORGANIZER_ROLE, ADMIN_ROLE]);

// ============================================================
// USER STATUS
// ============================================================
define('USER_ACTIVE',   'active');
define('USER_INACTIVE', 'inactive');
define('USER_BANNED',   'banned');

// ============================================================
// EVENT STATUSES
// ============================================================
define('EVENT_DRAFT',     'draft');
define('EVENT_ACTIVE',    'active');
define('EVENT_CANCELLED', 'cancelled');
define('EVENT_COMPLETED', 'completed');

define('EVENT_STATUSES', [
    EVENT_DRAFT     => 'Draft',
    EVENT_ACTIVE    => 'Active',
    EVENT_CANCELLED => 'Cancelled',
    EVENT_COMPLETED => 'Completed',
]);

// ============================================================
// BOOKING STATUSES
// ============================================================
define('BOOKING_PENDING',   'pending');
define('BOOKING_CONFIRMED', 'confirmed');
define('BOOKING_CANCELLED', 'cancelled');
define('BOOKING_ATTENDED',  'attended');

define('BOOKING_STATUSES', [
    BOOKING_PENDING   => 'Pending',
    BOOKING_CONFIRMED => 'Confirmed',
    BOOKING_CANCELLED => 'Cancelled',
    BOOKING_ATTENDED  => 'Attended',
]);

// ============================================================
// PAYMENT STATUSES
// ============================================================
define('PAYMENT_PENDING',  'pending');
define('PAYMENT_PAID',     'paid');
define('PAYMENT_REFUNDED', 'refunded');
define('PAYMENT_FAILED',   'failed');

define('PAYMENT_STATUSES', [
    PAYMENT_PENDING  => 'Pending',
    PAYMENT_PAID     => 'Paid',
    PAYMENT_REFUNDED => 'Refunded',
    PAYMENT_FAILED   => 'Failed',
]);

// ============================================================
// PAYMENT METHODS
// ============================================================
define('PAY_CASH',       'cash');
define('PAY_UPI',        'upi');
define('PAY_CARD',       'card');
define('PAY_NETBANKING', 'netbanking');

define('PAYMENT_METHODS', [
    PAY_CASH       => 'Cash',
    PAY_UPI        => 'UPI',
    PAY_CARD       => 'Credit / Debit Card',
    PAY_NETBANKING => 'Net Banking',
]);

// ============================================================
// BLOG / CONTENT STATUSES
// ============================================================
define('BLOG_DRAFT',     'draft');
define('BLOG_PUBLISHED', 'published');

// ============================================================
// REVIEW / TESTIMONIAL STATUSES
// ============================================================
define('REVIEW_PENDING',  'pending');
define('REVIEW_APPROVED', 'approved');
define('REVIEW_REJECTED', 'rejected');

// ============================================================
// COUPON TYPES
// ============================================================
define('COUPON_PERCENTAGE', 'percentage');
define('COUPON_FIXED',      'fixed');

// ============================================================
// NOTIFICATION TYPES
// ============================================================
define('NOTIF_INFO',    'info');
define('NOTIF_SUCCESS', 'success');
define('NOTIF_WARNING', 'warning');
define('NOTIF_DANGER',  'danger');
define('NOTIF_BOOKING', 'booking');
define('NOTIF_PAYMENT', 'payment');
define('NOTIF_SYSTEM',  'system');

// ============================================================
// SPONSOR TIERS
// ============================================================
define('SPONSOR_PLATINUM', 'platinum');
define('SPONSOR_GOLD',     'gold');
define('SPONSOR_SILVER',   'silver');
define('SPONSOR_BRONZE',   'bronze');

// ============================================================
// CONTACT STATUS
// ============================================================
define('CONTACT_NEW',     'new');
define('CONTACT_READ',    'read');
define('CONTACT_REPLIED', 'replied');

// ============================================================
// PAGINATION LIMITS
// ============================================================
define('PER_PAGE_EVENTS',   12);
define('PER_PAGE_BLOGS',    9);
define('PER_PAGE_BOOKINGS', 10);
define('PER_PAGE_ADMIN',    20);
define('PER_PAGE_USERS',    20);
define('PER_PAGE_GALLERY',  16);
define('FEATURED_LIMIT',    6);
define('UPCOMING_LIMIT',    8);
define('RELATED_LIMIT',     4);

// ============================================================
// FILE SIZE LIMITS (bytes)
// ============================================================
define('MAX_FILE_SIZE',       5  * 1024 * 1024);  // 5 MB
define('MAX_IMAGE_SIZE',      3  * 1024 * 1024);  // 3 MB
define('MAX_DOCUMENT_SIZE',   10 * 1024 * 1024);  // 10 MB
define('MAX_AVATAR_SIZE',     2  * 1024 * 1024);  // 2 MB

// ============================================================
// ALLOWED IMAGE TYPES
// ============================================================
define('ALLOWED_IMAGE_MIME', [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/webp',
]);

define('ALLOWED_IMAGE_EXT', [
    'jpg', 'jpeg', 'png', 'gif', 'webp',
]);

define('ALLOWED_DOCUMENT_MIME', [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
]);

define('ALLOWED_DOCUMENT_EXT', [
    'pdf', 'doc', 'docx',
]);

// ============================================================
// DATE / TIME FORMATS
// ============================================================
define('DATE_FORMAT',     'd M Y');
define('DATETIME_FORMAT', 'd M Y, h:i A');
define('TIME_FORMAT',     'h:i A');
define('DB_DATE_FORMAT',  'Y-m-d');
define('DB_DATETIME_FORMAT', 'Y-m-d H:i:s');

// ============================================================
// RATING LIMITS
// ============================================================
define('RATING_MIN', 1);
define('RATING_MAX', 5);

// ============================================================
// BOOKING REFERENCE PREFIX
// ============================================================
define('BOOKING_REF_PREFIX',  'EP');
define('INVOICE_NO_PREFIX',   'INV');

// ============================================================
// CACHE TTL (seconds)
// ============================================================
define('CACHE_SHORT',  300);    // 5 min
define('CACHE_MEDIUM', 1800);   // 30 min
define('CACHE_LONG',   86400);  // 24 hrs

// ============================================================
// IMAGE DIMENSIONS (thumbnail generation)
// ============================================================
define('THUMB_W', 400);
define('THUMB_H', 300);
define('BANNER_W', 1200);
define('BANNER_H', 600);
define('AVATAR_W', 200);
define('AVATAR_H', 200);

// ============================================================
// STATUS BADGE COLORS (Bootstrap classes)
// ============================================================
define('STATUS_BADGE_COLORS', [
    'active'    => 'success',
    'inactive'  => 'secondary',
    'banned'    => 'danger',
    'pending'   => 'warning',
    'confirmed' => 'success',
    'cancelled' => 'danger',
    'attended'  => 'info',
    'draft'     => 'secondary',
    'completed' => 'primary',
    'paid'      => 'success',
    'refunded'  => 'info',
    'failed'    => 'danger',
    'approved'  => 'success',
    'rejected'  => 'danger',
    'published' => 'success',
    'new'       => 'primary',
    'read'      => 'secondary',
    'replied'   => 'success',
]);
