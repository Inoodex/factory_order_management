-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 14, 2026 at 12:49 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `factory_order_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounting_periods`
--

CREATE TABLE `accounting_periods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` year DEFAULT NULL,
  `month` tinyint UNSIGNED DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `type` enum('fiscal_year','monthly','quarterly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `status` enum('open','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `is_closed` tinyint(1) DEFAULT '0',
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_periods`
--

INSERT INTO `accounting_periods` (`id`, `name`, `year`, `month`, `start_date`, `end_date`, `type`, `status`, `remarks`, `is_closed`, `closed_at`, `closed_by`, `created_at`, `updated_at`) VALUES
(1, 'FY-2026', '2026', NULL, '2026-01-01', '2026-12-31', 'fiscal_year', 'open', 'Fiscal Year 2026', 0, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `bank_reconciliations`
--

CREATE TABLE `bank_reconciliations` (
  `id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `statement_date` date NOT NULL,
  `statement_balance` decimal(15,2) NOT NULL,
  `system_balance` decimal(15,2) NOT NULL,
  `difference` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_reconciliation_items`
--

CREATE TABLE `bank_reconciliation_items` (
  `id` bigint UNSIGNED NOT NULL,
  `reconciliation_id` bigint UNSIGNED NOT NULL,
  `journal_entry_item_id` bigint UNSIGNED DEFAULT NULL,
  `bank_statement_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` enum('matched','unmatched','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unmatched',
  `matched_at` timestamp NULL DEFAULT NULL,
  `matched_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint UNSIGNED NOT NULL,
  `chart_of_account_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `period` enum('monthly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('factory-order-management-cache-active_currencies', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;O:19:\"App\\Models\\Currency\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"currencies\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:1;s:4:\"name\";s:9:\"US Dollar\";s:4:\"code\";s:3:\"USD\";s:6:\"symbol\";s:1:\"$\";s:13:\"exchange_rate\";s:10:\"1.00000000\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:1;s:10:\"created_at\";s:19:\"2026-09-14 11:07:48\";s:10:\"updated_at\";s:19:\"2026-09-14 11:07:48\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:1;s:4:\"name\";s:9:\"US Dollar\";s:4:\"code\";s:3:\"USD\";s:6:\"symbol\";s:1:\"$\";s:13:\"exchange_rate\";s:10:\"1.00000000\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:1;s:10:\"created_at\";s:19:\"2026-09-14 11:07:48\";s:10:\"updated_at\";s:19:\"2026-09-14 11:07:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:13:\"exchange_rate\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"is_default\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:4:\"name\";i:1;s:4:\"code\";i:2;s:6:\"symbol\";i:3;s:13:\"exchange_rate\";i:4;s:9:\"is_active\";i:5;s:10:\"is_default\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:19:\"App\\Models\\Currency\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"currencies\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:2;s:4:\"name\";s:4:\"Euro\";s:4:\"code\";s:3:\"EUR\";s:6:\"symbol\";s:3:\"€\";s:13:\"exchange_rate\";s:10:\"1.08000000\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:0;s:10:\"created_at\";s:19:\"2026-09-14 11:07:48\";s:10:\"updated_at\";s:19:\"2026-09-14 11:07:48\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:2;s:4:\"name\";s:4:\"Euro\";s:4:\"code\";s:3:\"EUR\";s:6:\"symbol\";s:3:\"€\";s:13:\"exchange_rate\";s:10:\"1.08000000\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:0;s:10:\"created_at\";s:19:\"2026-09-14 11:07:48\";s:10:\"updated_at\";s:19:\"2026-09-14 11:07:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:13:\"exchange_rate\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"is_default\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:4:\"name\";i:1;s:4:\"code\";i:2;s:6:\"symbol\";i:3;s:13:\"exchange_rate\";i:4;s:9:\"is_active\";i:5;s:10:\"is_default\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:19:\"App\\Models\\Currency\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"currencies\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:3;s:4:\"name\";s:16:\"Bangladeshi Taka\";s:4:\"code\";s:3:\"BDT\";s:6:\"symbol\";s:3:\"৳\";s:13:\"exchange_rate\";s:10:\"0.00830000\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:0;s:10:\"created_at\";s:19:\"2026-09-14 11:07:48\";s:10:\"updated_at\";s:19:\"2026-09-14 11:07:48\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:3;s:4:\"name\";s:16:\"Bangladeshi Taka\";s:4:\"code\";s:3:\"BDT\";s:6:\"symbol\";s:3:\"৳\";s:13:\"exchange_rate\";s:10:\"0.00830000\";s:9:\"is_active\";i:1;s:10:\"is_default\";i:0;s:10:\"created_at\";s:19:\"2026-09-14 11:07:48\";s:10:\"updated_at\";s:19:\"2026-09-14 11:07:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:3:{s:13:\"exchange_rate\";s:9:\"decimal:2\";s:9:\"is_active\";s:7:\"boolean\";s:10:\"is_default\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:4:\"name\";i:1;s:4:\"code\";i:2;s:6:\"symbol\";i:3;s:13:\"exchange_rate\";i:4;s:9:\"is_active\";i:5;s:10:\"is_default\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1789391474),
('factory-order-management-cache-tyro:user-1:roles', 'a:4:{i:0;s:10:\"accountant\";i:1;s:5:\"admin\";i:2;s:5:\"staff\";i:3;s:11:\"super-admin\";}', 1789390247);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('asset','liability','equity','revenue','expense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chart_of_accounts`
--

INSERT INTO `chart_of_accounts` (`id`, `parent_id`, `code`, `name`, `type`, `is_active`, `is_default`, `created_at`, `updated_at`) VALUES
(1, NULL, '10001', 'Office Cash', 'asset', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(2, NULL, '10002', 'Factory Operating Bank Account', 'asset', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(3, NULL, '20001', 'Accounts Payable', 'liability', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(4, NULL, '30001', 'Owner\'s Equity', 'equity', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(5, NULL, '41001', 'Garment Production Revenue', 'revenue', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(6, NULL, '41002', 'Export Sample & Freight Revenue', 'revenue', 1, 0, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(7, NULL, '51001', 'Factory Rent', 'expense', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(8, NULL, '51002', 'Worker & Staff Salaries', 'expense', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(9, NULL, '51003', 'Utilities & Power', 'expense', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(10, NULL, '51004', 'Fabric & Trims Raw Materials', 'expense', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(11, NULL, '51005', 'Logistics & Shipping', 'expense', 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_order_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `proposed_amount` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `workflow_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `claimed_at` timestamp NULL DEFAULT NULL,
  `claim_notes` text COLLATE utf8mb4_unicode_ci,
  `reviewed_by` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `review_notes` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exchange_rate` decimal(16,8) NOT NULL DEFAULT '1.00000000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `exchange_rate`, `is_active`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'US Dollar', 'USD', '$', 1.00000000, 1, 1, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(2, 'Euro', 'EUR', '€', 1.08000000, 1, 0, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(3, 'Bangladeshi Taka', 'BDT', '৳', 0.00830000, 1, 0, '2026-09-14 05:07:48', '2026-09-14 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `brand`, `session`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'H&M Hennes & Mauritz', 'H&M Divided', 'Summer 2026', 'sourcing.dhaka@hm.com', '+880 1711 111111', 'Gulshan 2, Dhaka 1212', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(2, 'Inditex Sourcing Ltd', 'Zara Man', 'Autumn/Winter 2026', 'buyer.bd@inditex.com', '+880 1722 222222', 'Banani, Dhaka 1213', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(3, 'Next Retail UK', 'Next Casuals', 'Spring 2026', 'next.sourcing@next.co.uk', '+880 1733 333333', 'Baridhara DOHS, Dhaka', '2026-09-14 05:07:48', '2026-09-14 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `supplier_id` bigint UNSIGNED NOT NULL,
  `order_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `style_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `style_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `style_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `composition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_qty` int NOT NULL DEFAULT '0',
  `order_date` date DEFAULT NULL,
  `etd_date` date DEFAULT NULL,
  `price` decimal(12,2) DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`id`, `customer_id`, `supplier_id`, `order_no`, `style_no`, `style_name`, `style_image`, `composition`, `color_name`, `color_qty`, `order_date`, `etd_date`, `price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'ORD-2026-00001', 'STY-HM-101', 'Men Relaxed Fit Jersey T-Shirt', NULL, '100% BCI Organic Cotton (180 GSM)', 'Navy Blue', 12000, '2026-08-20', '2026-10-04', 3.85, 'Enzyme bio-wash required, export hanger pack.', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(2, 2, 2, 'ORD-2026-00002', 'STY-ZR-440', 'Men Oversized French Terry Hoodie', NULL, '80% Cotton 20% Polyester (320 GSM)', 'Olive Heather', 8500, '2026-08-05', '2026-09-26', 7.60, 'Metal eyelets and matching flat drawstrings.', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(3, 3, 3, 'ORD-2026-00003', 'STY-NX-882', 'Ladies Ribbed Crewneck Long Sleeve', NULL, '95% Modal 5% Elastane 2x2 Rib', 'Dusty Rose', 6000, '2026-08-30', '2026-09-12', 5.20, 'Silicone softener wash, flatlock stitch detailing.', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(4, 1, 2, 'ORD-2026-00004', 'STY-HM-555', 'Kids Graphic Printed Romper', NULL, '100% Combed Cotton Single Jersey', 'Butter Yellow', 15000, '2026-09-04', '2026-10-29', 2.95, 'Nickel-free snaps at bottom, lead-free water base pigment print.', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(5, 2, 1, 'ORD-2026-00005', 'STY-ZR-109', 'Classic Cotton Pique Polo', NULL, '100% Combed Cotton Pique (220 GSM)', 'Classic White', 14000, '2026-04-12', '2026-06-20', 4.20, 'Flat knit collar and cuffs, 2-button placket.', '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(6, 3, 2, 'ORD-2026-00006', 'STY-NX-304', 'Ladies Lightweight Cardigan', NULL, '60% Cotton 40% Viscose Fine Knit', 'Oatmeal Melange', 18500, '2026-05-18', '2026-07-10', 5.10, 'Tortoiseshell effect buttons, rib trim hem.', '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(7, 1, 3, 'ORD-2026-00007', 'STY-HM-712', 'Unisex Brushed Fleece Joggers', NULL, '70% Cotton 30% Polyester (280 GSM)', 'Charcoal Grey', 22000, '2026-06-08', '2026-08-15', 4.80, 'Side welt pockets, elasticated waistband with drawcord.', '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(8, 2, 1, 'ORD-2026-00008', 'STY-ZR-620', 'Cargo Shorts with Utility Pockets', NULL, '98% Cotton 2% Spandex Twill', 'Khaki Tan', 25000, '2026-07-14', '2026-09-04', 5.40, 'Garment enzyme stone wash, double needle topstitch.', '2026-09-14 06:09:45', '2026-09-14 06:09:45');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `chart_of_account_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` enum('cash','bank_transfer','mobile_banking','cheque') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_account_id` bigint UNSIGNED DEFAULT NULL,
  `salary_id` bigint UNSIGNED DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factory_followups`
--

CREATE TABLE `factory_followups` (
  `id` bigint UNSIGNED NOT NULL,
  `factory_order_id` bigint UNSIGNED NOT NULL,
  `pps_date` date DEFAULT NULL,
  `pps_comments_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shs_sending_date` date DEFAULT NULL,
  `shs_comments_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `knitting_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dyeing_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cutting_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fob_price` decimal(12,2) DEFAULT '0.00',
  `sub_price` decimal(12,2) DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `factory_followups`
--

INSERT INTO `factory_followups` (`id`, `factory_order_id`, `pps_date`, `pps_comments_status`, `shs_sending_date`, `shs_comments_status`, `knitting_status`, `dyeing_status`, `cutting_status`, `fob_price`, `sub_price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-09-04', 'Approved', '2026-09-09', 'Sent', 'Completed', 'Completed', 'In Progress', 3.40, 0.45, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:44'),
(2, 2, '2026-09-04', 'Approved with Comments', '2026-09-09', 'Approved', 'Completed', 'Completed', 'Completed', 6.95, 1.20, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:45'),
(3, 3, '2026-09-04', 'Approved', NULL, 'Pending', 'Completed', 'In Progress', 'Not Started', 4.70, 0.60, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:45'),
(4, 4, '2026-09-04', 'Submitted', NULL, 'Pending', 'In Progress', 'Not Started', 'Not Started', 2.60, 0.35, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:45'),
(5, 5, '2026-09-04', 'Approved', '2026-09-09', 'Approved', 'Completed', 'Completed', 'Completed', 3.80, 0.40, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(6, 6, '2026-09-04', 'Approved', '2026-09-09', 'Approved', 'Completed', 'Completed', 'Completed', 4.60, 0.55, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(7, 7, '2026-09-04', 'Approved', '2026-09-09', 'Approved', 'Completed', 'Completed', 'Completed', 4.30, 0.50, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(8, 8, '2026-09-04', 'Approved', '2026-09-09', 'Approved', 'Completed', 'Completed', 'Completed', 4.85, 0.65, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45');

-- --------------------------------------------------------

--
-- Table structure for table `factory_orders`
--

CREATE TABLE `factory_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_order_id` bigint UNSIGNED NOT NULL,
  `etd_price` decimal(12,2) DEFAULT '0.00',
  `sub_price` decimal(12,2) DEFAULT '0.00',
  `aetd_date` date DEFAULT NULL,
  `fob_price` decimal(12,2) DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `factory_orders`
--

INSERT INTO `factory_orders` (`id`, `customer_order_id`, `etd_price`, `sub_price`, `aetd_date`, `fob_price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 3.50, 0.45, '2026-10-04', 3.40, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:44'),
(2, 2, 7.10, 1.20, '2026-09-29', 6.95, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:44'),
(3, 3, 4.80, 0.60, '2026-09-12', 4.70, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:45'),
(4, 4, 2.65, 0.35, '2026-10-29', 2.60, NULL, '2026-09-14 05:07:48', '2026-09-14 06:09:45'),
(5, 5, 3.85, 0.40, '2026-06-20', 3.80, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(6, 6, 4.65, 0.55, '2026-07-10', 4.60, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(7, 7, 4.35, 0.50, '2026-08-15', 4.30, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45'),
(8, 8, 4.90, 0.65, '2026-09-04', 4.85, NULL, '2026-09-14 06:09:45', '2026-09-14 06:09:45');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitation_links`
--

CREATE TABLE `invitation_links` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `hash` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invitation_referrals`
--

CREATE TABLE `invitation_referrals` (
  `id` bigint UNSIGNED NOT NULL,
  `invitation_link_id` bigint UNSIGNED NOT NULL,
  `referred_user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_order_id` bigint UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('draft','sent','paid','partially_paid','void') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `chart_of_account_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(15,2) NOT NULL DEFAULT '1.00',
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','posted','void') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry_items`
--

CREATE TABLE `journal_entry_items` (
  `id` bigint UNSIGNED NOT NULL,
  `journal_entry_id` bigint UNSIGNED NOT NULL,
  `chart_of_account_id` bigint UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2022_05_17_181447_create_roles_table', 1),
(3, '2022_05_17_181456_create_user_roles_table', 1),
(4, '2024_01_01_000000_create_social_accounts_table', 1),
(5, '2024_01_01_000001_add_two_factor_columns_to_users_table', 1),
(6, '2024_01_01_000002_create_invitation_system_tables', 1),
(7, '2025_01_01_000001_create_privileges_table', 1),
(8, '2025_01_01_000002_create_privilege_role_table', 1),
(9, '2025_01_01_000003_add_suspension_columns_to_users_table', 1),
(10, '2025_02_08_000000_add_profile_photo_to_users_table', 1),
(11, '2026_02_15_000000_create_tyro_audit_logs_table', 1),
(12, '2026_03_01_000001_create_factory_order_management_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `office_accounts`
--

CREATE TABLE `office_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_type` enum('bank','mfs','cash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chart_of_account_id` bigint UNSIGNED DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `office_accounts`
--

INSERT INTO `office_accounts` (`id`, `account_name`, `account_type`, `provider_name`, `account_number`, `chart_of_account_id`, `opening_balance`, `branch_name`, `status`, `created_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Office Cash Drawer', 'cash', NULL, 'CASH-DRAWER-01', 1, 150000.00, NULL, 'active', 1, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(2, 'Pubali Bank - Operations', 'bank', 'Pubali Bank Ltd', '3781901011402', 2, 500000.00, 'Panthapath Branch, Dhaka', 'active', 1, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_order_id` bigint UNSIGNED DEFAULT NULL,
  `invoice_id` bigint UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_type` enum('advance','partial','final') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'advance',
  `payment_date` datetime NOT NULL,
  `collected_by` bigint UNSIGNED DEFAULT NULL,
  `receipt_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `office_account_id` bigint UNSIGNED DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Wildcard Full Access', '*', NULL, '2026-09-14 05:07:47', '2026-09-14 05:07:47'),
(2, 'Accountant Access', '*accountant', NULL, '2026-09-14 05:07:47', '2026-09-14 05:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `privilege_role`
--

CREATE TABLE `privilege_role` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `privilege_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privilege_role`
--

INSERT INTO `privilege_role` (`id`, `role_id`, `privilege_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-09-14 05:07:47', '2026-09-14 05:07:47'),
(2, 2, 1, '2026-09-14 05:07:47', '2026-09-14 05:07:47'),
(3, 3, 2, '2026-09-14 05:07:47', '2026-09-14 05:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', 1, '2026-09-14 05:07:47', '2026-09-14 05:07:47'),
(2, 'Super Admin', 'super-admin', 1, '2026-09-14 05:07:47', '2026-09-14 05:07:47'),
(3, 'Accountant', 'accountant', 1, '2026-09-14 05:07:47', '2026-09-14 05:07:47'),
(4, 'Merchandiser Staff', 'staff', 1, '2026-09-14 05:07:47', '2026-09-14 05:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `employee_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `overtime_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00',
  `allowances` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gross_salary` decimal(12,2) NOT NULL,
  `tax_deduction` decimal(10,2) NOT NULL DEFAULT '0.00',
  `insurance_deduction` decimal(10,2) NOT NULL DEFAULT '0.00',
  `other_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','partial','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `payment_method` enum('cash','bank_transfer','mobile_banking','cheque') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routing_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_entry_id` bigint UNSIGNED DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`id`, `user_id`, `employee_name`, `month`, `basic_salary`, `overtime_amount`, `bonus`, `allowances`, `gross_salary`, `tax_deduction`, `insurance_deduction`, `other_deductions`, `net_salary`, `paid_amount`, `payment_status`, `payment_date`, `payment_method`, `account_number`, `bank_name`, `bank_branch`, `routing_number`, `transaction_id`, `journal_entry_id`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Md. Abul Hasan Saidy', '2026-05', 60000.00, 0.00, 0.00, 0.00, 60000.00, 0.00, 0.00, 0.00, 60000.00, 0.00, 'pending', NULL, NULL, '3781-101-83523', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(2, NULL, 'Mohammad Faisal', '2026-05', 60000.00, 0.00, 0.00, 0.00, 60000.00, 0.00, 0.00, 0.00, 60000.00, 0.00, 'pending', NULL, NULL, '3781-101-83725', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(3, NULL, 'Insan Kamal Shafat', '2026-05', 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 'pending', NULL, NULL, '3781-101-83764', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(4, NULL, 'Sakib Hasan', '2026-05', 46000.00, 0.00, 0.00, 0.00, 46000.00, 0.00, 0.00, 0.00, 46000.00, 0.00, 'pending', NULL, NULL, '3781-101-83536', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(5, NULL, 'Lutfur Kabir Rana', '2026-05', 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 'pending', NULL, NULL, '3781-101-83501', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(6, NULL, 'Sharafat Ullah Mohim', '2026-05', 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 'pending', NULL, NULL, '3781-101-83756', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(7, NULL, 'Mainul Hasan', '2026-05', 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 0.00, 0.00, 40000.00, 0.00, 'pending', NULL, NULL, '3781-101-83609', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(8, NULL, 'Singmay Chowdhury', '2026-05', 30000.00, 0.00, 0.00, 0.00, 30000.00, 0.00, 0.00, 0.00, 30000.00, 0.00, 'pending', NULL, NULL, '3781-101-83540', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(9, NULL, 'Arif Hossain Nayan', '2026-05', 17000.00, 0.00, 0.00, 0.00, 17000.00, 0.00, 0.00, 0.00, 17000.00, 0.00, 'pending', NULL, NULL, '3781-101-83710', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(10, NULL, 'Abdul Alim Shezan', '2026-05', 32000.00, 0.00, 0.00, 0.00, 32000.00, 0.00, 0.00, 0.00, 32000.00, 0.00, 'pending', NULL, NULL, '3781-101-83586', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(11, NULL, 'Moshraful Islam', '2026-05', 30000.00, 0.00, 0.00, 0.00, 30000.00, 0.00, 0.00, 0.00, 30000.00, 0.00, 'pending', NULL, NULL, '3781-101-83560', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(12, NULL, 'Harunur Rashid', '2026-05', 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 'pending', NULL, NULL, '3781-101-83684', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(13, NULL, 'Abu haider', '2026-05', 13000.00, 0.00, 0.00, 0.00, 13000.00, 0.00, 0.00, 0.00, 13000.00, 0.00, 'pending', NULL, NULL, '3781-101-83783', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(14, NULL, 'Anta Tasnim Rafa', '2026-05', 17500.00, 0.00, 0.00, 0.00, 17500.00, 0.00, 0.00, 0.00, 17500.00, 0.00, 'pending', NULL, NULL, '3781-101-83555', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(15, NULL, 'Kawcer Hossen Rakib', '2026-05', 25000.00, 0.00, 0.00, 0.00, 25000.00, 0.00, 0.00, 0.00, 25000.00, 0.00, 'pending', NULL, NULL, '3781-101-83497', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(16, NULL, 'Md Shohan', '2026-05', 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 'pending', NULL, NULL, '3781-101-83706', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(17, NULL, 'Mahabub Hossain Alif', '2026-05', 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 'pending', NULL, NULL, '3781-101-83730', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(18, NULL, 'Shah Amanat Ullah', '2026-05', 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 0.00, 0.00, 15000.00, 0.00, 'pending', NULL, NULL, '3781-101-83747', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(19, NULL, 'MOSHAROF RONY', '2026-05', 9000.00, 0.00, 0.00, 0.00, 9000.00, 0.00, 0.00, 0.00, 9000.00, 0.00, 'pending', NULL, NULL, '3781-101-83594', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(20, NULL, 'Mohammed Abdullah', '2026-05', 11000.00, 0.00, 0.00, 0.00, 11000.00, 0.00, 0.00, 0.00, 11000.00, 0.00, 'pending', NULL, NULL, '3781-101-83693', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(21, NULL, 'Chelsi Rema', '2026-05', 13000.00, 0.00, 0.00, 0.00, 13000.00, 0.00, 0.00, 0.00, 13000.00, 0.00, 'pending', NULL, NULL, '3781-101-83779', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(22, NULL, 'Emelia Ani Areng', '2026-05', 13000.00, 0.00, 0.00, 0.00, 13000.00, 0.00, 0.00, 0.00, 13000.00, 0.00, 'pending', NULL, NULL, '3781-101-83667', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(23, NULL, 'Rakesh Saha', '2026-05', 8000.00, 0.00, 0.00, 0.00, 8000.00, 0.00, 0.00, 0.00, 8000.00, 0.00, 'pending', NULL, NULL, '3781-101-83652', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(24, NULL, 'Barsha Saha', '2026-05', 8000.00, 0.00, 0.00, 0.00, 8000.00, 0.00, 0.00, 0.00, 8000.00, 0.00, 'pending', NULL, NULL, '3781-101-83630', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(25, NULL, 'Riad Mia', '2026-05', 21000.00, 0.00, 0.00, 0.00, 21000.00, 0.00, 0.00, 0.00, 21000.00, 0.00, 'pending', NULL, NULL, '3781-101-83822', 'Pubali Bank', 'Panthapath Branch, Dhaka', NULL, NULL, NULL, NULL, NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7IxmfEEZGSkKqPwr20TTvbw7dtGjDHt6tj0yED1Z', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibUR0YTB3SEdyVWhrM212eWZBZHhXSWtrUm1KZGN6THpyR1FOSnhUdSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQvbXktY29tbWlzc2lvbnMvb3JkZXJzLzEvY2xhaW0iO3M6NToicm91dGUiO3M6Mjc6Im15LWNvbW1pc3Npb25zLmNyZWF0ZS1jbGFpbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789384468),
('8KGsiHExwGDp7wCC45lYPvMdSZhNhEGeqwuo5vXM', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJwVExmY3hzbVM2QmRIa0JmQnljMWdrd1RtZlFyYkc0S1UzNlRhY0o0IjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjA6InR5cm8tZGFzaGJvYXJkLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789385455),
('DdaSEi4bARRU70PILtuQHPryXynpsOnChrQfWp4W', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiI5SHJJN1RCY1FXUFFBTDdJTkJORjlGdWJDcU5MYXhYeVZVTFp2eUtJIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjA6InR5cm8tZGFzaGJvYXJkLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789385414),
('IMUofPePWUgHluxQ95k6Xk7jDfm8Ln7ckoXdpWLc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidW5uSGFaRzF1OUdsenU5cjFzelVtR0tOWG96SnF1WW1RQjdCUHY1WCI7czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1MToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZC9ub3RpZmljYXRpb25zL2NvdW50IjtzOjU6InJvdXRlIjtzOjI1OiJhZG1pbi5ub3RpZmljYXRpb25zLmNvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1789390189),
('jKzVRqepvS5zXTJm20Wow3fXIAeolH4PyLl2RGB5', NULL, '127.0.0.1', '', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieXJiMGhpeFRkYzFxTUZXdHpRa0QxejBVdUJrMDBVc3V5ZHpyRVhrayI7czoxMDoidHlyby1sb2dpbiI7YToxOntzOjc6ImNhcHRjaGEiO2E6MTp7czo1OiJsb2dpbiI7aTo1O319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czoxNjoidHlyby1sb2dpbi5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789385574),
('K5HKWKJXEbzsGZh5DKmLzxrrMX3nRXPhtbzEsP3a', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZENSaVVLY05QeWNoVDMwU0xCVnlKRHRCT25mdXNPOEE1UndNbW5KWCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQvbXktY29tbWlzc2lvbnMvb3JkZXJzLzEvY2xhaW0iO3M6NToicm91dGUiO3M6Mjc6Im15LWNvbW1pc3Npb25zLmNyZWF0ZS1jbGFpbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789384488),
('Ks2hJrhtIOdDKwJH7HU9A2XdllExKPWitMnz6VGL', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJYSXBFZGtXOHhzb3BYWDU0UlVQYWY0TnBkdE1kS04zZGdvWE9uV1JQIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjA6InR5cm8tZGFzaGJvYXJkLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789387623),
('L8ieovo9xGhw2YeHVyiDKusPVtHE623re6mJlflY', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib3ExakZveXZ1dmpabXlmR09pcEVUNDNqYVl5a0JSbXVid041MEZjTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789385573),
('LzSpw4DkQrwXRIl1fhT2ruInre2lXPi6vXs27p3r', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJwUlRiQzlrYlBxSTNSMDloSUdYOTB3dDZ4dTVuSnd5c3A3NHZWWHkyIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjA6InR5cm8tZGFzaGJvYXJkLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789387874),
('mfMx2eye12KO2RUWffdcHg7d6eG17f3HkixYt6i5', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJhb0hpN0pJZFBnYzYzSGpuM1NMTWYyMUFyQ29zNlJ5NGh4NWhxTmVlIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjA6InR5cm8tZGFzaGJvYXJkLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789385565),
('nRFupxKXWHqSgiU8d0X73oZM4YHOqmOmpAhv2phq', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTnVZMUpobjM5Zk5XVDlxODN4ZkRBZDJqY0o4YnpPY04xbGU1UU5qQyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQvbXktY29tbWlzc2lvbnMvb3JkZXJzLzEvY2xhaW0iO3M6NToicm91dGUiO3M6Mjc6Im15LWNvbW1pc3Npb25zLmNyZWF0ZS1jbGFpbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789384093),
('UN8Hm9h1Q3pB20nnI3HtJrLOcmwhYHttBBD1FQmh', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJac0p4TUhCem4wNVB6Nkttdkc1SmphZU16VlhUOVN3amdaNDQ0VTVyIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyNjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MjA6InR5cm8tZGFzaGJvYXJkLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789385531);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Factory_Order_Management', '2026-09-14 05:09:49', '2026-09-14 05:09:49'),
(2, 'contact_email', NULL, '2026-09-14 05:09:49', '2026-09-14 05:09:49'),
(3, 'contact_phone', NULL, '2026-09-14 05:09:49', '2026-09-14 05:09:49'),
(4, 'address', NULL, '2026-09-14 05:09:49', '2026-09-14 05:09:49'),
(5, 'enable_registration', '0', '2026-09-14 05:09:49', '2026-09-14 05:09:49'),
(6, 'maintenance_mode', '0', '2026-09-14 05:09:49', '2026-09-14 05:09:49'),
(7, 'app_logo', 'uploads/settings/3XXxV5gRot3d3VIIYfDjlTYbRJvCnteHBVqdeVai.png', '2026-09-14 05:09:49', '2026-09-14 05:09:49'),
(8, 'app_favicon', 'uploads/settings/BoNyGC604sTgav1eyZweRN2otBig65Zr33kZTD66.png', '2026-09-14 05:09:49', '2026-09-14 05:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_token` text COLLATE utf8mb4_unicode_ci,
  `refresh_token` text COLLATE utf8mb4_unicode_ci,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `location`, `contact_person`, `phone`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Apex Knitting & Dyeing Mills Ltd', 'Kashimpur, Gazipur', 'Engr. Rafiqul Islam (GM Production)', '+880 1819 001122', 'production@apexknitting.com', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(2, 'Viyellatex Fashions Ltd', 'Tongi Industrial Area, Gazipur', 'Tanvir Ahmed (Merchandising Head)', '+880 1819 334455', 'orders@viyellatexgroup.com', '2026-09-14 05:07:48', '2026-09-14 05:07:48'),
(3, 'Square Fashions Ltd', 'Valuka, Mymensingh', 'Mustafa Kamal (Factory Manager)', '+880 1819 556677', 'squarefashions@squaregroup.com', '2026-09-14 05:07:48', '2026-09-14 05:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `chart_of_account_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tyro_audit_logs`
--

CREATE TABLE `tyro_audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auditable_id` bigint UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tyro_audit_logs`
--

INSERT INTO `tyro_audit_logs` (`id`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `metadata`, `created_at`) VALUES
(1, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(2, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(3, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(4, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(5, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(6, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(7, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(8, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(9, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(10, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(11, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(12, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:11'),
(13, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(14, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(15, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(16, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(17, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(18, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(19, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(20, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(21, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(22, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(23, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(24, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(25, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(26, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(27, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(28, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(29, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(30, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(31, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:12'),
(32, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(33, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(34, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(35, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(36, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(37, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(38, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(39, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(40, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(41, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:08:13'),
(42, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36\"}', '2026-09-14 11:08:35'),
(43, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(44, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(45, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(46, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(47, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(48, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(49, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(50, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(51, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(52, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(53, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:26'),
(54, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(55, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(56, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(57, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(58, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(59, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(60, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(61, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(62, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(63, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(64, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(65, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(66, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(67, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(68, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(69, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(70, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(71, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:27'),
(72, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(73, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(74, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(75, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(76, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(77, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(78, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(79, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(80, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(81, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(82, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(83, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(84, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:28'),
(85, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(86, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(87, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(88, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(89, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(90, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(91, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(92, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(93, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(94, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(95, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(96, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(97, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:46'),
(98, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(99, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(100, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(101, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(102, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(103, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(104, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(105, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(106, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(107, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(108, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(109, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(110, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(111, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(112, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(113, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(114, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:47'),
(115, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(116, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(117, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(118, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(119, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(120, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(121, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(122, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(123, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(124, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(125, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(126, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:14:48'),
(127, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:30:11'),
(128, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:30:44'),
(129, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:32:05'),
(130, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 11:32:45'),
(131, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 12:07:03'),
(132, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-14 12:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `plain_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspension_reason` text COLLATE utf8mb4_unicode_ci,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `use_gravatar` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `designation`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `plain_password`, `remember_token`, `created_at`, `updated_at`, `suspended_at`, `suspension_reason`, `profile_photo_path`, `use_gravatar`) VALUES
(1, 'Admin', 'hello@inoodex.com', 'admin', 'System Administrator', '2026-09-14 05:07:48', '$2y$12$WhYZ5EnUMWrdeg4caluPnOd4.NxgF4GJ44aIw6yPMUJaMHviWXltC', NULL, NULL, NULL, 'hello@inoodex.com', NULL, '2026-09-14 05:07:48', '2026-09-14 05:07:48', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_periods`
--
ALTER TABLE `accounting_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_periods_closed_by_foreign` (`closed_by`),
  ADD KEY `accounting_periods_year_month_index` (`year`,`month`);

--
-- Indexes for table `bank_reconciliations`
--
ALTER TABLE `bank_reconciliations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_reconciliations_account_id_foreign` (`account_id`),
  ADD KEY `bank_reconciliations_closed_by_foreign` (`closed_by`);

--
-- Indexes for table `bank_reconciliation_items`
--
ALTER TABLE `bank_reconciliation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_reconciliation_items_reconciliation_id_foreign` (`reconciliation_id`),
  ADD KEY `bank_reconciliation_items_journal_entry_item_id_foreign` (`journal_entry_item_id`),
  ADD KEY `bank_reconciliation_items_matched_by_foreign` (`matched_by`);

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_chart_of_account_id_foreign` (`chart_of_account_id`),
  ADD KEY `budgets_created_by_foreign` (`created_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chart_of_accounts_code_unique` (`code`),
  ADD KEY `chart_of_accounts_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commissions_customer_order_id_foreign` (`customer_order_id`),
  ADD KEY `commissions_user_id_foreign` (`user_id`),
  ADD KEY `commissions_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_orders_order_no_unique` (`order_no`),
  ADD KEY `customer_orders_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_orders_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_chart_of_account_id_foreign` (`chart_of_account_id`),
  ADD KEY `expenses_office_account_id_foreign` (`office_account_id`),
  ADD KEY `expenses_salary_id_foreign` (`salary_id`),
  ADD KEY `expenses_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `expenses_created_by_foreign` (`created_by`);

--
-- Indexes for table `factory_followups`
--
ALTER TABLE `factory_followups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factory_followups_factory_order_id_foreign` (`factory_order_id`);

--
-- Indexes for table `factory_orders`
--
ALTER TABLE `factory_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factory_orders_customer_order_id_foreign` (`customer_order_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invitation_links`
--
ALTER TABLE `invitation_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invitation_links_hash_unique` (`hash`),
  ADD KEY `invitation_links_user_id_index` (`user_id`);

--
-- Indexes for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invitation_referrals_invitation_link_id_index` (`invitation_link_id`),
  ADD KEY `invitation_referrals_referred_user_id_index` (`referred_user_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_customer_order_id_foreign` (`customer_order_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `journal_entries_reference_number_unique` (`reference_number`),
  ADD KEY `journal_entries_period_id_foreign` (`period_id`),
  ADD KEY `journal_entries_created_by_foreign` (`created_by`);

--
-- Indexes for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_items_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `journal_entry_items_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `office_accounts`
--
ALTER TABLE `office_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `office_accounts_chart_of_account_id_foreign` (`chart_of_account_id`),
  ADD KEY `office_accounts_created_by_foreign` (`created_by`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_customer_order_id_foreign` (`customer_order_id`),
  ADD KEY `payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `payments_collected_by_foreign` (`collected_by`),
  ADD KEY `payments_office_account_id_foreign` (`office_account_id`),
  ADD KEY `payments_journal_entry_id_foreign` (`journal_entry_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `privileges_slug_unique` (`slug`);

--
-- Indexes for table `privilege_role`
--
ALTER TABLE `privilege_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `privilege_role_role_id_privilege_id_unique` (`role_id`,`privilege_id`),
  ADD KEY `privilege_role_privilege_id_foreign` (`privilege_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_slug_index` (`slug`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salaries_journal_entry_id_foreign` (`journal_entry_id`),
  ADD KEY `salaries_created_by_foreign` (`created_by`),
  ADD KEY `salaries_user_id_month_index` (`user_id`,`month`),
  ADD KEY `salaries_payment_status_month_index` (`payment_status`,`month`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `social_accounts_provider_provider_user_id_unique` (`provider`,`provider_user_id`),
  ADD KEY `social_accounts_provider_provider_user_id_index` (`provider`,`provider_user_id`),
  ADD KEY `social_accounts_user_id_index` (`user_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taxes_chart_of_account_id_foreign` (`chart_of_account_id`);

--
-- Indexes for table `tyro_audit_logs`
--
ALTER TABLE `tyro_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tyro_audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `tyro_audit_logs_user_id_index` (`user_id`),
  ADD KEY `tyro_audit_logs_event_index` (`event`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounting_periods`
--
ALTER TABLE `accounting_periods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_reconciliations`
--
ALTER TABLE `bank_reconciliations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_reconciliation_items`
--
ALTER TABLE `bank_reconciliation_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factory_followups`
--
ALTER TABLE `factory_followups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `factory_orders`
--
ALTER TABLE `factory_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitation_links`
--
ALTER TABLE `invitation_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `office_accounts`
--
ALTER TABLE `office_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `privilege_role`
--
ALTER TABLE `privilege_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tyro_audit_logs`
--
ALTER TABLE `tyro_audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting_periods`
--
ALTER TABLE `accounting_periods`
  ADD CONSTRAINT `accounting_periods_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bank_reconciliations`
--
ALTER TABLE `bank_reconciliations`
  ADD CONSTRAINT `bank_reconciliations_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `office_accounts` (`id`),
  ADD CONSTRAINT `bank_reconciliations_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bank_reconciliation_items`
--
ALTER TABLE `bank_reconciliation_items`
  ADD CONSTRAINT `bank_reconciliation_items_journal_entry_item_id_foreign` FOREIGN KEY (`journal_entry_item_id`) REFERENCES `journal_entry_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bank_reconciliation_items_matched_by_foreign` FOREIGN KEY (`matched_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bank_reconciliation_items_reconciliation_id_foreign` FOREIGN KEY (`reconciliation_id`) REFERENCES `bank_reconciliations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD CONSTRAINT `chart_of_accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commissions_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD CONSTRAINT `customer_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_office_account_id_foreign` FOREIGN KEY (`office_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_salary_id_foreign` FOREIGN KEY (`salary_id`) REFERENCES `salaries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `factory_followups`
--
ALTER TABLE `factory_followups`
  ADD CONSTRAINT `factory_followups_factory_order_id_foreign` FOREIGN KEY (`factory_order_id`) REFERENCES `factory_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `factory_orders`
--
ALTER TABLE `factory_orders`
  ADD CONSTRAINT `factory_orders_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invitation_referrals`
--
ALTER TABLE `invitation_referrals`
  ADD CONSTRAINT `invitation_referrals_invitation_link_id_foreign` FOREIGN KEY (`invitation_link_id`) REFERENCES `invitation_links` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `journal_entries_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `accounting_periods` (`id`);

--
-- Constraints for table `journal_entry_items`
--
ALTER TABLE `journal_entry_items`
  ADD CONSTRAINT `journal_entry_items_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`),
  ADD CONSTRAINT `journal_entry_items_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `office_accounts`
--
ALTER TABLE `office_accounts`
  ADD CONSTRAINT `office_accounts_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `office_accounts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_collected_by_foreign` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_customer_order_id_foreign` FOREIGN KEY (`customer_order_id`) REFERENCES `customer_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_office_account_id_foreign` FOREIGN KEY (`office_account_id`) REFERENCES `office_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `privilege_role`
--
ALTER TABLE `privilege_role`
  ADD CONSTRAINT `privilege_role_privilege_id_foreign` FOREIGN KEY (`privilege_id`) REFERENCES `privileges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `privilege_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salaries_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salaries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `taxes`
--
ALTER TABLE `taxes`
  ADD CONSTRAINT `taxes_chart_of_account_id_foreign` FOREIGN KEY (`chart_of_account_id`) REFERENCES `chart_of_accounts` (`id`);

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
