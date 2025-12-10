-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 04:25 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finale_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `size` varchar(10) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_at`, `size`, `color`) VALUES
(41, 11, 14, 1, '2025-12-10 01:21:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(1, 'Full Face', 'full-face'),
(2, 'Modular', 'modular'),
(3, 'Open Face', 'open-face'),
(4, 'Half Helmet', 'half-helmet'),
(5, 'Off-Road', 'off-road'),
(6, 'Dual Sport', 'dual-sport');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `distance_from_cebu_km` decimal(8,2) DEFAULT NULL,
  `category` varchar(20) NOT NULL DEFAULT 'Visayas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `province`, `distance_from_cebu_km`, `category`) VALUES
(3, 'GALLON', 'Negros', 23.00, 'Visayas'),
(4, 'Manila', 'Metro Manila', 570.00, 'Luzon'),
(5, 'Quezon City', 'Metro Manila', 572.00, 'Luzon'),
(6, 'Caloocan', 'Metro Manila', 574.00, 'Luzon'),
(7, 'Makati', 'Metro Manila', 573.00, 'Luzon'),
(8, 'Pasig', 'Metro Manila', 573.00, 'Luzon'),
(9, 'Taguig', 'Metro Manila', 573.00, 'Luzon'),
(10, 'Pasay', 'Metro Manila', 571.00, 'Luzon'),
(11, 'Mandaluyong', 'Metro Manila', 573.00, 'Luzon'),
(12, 'Las Piñas', 'Metro Manila', 575.00, 'Luzon'),
(13, 'Muntinlupa', 'Metro Manila', 578.00, 'Luzon'),
(14, 'Malabon', 'Metro Manila', 575.00, 'Luzon'),
(15, 'Navotas', 'Metro Manila', 574.00, 'Luzon'),
(16, 'Valenzuela', 'Metro Manila', 575.00, 'Luzon'),
(17, 'Marikina', 'Metro Manila', 573.00, 'Luzon'),
(18, 'San Juan', 'Metro Manila', 573.00, 'Luzon'),
(19, 'Parañaque', 'Metro Manila', 573.00, 'Luzon'),
(20, 'Cavite City', 'Cavite', 545.00, 'Luzon'),
(21, 'Bacoor', 'Cavite', 546.00, 'Luzon'),
(22, 'Imus', 'Cavite', 546.00, 'Luzon'),
(23, 'Dasmariñas', 'Cavite', 547.00, 'Luzon'),
(24, 'Santa Rosa', 'Laguna', 533.00, 'Luzon'),
(25, 'Lipa', 'Laguna', 532.00, 'Luzon'),
(26, 'Tanauan', 'Laguna', 533.00, 'Luzon'),
(27, 'Antipolo', 'Rizal', 570.00, 'Luzon'),
(28, 'Angeles City', 'Pampanga', 564.00, 'Luzon'),
(29, 'San Fernando', 'La Union', 649.00, 'Luzon'),
(30, 'Baguio', 'Benguet', 684.00, 'Luzon'),
(31, 'Naga City', 'Camarines Sur', 480.00, 'Luzon'),
(32, 'Legazpi City', 'Albay', 616.00, 'Luzon'),
(33, 'Lucena', 'Quezon', 520.00, 'Luzon'),
(34, 'Batangas City', 'Batangas', 535.00, 'Luzon'),
(35, 'Malolos', 'Bulacan', 567.00, 'Luzon'),
(36, 'Tarlac City', 'Tarlac', 609.00, 'Luzon'),
(37, 'Cabanatuan', 'Nueva Ecija', 597.00, 'Luzon'),
(38, 'Dagupan', 'Pangasinan', 635.00, 'Luzon'),
(39, 'Olongapo', 'Zambales', 604.00, 'Luzon'),
(40, 'Cebu City', 'Cebu', 5.00, 'Visayas'),
(41, 'Mandaue', 'Cebu', 7.00, 'Visayas'),
(42, 'Lapu-Lapu', 'Cebu', 14.00, 'Visayas'),
(43, 'Talisay', 'Cebu', 10.00, 'Visayas'),
(44, 'Danao', 'Cebu', 30.00, 'Visayas'),
(45, 'Carcar', 'Cebu', 38.00, 'Visayas'),
(46, 'Toledo', 'Cebu', 50.00, 'Visayas'),
(47, 'Dumaguete', 'Negros Oriental', 120.00, 'Visayas'),
(48, 'Bacolod', 'Negros Occidental', 210.00, 'Visayas'),
(49, 'Iloilo City', 'Iloilo', 195.00, 'Visayas'),
(50, 'Roxas City', 'Capiz', 285.00, 'Visayas'),
(51, 'Tagbilaran', 'Bohol', 75.00, 'Visayas'),
(52, 'Ormoc', 'Leyte', 140.00, 'Visayas'),
(53, 'Tacloban', 'Leyte', 185.00, 'Visayas'),
(54, 'Baybay', 'Leyte', 165.00, 'Visayas'),
(55, 'Bogo', 'Cebu', 60.00, 'Visayas'),
(56, 'Maasin', 'Southern Leyte', 180.00, 'Visayas'),
(57, 'Davao City', 'Davao del Sur', 550.00, 'Mindanao'),
(58, 'Cagayan de Oro', 'Misamis Oriental', 640.00, 'Mindanao'),
(59, 'Zamboanga City', 'Zamboanga del Sur', 780.00, 'Mindanao'),
(60, 'General Santos', 'South Cotabato', 700.00, 'Mindanao'),
(61, 'Butuan', 'Agusan del Norte', 640.00, 'Mindanao'),
(62, 'Iligan', 'Lanao del Norte', 620.00, 'Mindanao'),
(63, 'Dipolog', 'Zamboanga del Norte', 780.00, 'Mindanao'),
(64, 'Tagum', 'Davao del Norte', 560.00, 'Mindanao'),
(65, 'Pagadian', 'Zamboanga del Sur', 730.00, 'Mindanao'),
(66, 'Koronadal', 'South Cotabato', 670.00, 'Mindanao'),
(67, 'Cotabato City', 'Cotabato', 650.00, 'Mindanao'),
(68, 'Surigao City', 'Surigao del Sur', 780.00, 'Mindanao'),
(69, 'Tandag', 'Surigao del Sur', 710.00, 'Mindanao');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `size` enum('SMALL','MEDIUM','LARGE') DEFAULT 'MEDIUM',
  `color` enum('Black','White','Red') DEFAULT 'Black',
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `status` enum('available','sold') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sold_count` int(11) NOT NULL DEFAULT 0,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `size`, `color`, `price`, `quantity`, `image`, `status`, `created_at`, `description`, `category_id`, `sold_count`, `archived`) VALUES
(10, 'Spyder Neo Dual Visor', 'Spyder', 'MEDIUM', 'Black', 2000.00, 15, 'uploads/prod_1765312431_4eef4692.webp', 'available', '2025-12-09 20:33:51', 'Full Face All Black Helmet', 1, 0, 0),
(12, 'Skull Crasher', 'KYT', 'MEDIUM', 'White', 5000.00, 9, 'uploads/prod_1765312617_b7582831.jpg', 'available', '2025-12-09 20:36:57', '', 6, 0, 0),
(13, 'Tractiic', 'Ducati', 'MEDIUM', 'Red', 6000.00, 3, 'uploads/prod_1765312677_581be11c.jpg', 'available', '2025-12-09 20:37:57', '', 2, 0, 0),
(14, 'Wosh', 'Zebra', 'MEDIUM', 'White', 3000.00, 20, 'uploads/prod_1765312759_2e187781.jpg', 'available', '2025-12-09 20:39:19', '', 6, 0, 0),
(15, 'Swoop', 'SMK', 'MEDIUM', 'Red', 5000.00, 10, 'uploads/prod_1765312862_ab05ab53.png', 'available', '2025-12-09 20:41:02', '', 4, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `review_text` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `sale_id`, `user_id`, `product_id`, `rating`, `review_text`, `photo`, `created_at`) VALUES
(1, 19, 9, 9, 3, 'bhat', NULL, '2025-12-06 15:49:55'),
(2, 26, 9, 2, 5, 'mazizing', NULL, '2025-12-06 16:46:58'),
(3, 27, 9, 4, 1, 'it hit my head good', NULL, '2025-12-06 16:50:39'),
(4, 31, 11, 14, 4, 'nice', NULL, '2025-12-10 09:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) DEFAULT 0.00,
  `city_id` int(11) DEFAULT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_method` varchar(20) DEFAULT 'gcash',
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `total_amount`, `delivery_fee`, `city_id`, `municipality`, `province`, `phone`, `address`, `order_date`, `status`, `payment_method`, `archived`) VALUES
(1, 1, 7600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:22:58', 'pending', 'gcash', 0),
(2, 1, 9900.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:25:09', 'pending', 'gcash', 0),
(3, 5, 8500.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-23 08:41:01', 'cancelled', 'gcash', 0),
(4, 5, 7600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-23 09:19:01', 'pending', 'gcash', 0),
(5, 5, 7600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-23 09:25:33', 'pending', 'gcash', 0),
(6, 5, 7600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-23 09:25:48', 'cancelled', 'gcash', 0),
(7, 6, 7600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-27 10:32:37', 'delivered', 'gcash', 0),
(8, 8, 7600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-29 04:46:43', 'delivered', 'gcash', 0),
(9, 8, 7600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-29 05:01:06', 'delivered', 'bank', 0),
(10, 8, 3600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-29 06:32:46', 'pending', 'gcash', 0),
(11, 8, 3600.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-29 06:52:38', 'pending', 'gcash', 0),
(12, 8, 9900.00, 0.00, NULL, NULL, NULL, NULL, NULL, '2025-11-29 06:53:06', 'pending', 'bank', 0),
(13, 1, 12977.00, 656.00, 32, 'Legazpi City', 'Albay', '132312311', 'werqwrwqwer', '2025-12-01 06:45:33', 'pending', 'bank', 0),
(14, 9, 868.00, 724.00, 30, 'Baguio', 'Benguet', '09392389220', 'gfhgfhfhgffhghfhg', '2025-12-02 14:58:47', 'pending', 'gcash', 0),
(15, 9, 9656.00, 656.00, 32, 'Legazpi City', 'Albay', '09392389220', 'qweqweq', '2025-12-02 17:29:25', 'pending', 'bank', 0),
(16, 9, 672.00, 572.00, 25, 'Lipa', 'Laguna', '098765644', 'ewrwerwer', '2025-12-02 17:41:48', 'pending', 'gcash', 0),
(17, 9, 824.00, 724.00, 30, 'Baguio', 'Benguet', '09392389220', 'asdasdwwdq', '2025-12-02 21:11:28', 'pending', 'gcash', 0),
(18, 9, 4800.00, 600.00, 64, 'Tagum', 'Davao del Norte', '12312312312', 'eqweqsadwd', '2025-12-02 21:12:18', 'pending', 'bank', 0),
(19, 9, 675.00, 575.00, 34, 'Batangas City', 'Batangas', '23133213212', 'dadwdqwd', '2025-12-02 21:15:16', 'delivered', 'gcash', 0),
(20, 9, 4525.00, 325.00, 50, 'Roxas City', 'Capiz', '09324234324', 'sdfasfasfsd', '2025-12-06 08:02:32', 'delivered', 'gcash', 0),
(21, 9, 4300.00, 100.00, 55, 'Bogo', 'Cebu', '09324234324', 'cabancalan\r\ncasuntingan', '2025-12-06 08:07:50', 'delivered', 'bank', 0),
(22, 9, 4250.00, 50.00, 41, 'Mandaue', 'Cebu', '09324234324', 'cabancalan\r\ncasuntingan', '2025-12-06 08:15:38', 'pending', 'gcash', 0),
(23, 9, 4924.00, 724.00, 30, 'Baguio', 'Benguet', '09324234324', 'kjkjk', '2025-12-06 08:18:02', 'pending', 'gcash', 0),
(24, 9, 4775.00, 575.00, 34, 'Batangas City', 'Batangas', '09324234324', 'kjkjkj', '2025-12-06 08:21:14', 'pending', 'gcash', 0),
(25, 9, 4924.00, 724.00, 30, 'Baguio', 'Benguet', '09324234324', 'klklkllk', '2025-12-06 08:27:51', 'pending', 'gcash', 0),
(26, 9, 4300.00, 100.00, 55, 'Bogo', 'Cebu', '09324234324', 'cabancalan\r\ncasuntingan', '2025-12-06 08:46:28', 'delivered', 'gcash', 0),
(27, 9, 4020.00, 520.00, 31, 'Naga City', 'Camarines Sur', '09324234324', 'qwe', '2025-12-06 08:50:05', 'delivered', 'bank', 0),
(28, 9, 200.00, 100.00, 55, 'Bogo', 'Cebu', '09324234324', 'nhfhgdhgdhgfhbv', '2025-12-06 13:44:11', 'shipped', 'bank', 0),
(29, 10, 2756.00, 656.00, 32, 'Legazpi City', 'Albay', '09392389220', 'qkidhakshhkasjbdb', '2025-12-09 21:28:27', 'pending', 'bank', 1),
(30, 10, 6680.00, 680.00, 61, 'Butuan', 'Agusan del Norte', '12312321312', 'sdadqwdqw', '2025-12-09 21:38:47', 'pending', 'gcash', 0),
(31, 11, 3050.00, 50.00, 41, 'Mandaue', 'Cebu', '12345678999', 'cebu,lacion', '2025-12-10 01:13:01', 'delivered', 'gcash', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_products`
--

CREATE TABLE `sales_products` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_products`
--

INSERT INTO `sales_products` (`id`, `sale_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(38, 30, 13, 1, 6000.00),
(39, 31, 14, 1, 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sale_promo`
--

CREATE TABLE `sale_promo` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_promo`
--

INSERT INTO `sale_promo` (`id`, `title`, `description`) VALUES
(1, '🔥 Christmas Sale — Up to 40% OFF! 🔥 For Walk-In customer visit us at safety St. Cebu City Near Gear Building', 'Top-brand helmets at massive discounts. Limited stocks only!');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(50) DEFAULT NULL,
  `value` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`) VALUES
(1, 'price_per_km', 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `city_id` int(11) DEFAULT 40,
  `email_address` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `archived` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `address`, `contact_number`, `city_id`, `email_address`, `password`, `created_at`, `is_admin`, `archived`, `status`) VALUES
(1, 'louis', 'baulita', 'phili,cebu,lacion', '09121212121', 40, 'baulita@gmail.com', '$2y$10$frrc.2w.VnccOsrEJxorU.VVB0QJqfNSe.aIle23m.WD3lIA4Rzha', '2025-11-21 02:35:47', 0, 0, 'active'),
(5, 'clark kent', 'judilla', '', '', 40, 'admin@gmail.com', '$2y$10$vBOkVPF60PokiJu86Fn0XOziC4/PVBoCL/vUEXid7GlU4/7NIRPrq', '2025-11-23 08:32:28', 1, 0, 'active'),
(6, 'clark kent', 'judilla', 'cabancalan', '09392389220', 40, 'clark@hhh.com', '$2y$10$j4Jo9k67JRchlB9NIFJoZ.HUcksXJM.By7zFdb5r.F1Gxpr/tZraC', '2025-11-27 10:32:03', 0, 0, 'active'),
(7, 'clark kent', 'judilla', 'cabancalan', '09392389220', 40, 'clarky@hhh.com', '$2y$10$3X/V.mB8DNsOHeuUUsnyvO2AoHKt5PYTdwlopWKfKdJ4YnHOGt89a', '2025-11-29 00:32:46', 0, 0, 'inactive'),
(8, 'alssadlfs', 'asdf', 'asdfsa', '123123123', 40, 'baba@gmail.com', '$2y$10$13GOkyE.6z.WZ3hlNriLruezX9664r/ovJVzhIt1mz1nhuDjVtG3C', '2025-11-29 04:46:18', 0, 0, 'inactive'),
(9, 'clark', 'judilla', 'amoa', '09392389220', 40, 'clarkk@gmail.com', '$2y$10$W0Vk2Y5GjByKxaoAI9uH1.MBGYnoXxlINq4DzYnTQA/PbWCXY3WLm', '2025-12-02 14:53:20', 0, 0, 'inactive'),
(10, 'clark', 'judilla', 'amoa', '09392389220', 40, 'clark@gmail.com', '$2y$10$V2hDd8rnIQUocDjSFuAFzOk6DKIG.OmTmcJk8P2Ib6OwYxHpDdX06', '2025-12-09 21:06:26', 0, 1, 'active'),
(11, 'Debe', 'upura', 'cebu,lacion', '09123456789', 40, 'deb@gmail.com', '$2y$10$r5gpBPNDmWp0W18EKbbE.u5rS5uAPnN6ZT2Intxbnvt4qyw8oWiDW', '2025-12-10 01:08:59', 0, 1, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_id` (`sale_id`,`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_products`
--
ALTER TABLE `sales_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sale_promo`
--
ALTER TABLE `sale_promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`),
  ADD KEY `fk_user_city` (`city_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sales_products`
--
ALTER TABLE `sales_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `sale_promo`
--
ALTER TABLE `sale_promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_products`
--
ALTER TABLE `sales_products`
  ADD CONSTRAINT `sales_products_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
