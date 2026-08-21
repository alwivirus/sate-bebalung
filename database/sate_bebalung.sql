-- Depot Sate & Gulai Be Ba Lung Database Export for XAMPP MySQL
-- Database: `sate_bebalung`

CREATE DATABASE IF NOT EXISTS `sate_bebalung` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sate_bebalung`;

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`, `created_at`, `updated_at`) VALUES (1, 'MENU MAKANAN', 'makanan', '🍱', 1, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`, `created_at`, `updated_at`) VALUES (2, 'MINUMAN', 'minuman', '☕', 2, '2026-08-21 04:08:49', '2026-08-21 04:08:49');

CREATE TABLE `menus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_category_id_foreign` (`category_id`),
  CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (1, 1, 'Sate Kambing Polos', 'Sate daging kambing empuk tanpa lemak, bumbu kacang/kecap khas Be Ba Lung.', 50000.00, 'sate_kambing_polos.png', 1, 1, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (2, 1, 'Sate Kambing Campur', 'Sate daging kambing campur lemak gurih dengan bumbu spesial.', 45000.00, 'sate_kambing_campur.png', 1, 2, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (3, 1, 'Tongseng Kambing', 'Olahan daging kambing berkuah santan gurih, kol segar, dan tomat.', 35000.00, 'tongseng_kambing.png', 1, 3, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (4, 1, 'Sop Kambing', 'Kuah bening rempah segar dengan potongan daging kambing lembut.', 30000.00, 'sop_kambing.png', 1, 4, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (5, 1, 'Gulai Kambing', 'Gulai kuah kuning kental rempah istimewa dan daging kambing empuk.', 30000.00, 'gulai_kambing.png', 1, 5, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (6, 1, 'Paket Murah', 'Paket hemat nasi + sate/gulai porsi pas untuk santap siang.', 22000.00, 'paket_murah.png', 1, 6, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (7, 2, 'Air Putih', 'Air mineral segar dan higienis.', 2000.00, 'air_putih.png', 1, 1, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (8, 2, 'Teh Tawar', 'Teh hangat tawar aroma melati.', 2000.00, 'teh_tawar.png', 1, 2, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (9, 2, 'Es Teh Tawar', 'Es teh tawar dingin segar.', 3000.00, 'es_teh_tawar.png', 1, 3, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (10, 2, 'Es Teh Manis', 'Es teh manis segar pelepas dahaga.', 4000.00, 'es_teh_manis.png', 0, 4, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (11, 2, 'Jeruk Panas', 'Perasan jeruk asli hangat dengan gula murni.', 8000.00, 'jeruk_panas.png', 1, 5, '2026-08-21 04:08:49', '2026-08-21 04:08:49');
INSERT INTO `menus` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES (12, 2, 'Es Jeruk', 'Perasan jeruk segar asli dingin nikmat.', 10000.00, 'es_jeruk.png', 1, 6, '2026-08-21 04:08:49', '2026-08-21 04:08:49');

CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `table_number` varchar(255) NOT NULL,
  `payment_method` enum('online','kasir') NOT NULL DEFAULT 'online',
  `payment_status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `order_status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `menu_id` bigint(20) unsigned NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
