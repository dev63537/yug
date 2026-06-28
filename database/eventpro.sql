-- EventPro - Complete Database Schema & Seed Data
-- Database: `eventpro`

CREATE DATABASE IF NOT EXISTS `eventpro` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `eventpro`;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
  `role` ENUM('user', 'organizer', 'admin') NOT NULL DEFAULT 'user',
  `status` ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
  `email_verified` TINYINT(1) DEFAULT 1,
  `verification_token` VARCHAR(100) DEFAULT NULL,
  `reset_token` VARCHAR(100) DEFAULT NULL,
  `reset_expiry` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: organizers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `organizers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `org_name` VARCHAR(150) NOT NULL,
  `bio` TEXT DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `verified` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `icon` VARCHAR(50) DEFAULT 'fa-calendar',
  `color` VARCHAR(20) DEFAULT '#6c63ff',
  `description` TEXT DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: events
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `organizer_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` LONGTEXT NOT NULL,
  `short_desc` VARCHAR(500) DEFAULT NULL,
  `rules` TEXT DEFAULT NULL,
  `venue` VARCHAR(255) NOT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT 'India',
  `start_date` DATE NOT NULL,
  `end_date` DATE DEFAULT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `seats_total` INT NOT NULL,
  `seats_booked` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(255) DEFAULT 'assets/images/default-event.jpg',
  `status` ENUM('draft', 'active', 'cancelled', 'completed') DEFAULT 'active',
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_free` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`organizer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: event_images
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_images` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: event_agenda
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `event_agenda` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `agenda_date` DATE DEFAULT NULL,
  `agenda_time` TIME NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `speaker_name` VARCHAR(100) DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: speakers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `speakers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `designation` VARCHAR(100) DEFAULT NULL,
  `company` VARCHAR(100) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: coupons
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(20) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  `type` ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
  `value` DECIMAL(10,2) NOT NULL,
  `min_amount` DECIMAL(10,2) DEFAULT 0.00,
  `max_uses` INT DEFAULT 100,
  `used_count` INT DEFAULT 0,
  `expiry_date` DATE DEFAULT NULL,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: bookings
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `event_id` INT NOT NULL,
  `booking_ref` VARCHAR(20) NOT NULL UNIQUE,
  `seats` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `coupon_id` INT DEFAULT NULL,
  `discount` DECIMAL(10,2) DEFAULT 0.00,
  `total` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'confirmed', 'cancelled', 'attended') DEFAULT 'pending',
  `payment_status` ENUM('pending', 'paid', 'refunded', 'failed') DEFAULT 'pending',
  `participant_name` VARCHAR(100) NOT NULL,
  `participant_email` VARCHAR(150) NOT NULL,
  `participant_phone` VARCHAR(20) NOT NULL,
  `special_requirements` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`coupon_id`) REFERENCES `coupons`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: payments
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `payment_method` ENUM('cash', 'upi', 'card', 'netbanking') NOT NULL,
  `transaction_id` VARCHAR(100) NOT NULL UNIQUE,
  `amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'completed',
  `gateway_response` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: invoices
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `invoice_no` VARCHAR(50) NOT NULL UNIQUE,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `tax` DECIMAL(10,2) NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `pdf_path` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: reviews
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `rating` TINYINT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: wishlist
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `event_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `user_event` (`user_id`, `event_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: notifications
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` VARCHAR(50) DEFAULT 'info',
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: blogs
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `author_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` TEXT DEFAULT NULL,
  `content` LONGTEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT 'assets/images/default-blog.jpg',
  `category` VARCHAR(100) DEFAULT 'General',
  `tags` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft', 'published') DEFAULT 'published',
  `views` INT DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: gallery
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT DEFAULT NULL,
  `image` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `category` VARCHAR(50) DEFAULT 'Events',
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: testimonials
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `name` VARCHAR(100) NOT NULL,
  `designation` VARCHAR(100) DEFAULT NULL,
  `company` VARCHAR(100) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT 'assets/images/default-avatar.png',
  `content` TEXT NOT NULL,
  `rating` TINYINT DEFAULT 5,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: contacts
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('new', 'read', 'replied') DEFAULT 'new',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: newsletter
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `status` ENUM('active', 'unsubscribed') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: sponsors
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sponsors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `logo` VARCHAR(255) NOT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `tier` ENUM('platinum', 'gold', 'silver', 'bronze') DEFAULT 'gold',
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: settings
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT DEFAULT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: audit_logs
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `action` VARCHAR(255) NOT NULL,
  `table_name` VARCHAR(100) DEFAULT NULL,
  `record_id` INT DEFAULT 0,
  `old_values` JSON DEFAULT NULL,
  `new_values` JSON DEFAULT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- SEED DATA
-- ========================================================

-- Users (Passwords are bcrypt hashed: Admin@123, Org@123, User@123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`) VALUES
(1, 'System Administrator', 'admin@eventpro.com', '$2y$10$yKAYEljaGqpTFuE601EjUOIJA6HOfFpyuIq8ItmUWitL5TNoKB.zC', '+91 9876543210', 'admin', 'active'),
(2, 'Grand Events India', 'organizer@eventpro.com', '$2y$10$sXtI68kHfx4fscdaKdeB7.ofIoQXRnMvs33aSXkz5Mn6lHVjNz7qO', '+91 9876543211', 'organizer', 'active'),
(3, 'Rahul Sharma', 'user@eventpro.com', '$2y$10$NWmNOZh6QW7aIZBXV7m5KeBRm2RR4BPbKY/pVSSxLtTNu9ZvRB6AW', '+91 9876543212', 'user', 'active');

INSERT INTO `organizers` (`id`, `user_id`, `org_name`, `bio`, `website`, `phone`, `address`, `verified`) VALUES
(1, 2, 'Grand Events India Pvt Ltd', 'Leading event management company organizing tech conferences, music fests, and workshops across India.', 'https://grandevents.com', '+91 9876543211', 'Suite 402, Tech Park, Bangalore', 1);

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `color`, `description`, `sort_order`) VALUES
(1, 'Music & Concerts', 'music-concerts', 'fa-music', '#6c63ff', 'Live concerts, musical nights, and DJ performances.', 1),
(2, 'Tech & Innovation', 'tech-innovation', 'fa-laptop-code', '#00d4aa', 'Hackathons, coding bootcamps, and AI summits.', 2),
(3, 'Business & Startup', 'business-startup', 'fa-briefcase', '#f4c430', 'Networking meets, investor pitches, and seminars.', 3),
(4, 'Sports & Fitness', 'sports-fitness', 'fa-running', '#ef4444', 'Marathons, cricket tournaments, and yoga retreats.', 4),
(5, 'Art & Culture', 'art-culture', 'fa-palette', '#ec4899', 'Exhibitions, theater plays, and cultural fests.', 5),
(6, 'Food & Drinks', 'food-drinks', 'fa-utensils', '#f97316', 'Food festivals, wine tasting, and cooking classes.', 6);

INSERT INTO `events` (`id`, `organizer_id`, `category_id`, `title`, `slug`, `description`, `short_desc`, `venue`, `city`, `start_date`, `start_time`, `price`, `seats_total`, `seats_booked`, `is_featured`, `status`) VALUES
(1, 2, 2, 'India AI & Cloud Summit 2026', 'india-ai-cloud-summit-2026', 'Join top industry leaders, developers, and AI enthusiasts for a 2-day immersive conference on Artificial Intelligence, Machine Learning, and Cloud Architecture.', 'Premier tech conference bringing together global AI innovators.', 'Bangalore International Exhibition Centre', 'Bangalore', '2026-08-15', '09:00:00', 1499.00, 500, 45, 1, 'active'),
(2, 2, 1, 'Sunburn Summer Music Festival', 'sunburn-summer-music-festival', 'Experience the biggest EDM night of the year with international DJs, stunning light displays, and non-stop music.', 'The ultimate EDM music festival experience.', 'EZone Club Ground', 'Bangalore', '2026-09-10', '17:00:00', 999.00, 1000, 320, 1, 'active'),
(3, 2, 3, 'Startup India Investor Pitch 2026', 'startup-india-investor-pitch-2026', 'Are you a founder looking for seed funding? Pitch directly to top VCs and angel investors in this high-stakes startup showcase.', 'Connect with top investors and pitch your startup idea.', 'The Leela Palace', 'Bangalore', '2026-07-20', '10:00:00', 499.00, 200, 85, 1, 'active'),
(4, 2, 4, 'Bangalore Midnight Marathon', 'bangalore-midnight-marathon', 'Run under the stars! 10K, Half Marathon, and Full Marathon categories. Proceeds go to charity.', 'Annual night marathon for health and charity.', 'Kanteerava Stadium', 'Bangalore', '2026-10-05', '22:00:00', 299.00, 2000, 600, 0, 'active');

INSERT INTO `coupons` (`id`, `code`, `description`, `type`, `value`, `min_amount`, `max_uses`, `status`) VALUES
(1, 'WELCOME10', 'Get 10% off on your first booking', 'percentage', 10.00, 300.00, 500, 'active'),
(2, 'EVENTPRO200', 'Flat Rs. 200 off on bookings above Rs. 1000', 'fixed', 200.00, 1000.00, 200, 'active');

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'EventPro Portal'),
('site_email', 'contact@eventpro.com'),
('site_phone', '+91 98765 43210'),
('currency', '₹'),
('tax_rate', '5');
