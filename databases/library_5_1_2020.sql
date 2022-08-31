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

-- Dumping structure for table library.cities
CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `city_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_province_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1637 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_organization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_contact_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.iecrequests
CREATE TABLE IF NOT EXISTS `iecrequests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.iecs
CREATE TABLE IF NOT EXISTS `iecs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iec_refno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_publisher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_copyright_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_page` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_material_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_threshold` int(11) DEFAULT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.iec_printing_logs
CREATE TABLE IF NOT EXISTS `iec_printing_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iec_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_printing_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_printing_contractor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_printing_cost` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_printing_pcs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_printing_remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.iec_stock_updates
CREATE TABLE IF NOT EXISTS `iec_stock_updates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iec_update_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_update_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_update_threshold` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_update_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iec_update_pieces` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iec_update_remarks` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.materials
CREATE TABLE IF NOT EXISTS `materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `material_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_stock` int(11) DEFAULT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.organizations
CREATE TABLE IF NOT EXISTS `organizations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.provinces
CREATE TABLE IF NOT EXISTS `provinces` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `province_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.regions
CREATE TABLE IF NOT EXISTS `regions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `region_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.requests
CREATE TABLE IF NOT EXISTS `requests` (
  `rec_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_organization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_chk_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_purpose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_material_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_material_quantity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `request_client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_client_organization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `editorID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table library.usertypes
CREATE TABLE IF NOT EXISTS `usertypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `utype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
