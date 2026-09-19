-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 17, 2026 at 11:33 AM
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
(1, 'FY-2026', '2026', NULL, '2026-01-01', '2026-12-31', 'fiscal_year', 'open', 'Fiscal Year 2026', 0, NULL, NULL, '2026-09-15 22:50:58', '2026-09-15 22:50:58');

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
('factory-order-management-cache-tyro_dashboard_heartbeat_1', 'i:1789637144;', 1789637744),
('factory-order-management-cache-tyro:user-1:roles', 'a:4:{i:0;s:11:\"super-admin\";i:1;s:5:\"admin\";i:2;s:10:\"accountant\";i:3;s:5:\"staff\";}', 1789644890);

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
(1, NULL, '10001', 'Office Cash', 'asset', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(2, NULL, '10002', 'Pubali Bank', 'asset', 1, 1, '2026-09-15 22:50:58', '2026-09-17 04:17:58'),
(3, NULL, '20001', 'Accounts Payable', 'liability', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(4, NULL, '30001', 'Owner\'s Equity', 'equity', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(5, NULL, '41001', 'Garment Production Revenue', 'revenue', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(6, NULL, '41002', 'Export Sample & Freight Revenue', 'revenue', 1, 0, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(7, NULL, '51001', 'Factory Rent', 'expense', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(8, NULL, '51002', 'Worker & Staff Salaries', 'expense', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(9, NULL, '51003', 'Utilities & Power', 'expense', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(10, NULL, '51004', 'Fabric & Trims Raw Materials', 'expense', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(11, NULL, '51005', 'Logistics & Shipping', 'expense', 1, 1, '2026-09-15 22:50:58', '2026-09-15 22:50:58');

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
(1, 'Schuyler Green', 'Tracto', 'Celo', 'your.email+fakedata40936@gmail.com', '509-918-7000', '45759 E Washington Street', '2026-09-17 04:42:22', '2026-09-17 05:13:12');

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
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `customer_orders` (`id`, `customer_id`, `supplier_id`, `order_no`, `style_no`, `style_name`, `brand`, `style_image`, `composition`, `color_name`, `color_qty`, `order_date`, `etd_date`, `price`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '180', '525', 'Deleniti', 'Alius ver vito turba dolorum curriculum amplus curis.', 'styles/NGQWIZE4EYxhuZGk9HWy6NrDCVhO1USpAqq4NhqZ.jpg', '15', 'Bradley Schaefer', 493, '2026-09-17', '2026-09-25', 153.00, 'Test', '2026-09-17 05:04:36', '2026-09-17 05:04:36');

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
(1, 1, NULL, 'Pending', NULL, 'Pending', 'Not Started', 'Not Started', 'Not Started', 0.00, 0.00, NULL, '2026-09-17 05:04:36', '2026-09-17 05:04:36');

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
(1, 1, 153.00, NULL, '2026-09-25', NULL, NULL, '2026-09-17 05:04:36', '2026-09-17 05:04:36');

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
(12, '2026_03_01_000001_create_factory_order_management_tables', 1),
(13, '2025_01_01_000001_create_media_table', 2),
(14, '2025_01_01_000002_create_starred_import_images_table', 2),
(15, '2026_02_20_000000_create_smtp_presets_table', 2),
(16, '2026_09_17_105840_add_brand_to_customer_orders_table', 2);

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
(1, 'Office Cash Drawer', 'cash', NULL, '00000000', 1, 500000.00, NULL, 'active', 1, NULL, '2026-09-15 22:50:58', '2026-09-15 22:50:58'),
(2, 'Pubali Bank', 'bank', 'Pubali Bank Ltd', '3781901011402', 2, 500000.00, 'Panthapath Branch, Dhaka', 'active', 1, NULL, '2026-09-15 22:50:58', '2026-09-17 04:17:58');

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
(1, 'Wildcard Full Access', '*', NULL, '2026-09-15 22:50:57', '2026-09-15 22:50:57'),
(2, 'Accountant Access', '*accountant', NULL, '2026-09-15 22:50:57', '2026-09-15 22:50:57');

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
(1, 2, 1, '2026-09-15 22:50:57', '2026-09-15 22:50:57'),
(2, 1, 1, '2026-09-15 22:50:57', '2026-09-15 22:50:57'),
(3, 3, 2, '2026-09-15 22:50:57', '2026-09-15 22:50:57');

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
(1, 'Super Admin', 'super-admin', 1, '2026-09-15 22:50:57', '2026-09-15 22:50:57'),
(2, 'Administrator', 'admin', 1, '2026-09-15 22:50:57', '2026-09-15 22:50:57'),
(3, 'Accountant', 'accountant', 1, '2026-09-15 22:50:57', '2026-09-15 22:50:57'),
(4, 'Merchandiser Staff', 'staff', 1, '2026-09-15 22:50:57', '2026-09-15 22:50:57');

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
('eRs7BzBjHPz2cp2b8ggrwbnNgmqkNtpRfcMRqLel', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJaTlZ3WVQzbzZjbkRBRHdkRDlUd25heVNQVnhMMmVCcnROdUFlSVlvIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo2MjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQvaW52b2ljZXMvY3JlYXRlP2N1c3RvbWVyX29yZGVyX2lkPTEiO3M6NToicm91dGUiO3M6MjE6ImFkbWluLmludm9pY2VzLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789644271),
('GpzJvotoKHj7zNNr2jOVONP3roqHvkMNKfyxs3S8', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJOaUIxczFzV2VJckY0amxUcEQ3NEQxWGtsMUtwVHQzWHdFM3Q3ckZQIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0MjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQvaW52b2ljZXMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5pbnZvaWNlcy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789644522),
('jKzxJyv2lMY3I8vRBCU7y2rxj30kLwy86FZcMEXd', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaTI4OG1jYU9NeWNRZW9hYU5OOUtPQ1BleEJ4MjJqYTdaVTRFRlNnMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvbm90aWZpY2F0aW9ucy9jb3VudCI7czo1OiJyb3V0ZSI7czoyNToiYWRtaW4ubm90aWZpY2F0aW9ucy5jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTA6InR5cm8tbG9naW4iO2E6MTp7czo3OiJjYXB0Y2hhIjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1789644771),
('mpJtfaqzef249GZaMpXrojFCQ18Q2wcOuUANasoo', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibzlUUklxY25tOUY1Sm8zUUxyZ29yUEJjaVV5V0ptdENMaWNCTm4yRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvaW52b2ljZXMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5pbnZvaWNlcy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789644081),
('tGuSiJPHQvRHC96Tq4z1UewxFeE9PZaJtfiUczKm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicmh5MmRGM0F1R0lwM1BkcnVJUXhyVE5kQ0NKQkc4elk5QzZBTFZvRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQvaW52b2ljZXMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5pbnZvaWNlcy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789643871),
('uyZ54D760nVY8Sm6fpo3gQq0MZUGXtIWNs0vyGlj', 1, '127.0.0.1', 'Symfony', 'YTo0OntzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NjoiX3Rva2VuIjtzOjQwOiJUSHZwbHRXSGhDRmNiNDI5dUVSWjZkZXFScGFzdkdoRGRGMDJVcGkyIjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0MjoiaHR0cDovL2xvY2FsaG9zdC9kYXNoYm9hcmQvaW52b2ljZXMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5pbnZvaWNlcy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789644260);

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
(1, 'app_name', 'Factory_Order_Management', '2026-09-15 22:57:12', '2026-09-15 22:57:12'),
(2, 'contact_email', NULL, '2026-09-15 22:57:12', '2026-09-15 22:57:12'),
(3, 'contact_phone', NULL, '2026-09-15 22:57:12', '2026-09-15 22:57:12'),
(4, 'address', NULL, '2026-09-15 22:57:12', '2026-09-15 22:57:12'),
(5, 'enable_registration', '0', '2026-09-15 22:57:12', '2026-09-15 22:57:12'),
(6, 'maintenance_mode', '0', '2026-09-15 22:57:12', '2026-09-15 22:57:12'),
(7, 'app_logo', 'uploads/settings/cOciXRyulg5nP4Ih3OSiLtNQOfVzuAg4VfqagJwz.png', '2026-09-15 22:57:12', '2026-09-15 22:57:12'),
(8, 'app_favicon', 'uploads/settings/Q6CPmXKoMgDT2UKJUuJikEuIu06FpQTRDrnNgcbO.png', '2026-09-15 22:57:12', '2026-09-15 22:57:12');

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
(1, 'Esteban Heaney', 'Vetus uter', 'Cook Islands', '457-771-6965', 'your.email+fakedata88434@gmail.com', '2026-09-17 04:50:53', '2026-09-17 04:50:53');

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
(1, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36\"}', '2026-09-16 04:56:47'),
(2, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36\"}', '2026-09-16 04:57:18'),
(3, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36\"}', '2026-09-17 04:45:56'),
(4, 1, 'user.logout', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36\"}', '2026-09-17 05:15:32'),
(5, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": false, \"user_agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36\"}', '2026-09-17 09:25:44'),
(6, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-17 11:21:32'),
(7, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-17 11:23:49'),
(8, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-17 11:24:20'),
(9, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-17 11:24:30'),
(10, 1, 'user.login', 'App\\Models\\User', 1, NULL, '{\"email\": \"hello@inoodex.com\"}', '{\"ip\": \"127.0.0.1\", \"is_console\": true, \"user_agent\": \"Symfony\"}', '2026-09-17 11:28:42');

-- --------------------------------------------------------

--
-- Table structure for table `tyro_media`
--

CREATE TABLE `tyro_media` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `webp_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tyro_smtp_presets`
--

CREATE TABLE `tyro_smtp_presets` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mailer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'smtp',
  `host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `port` smallint UNSIGNED NOT NULL DEFAULT '587',
  `encryption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` text COLLATE utf8mb4_unicode_ci,
  `from_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tyro_starred_import_images`
--

CREATE TABLE `tyro_starred_import_images` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `star_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt` text COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumb_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preview_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_location` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_url` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `starred_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Admin', 'hello@inoodex.com', 'admin', 'System Administrator', '2026-09-15 22:50:57', '$2y$12$pDTLkwnMQXYipQZOVycMp.HwNPPV8TtmSOYRA0TIHOdswHbuiEQ4i', NULL, NULL, NULL, 'hello@inoodex.com', NULL, '2026-09-15 22:50:57', '2026-09-15 22:50:57', NULL, NULL, NULL, 0);

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
(1, 1, 3, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 4, NULL, NULL),
(4, 1, 1, NULL, NULL);

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
-- Indexes for table `tyro_media`
--
ALTER TABLE `tyro_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tyro_media_user_id_foreign` (`user_id`);

--
-- Indexes for table `tyro_smtp_presets`
--
ALTER TABLE `tyro_smtp_presets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tyro_smtp_presets_name_unique` (`name`);

--
-- Indexes for table `tyro_starred_import_images`
--
ALTER TABLE `tyro_starred_import_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tyro_starred_import_images_user_id_star_key_unique` (`user_id`,`star_key`),
  ADD KEY `tyro_starred_import_images_user_id_starred_at_index` (`user_id`,`starred_at`);

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
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factory_followups`
--
ALTER TABLE `factory_followups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `factory_orders`
--
ALTER TABLE `factory_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tyro_audit_logs`
--
ALTER TABLE `tyro_audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tyro_media`
--
ALTER TABLE `tyro_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tyro_smtp_presets`
--
ALTER TABLE `tyro_smtp_presets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tyro_starred_import_images`
--
ALTER TABLE `tyro_starred_import_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `tyro_media`
--
ALTER TABLE `tyro_media`
  ADD CONSTRAINT `tyro_media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tyro_starred_import_images`
--
ALTER TABLE `tyro_starred_import_images`
  ADD CONSTRAINT `tyro_starred_import_images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
