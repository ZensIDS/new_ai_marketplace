-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for marketplace
CREATE DATABASE IF NOT EXISTS `marketplace` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `marketplace`;

-- Dumping structure for table marketplace.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.categories: ~5 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `created_at`, `updated_at`) VALUES
	(1, 'Elektronik', 'elektronik-CfQi', NULL, '2026-09-01 01:51:26', '2026-09-01 01:51:26'),
	(2, 'Fashion Pria', 'fashion-pria-jhPS', NULL, '2026-09-01 01:51:26', '2026-09-01 01:51:26'),
	(3, 'Fashion Wanita', 'fashion-wanita-6qAW', NULL, '2026-09-01 01:51:26', '2026-09-01 01:51:26'),
	(4, 'Kesehatan & Kecantikan', 'kesehatan-kecantikan-YM34', NULL, '2026-09-01 01:51:26', '2026-09-01 01:51:26'),
	(5, 'Peralatan Rumah', 'peralatan-rumah-Cfau', NULL, '2026-09-01 01:51:26', '2026-09-01 01:51:26'),
	(6, 'Mebel', 'mebel-p5gK', 'categories/MKD3DVnOzZlfi8RWIkZZzyFsTGQqPxkcSgbtf3eL.jpg', '2026-09-01 02:16:03', '2026-09-01 02:16:23');

-- Dumping structure for table marketplace.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table marketplace.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.migrations: ~0 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2014_10_12_100000_create_password_resets_table', 1),
	(4, '2019_08_19_000000_create_failed_jobs_table', 1),
	(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(6, '2024_01_01_000001_create_categories_table', 1),
	(7, '2024_01_01_000002_create_products_table', 1);

-- Dumping structure for table marketplace.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.password_resets: ~0 rows (approximately)

-- Dumping structure for table marketplace.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table marketplace.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table marketplace.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.products: ~9 rows (approximately)
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Headphone Bluetooth X1', 'headphone-bluetooth-x1-AcaSK', 'Produk berkualitas Headphone Bluetooth X1. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 250000.00, 8, 'products/Tc1GBBpCYpowhog6SsD7DIHRI9iWsCFLbZeaTWXq.jpg', 1, '2026-09-01 01:51:26', '2026-09-01 02:35:11'),
	(2, 2, 'Kaos Polos Premium', 'kaos-polos-premium-z8b1M', 'Produk berkualitas Kaos Polos Premium. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 85000.00, 5, 'products/0RqjE55W5MGwTszXiA9RFKglWmhJmxhU1lJhZBhQ.jpg', 1, '2026-09-01 01:51:26', '2026-09-01 02:35:18'),
	(3, 3, 'Dress Casual Wanita', 'dress-casual-wanita-hxW1R', 'Produk berkualitas Dress Casual Wanita. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 175000.00, 37, 'products/IPx0H0IBRQy1eVc3UnXRqiMaLj4fsl3zlFyPORLv.webp', 1, '2026-09-01 01:51:26', '2026-09-01 02:35:26'),
	(4, 4, 'Serum Wajah Vitamin C', 'serum-wajah-vitamin-c-2Egav', 'Produk berkualitas Serum Wajah Vitamin C. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 95000.00, 17, 'products/CCwgFxGqGNqyWE8bknxY5lczCKG1b27UWIsjLh92.jpg', 1, '2026-09-01 01:51:26', '2026-09-01 02:35:34'),
	(5, 5, 'Rice Cooker Mini 1L', 'rice-cooker-mini-1l-K1fqy', 'Produk berkualitas Rice Cooker Mini 1L. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 210000.00, 25, 'products/JMRVXbTKjm7HTudvt293Vasm7rad6onllo22CV2n.jpg', 1, '2026-09-01 01:51:26', '2026-09-01 02:35:42'),
	(6, 1, 'Power Bank 20000mAh', 'power-bank-20000mah-cCGFL', 'Produk berkualitas Power Bank 20000mAh. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 275000.00, 20, 'products/G5ACsf0wiJLpLPzm9o3Fb6RNKWqFEBhiAa8RmBi5.jpg', 1, '2026-09-01 01:51:26', '2026-09-01 02:35:50'),
	(7, 2, 'Jaket Hoodie Unisex', 'jaket-hoodie-unisex-jofhl', 'Produk berkualitas Jaket Hoodie Unisex. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 150000.00, 44, 'products/vba7wk0lN7t8lvRYsbw2uNDXhwhLTDXgOsKZs8hQ.jpg', 1, '2026-09-01 01:51:26', '2026-09-01 02:35:58'),
	(8, 3, 'Tas Selempang Wanita', 'tas-selempang-wanita-Vv8eM', 'Produk berkualitas Tas Selempang Wanita. Cocok untuk kebutuhan sehari-hari dengan harga terjangkau.', 130000.00, 25, 'products/MWAyQdAnWHZ4Rj8KVpw7FFbJcJWJH4m4hT0f3VfZ.jpg', 1, '2026-09-01 01:51:26', '2026-09-01 02:36:05'),
	(9, 6, 'Kursi Goyang', 'kursi-goyang-dI0S8', 'Bikin Goyang', 250000.00, 50, 'products/Xuy5Ah61fdCvgeKT0wYfbGz8kDlbdAlUkn1NkAAV.jpg', 1, '2026-09-01 02:17:09', '2026-09-01 02:35:04');

-- Dumping structure for table marketplace.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table marketplace.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin Marketplace', 'admin@marketplace.test', NULL, '$2y$10$MUizRWSD/pCcJxVJzeLfy.LGNq0OlkEmB6kn7hcSNoXbtByjoANCS', 'admin', '628123456789', NULL, '2026-09-01 01:51:26', '2026-09-01 01:51:26'),
	(2, 'Budi Customer', 'customer@marketplace.test', NULL, '$2y$10$aY3deA7Z9l1/vDSzNyIMEefigZH8Wj372bCl3jDx2Tkdyyj5FlzmW', 'customer', '628987654321', NULL, '2026-09-01 01:51:26', '2026-09-01 01:51:26'),
	(3, 'Hermawan Juliyanto', 'hermawan@gmail.com', NULL, '$2y$10$C2yXrMKtMAmizqJFEtprPurgFZnytJqj1CSOQU7cOJbeE6D/lRddK', 'customer', '098765436543456', NULL, '2026-09-01 02:12:05', '2026-09-01 02:12:05'),
	(4, 'Budi Joko', 'Budi@gmail.com', NULL, '$2y$10$Q1JCe8y6meDymr1mmBl3Gu1HQf7HmdIiXUf.wxiChKo7hbEjGEt/C', 'customer', '09876543456', NULL, '2026-09-01 02:38:59', '2026-09-01 02:38:59');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
