-- phpMyAdmin SQL Dump
-- version 4.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 02, 2014 at 07:37 AM
-- Server version: 5.5.35
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `digitar`
--

-- --------------------------------------------------------

--
-- Table structure for table `clientfolders`
--

CREATE TABLE IF NOT EXISTS `clientfolders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `sid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `geboekt` int(11) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE IF NOT EXISTS `folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `subid` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_01_06_181357_create_users_table', 1),
('2014_01_06_204427_create_organizations_table', 1),
('2014_01_12_113543_create_standardfolders_table', 1),
('2014_01_12_202727_create_files_table', 1),
('2014_01_12_205853_create_userfolders_table', 1),
('2014_01_15_103521_create_folders_table', 1),
('2014_01_15_103643_create_clientfolders_table', 1),
('2014_01_30_074436_create_usermods_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tell` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `address`, `zipcode`, `city`, `tell`, `email`, `website`, `created_at`, `updated_at`) VALUES
(1, 'Heinen Accountancy', 'Dwarsdijk 6', '7134 PM', 'Vragender', '0543-519574', 'info@heinenaccountancy.nl', 'www.heinenaccountancy.nl', '2014-01-31 23:36:22', '2014-01-31 23:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `standardfolders`
--

CREATE TABLE IF NOT EXISTS `standardfolders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(11) DEFAULT NULL,
  `subid` int(11) DEFAULT NULL,
  `geboektcheck` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `userfolders`
--

CREATE TABLE IF NOT EXISTS `userfolders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cfid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `usermods`
--

CREATE TABLE IF NOT EXISTS `usermods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `modid` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rights` int(11) NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tell` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `listed` int(11) NOT NULL,
  `lookonly` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `oid`, `cid`, `username`, `password`, `rights`, `company`, `name`, `email`, `website`, `tell`, `address`, `zipcode`, `city`, `listed`, `lookonly`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 'digitar', 'eyJpdiI6IldmZ2JzdE13dnVrQkVYemdkOHBlREtqYXpxQ0ZOS3BqZGxxZm5cL3E5dDhRPSIsInZhbHVlIjoiU1c5NGhzVGtMWlF4bkN0WUZqXC9EZUlSNWdWbE9UdnFCQWE5MngzXC9hZjdVPSIsIm1hYyI6ImExMTE0Y2EwMzg3ZTI2NDEyMjYyYWYwOTAyZWJkY2M0NzBhYmIyZmQ0NjI0Mzk5NTFkZGI0ZjJhN2JkZDdkMWUifQ==', 5, '', 'Digitar', 'info@digitar.nl', 'www.digitar.u', '0314-820990', 'Dorpsstraat 55', '7025 AB', 'Halle', 0, 0, '2014-01-31 23:36:22', '2014-01-31 23:36:22'),
(2, 1, 0, 'heinenaccountancy', 'eyJpdiI6IldmZ2JzdE13dnVrQkVYemdkOHBlREtqYXpxQ0ZOS3BqZGxxZm5cL3E5dDhRPSIsInZhbHVlIjoiU1c5NGhzVGtMWlF4bkN0WUZqXC9EZUlSNWdWbE9UdnFCQWE5MngzXC9hZjdVPSIsIm1hYyI6ImExMTE0Y2EwMzg3ZTI2NDEyMjYyYWYwOTAyZWJkY2M0NzBhYmIyZmQ0NjI0Mzk5NTFkZGI0ZjJhN2JkZDdkMWUifQ==', 4, '', 'Heinen Accountancy', 'info@heinenaccountancy.nl', 'www.heinenaccountancy.nl', '0543-519574', 'Dwarsdijk 6', '7134 PM', 'Vragender', 0, 0, '2014-01-31 23:36:22', '2014-01-31 23:36:22'),
(3, 2, 0, 'rollcomm', 'eyJpdiI6IldmZ2JzdE13dnVrQkVYemdkOHBlREtqYXpxQ0ZOS3BqZGxxZm5cL3E5dDhRPSIsInZhbHVlIjoiU1c5NGhzVGtMWlF4bkN0WUZqXC9EZUlSNWdWbE9UdnFCQWE5MngzXC9hZjdVPSIsIm1hYyI6ImExMTE0Y2EwMzg3ZTI2NDEyMjYyYWYwOTAyZWJkY2M0NzBhYmIyZmQ0NjI0Mzk5NTFkZGI0ZjJhN2JkZDdkMWUifQ==', 2, '', 'RollComm Media', 'info@rollcomm.nl', 'www.rollcomm.nl', '0314-820990', 'Dorpsstraat 55', '7025 AB', 'Halle', 0, 0, '2014-01-31 23:36:22', '2014-01-31 23:36:22');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
