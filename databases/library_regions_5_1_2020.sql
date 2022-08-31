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

-- Dumping structure for table library.regions
CREATE TABLE IF NOT EXISTS `regions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `region_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table library.regions: ~17 rows (approximately)
DELETE FROM `regions`;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` (`id`, `region_code`, `region_name`, `created_at`, `updated_at`) VALUES
	(1, 'NCR', 'National Capital Region', NULL, NULL),
	(2, 'CAR', 'Cordillera Administrative Region', NULL, NULL),
	(3, '1', 'Ilocos Region', NULL, NULL),
	(4, '2', 'Cagayan Valley', NULL, NULL),
	(5, '3', 'Central Luzon', NULL, NULL),
	(6, '4A', 'Calabarzon', NULL, NULL),
	(7, '4B', 'Mimaropa', NULL, NULL),
	(8, '5', 'Bicol Region', NULL, NULL),
	(9, '6', 'Western Visayas', NULL, NULL),
	(10, '7', 'Central Visayas', NULL, NULL),
	(11, '8', 'Eastern Visayas', NULL, NULL),
	(12, '9', 'Zamboanga Peninsula', NULL, NULL),
	(13, '10', 'Northern Mindanao', NULL, NULL),
	(14, '11', 'Davao Region', NULL, NULL),
	(15, '12', 'Soccskargen', NULL, NULL),
	(16, '13', 'Caraga', NULL, NULL),
	(17, 'ARMM', 'Autonomous Region in Muslim Mindanao', NULL, NULL);
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
