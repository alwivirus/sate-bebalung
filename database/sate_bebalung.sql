-- Database Dump for Depot Sate Be Ba Lung
-- Domain: bebalung.my.id
-- Import this file into phpMyAdmin in cPanel

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL UNIQUE,
  `role` varchar(50) NOT NULL DEFAULT 'kasir',
  `email` varchar(255) NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Users (Password: 'password', admin juga punya 'admin123')
INSERT INTO `users` (`id`, `name`, `username`, `role`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 'admin', 'admin@bebarung.com', '$2y$12$6/7/L9xVev61MvJzWf2x0OeVp45p92j221uFfOq7a2zM1cE3lK1yC', NOW(), NOW()),
(2, 'Kasir Utama', 'kasir', 'kasir', 'kasir@bebarung.com', '$2y$12$e6/n3y4v.Y8k9k8Jk8l2ve90v12j221uFfOq7a2zM1cE3lK1yC5vW', NOW(), NOW()),
(3, 'Dapur / Koki', 'dapur', 'dapur', 'dapur@bebarung.com', '$2y$12$e6/n3y4v.Y8k9k8Jk8l2ve90v12j221uFfOq7a2zM1cE3lK1yC5vW', NOW(), NOW()),
(4, 'Owner / Pemilik', 'owner', 'owner', 'owner@bebarung.com', '$2y$12$e6/n3y4v.Y8k9k8Jk8l2ve90v12j221uFfOq7a2zM1cE3lK1yC5vW', NOW(), NOW());

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Paket Murah', 'paket-murah', 'fa-fire-flame-curved', 1, NOW(), NOW()),
(2, 'Sate Khas', 'sate', 'fa-utensils', 2, NOW(), NOW()),
(3, 'Olahan Kuah', 'olahan-kuah', 'fa-bowl-food', 3, NOW(), NOW()),
(4, 'Minuman Segar', 'minuman', 'fa-glass-water', 4, NOW(), NOW()),
(5, 'Pelengkap & Nasi', 'pelengkap', 'fa-bowl-rice', 5, NOW(), NOW());

-- --------------------------------------------------------
-- Table: menus
-- --------------------------------------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menus` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `image`, `badge`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paket Hemat Bebalung 1', 'paket-hemat-bebalung-1', 'Sate Kambing (5 tusuk) + Sop Bebalung Gurih + Nasi Putih + Es Teh', 45000.00, 'images/menus/paket_murah.png', 'FAVORIT', 1, 1, NOW(), NOW()),
(2, 1, 'Paket Bebalung Berdua', 'paket-bebalung-berdua', 'Sate Kambing (10 tusuk) + Tongseng Kambing + 2 Nasi Gurih + 2 Es Jeruk', 85000.00, 'images/menus/paket_murah.png', 'HEMAT 20%', 1, 2, NOW(), NOW()),
(3, 2, 'Sate Bebalung Polos (10 Tusuk)', 'sate-bebalung-polos-10-tusuk', '10 tusuk daging kambing muda pilihan tanpa lemak dengan bumbu rempah khas', 42000.00, 'images/menus/sate_kambing_polos.png', 'BEST SELLER', 1, 3, NOW(), NOW()),
(4, 2, 'Sate Bebalung Campur (10 Tusuk)', 'sate-bebalung-campur-10-tusuk', '10 tusuk daging kambing muda gurih kombinasi selingan lemak renyah juicy', 38000.00, 'images/menus/sate_kambing_campur.png', 'POPULER', 1, 4, NOW(), NOW()),
(5, 2, 'Sate Ayam Gurih (10 Tusuk)', 'sate-ayam-gurih-10-tusuk', '10 tusuk daging ayam empuk bakar bumbu kacang istimewa', 28000.00, 'images/menus/sate_ayam.png', NULL, 1, 5, NOW(), NOW()),
(6, 3, 'Sop Bebalung Iga Kambing', 'sop-bebalung-iga-kambing', 'Sup iga kambing empuk kuah kaldu rempah segar khas Sasak Lombok', 35000.00, 'images/menus/sop_kambing.png', 'REKOMENDASI', 1, 6, NOW(), NOW()),
(7, 3, 'Gulai Bebalung Rempah', 'gulai-bebalung-rempah', 'Gulai kambing kuah santan pekat aroma rempah nusantara yang hangat gurih', 35000.00, 'images/menus/gulai_kambing.png', NULL, 1, 7, NOW(), NOW()),
(8, 3, 'Tongseng Kambing Pedas', 'tongseng-kambing-pedas', 'Potongan kambing empuk dimasak kuah tongseng manis gurih dengan irisan kol & tomat', 36000.00, 'images/menus/tongseng_kambing.png', 'PEDAS MANTAP', 1, 8, NOW(), NOW()),
(9, 4, 'Es Teh Manis Segar', 'es-teh-manis-segar', 'Teh racikan asli wangi melati disajikan dingin menyegarkan', 5000.00, 'images/menus/es_teh_manis.png', NULL, 1, 9, NOW(), NOW()),
(10, 4, 'Es Jeruk Peras Murni', 'es-jeruk-peras-murni', 'Perasan jeruk asli segar kaya vitamin C', 8000.00, 'images/menus/es_jeruk.png', 'SEGAR', 1, 10, NOW(), NOW()),
(11, 4, 'Teh Poci Gula Batu', 'teh-poci-gula-batu', 'Teh poci tanah liat panas harum dengan gula batu tradisional', 10000.00, 'images/menus/teh_poci.png', 'KLASIK', 1, 11, NOW(), NOW()),
(12, 4, 'Kopi Toebroek Mantap', 'kopi-toebroek-mantap', 'Kopi hitam tubruk biji kopi nusantara pilihan harum pekat', 7000.00, 'images/menus/kopi_toebroek.svg', NULL, 1, 12, NOW(), NOW()),
(13, 5, 'Nasi Putih Pulen', 'nasi-putih-pulen', 'Satu porsi nasi putih hangat pulen harum', 5000.00, 'images/menus/nasi_putih.png', NULL, 1, 13, NOW(), NOW()),
(14, 5, 'Nasi Gurih Rempah', 'nasi-gurih-rempah', 'Nasi dengan bumbu gurih rempah aromatik daun jeruk', 7000.00, 'images/menus/nasi_gurih.png', 'GURIH', 1, 14, NOW(), NOW());

-- --------------------------------------------------------
-- Table: tables
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tables`;
CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_number` varchar(50) NOT NULL UNIQUE,
  `status` enum('available','occupied') NOT NULL DEFAULT 'available',
  `current_customer_name` varchar(255) DEFAULT NULL,
  `current_order_code` varchar(100) DEFAULT NULL,
  `last_scanned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tables` (`table_number`, `status`, `created_at`, `updated_at`) VALUES
('01', 'available', NOW(), NOW()),
('02', 'available', NOW(), NOW()),
('03', 'available', NOW(), NOW()),
('04', 'available', NOW(), NOW()),
('05', 'available', NOW(), NOW()),
('06', 'available', NOW(), NOW()),
('07', 'available', NOW(), NOW()),
('08', 'available', NOW(), NOW()),
('09', 'available', NOW(), NOW()),
('10', 'available', NOW(), NOW()),
('11', 'available', NOW(), NOW()),
('12', 'available', NOW(), NOW()),
('13', 'available', NOW(), NOW()),
('14', 'available', NOW(), NOW()),
('15', 'available', NOW(), NOW()),
('16', 'available', NOW(), NOW()),
('17', 'available', NOW(), NOW()),
('18', 'available', NOW(), NOW()),
('19', 'available', NOW(), NOW()),
('20', 'available', NOW(), NOW());

-- --------------------------------------------------------
-- Table: settings
-- --------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL UNIQUE,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('qris_account_name', 'DEPOT SATE BE BA LUNG', NOW(), NOW()),
('qris_account_number', '0812-2591-1012 (GoPay / OVO / Dana / BCA)', NOW(), NOW()),
('qris_note', 'Scan QRIS via GoPay, BCA Mobile, Dana, OVO, ShopeePay, atau Bank apa saja.', NOW(), NOW());

-- --------------------------------------------------------
-- Table: orders
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) NOT NULL UNIQUE,
  `customer_name` varchar(255) NOT NULL,
  `table_number` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'kasir',
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `order_status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_proof` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: order_items
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: sessions & cache
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
