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

-- Dumping data for table library.users: ~5 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `organization`, `email_verified_at`, `password`, `uid`, `authorID`, `editorID`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Super Admin', 'admin.liims@pcw.gov.ph', '1', NULL, '$2y$12$Hwh6OxbWY4qjE9lsePL3duXlfaBoBtWfLLkBl8oSi6jfJ/3k6rnNq', '1', '', '', 'Lb3vqP0u0JebsRcwqAgMEmzfVYQvsxL6mcSoDQQtjL76trD4zivFc7Fshhhd', NULL, NULL),
	(6, 'Nico Natividad', 'ithelpdesk@pcw.gov.ph', NULL, NULL, '$2y$10$MUgOqpBf2kjKWOlW2Yc5WeeQ5hTYERYh1Nss/VasrihsDDFNVs.lO', '1', '1', NULL, NULL, '2020-04-30 12:05:09', NULL),
	(7, 'Vicky Atanacio', 'vatanacio@pcw.gov.ph', NULL, NULL, '$2y$10$C5nMJ4CTTgJBNa1t5nDvduNb1lfvg17yDn75jntr7I6I9Gkkh.rby', '1', '1', NULL, NULL, '2020-04-30 12:08:10', NULL),
	(8, 'Jonathan Pascual', 'japascual@pcw.gov.ph', NULL, NULL, '$2y$10$QWH9IEDufWpCh1o14ThM0usGSxpL5lkr/uzSnekVKHs9.LQdAQnVC', '1', '1', NULL, NULL, '2020-04-30 12:08:41', NULL),
	(9, 'Honey M. Castro', 'cairmd.chief@pcw.gov.ph', NULL, NULL, '$2y$10$GVXxokuTxO2iJKzLxjWqSe/hh3GhfCh0mwXjp7aXafsIcITvppKtW', '1', '1', NULL, NULL, '2020-04-30 12:10:12', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
