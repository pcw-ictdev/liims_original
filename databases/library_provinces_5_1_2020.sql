-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.37-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for library
CREATE DATABASE IF NOT EXISTS `library` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `library`;

-- Dumping structure for table library.provinces
CREATE TABLE IF NOT EXISTS `provinces` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `province_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library.provinces: ~80 rows (approximately)
DELETE FROM `provinces`;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` (`id`, `province_name`, `region`, `created_at`, `updated_at`) VALUES
	(1, 'Abra', 'CAR', NULL, NULL),
	(2, 'Agusan del Norte', '13', NULL, NULL),
	(3, 'Agusan del Sur', '13', NULL, NULL),
	(4, 'Aklan', '6', NULL, NULL),
	(5, 'Albay', '5', NULL, NULL),
	(6, 'Antique', '6', NULL, NULL),
	(7, 'Apayao', 'CAR', NULL, NULL),
	(8, 'Aurora', '3', NULL, NULL),
	(9, 'Basilan', 'ARMM', NULL, NULL),
	(10, 'Bataan', '3', NULL, NULL),
	(11, 'Batanes', '2', NULL, NULL),
	(12, 'Batangas', '4A', NULL, NULL),
	(13, 'Benguet', 'CAR', NULL, NULL),
	(14, 'Biliran', '8', NULL, NULL),
	(15, 'Bohol', '7', NULL, NULL),
	(16, 'Bukidnon', '10', NULL, NULL),
	(17, 'Bulacan', '3', NULL, NULL),
	(18, 'Cagayan', '2', NULL, NULL),
	(19, 'Camarines Norte', '5', NULL, NULL),
	(20, 'Camarines Sur', '5', NULL, NULL),
	(21, 'Camiguin', '10', NULL, NULL),
	(22, 'Capiz', '6', NULL, NULL),
	(23, 'Catanduanes', '5', NULL, NULL),
	(24, 'Cavite', '4A', NULL, NULL),
	(25, 'Cebu', '7', NULL, NULL),
	(26, 'Compostela Valley', '11', NULL, NULL),
	(27, 'Cotabato', '12', NULL, NULL),
	(28, 'Davao del Norte', '11', NULL, NULL),
	(29, 'Davao del Sur', '11', NULL, NULL),
	(30, 'Davao Oriental', '11', NULL, NULL),
	(31, 'Eastern Samar', '8', NULL, NULL),
	(32, 'Guimaras', '6', NULL, NULL),
	(33, 'Ifugao', 'CAR', NULL, NULL),
	(34, 'Ilocos Norte', '1', NULL, NULL),
	(35, 'Ilocos Sur', '1', NULL, NULL),
	(36, 'Iloilo', '6', NULL, NULL),
	(37, 'Isabela', '2', NULL, NULL),
	(38, 'Kalinga', 'CAR', NULL, NULL),
	(39, 'La Union', '1', NULL, NULL),
	(40, 'Laguna', '4A', NULL, NULL),
	(41, 'Lanao del Norte', '10', NULL, NULL),
	(42, 'Lanao del Sur', 'ARMM', NULL, NULL),
	(43, 'Leyte', '8', NULL, NULL),
	(44, 'Maguindanao', 'ARMM', NULL, NULL),
	(45, 'Marinduque', '4B', NULL, NULL),
	(46, 'Masbate', '5', NULL, NULL),
	(47, 'Metro Manila', 'NCR', NULL, NULL),
	(48, 'Misamis Occidental', '10', NULL, NULL),
	(49, 'Misamis Oriental', '10', NULL, NULL),
	(50, 'Mountain Province', 'CAR', NULL, NULL),
	(51, 'Negros Occidental', '6', NULL, NULL),
	(52, 'Negros Oriental', '7', NULL, NULL),
	(53, 'Northern Samar', '8', NULL, NULL),
	(54, 'Nueva Ecija', '3', NULL, NULL),
	(55, 'Nueva Vizcaya', '2', NULL, NULL),
	(56, 'Occidental Mindoro', '4B', NULL, NULL),
	(57, 'Oriental Mindoro', '4B', NULL, NULL),
	(58, 'Palawan', '4B', NULL, NULL),
	(59, 'Pampanga', '3', NULL, NULL),
	(60, 'Pangasinan', '1', NULL, NULL),
	(61, 'Quezon', '4A', NULL, NULL),
	(62, 'Quirino', '2', NULL, NULL),
	(63, 'Rizal', '4A', NULL, NULL),
	(64, 'Romblon', '4B', NULL, NULL),
	(65, 'Samar', '8', NULL, NULL),
	(66, 'Sarangani', '12', NULL, NULL),
	(67, 'Siquijor', '7', NULL, NULL),
	(68, 'Sorsogon', '5', NULL, NULL),
	(69, 'South Cotabato', '12', NULL, NULL),
	(70, 'Southern Leyte', '8', NULL, NULL),
	(71, 'Sultan Kudarat', '12', NULL, NULL),
	(72, 'Sulu', 'ARMM', NULL, NULL),
	(73, 'Surigao del Norte', '13', NULL, NULL),
	(74, 'Surigao del Sur', '13', NULL, NULL),
	(75, 'Tarlac', '3', NULL, NULL),
	(76, 'Tawi-Tawi', 'ARMM', NULL, NULL),
	(77, 'Zambales', '3', NULL, NULL),
	(78, 'Zamboanga del Norte', '9', NULL, NULL),
	(79, 'Zamboanga del Sur', '9', NULL, NULL),
	(80, 'Zamboanga Sibugay', '9', NULL, NULL);
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
