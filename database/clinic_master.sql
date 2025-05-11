-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 11, 2025 at 12:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinic_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `status` enum('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `notes` text DEFAULT NULL,
  `fees` decimal(10,2) DEFAULT NULL,
  `waiting_time` int(11) DEFAULT NULL COMMENT 'Waiting time in minutes',
  `is_important` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'طب الأسنان', 'dentistry', 'خدمات طب الأسنان وعلاج الأسنان', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(2, 'طب العيون', 'ophthalmology', 'خدمات طب وجراحة العيون', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(3, 'طب الأطفال', 'pediatrics', 'رعاية صحية للأطفال والرضع', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(4, 'الطب النفسي', 'psychiatry', 'علاج الاضطرابات النفسية والعقلية', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(9, 'طب القلب', '', NULL, 1, '2025-05-10 22:10:39', '2025-05-10 22:10:39');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `governorate_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `governorate_id`, `created_at`, `updated_at`) VALUES
(1, 'مدينة نصر', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(2, 'مصر الجديدة', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(3, 'المعادي', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(4, 'مدينة السلام', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(5, 'الزيتون', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(6, 'عين شمس', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(7, 'المطرية', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(8, 'حدائق القبة', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(9, 'الزمالك', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(10, 'المقطم', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(11, 'شبرا', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(12, 'روض الفرج', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(13, 'السيدة زينب', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(14, 'الدرب الأحمر', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(15, 'الموسكي', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(16, 'باب الشعرية', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(17, 'الأزبكية', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(18, 'عابدين', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(19, 'بولاق', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(20, 'الظاهر', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(21, 'الشرابية', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(22, 'الساحل', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(23, 'الوايلي', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(24, 'حدائق حلوان', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(25, 'التبين', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(26, '15 مايو', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(27, 'حلوان', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(28, 'المرج', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(29, 'القاهرة الجديدة', 1, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(30, 'الدقي', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(31, 'العجوزة', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(32, 'الهرم', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(33, 'فيصل', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(34, 'أكتوبر', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(35, 'الشيخ زايد', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(36, 'بولاق الدكرور', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(37, 'العمرانية', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(38, 'الوراق', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(39, 'إمبابة', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(40, 'الواحات البحرية', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(41, 'منشأة القناطر', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(42, 'أوسيم', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(43, 'كرداسة', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(44, 'أبو النمرس', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(45, 'البدرشين', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(46, 'الصف', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(47, 'أطفيح', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(48, 'العياط', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(49, 'الباويطي', 2, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(50, 'المنتزه', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(51, 'شرق الإسكندرية', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(52, 'وسط الإسكندرية', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(53, 'الجمرك', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(54, 'العامرية', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(55, 'العجمي', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(56, 'برج العرب', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(57, 'محرم بك', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(58, 'الدخيلة', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(59, 'باب شرق', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(60, 'كرموز', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(61, 'اللبان', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(62, 'المنشية', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(63, 'سيدي جابر', 3, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(64, 'المنصورة', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(65, 'طلخا', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(66, 'ميت غمر', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(67, 'دكرنس', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(68, 'أجا', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(69, 'منية النصر', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(70, 'السنبلاوين', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(71, 'بلقاس', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(72, 'المطرية', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(73, 'المنزلة', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(74, 'تمي الأمديد', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(75, 'الجمالية', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(76, 'شربين', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(77, 'بني عبيد', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(78, 'نبروه', 4, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(79, 'الغردقة', 5, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(80, 'رأس غارب', 5, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(81, 'القصير', 5, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(82, 'سفاجا', 5, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(83, 'مرسى علم', 5, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(84, 'شلاتين', 5, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(85, 'حلايب', 5, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(86, 'دمنهور', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(87, 'كفر الدوار', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(88, 'رشيد', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(89, 'إدكو', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(90, 'أبو المطامير', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(91, 'أبو حمص', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(92, 'الدلنجات', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(93, 'المحمودية', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(94, 'الرحمانية', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(95, 'إيتاي البارود', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(96, 'حوش عيسى', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(97, 'شبراخيت', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(98, 'كوم حمادة', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(99, 'بدر', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(100, 'وادي النطرون', 6, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(101, 'بني سويف', 7, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(102, 'الواسطى', 7, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(103, 'ناصر', 7, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(104, 'إهناسيا', 7, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(105, 'ببا', 7, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(106, 'سمسطا', 7, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(107, 'الفشن', 7, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(108, 'حي الشرق', 8, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(109, 'حي الجنوب', 8, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(110, 'حي بورفؤاد', 8, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(111, 'حي المناخ', 8, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(112, 'حي الزهور', 8, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(113, 'حي العرب', 8, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(114, 'حي الضواحي', 8, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(115, 'الفيوم', 9, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(116, 'طامية', 9, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(117, 'سنورس', 9, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(118, 'إطسا', 9, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(119, 'إبشواي', 9, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(120, 'يوسف الصديق', 9, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(121, 'طنطا', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(122, 'المحلة الكبرى', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(123, 'كفر الزيات', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(124, 'زفتى', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(125, 'السنطة', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(126, 'قطور', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(127, 'بسيون', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(128, 'سمنود', 10, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(129, 'مدينة نصر', 1, '2025-05-10 22:10:39', '2025-05-10 22:10:39'),
(130, 'المعادي', 1, '2025-05-10 22:10:39', '2025-05-10 22:10:39'),
(131, 'المهندسين', 3, '2025-05-10 22:10:39', '2025-05-10 22:10:39'),
(132, 'سموحة', 2, '2025-05-10 22:10:39', '2025-05-10 22:10:39');

-- --------------------------------------------------------

--
-- Table structure for table `consolidated_doctor_ratings`
--

CREATE TABLE `consolidated_doctor_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(1, 'Mechelle Snider', 'hosux@mailinator.com', 'Culpa nostrud volup', 'Quia neque assumenda', '2025-05-10 20:57:48', '2025-05-10 20:57:48'),
(2, 'Abdul Blanchard', 'bugezaw@mailinator.com', 'Mollitia magnam iste', 'Autem eiusmod aliqua', '2025-05-10 21:01:12', '2025-05-10 21:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `governorate_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `gender` enum('ذكر','انثي') DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `waiting_time` int(11) NOT NULL DEFAULT 15 COMMENT 'Average waiting time in minutes',
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,
  `is_profile_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_category`
--

CREATE TABLE `doctor_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_ratings`
--

CREATE TABLE `doctor_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comment` text DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `day` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `governorates`
--

CREATE TABLE `governorates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `governorates`
--

INSERT INTO `governorates` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(1, 'القاهرة', 'EG-C', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(2, 'الجيزة', 'EG-GZ', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(3, 'الإسكندرية', 'EG-ALX', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(4, 'الدقهلية', 'EG-DK', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(5, 'البحر الأحمر', 'EG-BA', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(6, 'البحيرة', 'EG-BH', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(7, 'بني سويف', 'EG-BNS', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(8, 'بورسعيد', 'EG-PTS', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(9, 'الفيوم', 'EG-FYM', '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(10, 'الغربية', 'EG-GH', '2025-05-10 12:01:07', '2025-05-10 12:01:07');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2024_01_20_000000_create_contacts_table', 1),
(4, '2025_04_22_000001_create_governorates_table', 1),
(5, '2025_04_22_000001_create_permission_tables', 1),
(6, '2025_04_22_000002_create_categories_table', 1),
(7, '2025_04_22_000002_create_cities_table', 1),
(8, '2025_04_22_099998_create_users_table', 1),
(9, '2025_04_23_101748_create_consolidated_doctors_table', 1),
(10, '2025_04_24_099999_create_patients_table', 1),
(11, '2025_04_24_100000_create_appointments_table', 1),
(12, '2025_04_24_100001_add_indexes_to_appointments_table', 1),
(13, '2025_04_24_100003_add_consultation_fee_to_doctors_table', 1),
(14, '2025_04_24_132127_create_personal_access_tokens_table', 1),
(15, '2025_04_24_235746_create_sessions_table', 1),
(16, '2025_04_25_000001_create_notifications_table', 1),
(17, '2025_04_26_124022_modify_status_column_in_categories_table', 1),
(18, '2025_04_30_194312_add_slug_to_categories_table', 1),
(19, '2025_05_02_200519_create_telescope_entries_table', 1),
(20, '2025_05_10_095750_consolidate_doctor_fields_migrations', 1),
(21, '2025_05_10_100022_consolidate_appointment_fields_migrations', 1),
(22, '2025_05_10_100437_consolidate_patients_migrations', 1),
(23, '2025_05_10_100843_remove_unused_fields_from_doctors_table', 1),
(24, '2025_05_10_101242_fix_doctor_table_dependencies', 1),
(25, '2025_05_10_101900_create_consolidated_doctor_ratings_table', 1),
(26, '2025_05_10_101932_create_consolidated_doctor_ratings_table', 1),
(27, '2025_05_10_101946_create_consolidated_doctor_schedules_table', 1),
(28, '2025_05_10_201559_create_payments_table', 2),
(29, '2025_05_10_201718_add_payment_id_to_appointments_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'Modules\\Users\\Entities\\User', 1),
(1, 'Modules\\Users\\Entities\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `medical_history` text DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `blood_type` varchar(255) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'EGP',
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) NOT NULL DEFAULT 'stripe',
  `payment_id` varchar(255) DEFAULT NULL COMMENT 'Payment gateway reference ID',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT 'Internal transaction reference',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Additional payment information' CHECK (json_valid(`metadata`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-05-10 12:01:06', '2025-05-10 12:01:06'),
(2, 'Doctor', 'web', '2025-05-10 12:01:06', '2025-05-10 12:01:06'),
(3, 'Patient', 'web', '2025-05-10 12:01:06', '2025-05-10 12:01:06');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('cFPIubmWLmEnxwYIzKcjtyFSHCOyPyrPq3IYO3VV', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV3BwUE43cUNDeGxRSEdsc3VOQ0NpS2h4MHp0dTNFWTc5eDFQWWtxSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9ub3RpZmljYXRpb25zL2NvdW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1746915080),
('hbKGw9oBQl47EyjgWfwYGy7WYKSiCcWKPZZNTEvG', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieVhCOXpwdVNUYzJaeXV3anI4bVNiSk5Za1dNM0V2dnA0N0g1aldCWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1746915048);

-- --------------------------------------------------------

--
-- Table structure for table `telescope_entries`
--

CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `batch_id` char(36) NOT NULL,
  `family_hash` varchar(255) DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(20) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Table structure for table `telescope_monitoring`
--

CREATE TABLE `telescope_monitoring` (
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `last_seen` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `password`, `remember_token`, `status`, `last_seen`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@clinic.com', '01066181943', NULL, '$2y$12$FklQ8y1oVfy6Lxt9HLoRVuu5gl4INMDuouRu0R9hT2ip/smTMP2ei', NULL, 1, NULL, '2025-05-10 12:01:06', '2025-05-10 12:01:06'),
(2, 'Ahmed Makled', 'ahmed.makled@live.com', '01066181942', NULL, '$2y$12$VooirYm4PrObvbu5VTyy8.PMJyQ01AfHMVbx/Kze2h2Rhu72UQsJ.', NULL, 1, NULL, '2025-05-10 12:01:07', '2025-05-10 12:01:07'),
(7, 'محمد أحمد', 'mohamed@example.com', '01234567890', '2025-05-10 22:03:24', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2025-05-10 22:03:24', '2025-05-10 22:03:24', '2025-05-10 22:03:24'),
(8, 'فاطمة علي', 'fatma@example.com', '01134567890', '2025-05-10 22:03:24', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2025-05-10 22:03:24', '2025-05-10 22:03:24', '2025-05-10 22:03:24'),
(9, 'أحمد حسن', 'ahmedhasan@example.com', '01034567891', '2025-05-10 22:03:24', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2025-05-10 22:03:24', '2025-05-10 22:03:24', '2025-05-10 22:03:24'),
(10, 'ليلى خالد', 'laila@example.com', '01534567892', '2025-05-10 22:03:24', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2025-05-10 22:03:24', '2025-05-10 22:03:24', '2025-05-10 22:03:24'),
(11, 'إيمان محمود', 'eman@example.com', '01234567893', '2025-05-10 22:03:24', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2025-05-10 22:03:24', '2025-05-10 22:03:24', '2025-05-10 22:03:24'),
(12, 'مدير اختبار', 'admin_test@clinic.com', '01000000001', '2025-05-10 22:10:39', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, NULL, '2025-05-10 22:10:39', '2025-05-10 22:10:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_doctor_id_index` (`doctor_id`),
  ADD KEY `appointments_patient_id_index` (`patient_id`),
  ADD KEY `appointments_scheduled_at_index` (`scheduled_at`),
  ADD KEY `appointments_status_scheduled_at_index` (`status`,`scheduled_at`),
  ADD KEY `appointments_payment_id_foreign` (`payment_id`);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_governorate_id_foreign` (`governorate_id`);

--
-- Indexes for table `consolidated_doctor_ratings`
--
ALTER TABLE `consolidated_doctor_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctors_user_id_index` (`user_id`),
  ADD KEY `doctors_governorate_id_foreign` (`governorate_id`),
  ADD KEY `doctors_city_id_foreign` (`city_id`);

--
-- Indexes for table `doctor_category`
--
ALTER TABLE `doctor_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_category_doctor_id_foreign` (`doctor_id`),
  ADD KEY `doctor_category_category_id_foreign` (`category_id`);

--
-- Indexes for table `doctor_ratings`
--
ALTER TABLE `doctor_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_patient_appointment_rating` (`patient_id`,`appointment_id`),
  ADD KEY `doctor_ratings_doctor_id_foreign` (`doctor_id`),
  ADD KEY `doctor_ratings_appointment_id_index` (`appointment_id`);

--
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_schedules_doctor_id_day_index` (`doctor_id`,`day`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `governorates`
--
ALTER TABLE `governorates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `governorates_code_unique` (`code`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patients_user_id_foreign` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_appointment_id_foreign` (`appointment_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  ADD PRIMARY KEY (`sequence`),
  ADD UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  ADD KEY `telescope_entries_batch_id_index` (`batch_id`),
  ADD KEY `telescope_entries_family_hash_index` (`family_hash`),
  ADD KEY `telescope_entries_created_at_index` (`created_at`),
  ADD KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`);

--
-- Indexes for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD PRIMARY KEY (`entry_uuid`,`tag`),
  ADD KEY `telescope_entries_tags_tag_index` (`tag`);

--
-- Indexes for table `telescope_monitoring`
--
ALTER TABLE `telescope_monitoring`
  ADD PRIMARY KEY (`tag`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `consolidated_doctor_ratings`
--
ALTER TABLE `consolidated_doctor_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctor_category`
--
ALTER TABLE `doctor_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctor_ratings`
--
ALTER TABLE `doctor_ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `governorates`
--
ALTER TABLE `governorates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `telescope_entries`
--
ALTER TABLE `telescope_entries`
  MODIFY `sequence` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3931;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_governorate_id_foreign` FOREIGN KEY (`governorate_id`) REFERENCES `governorates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_governorate_id_foreign` FOREIGN KEY (`governorate_id`) REFERENCES `governorates` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `doctors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_category`
--
ALTER TABLE `doctor_category`
  ADD CONSTRAINT `doctor_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_category_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_ratings`
--
ALTER TABLE `doctor_ratings`
  ADD CONSTRAINT `doctor_ratings_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_ratings_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctor_ratings_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `doctor_schedules_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `telescope_entries_tags`
--
ALTER TABLE `telescope_entries_tags`
  ADD CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
