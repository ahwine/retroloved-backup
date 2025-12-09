-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2025 at 03:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `retroloved`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_page_visits`
--

CREATE TABLE `admin_page_visits` (
  `visit_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page_name` varchar(50) NOT NULL,
  `last_visit_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_page_visits`
--

INSERT INTO `admin_page_visits` (`visit_id`, `user_id`, `page_name`, `last_visit_at`) VALUES
(1, 4, 'orders', '2025-12-09 14:57:30'),
(2, 4, 'customers', '2025-12-09 14:47:37'),
(44, 4, 'contact_support', '2025-12-08 09:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `added_at`) VALUES
(38, 5, 23, '2025-12-01 02:12:28');

-- --------------------------------------------------------

--
-- Table structure for table `contact_support`
--

CREATE TABLE `contact_support` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','in_progress','resolved') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_support`
--

INSERT INTO `contact_support` (`id`, `user_id`, `name`, `email`, `subject`, `message`, `status`, `created_at`, `updated_at`) VALUES
(4, 6, 'Andre Abdilillah Ahwien', 'andreabdilillah67@gmail.com', 'Pertanyaan Produk', 'Haloo ini adalah pesan contact support pertama kali yang ada di RetroLoved', 'in_progress', '2025-12-08 09:15:33', '2025-12-08 09:16:11'),
(5, 6, 'Andre Abdilillah Ahwien', 'andreabdilillah67@gmail.com', 'Pertanyaan Produk', 'Haloo ini saya andre mantap', 'new', '2025-12-08 09:48:29', '2025-12-08 09:48:29'),
(6, 6, 'Andre Abdilillah Ahwien', 'andreabdilillah67@gmail.com', 'Pertanyaan Produk', '1234567890', 'new', '2025-12-08 10:00:59', '2025-12-08 10:00:59'),
(7, 6, 'Andre Abdilillah Ahwien', 'andreabdilillah67@gmail.com', 'Pertanyaan Pesanan', '11234567890', 'new', '2025-12-08 10:06:36', '2025-12-08 10:06:36'),
(8, 6, 'Andre Abdilillah Ahwien', 'andreabdilillah67@gmail.com', 'Pertanyaan Pesanan', '123456789098765432', 'new', '2025-12-08 10:08:28', '2025-12-08 10:08:28'),
(9, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 'Pertanyaan Pesanan', 'aaaaaaaaaaaa', 'new', '2025-12-08 10:12:45', '2025-12-08 10:12:45');

-- --------------------------------------------------------

--
-- Table structure for table `contact_support_replies`
--

CREATE TABLE `contact_support_replies` (
  `id` int(11) NOT NULL,
  `support_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_status` enum('sent','failed') DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_support_replies`
--

INSERT INTO `contact_support_replies` (`id`, `support_id`, `admin_id`, `admin_name`, `message`, `sent_at`, `email_status`) VALUES
(1, 2, 4, 'admin', 'Ya legit semua mantap enak rasanya kaya itu', '2025-12-08 08:05:03', 'failed'),
(2, 2, 4, 'admin', 'Ya legit rasanya mantap', '2025-12-08 08:05:25', 'failed'),
(3, 3, 4, 'admin', 'Halo apakah sudah berhasil', '2025-12-08 08:06:55', 'failed'),
(4, 3, 4, 'admin', 'Halo apakah sudah berhasil', '2025-12-08 09:05:51', 'sent'),
(5, 2, 4, 'admin', 'HAII APA KANAR', '2025-12-08 09:10:38', 'sent'),
(6, 3, 4, 'admin', 'Halo ini adalah admin dari preloved, ini percobaaan pertama', '2025-12-08 09:11:23', 'sent'),
(7, 3, 4, 'admin', 'Halo ini adalah admin dari preloved, ini percobaaan pertama', '2025-12-08 09:13:20', 'sent'),
(8, 4, 4, 'admin', 'Haloo apakabar Andre,\r\nPercobaan pertama dan berhasil dari tim RetroLoved', '2025-12-08 09:16:11', 'sent');

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `verification_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `order_id`, `type`, `title`, `message`, `is_read`, `created_at`) VALUES
(7, 5, 10, 'order_pending', 'Pesanan Berhasil Dibuat', 'Pesanan #10 berhasil dibuat. Total: Rp 135.000. Silakan upload bukti pembayaran.', 1, '2025-11-30 09:11:14'),
(8, 5, 10, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #10 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 1, '2025-11-30 09:11:37'),
(9, 5, 11, 'order_pending', 'Pesanan Berhasil Dibuat', 'Pesanan #11 berhasil dibuat. Total: Rp 130.000. Silakan upload bukti pembayaran.', 1, '2025-12-01 02:09:29'),
(52, 5, 7, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #7 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-07 23:59:29'),
(53, 5, 11, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #11 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-07 23:59:34'),
(55, 5, 39, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #39 berhasil dibuat. Total: Rp 215.000. Silakan upload bukti pembayaran.', 0, '2025-12-08 00:25:00'),
(58, 5, 39, 'order_cancelled', 'Pesanan Dibatalkan', 'Pesanan #39 telah dibatalkan. Jika ada pertanyaan, silakan hubungi admin.', 0, '2025-12-08 03:04:23'),
(66, 5, 10, 'order_cancelled', 'Pesanan Dibatalkan', 'Pesanan #10 telah dibatalkan. Jika ada pertanyaan, silakan hubungi admin.', 0, '2025-12-08 03:50:01'),
(67, 5, 11, 'order_cancelled', 'Pesanan Dibatalkan', 'Pesanan #11 telah dibatalkan. Jika ada pertanyaan, silakan hubungi admin.', 0, '2025-12-08 03:50:03'),
(219, 6, 76, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #76 berhasil dibuat. Total: Rp 120.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:05:09'),
(220, 6, 76, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #76 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:05:33'),
(221, 6, 76, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #76 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:05:43'),
(222, 6, 76, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #76 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:05:49'),
(223, 6, 76, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #76 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:06:01'),
(224, 6, 77, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #77 berhasil dibuat. Total: Rp 225.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:10:33'),
(225, 6, 77, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #77 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:11:10'),
(226, 6, 77, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #77 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:11:26'),
(227, 6, 77, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #77 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:11:35'),
(228, 6, 77, 'order_completed', 'Pesanan Selesai', 'Pesanan #77 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:11:39'),
(229, 6, 77, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #77 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:11:47'),
(230, 6, 78, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #78 berhasil dibuat. Total: Rp 215.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:18:45'),
(231, 6, 78, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #78 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:19:03'),
(232, 6, 78, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #78 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:19:14'),
(233, 6, 78, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #78 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:19:22'),
(234, 6, 78, 'order_completed', 'Pesanan Selesai', 'Pesanan #78 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:19:28'),
(235, 6, 77, 'order_completed', 'Pesanan Selesai', 'Pesanan #77 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:19:58'),
(236, 6, 76, 'order_completed', 'Pesanan Selesai', 'Pesanan #76 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:20:03'),
(237, 6, 79, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #79 berhasil dibuat. Total: Rp 80.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:24:32'),
(238, 6, 79, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #79 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:24:51'),
(239, 6, 79, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #79 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:24:58'),
(240, 6, 79, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #79 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:25:06'),
(241, 6, 79, 'order_completed', 'Pesanan Selesai', 'Pesanan #79 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:25:09'),
(242, 6, 80, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #80 berhasil dibuat. Total: Rp 125.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:26:41'),
(243, 6, 80, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #80 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:26:58'),
(244, 6, 80, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #80 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:27:07'),
(245, 6, 80, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #80 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:27:14'),
(246, 6, 80, 'order_completed', 'Pesanan Selesai', 'Pesanan #80 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:27:48'),
(247, 6, 81, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #81 berhasil dibuat. Total: Rp 130.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:29:21'),
(248, 6, 81, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #81 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:29:35'),
(249, 6, 81, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #81 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:29:48'),
(250, 6, 81, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #81 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:29:55'),
(251, 6, 81, 'order_completed', 'Pesanan Selesai', 'Pesanan #81 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:29:59'),
(252, 6, 82, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #82 berhasil dibuat. Total: Rp 135.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:31:50'),
(253, 6, 81, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #81 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:31:59'),
(254, 6, 81, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #81 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:32:07'),
(255, 6, 82, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #82 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:32:16'),
(256, 6, 82, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #82 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:32:23'),
(257, 6, 82, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #82 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:32:29'),
(258, 6, 82, 'order_completed', 'Pesanan Selesai', 'Pesanan #82 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:32:43'),
(259, 6, 83, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #83 berhasil dibuat. Total: Rp 150.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:37:37'),
(260, 6, 83, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #83 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:37:57'),
(261, 6, 83, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #83 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:38:10'),
(262, 6, 83, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #83 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:38:19'),
(263, 6, 83, 'order_completed', 'Pesanan Selesai', 'Pesanan #83 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:38:23'),
(264, 6, 81, 'order_completed', 'Pesanan Selesai', 'Pesanan #81 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:39:01'),
(265, 6, 84, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #84 berhasil dibuat. Total: Rp 2.900.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:52:19'),
(266, 6, 84, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #84 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:52:36'),
(267, 6, 84, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #84 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:52:49'),
(268, 6, 84, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #84 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:52:58'),
(269, 6, 84, 'order_completed', 'Pesanan Selesai', 'Pesanan #84 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:53:04'),
(270, 6, 85, 'order', 'Pesanan Berhasil Dibuat', 'Pesanan #85 berhasil dibuat. Total: Rp 130.000. Silakan upload bukti pembayaran.', 0, '2025-12-09 14:56:54'),
(271, 6, 85, 'order_confirmed', 'Pesanan Dikonfirmasi', 'Pesanan #85 telah dikonfirmasi dan sedang diproses. Kami akan segera mengirimkan produk Anda.', 0, '2025-12-09 14:57:07'),
(272, 6, 85, 'order_shipped', 'Pesanan Telah Dikirim', 'Pesanan #85 telah dikirim! Silakan tunggu paket Anda tiba.', 0, '2025-12-09 14:57:17'),
(273, 6, 85, 'order_delivered', 'Pesanan Telah Sampai', 'Pesanan #85 telah sampai! Terima kasih telah berbelanja di RetroLoved.', 0, '2025-12-09 14:57:24'),
(274, 6, 85, 'order_completed', 'Pesanan Selesai', 'Pesanan #85 telah selesai. Terima kasih telah berbelanja di RetroLoved!', 0, '2025-12-09 14:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `status` enum('Pending','Processing','Shipped','Delivered','Completed','Cancelled') DEFAULT 'Pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `courier_name` varchar(100) DEFAULT NULL,
  `courier_phone` varchar(20) DEFAULT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  `current_status_detail` varchar(50) DEFAULT NULL,
  `estimated_delivery_date` datetime DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `shipping_address` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `shipping_service_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `customer_name`, `customer_email`, `total_amount`, `subtotal`, `shipping_cost`, `status`, `tracking_number`, `courier_name`, `courier_phone`, `current_location`, `current_status_detail`, `estimated_delivery_date`, `shipped_at`, `delivered_at`, `admin_notes`, `shipping_address`, `phone`, `shipping_service_id`, `payment_method`, `payment_proof`, `notes`, `created_at`, `updated_at`) VALUES
(1, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 200000.00, 200000.00, 0.00, 'Delivered', NULL, NULL, NULL, NULL, 'delivered', NULL, NULL, NULL, NULL, 'Jakarta, Indonesia', '081234567891', NULL, 'E-Wallet', NULL, NULL, '2025-10-28 10:44:36', '2025-12-09 03:20:49'),
(2, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 200000.00, 200000.00, 0.00, 'Delivered', NULL, NULL, NULL, NULL, 'delivered', NULL, NULL, NULL, NULL, 'Jakarta, Indonesia', '081234567891', NULL, 'E-Wallet', NULL, NULL, '2025-10-28 10:48:47', '2025-12-09 03:20:49'),
(3, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 200000.00, 200000.00, 0.00, 'Delivered', NULL, NULL, NULL, NULL, 'delivered', NULL, NULL, NULL, NULL, 'Graha Family', '081231793810', NULL, 'Transfer Bank - BCA', NULL, NULL, '2025-10-28 14:23:17', '2025-12-09 03:20:49'),
(4, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 200000.00, 200000.00, 0.00, 'Delivered', NULL, NULL, NULL, NULL, 'delivered', NULL, NULL, NULL, NULL, 'Graha Natura', '081231793810', NULL, 'Transfer Bank - BRI', 'payment_4_1761734205.png', NULL, '2025-10-29 10:25:11', '2025-12-09 03:20:49'),
(5, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 75000.00, 75000.00, 0.00, 'Delivered', NULL, NULL, NULL, NULL, 'delivered', NULL, NULL, NULL, NULL, 'doly', '081231793810', NULL, 'Transfer Bank - BRI', 'payment_5_1761735225.png', NULL, '2025-10-29 10:46:52', '2025-12-09 03:20:49'),
(6, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 260000.00, 260000.00, 0.00, 'Delivered', NULL, NULL, NULL, NULL, 'delivered', NULL, NULL, NULL, NULL, 'doly', '0881026054209', NULL, 'E-Wallet - GoPay', 'payment_6_1761750946.png', NULL, '2025-10-29 15:15:18', '2025-12-09 03:20:49'),
(7, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 170000.00, 170000.00, 0.00, 'Delivered', '', NULL, NULL, NULL, 'delivered', NULL, NULL, NULL, NULL, 'do', '081231793810', NULL, 'E-Wallet - DANA', 'payment_7_1761751890.png', NULL, '2025-10-29 15:23:05', '2025-12-09 03:20:49'),
(8, 6, 'Andre Abdilillah', 'andre@gmail.com', 80000.00, 80000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'Transfer Bank - BRI', 'payment_8_1764485876.png', NULL, '2025-11-30 06:57:46', '2025-12-09 03:20:49'),
(9, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 2900000.00, 2900000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'Transfer Bank - BCA', 'payment_9_1764487898.png', NULL, '2025-11-30 07:31:32', '2025-12-09 03:20:49'),
(10, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 135000.00, 135000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I , Surabaya, Jawa Timur 60252', '08123456789', NULL, 'Transfer Bank - BRI', NULL, NULL, '2025-11-30 09:11:14', '2025-12-09 03:20:49'),
(11, 5, 'Gilang Ramadhan', 'gilang@gmail.com', 130000.00, 130000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I , Surabaya, Jawa Timur 60252', '08123456789', NULL, 'Transfer Bank - Mandiri', NULL, NULL, '2025-12-01 02:09:29', '2025-12-09 03:20:49'),
(12, 6, 'Andre ', 'andre@gmail.com', 130000.00, 130000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'Transfer Bank - Jatim', NULL, NULL, '2025-12-01 02:14:02', '2025-12-09 03:20:49'),
(13, 6, 'a', 'a@gmail.com', 130000.00, 130000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'Transfer Bank - Mandiri', NULL, NULL, '2025-12-01 02:38:24', '2025-12-09 03:20:49'),
(14, 6, 'Andre', 'andre@gmail.com', 310000.00, 310000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'Transfer Bank - BCA', NULL, NULL, '2025-12-01 03:42:51', '2025-12-09 03:20:49'),
(15, 6, 'aa', 'aa', 300000.00, 300000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - OVO', NULL, NULL, '2025-12-05 08:26:01', '2025-12-09 03:20:49'),
(16, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 09:15:53', '2025-12-09 03:20:49'),
(17, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 09:45:17', '2025-12-09 03:20:49'),
(18, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 09:54:02', '2025-12-09 03:20:49'),
(19, 6, 'a', 'a', 215000.00, 215000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 10:12:40', '2025-12-09 03:20:49'),
(20, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 180000.00, 180000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 11:27:57', '2025-12-09 03:20:49'),
(21, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 180000.00, 180000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 13:09:59', '2025-12-09 03:20:49'),
(22, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 135000.00, 135000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 13:37:54', '2025-12-09 03:20:49'),
(23, 6, 'a', 'a', 215000.00, 215000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 14:29:34', '2025-12-09 03:20:49'),
(24, 6, 'a', 'a', 120000.00, 120000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - OVO', NULL, NULL, '2025-12-05 14:41:01', '2025-12-09 03:20:49'),
(25, 6, 'a', 'a', 215000.00, 215000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 14:47:54', '2025-12-09 03:20:49'),
(26, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 120000.00, 120000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 14:57:13', '2025-12-09 03:20:49'),
(27, 6, 'a', 'a', 180000.00, 180000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 14:59:36', '2025-12-09 03:20:49'),
(28, 6, 'a', 'aa', 215000.00, 215000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-05 15:08:29', '2025-12-09 03:20:49'),
(29, 6, 'a', 'aa', 180000.00, 180000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_29_1764948747.png', NULL, '2025-12-05 15:13:26', '2025-12-09 03:20:49'),
(30, 6, 'Andre Abdilillah Ahwien', 'andre@gmail.com', 435000.00, 435000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_30_1764952199.png', NULL, '2025-12-05 16:29:50', '2025-12-09 03:20:49'),
(31, 6, 'andre', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_31_1765088788.png', NULL, '2025-12-07 06:25:48', '2025-12-09 03:20:49'),
(32, 6, 'andre', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_32_1765088847.png', NULL, '2025-12-07 06:27:21', '2025-12-09 03:20:49'),
(33, 6, 'andre', 'andre@gmail.com', 180000.00, 180000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'Transfer Bank - BRI', 'payment_33_1765089195.png', NULL, '2025-12-07 06:33:08', '2025-12-09 03:20:49'),
(34, 6, 'andrecaks', 'andrehacker123@hack.com', 135000.00, 135000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_34_1765111306.png', NULL, '2025-12-07 12:41:23', '2025-12-09 03:20:49'),
(35, 6, 'andre', 'andre@gmail.com', 130000.00, 130000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-07 23:38:50', '2025-12-09 03:20:49'),
(36, 6, 'andre', 'andre@gmail.com', 135000.00, 135000.00, 0.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-07 23:41:47', '2025-12-09 03:20:49'),
(37, 6, 'aa', 'aanndre@gmail.com', 120000.00, 120000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-07 23:58:03', '2025-12-09 03:20:49'),
(38, 6, 'andre', 'anndre@gmail.com', 130000.00, 130000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_38_1765152617.png', NULL, '2025-12-08 00:10:06', '2025-12-09 03:20:49'),
(39, 5, 'gilang', 'gilang@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I , Surabaya, Jawa Timur 60252', '08123456789', NULL, 'E-Wallet - DANA', 'payment_39_1765153530.png', NULL, '2025-12-08 00:25:00', '2025-12-09 03:20:49'),
(40, 6, 'andre', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_40_1765155905.png', NULL, '2025-12-08 01:02:43', '2025-12-09 03:20:49'),
(41, 6, 'andre', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_41_1765164958.png', NULL, '2025-12-08 03:34:52', '2025-12-09 03:20:49'),
(42, 6, 'andre', 'andre@gmail.com', 180000.00, 180000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_42_1765165674.png', NULL, '2025-12-08 03:47:21', '2025-12-09 03:20:49'),
(43, 6, 'andre', 'andre@gmail.com', 180000.00, 180000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', NULL, NULL, '2025-12-08 03:52:48', '2025-12-09 03:20:49'),
(44, 6, 'andre', 'andre@gmail.com', 215000.00, 215000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_44_1765166186.png', NULL, '2025-12-08 03:56:04', '2025-12-09 03:20:49'),
(45, 6, 'andre', 'andre@gmail.com', 120000.00, 120000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_45_1765167576.png', NULL, '2025-12-08 03:57:19', '2025-12-09 03:20:49'),
(46, 6, 'andre', 'andre@gmail.com', 130000.00, 130000.00, 0.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', NULL, 'E-Wallet - DANA', 'payment_46_1765167974.png', NULL, '2025-12-08 04:26:05', '2025-12-09 03:20:49'),
(47, 6, 'Andre', 'andre@gmail.com', 175000.00, 150000.00, 25000.00, 'Cancelled', NULL, NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - DANA', NULL, NULL, '2025-12-09 03:54:30', '2025-12-09 03:54:33'),
(48, 6, 'Andre', 'andre@gmail.com', 205000.00, 180000.00, 25000.00, 'Cancelled', '', 'Ahmad Ridwan', '0813-9876-5432', 'Alamat Customer', 'delivered', '2025-12-12 04:55:42', '2025-12-10 05:55:42', '2025-12-11 21:55:42', 'Halo', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - DANA', 'payment_48_1765252552.png', NULL, '2025-12-09 03:55:42', '2025-12-09 04:00:06'),
(49, 6, 'andre', 'andre@gmail.com', 192000.00, 180000.00, 12000.00, 'Cancelled', 'JN332061208', 'Dedi Kurniawan', '0857-6677-8899', 'Alamat Customer', 'delivered', '2025-12-12 06:12:34', '2025-12-10 07:12:34', '2025-12-11 23:12:34', NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 3, 'E-Wallet - DANA', 'payment_49_1765257164.png', NULL, '2025-12-09 05:12:34', '2025-12-09 05:54:06'),
(50, 6, 'andre', 'andre@gmail.com', 144000.00, 130000.00, 14000.00, 'Cancelled', 'AN733184145', 'Siti Rahayu', '0821-1122-3344', 'Alamat Customer', 'delivered', '2025-12-13 12:39:57', '2025-12-10 05:39:57', '2025-12-13 05:39:57', NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 8, 'E-Wallet - OVO', NULL, NULL, '2025-12-09 05:39:57', '2025-12-09 05:54:06'),
(51, 6, 'andre', 'andre@gmail.com', 195000.00, 180000.00, 15000.00, 'Cancelled', '', 'Dedi Kurniawan', '0857-6677-8899', 'Sistem Otomatis', 'order_placed', '2025-12-12 12:55:41', NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 6, 'E-Wallet - DANA', 'payment_51_1765259827.png', NULL, '2025-12-09 05:55:41', '2025-12-09 06:05:46'),
(52, 6, 'andre', 'andre@gmail.com', 135000.00, 120000.00, 15000.00, 'Cancelled', '', 'Siti Rahayu', '0821-1122-3344', 'Sistem Otomatis', 'order_placed', '2025-12-12 13:05:30', NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 6, 'E-Wallet - DANA', 'payment_52_1765260337.png', NULL, '2025-12-09 06:05:30', '2025-12-09 06:18:11'),
(53, 6, 'andre', 'andre@gmail.com', 192000.00, 180000.00, 12000.00, 'Cancelled', '', 'Ahmad Ridwan', '0813-9876-5432', 'Sistem Otomatis', 'order_placed', '2025-12-14 13:18:34', NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'E-Wallet - DANA', 'payment_53_1765261137.png', NULL, '2025-12-09 06:18:34', '2025-12-09 06:35:10'),
(54, 6, 'andre', 'andre@gmail.com', 195000.00, 180000.00, 15000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 1, 'E-Wallet - DANA', 'payment_54_1765261842.png', NULL, '2025-12-09 06:30:10', '2025-12-09 06:35:06'),
(55, 6, 'andre', 'andre@gmail.com', 192000.00, 180000.00, 12000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'E-Wallet - DANA', 'payment_55_1765262099.png', NULL, '2025-12-09 06:34:48', '2025-12-09 06:48:04'),
(56, 6, 'andre', 'andre@gmail.com', 240000.00, 215000.00, 25000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - DANA', 'payment_56_1765262925.png', NULL, '2025-12-09 06:48:29', '2025-12-09 07:02:00'),
(57, 6, 'andre', 'andre@gmail.com', 150000.00, 125000.00, 25000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - GoPay', 'payment_57_1765263790.png', NULL, '2025-12-09 07:02:50', '2025-12-09 07:11:02'),
(58, 6, 'andre', 'andre@gmail.com', 250000.00, 225000.00, 25000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - GoPay', NULL, NULL, '2025-12-09 07:11:57', '2025-12-09 07:13:19'),
(59, 6, 'andre', 'andre@gmail.com', 145000.00, 120000.00, 25000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - GoPay', 'payment_59_1765264448.png', NULL, '2025-12-09 07:13:59', '2025-12-09 07:20:47'),
(60, 6, 'andre', 'andre@gmail.com', 227000.00, 215000.00, 12000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'E-Wallet - DANA', 'payment_60_1765264839.png', NULL, '2025-12-09 07:19:53', '2025-12-09 07:28:09'),
(61, 6, 'andre', 'andre@gmail.com', 192000.00, 180000.00, 12000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'E-Wallet - DANA', 'payment_61_1765265314.png', NULL, '2025-12-09 07:28:29', '2025-12-09 08:13:22'),
(62, 6, 'andre', 'andre@gmail.com', 142000.00, 130000.00, 12000.00, 'Cancelled', '', 'Indah Permata', '0877-5566-7788', NULL, 'completed', '2025-12-16 15:18:47', NULL, '2025-12-09 08:51:24', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'E-Wallet - DANA', 'payment_62_1765268039.png', NULL, '2025-12-09 08:13:53', '2025-12-09 10:32:49'),
(63, 6, 'andre', 'andre@gmail.com', 227000.00, 215000.00, 12000.00, 'Cancelled', '', NULL, NULL, NULL, 'completed', NULL, NULL, '2025-12-09 08:57:01', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 3, 'E-Wallet - DANA', 'payment_63_1765270575.png', NULL, '2025-12-09 08:56:08', '2025-12-09 10:32:51'),
(64, 6, 'andre', 'andre@gmail.com', 162000.00, 150000.00, 12000.00, 'Cancelled', '', 'Dedi Kurniawan', '0857-6677-8899', NULL, 'completed', '2025-12-17 16:05:34', NULL, '2025-12-09 09:06:18', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 3, 'E-Wallet - DANA', 'payment_64_1765271123.png', NULL, '2025-12-09 09:05:15', '2025-12-09 10:32:52'),
(65, 6, 'andre', 'andre@gmail.com', 192000.00, 180000.00, 12000.00, 'Cancelled', '', 'Rudi Hartono', '0822-4455-6677', NULL, 'completed', '2025-12-17 16:32:11', NULL, '2025-12-09 09:37:38', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 3, 'E-Wallet - DANA', 'payment_65_1765272723.png', NULL, '2025-12-09 09:31:57', '2025-12-09 10:32:55'),
(66, 6, 'andre', 'andre@gmail.com', 142000.00, 130000.00, 12000.00, 'Cancelled', '', 'Siti Rahayu', '0821-1122-3344', NULL, 'completed', '2025-12-16 16:49:35', NULL, '2025-12-09 09:49:55', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'Transfer Bank - BRI', 'payment_66_1765273769.png', NULL, '2025-12-09 09:49:14', '2025-12-09 10:33:04'),
(67, 6, 'andre', 'andre@gmail.com', 227000.00, 215000.00, 12000.00, 'Cancelled', '', 'Dewi Lestari', '0856-3344-5566', NULL, 'completed', '2025-12-17 17:01:42', NULL, '2025-12-09 10:02:02', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 3, 'E-Wallet - GoPay', 'payment_67_1765274489.png', NULL, '2025-12-09 10:01:22', '2025-12-09 10:33:04'),
(68, 6, 'andre', 'andre@gmail.com', 230000.00, 215000.00, 15000.00, 'Cancelled', '', 'Rina Wati', '0878-9988-7766', NULL, 'completed', '2025-12-15 17:24:10', NULL, '2025-12-09 10:24:26', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 1, 'E-Wallet - DANA', 'payment_68_1765275837.png', NULL, '2025-12-09 10:23:51', '2025-12-09 10:33:04'),
(69, 6, 'andre', 'andre@gmail.com', 192000.00, 180000.00, 12000.00, 'Cancelled', 'JNT25120939040167', 'Rudi Hartono', '0822-4455-6677', NULL, 'completed', '2025-12-16 17:28:49', NULL, '2025-12-09 10:29:11', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'E-Wallet - DANA', 'payment_69_1765276118.png', NULL, '2025-12-09 10:28:32', '2025-12-09 10:33:04'),
(70, 6, 'andre', 'andre@gmail.com', 230000.00, 215000.00, 15000.00, 'Cancelled', '', 'Rudi Hartono', '0822-4455-6677', NULL, 'completed', '2025-12-12 17:33:56', NULL, '2025-12-09 10:34:18', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 6, 'Transfer Bank - BRI', 'payment_70_1765276427.png', NULL, '2025-12-09 10:33:39', '2025-12-09 10:39:59'),
(71, 6, 'andre', 'andre@gmail.com', 198000.00, 180000.00, 18000.00, 'Cancelled', '', 'Ahmad Ridwan', '0813-9876-5432', NULL, 'completed', '2025-12-11 17:40:46', NULL, '2025-12-09 10:50:05', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 7, 'E-Wallet - DANA', 'payment_71_1765276837.png', NULL, '2025-12-09 10:40:27', '2025-12-09 12:51:25'),
(72, 6, 'andre', 'percayatuhan@gmail.com', 2925000.00, 2900000.00, 25000.00, 'Cancelled', '', 'Rudi Hartono', '0822-4455-6677', NULL, 'completed', '2025-12-11 17:49:16', NULL, '2025-12-09 10:49:57', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'Transfer Bank - BRI', 'payment_72_1765277338.png', NULL, '2025-12-09 10:48:52', '2025-12-09 12:51:22'),
(73, 6, 'andre', 'andre@gmail.com', 195000.00, 180000.00, 15000.00, 'Cancelled', '', 'Joko Widodo', '0838-6677-8899', NULL, 'completed', '2025-12-15 19:52:33', NULL, '2025-12-09 12:57:11', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 1, 'E-Wallet - DANA', 'payment_73_1765284739.png', NULL, '2025-12-09 12:51:58', '2025-12-09 13:28:54'),
(74, 6, 'andre', 'andre@gmail.com', 135000.00, 120000.00, 15000.00, 'Cancelled', '', NULL, NULL, NULL, 'order_placed', NULL, NULL, NULL, NULL, 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 6, 'Transfer Bank - Mandiri', 'payment_74_1765285001.png', NULL, '2025-12-09 12:56:35', '2025-12-09 13:28:56'),
(75, 6, 'andre', 'andre@gmail.com', 192000.00, 180000.00, 12000.00, 'Cancelled', '', 'Rudi Hartono', '0822-4455-6677', NULL, 'completed', '2025-12-17 20:53:10', NULL, '2025-12-09 13:55:50', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 3, 'E-Wallet - DANA', 'payment_75_1765288380.png', NULL, '2025-12-09 13:52:51', '2025-12-09 14:04:20'),
(76, 6, 'andre', 'andre@gmail.com', 132000.00, 120000.00, 12000.00, '', 'JNE25120933850275', 'Siti Rahayu', '0821-1122-3344', NULL, 'completed', '2025-12-17 21:05:33', NULL, '2025-12-09 14:20:03', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 3, 'E-Wallet - DANA', 'payment_76_1765289115.png', NULL, '2025-12-09 14:05:09', '2025-12-09 14:20:03'),
(77, 6, 'andre', 'andre@gmail.com', 240000.00, 225000.00, 15000.00, '', 'JNE25120989909445', 'Agus Setiawan', '0819-2233-4455', NULL, 'completed', '2025-12-15 21:11:10', NULL, '2025-12-09 14:19:58', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 1, 'E-Wallet - DANA', 'payment_77_1765289444.png', NULL, '2025-12-09 14:10:33', '2025-12-09 14:19:58'),
(78, 6, 'andre', 'andre@gmail.com', 227000.00, 215000.00, 12000.00, '', 'JNT25120905375566', 'Rudi Hartono', '0822-4455-6677', NULL, 'completed', '2025-12-16 21:19:03', NULL, '2025-12-09 14:19:28', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'Transfer Bank - BRI', 'payment_78_1765289934.png', NULL, '2025-12-09 14:18:45', '2025-12-09 14:19:28'),
(79, 6, 'andre', 'andre@gmail.com', 98000.00, 80000.00, 18000.00, '', 'SIC25120982220645', 'Rina Wati', '0878-9988-7766', NULL, 'completed', '2025-12-11 21:24:51', NULL, '2025-12-09 14:25:09', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 7, 'E-Wallet - DANA', 'payment_79_1765290279.png', NULL, '2025-12-09 14:24:32', '2025-12-09 14:25:09'),
(80, 6, 'nada', 'andre@gmail.com', 139000.00, 125000.00, 14000.00, '', 'ANT25120925262117', 'Ahmad Ridwan', '0813-9876-5432', NULL, 'completed', '2025-12-15 21:26:58', NULL, '2025-12-09 14:27:48', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 8, 'Transfer Bank - Mandiri', 'payment_80_1765290412.png', NULL, '2025-12-09 14:26:41', '2025-12-09 14:27:48'),
(81, 6, 'andre', 'andre@gmail.com', 155000.00, 130000.00, 25000.00, 'Completed', 'JNE25120951339710', 'Budi Santoso', '0812-3456-7890', NULL, 'completed', '2025-12-11 21:29:35', NULL, '2025-12-09 14:39:01', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - DANA', 'payment_81_1765290566.png', NULL, '2025-12-09 14:29:21', '2025-12-09 14:39:01'),
(82, 6, 'work', 'andre@gmail.com', 150000.00, 135000.00, 15000.00, 'Completed', 'JNE25120938927665', 'Rudi Hartono', '0822-4455-6677', NULL, 'completed', '2025-12-15 21:32:16', NULL, '2025-12-09 14:32:43', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 1, 'Transfer Bank - BRI', 'payment_82_1765290716.png', NULL, '2025-12-09 14:31:50', '2025-12-09 14:36:40'),
(83, 6, 'kontnol', 'andre@gmail.com', 175000.00, 150000.00, 25000.00, 'Completed', 'JNE25120911975894', 'Indah Permata', '0877-5566-7788', NULL, 'completed', '2025-12-11 21:37:57', NULL, '2025-12-09 14:38:23', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 2, 'E-Wallet - DANA', 'payment_83_1765291067.png', NULL, '2025-12-09 14:37:37', '2025-12-09 14:38:23'),
(84, 6, 'andre', 'awdw@gmail.com', 2912000.00, 2900000.00, 12000.00, 'Completed', '', NULL, NULL, NULL, 'completed', NULL, NULL, '2025-12-09 14:53:04', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'Transfer Bank - BRI', 'payment_84_1765291946.png', NULL, '2025-12-09 14:52:19', '2025-12-09 14:53:04'),
(85, 6, 'final', 'final@gmail.com', 142000.00, 130000.00, 12000.00, 'Completed', 'JNT25120957031765', 'Joko Widodo', '0838-6677-8899', NULL, 'completed', '2025-12-16 21:57:07', '2025-12-09 14:57:17', '2025-12-09 14:57:27', '', 'Jln. Petemon I No. 81 RT. 08/RW. 09, Surabaya, Jawa Timur 60252', '081336019251', 4, 'Transfer Bank - Mandiri', 'payment_85_1765292220.png', NULL, '2025-12-09 14:56:54', '2025-12-09 14:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `history_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `status_detail` varchar(50) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `courier_name` varchar(100) DEFAULT NULL,
  `courier_phone` varchar(20) DEFAULT NULL,
  `estimated_arrival` datetime DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`history_id`, `order_id`, `status`, `status_detail`, `tracking_number`, `notes`, `location`, `courier_name`, `courier_phone`, `estimated_arrival`, `changed_by`, `created_at`) VALUES
(1, 1, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 10:44:36'),
(2, 2, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 10:48:47'),
(3, 3, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 14:23:17'),
(4, 4, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 10:25:11'),
(5, 5, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 10:46:52'),
(6, 6, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 15:15:18'),
(7, 7, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-29 15:23:05'),
(8, 8, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-30 06:57:46'),
(9, 9, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-30 07:31:32'),
(10, 10, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-30 09:11:14'),
(11, 11, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-01 02:09:29'),
(12, 12, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-01 02:14:02'),
(13, 13, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-01 02:38:24'),
(14, 14, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-01 03:42:51'),
(15, 15, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 08:26:01'),
(16, 16, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 09:15:53'),
(17, 17, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 09:45:17'),
(18, 18, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 09:54:02'),
(19, 19, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 10:12:40'),
(20, 20, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 11:27:57'),
(21, 21, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 13:09:59'),
(22, 22, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 13:37:54'),
(23, 23, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 14:29:34'),
(24, 24, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 14:41:01'),
(25, 25, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 14:47:54'),
(26, 26, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 14:57:13'),
(27, 27, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 14:59:36'),
(28, 28, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 15:08:29'),
(29, 29, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-05 15:13:26'),
(32, 30, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-05 16:31:10'),
(33, 30, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-06 11:12:55'),
(34, 29, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-06 11:12:56'),
(35, 32, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 06:29:31'),
(36, 31, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 06:29:32'),
(37, 34, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 12:43:08'),
(38, 37, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 23:58:25'),
(39, 33, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 23:58:28'),
(40, 34, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 23:58:30'),
(41, 7, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 23:59:29'),
(42, 11, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-07 23:59:34'),
(43, 40, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:03:59'),
(44, 39, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:04:23'),
(45, 38, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:04:27'),
(46, 42, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:49:40'),
(47, 41, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:49:41'),
(48, 40, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:49:43'),
(49, 12, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:49:49'),
(50, 10, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:50:01'),
(51, 11, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:50:03'),
(52, 9, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:50:07'),
(53, 8, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:50:10'),
(54, 43, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:56:41'),
(55, 44, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 03:56:43'),
(56, 45, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 04:19:51'),
(57, 46, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-08 04:26:27'),
(58, 48, 'Pending', 'order_placed', 'JN345139456', 'Order berhasil dibuat oleh customer', 'Sistem Otomatis', NULL, NULL, '2025-12-12 04:55:42', 4, '2025-12-09 03:55:42'),
(59, 48, 'Processing', 'payment_confirmed', 'JN345139456', 'Pembayaran telah diverifikasi - Paket sudah dikemas dengan bubble wrap untuk keamanan ekstra', 'Admin Panel', NULL, NULL, '2025-12-12 04:55:42', 4, '2025-12-09 05:55:42'),
(60, 48, 'Processing', 'processing', 'JN345139456', 'Produk telah melalui quality check sebelum dikirim', 'Gudang Pusat, Jakarta Selatan', NULL, NULL, '2025-12-12 04:55:42', 4, '2025-12-09 21:55:42'),
(61, 48, 'Shipped', 'picked_up', 'JN345139456', 'Paket telah diserahkan ke ekspedisi dengan aman', 'JNE Jakarta Pusat Hub', 'Ahmad Ridwan', '0813-9876-5432', '2025-12-12 04:55:42', 4, '2025-12-10 05:55:42'),
(62, 48, 'Shipped', 'in_sorting', 'JN345139456', 'Paket sedang disortir untuk pengiriman antar kota', 'Jakarta Selatan Sorting Center', 'Ahmad Ridwan', '0813-9876-5432', '2025-12-12 04:55:42', 4, '2025-12-10 09:55:42'),
(63, 48, 'Shipped', 'in_transit', 'JN345139456', 'Paket sedang dalam perjalanan ke kota tujuan', 'Tol Cipularang (Dalam Perjalanan)', 'Ahmad Ridwan', '0813-9876-5432', '2025-12-12 04:55:42', 4, '2025-12-10 17:55:42'),
(64, 48, 'Shipped', 'arrived_destination', 'JN345139456', 'Paket telah tiba di kota tujuan - Siap untuk pengiriman', 'JNE Bandung Hub', 'Ahmad Ridwan', '0813-9876-5432', '2025-12-12 04:55:42', 4, '2025-12-11 05:55:42'),
(65, 48, 'Shipped', 'out_for_delivery', 'JN345139456', 'Kurir sedang dalam perjalanan menuju alamat Anda', 'Kurir Delivery - Menuju Alamat Customer', 'Ahmad Ridwan', '0813-9876-5432', '2025-12-12 04:55:42', 4, '2025-12-11 17:55:42'),
(66, 48, 'Delivered', 'delivered', 'JN345139456', 'Terima kasih telah berbelanja di RetroLoved!', 'Alamat Customer', 'Ahmad Ridwan', '0813-9876-5432', '2025-12-12 04:55:42', 4, '2025-12-11 21:55:42'),
(67, 48, 'Delivered', NULL, 'JN345139456', 'Halo', NULL, NULL, NULL, NULL, 4, '2025-12-09 03:59:39'),
(68, 48, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 04:00:06'),
(69, 49, 'Pending', 'order_placed', 'JN332061208', 'Order berhasil dibuat oleh customer', 'Sistem Otomatis', NULL, NULL, '2025-12-12 06:12:34', 4, '2025-12-09 05:12:34'),
(70, 49, 'Processing', 'payment_confirmed', 'JN332061208', 'Pembayaran telah diverifikasi - Paket sudah dikemas dengan bubble wrap untuk keamanan ekstra', 'Admin Panel', NULL, NULL, '2025-12-12 06:12:34', 4, '2025-12-09 07:12:34'),
(71, 49, 'Processing', 'processing', 'JN332061208', 'Produk telah melalui quality check sebelum dikirim', 'Gudang Pusat, Jakarta Selatan', NULL, NULL, '2025-12-12 06:12:34', 4, '2025-12-09 23:12:34'),
(72, 49, 'Shipped', 'picked_up', 'JN332061208', 'Paket telah diserahkan ke ekspedisi dengan aman', 'JNE Jakarta Pusat Hub', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 06:12:34', 4, '2025-12-10 07:12:34'),
(73, 49, 'Shipped', 'in_sorting', 'JN332061208', 'Paket sedang disortir untuk pengiriman antar kota', 'Jakarta Selatan Sorting Center', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 06:12:34', 4, '2025-12-10 11:12:34'),
(74, 49, 'Shipped', 'in_transit', 'JN332061208', 'Paket sedang dalam perjalanan ke kota tujuan', 'Tol Cipularang (Dalam Perjalanan)', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 06:12:34', 4, '2025-12-10 19:12:34'),
(75, 49, 'Shipped', 'arrived_destination', 'JN332061208', 'Paket telah tiba di kota tujuan - Siap untuk pengiriman', 'JNE Bandung Hub', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 06:12:34', 4, '2025-12-11 07:12:34'),
(76, 49, 'Shipped', 'out_for_delivery', 'JN332061208', 'Kurir sedang dalam perjalanan menuju alamat Anda', 'Kurir Delivery - Menuju Alamat Customer', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 06:12:34', 4, '2025-12-11 19:12:34'),
(77, 49, 'Delivered', 'delivered', 'JN332061208', 'Terima kasih telah berbelanja di RetroLoved!', 'Alamat Customer', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 06:12:34', 4, '2025-12-11 23:12:34'),
(78, 50, 'Pending', 'order_placed', 'AN733184145', 'Order berhasil dibuat oleh customer', 'Sistem Otomatis', NULL, NULL, '2025-12-13 12:39:57', 4, '2025-12-09 05:39:57'),
(79, 50, 'Processing', 'payment_confirmed', 'AN733184145', 'Pembayaran telah diverifikasi - Paket sudah dikemas dengan bubble wrap untuk keamanan ekstra', 'Admin Panel', NULL, NULL, '2025-12-13 12:39:57', 4, '2025-12-09 08:32:45'),
(80, 50, 'Processing', 'processing', 'AN733184145', 'Produk telah melalui quality check sebelum dikirim', 'Gudang Pusat, Jakarta Selatan', NULL, NULL, '2025-12-13 12:39:57', 4, '2025-12-09 20:03:57'),
(81, 50, 'Shipped', 'picked_up', 'AN733184145', 'Paket telah diserahkan ke ekspedisi dengan aman', 'JNE Jakarta Pusat Hub', 'Siti Rahayu', '0821-1122-3344', '2025-12-13 12:39:57', 4, '2025-12-10 05:39:57'),
(82, 50, 'Shipped', 'in_sorting', 'AN733184145', 'Paket sedang disortir untuk pengiriman antar kota', 'Jakarta Selatan Sorting Center', 'Siti Rahayu', '0821-1122-3344', '2025-12-13 12:39:57', 4, '2025-12-10 15:15:57'),
(83, 50, 'Shipped', 'in_transit', 'AN733184145', 'Paket sedang dalam perjalanan ke kota tujuan', 'Tol Cipularang (Dalam Perjalanan)', 'Siti Rahayu', '0821-1122-3344', '2025-12-13 12:39:57', 4, '2025-12-11 05:39:57'),
(84, 50, 'Shipped', 'arrived_destination', 'AN733184145', 'Paket telah tiba di kota tujuan - Siap untuk pengiriman', 'JNE Bandung Hub', 'Siti Rahayu', '0821-1122-3344', '2025-12-13 12:39:57', 4, '2025-12-12 00:51:57'),
(85, 50, 'Shipped', 'out_for_delivery', 'AN733184145', 'Kurir sedang dalam perjalanan menuju alamat Anda', 'Kurir Delivery - Menuju Alamat Customer', 'Siti Rahayu', '0821-1122-3344', '2025-12-13 12:39:57', 4, '2025-12-12 20:03:57'),
(86, 50, 'Delivered', 'delivered', 'AN733184145', 'Terima kasih telah berbelanja di RetroLoved!', 'Alamat Customer', 'Siti Rahayu', '0821-1122-3344', '2025-12-13 12:39:57', 4, '2025-12-13 05:39:57'),
(87, 50, 'Delivered', NULL, 'AN733184145', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 05:53:51'),
(88, 50, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 05:54:06'),
(89, 49, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 05:54:06'),
(90, 51, 'Pending', 'order_placed', 'SI250807365', 'Order berhasil dibuat oleh customer', 'Sistem Otomatis', NULL, NULL, '2025-12-12 12:55:41', 4, '2025-12-09 05:55:41'),
(91, 51, 'Processing', 'payment_confirmed', 'SI250807365', 'Pembayaran telah diverifikasi - Paket sudah dikemas dengan bubble wrap untuk keamanan ekstra', 'Admin Panel', NULL, NULL, '2025-12-12 12:55:41', 4, '2025-12-09 08:05:17'),
(92, 51, 'Processing', 'processing', 'SI250807365', 'Produk telah melalui quality check sebelum dikirim', 'Gudang Pusat, Jakarta Selatan', NULL, NULL, '2025-12-12 12:55:41', 4, '2025-12-09 16:43:41'),
(93, 51, 'Shipped', 'picked_up', 'SI250807365', 'Paket telah diserahkan ke ekspedisi dengan aman', 'JNE Jakarta Pusat Hub', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 12:55:41', 4, '2025-12-09 23:55:41'),
(94, 51, 'Shipped', 'in_sorting', 'SI250807365', 'Paket sedang disortir untuk pengiriman antar kota', 'Jakarta Selatan Sorting Center', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 12:55:41', 4, '2025-12-10 07:07:41'),
(95, 51, 'Shipped', 'in_transit', 'SI250807365', 'Paket sedang dalam perjalanan ke kota tujuan', 'Tol Cipularang (Dalam Perjalanan)', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 12:55:41', 4, '2025-12-10 17:55:41'),
(96, 51, 'Shipped', 'arrived_destination', 'SI250807365', 'Paket telah tiba di kota tujuan - Siap untuk pengiriman', 'JNE Bandung Hub', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 12:55:41', 4, '2025-12-11 08:19:41'),
(97, 51, 'Shipped', 'out_for_delivery', 'SI250807365', 'Kurir sedang dalam perjalanan menuju alamat Anda', 'Kurir Delivery - Menuju Alamat Customer', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 12:55:41', 4, '2025-12-11 22:43:41'),
(98, 51, 'Delivered', 'delivered', 'SI250807365', 'Terima kasih telah berbelanja di RetroLoved!', 'Alamat Customer', 'Dedi Kurniawan', '0857-6677-8899', '2025-12-12 12:55:41', 4, '2025-12-12 05:55:41'),
(99, 51, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:05:46'),
(100, 52, 'Pending', 'order_placed', 'SI265923911', 'Order berhasil dibuat oleh customer', 'Sistem Otomatis', NULL, NULL, '2025-12-12 13:05:30', 4, '2025-12-09 06:05:30'),
(101, 52, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:18:11'),
(102, 53, 'Pending', 'order_placed', 'JN390051701', 'Order berhasil dibuat oleh customer', 'Sistem Otomatis', NULL, NULL, '2025-12-14 13:18:34', 4, '2025-12-09 06:18:34'),
(103, 53, 'Processing', NULL, 'JN390051701', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:20:06'),
(104, 53, 'Shipped', NULL, 'JN390051701', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:21:27'),
(105, 53, 'Shipped', NULL, 'JN390051701', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:29:21'),
(106, 54, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:35:06'),
(107, 53, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:35:10'),
(108, 55, 'Pending', NULL, NULL, 'Payment confirmed and verified by admin', NULL, NULL, NULL, NULL, 4, '2025-12-09 06:35:18'),
(109, 55, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:35:30'),
(110, 55, 'Pending', NULL, NULL, 'Payment confirmed and verified by admin', NULL, NULL, NULL, NULL, 4, '2025-12-09 06:35:35'),
(111, 55, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 06:47:56'),
(112, 55, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 06:48:04'),
(113, 56, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 06:48:51'),
(114, 56, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 06:49:10'),
(115, 56, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:01:50'),
(116, 56, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:02:00'),
(117, 57, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 07:03:10'),
(118, 57, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:03:40'),
(119, 57, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:03:44'),
(120, 57, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:03:57'),
(121, 57, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:04:04'),
(122, 57, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:10:57'),
(123, 57, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:11:02'),
(124, 58, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 07:12:22'),
(125, 58, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:12:39'),
(126, 58, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:12:45'),
(127, 58, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:13:04'),
(128, 58, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:13:10'),
(129, 58, 'Cancelled', NULL, NULL, 'Payment proof rejected by admin. Order has been cancelled.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:13:19'),
(130, 59, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 07:14:08'),
(131, 59, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:14:25'),
(132, 60, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 07:20:39'),
(133, 59, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:20:47'),
(134, 60, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:20:50'),
(135, 60, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:28:09'),
(136, 61, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 07:28:34'),
(137, 61, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 07:28:42'),
(138, 61, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:28:56'),
(139, 61, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:29:04'),
(140, 61, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 07:32:47'),
(141, 61, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 08:13:22'),
(142, 62, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 08:13:59'),
(143, 62, 'Processing', 'processing', 'JNT25120915468979', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Indah Permata', '0877-5566-7788', '2025-12-16 15:18:47', 4, '2025-12-09 08:18:47'),
(144, 62, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 08:18:47'),
(145, 62, 'Shipped', NULL, 'JNT25120915468979', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 08:19:18'),
(146, 62, 'Delivered', NULL, 'JNT25120915468979', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 08:19:28'),
(147, 62, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 08:45:10'),
(148, 62, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 08:51:24'),
(149, 63, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 08:56:15'),
(150, 62, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 08:56:24'),
(151, 63, 'Processing', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 08:56:34'),
(152, 63, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 08:56:49'),
(153, 63, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 08:56:57'),
(154, 63, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 08:57:01'),
(155, 63, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:04:30'),
(156, 64, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:05:23'),
(157, 64, 'Processing', 'processing', 'JNE25120921111283', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Dedi Kurniawan', '0857-6677-8899', '2025-12-17 16:05:34', 4, '2025-12-09 09:05:34'),
(158, 64, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 09:05:34'),
(159, 64, 'Shipped', NULL, 'JNE25120921111283', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:06:00'),
(160, 64, 'Processing', NULL, 'JNE25120921111283', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:06:06'),
(161, 64, 'Delivered', NULL, 'JNE25120921111283', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:06:14'),
(162, 64, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:06:18'),
(163, 64, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:31:35'),
(164, 65, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:32:03'),
(165, 65, 'Processing', 'processing', 'JNE25120980662482', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rudi Hartono', '0822-4455-6677', '2025-12-17 16:32:11', 4, '2025-12-09 09:32:11'),
(166, 65, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 09:32:11'),
(167, 65, 'Shipped', NULL, 'JNE25120980662482', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:32:17'),
(168, 65, 'Delivered', NULL, 'JNE25120980662482', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:32:23'),
(169, 65, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:32:28'),
(170, 65, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:32:32'),
(171, 65, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:32:35'),
(172, 65, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:32:40'),
(173, 65, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:32:44'),
(174, 65, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:37:38'),
(175, 65, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:48:54'),
(176, 66, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:49:29'),
(177, 66, 'Processing', 'processing', 'JNT25120915691590', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Siti Rahayu', '0821-1122-3344', '2025-12-16 16:49:35', 4, '2025-12-09 09:49:35'),
(178, 66, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 09:49:35'),
(179, 66, 'Shipped', NULL, 'JNT25120915691590', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:49:46'),
(180, 66, 'Delivered', NULL, 'JNT25120915691590', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 09:49:52'),
(181, 66, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 09:49:55'),
(182, 66, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:00:47'),
(183, 67, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:01:29'),
(184, 67, 'Processing', 'processing', 'JNE25120955900739', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Dewi Lestari', '0856-3344-5566', '2025-12-17 17:01:42', 4, '2025-12-09 10:01:42'),
(185, 67, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 10:01:42'),
(186, 67, 'Shipped', NULL, 'JNE25120955900739', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:01:48'),
(187, 67, 'Delivered', NULL, 'JNE25120955900739', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:01:55'),
(188, 67, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:02:02'),
(189, 67, 'Delivered', NULL, 'JNE25120955900739', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:23:22'),
(190, 67, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:23:30'),
(191, 68, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:23:57'),
(192, 68, 'Processing', 'processing', 'JNE25120921553542', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rina Wati', '0878-9988-7766', '2025-12-15 17:24:10', 4, '2025-12-09 10:24:10'),
(193, 68, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 10:24:10'),
(194, 68, 'Shipped', NULL, 'JNE25120921553542', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:24:16'),
(195, 68, 'Delivered', NULL, 'JNE25120921553542', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:24:21'),
(196, 68, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:24:26'),
(197, 68, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:28:14'),
(198, 69, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:28:38'),
(199, 69, 'Processing', 'processing', 'JNT25120939040167', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rudi Hartono', '0822-4455-6677', '2025-12-16 17:28:49', 4, '2025-12-09 10:28:49'),
(200, 69, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 10:28:49'),
(201, 69, 'Processing', NULL, 'JNT25120939040167', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:28:52'),
(202, 69, 'Shipped', NULL, 'JNT25120939040167', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:29:02'),
(203, 69, 'Delivered', NULL, 'JNT25120939040167', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:29:08'),
(204, 69, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:29:11'),
(205, 62, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:32:49'),
(206, 63, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:32:51'),
(207, 64, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:32:52'),
(208, 65, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:32:55'),
(209, 69, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:33:04'),
(210, 68, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:33:04'),
(211, 67, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:33:04'),
(212, 66, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:33:04'),
(213, 70, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:33:47'),
(214, 70, 'Processing', 'processing', 'SIC25120975496252', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rudi Hartono', '0822-4455-6677', '2025-12-12 17:33:56', 4, '2025-12-09 10:33:56'),
(215, 70, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 10:33:56'),
(216, 70, 'Shipped', NULL, 'SIC25120975496252', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:34:05'),
(217, 70, 'Delivered', NULL, 'SIC25120975496252', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:34:15'),
(218, 70, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:34:18'),
(219, 70, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:39:59'),
(220, 71, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:40:37'),
(221, 71, 'Processing', 'processing', 'SIC25120974319388', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Ahmad Ridwan', '0813-9876-5432', '2025-12-11 17:40:46', 4, '2025-12-09 10:40:46'),
(222, 71, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 10:40:46'),
(223, 71, 'Shipped', NULL, 'SIC25120974319388', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:40:52'),
(224, 71, 'Delivered', NULL, 'SIC25120974319388', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:41:02'),
(225, 71, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:41:06'),
(226, 72, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:48:58'),
(227, 71, 'Delivered', NULL, 'SIC25120974319388', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:49:07'),
(228, 72, 'Processing', 'processing', 'JNE25120938028112', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rudi Hartono', '0822-4455-6677', '2025-12-11 17:49:16', 4, '2025-12-09 10:49:16'),
(229, 72, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 10:49:16'),
(230, 72, 'Shipped', NULL, 'JNE25120938028112', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:49:23'),
(231, 72, 'Delivered', NULL, 'JNE25120938028112', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 10:49:53'),
(232, 72, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:49:57'),
(233, 71, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 10:50:05'),
(234, 72, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 12:51:22'),
(235, 71, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 12:51:25'),
(236, 73, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 12:52:19'),
(237, 73, 'Processing', 'processing', 'JNE25120996100677', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Joko Widodo', '0838-6677-8899', '2025-12-15 19:52:33', 4, '2025-12-09 12:52:33'),
(238, 73, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 12:52:33'),
(239, 73, 'Shipped', NULL, 'JNE25120996100677', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 12:52:44'),
(240, 73, 'Delivered', NULL, 'JNE25120996100677', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 12:52:52'),
(241, 73, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 12:52:55'),
(242, 74, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 12:56:41'),
(243, 73, 'Delivered', NULL, 'JNE25120996100677', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 12:57:00'),
(244, 73, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer. Terima kasih telah berbelanja di RetroLoved!', NULL, NULL, NULL, NULL, 6, '2025-12-09 12:57:11'),
(245, 73, 'Delivered', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 12:57:23'),
(246, 73, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 13:28:54'),
(247, 74, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 13:28:56'),
(248, 75, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 13:53:00'),
(249, 75, 'Processing', 'processing', 'JNE25120992749781', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rudi Hartono', '0822-4455-6677', '2025-12-17 20:53:10', 4, '2025-12-09 13:53:10'),
(250, 75, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 13:53:10'),
(251, 75, 'Shipped', NULL, 'JNE25120992749781', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 13:53:27'),
(252, 75, 'Delivered', NULL, 'JNE25120992749781', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 13:53:36'),
(253, 75, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 13:53:39'),
(254, 75, 'Delivered', NULL, 'JNE25120992749781', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 13:55:13'),
(255, 75, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 13:55:50'),
(256, 75, 'Cancelled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:04:20'),
(257, 76, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:05:15'),
(258, 76, 'Processing', 'processing', 'JNE25120933850275', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Siti Rahayu', '0821-1122-3344', '2025-12-17 21:05:33', 4, '2025-12-09 14:05:33'),
(259, 76, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:05:33'),
(260, 76, 'Shipped', NULL, 'JNE25120933850275', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:05:43'),
(261, 76, 'Delivered', NULL, 'JNE25120933850275', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:05:49'),
(262, 76, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:05:53'),
(263, 76, 'Delivered', NULL, 'JNE25120933850275', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:06:01'),
(264, 77, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:10:44'),
(265, 77, 'Processing', 'processing', 'JNE25120989909445', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Agus Setiawan', '0819-2233-4455', '2025-12-15 21:11:10', 4, '2025-12-09 14:11:10'),
(266, 77, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:11:10'),
(267, 77, 'Shipped', NULL, 'JNE25120989909445', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:11:26'),
(268, 77, 'Delivered', NULL, 'JNE25120989909445', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:11:35'),
(269, 77, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:11:39'),
(270, 77, 'Delivered', NULL, 'JNE25120989909445', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:11:47'),
(271, 78, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:18:54'),
(272, 78, 'Processing', 'processing', 'JNT25120905375566', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rudi Hartono', '0822-4455-6677', '2025-12-16 21:19:03', 4, '2025-12-09 14:19:03'),
(273, 78, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:19:03'),
(274, 78, 'Shipped', NULL, 'JNT25120905375566', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:19:14'),
(275, 78, 'Delivered', NULL, 'JNT25120905375566', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:19:22'),
(276, 78, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:19:28'),
(277, 77, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:19:58'),
(278, 76, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:20:03'),
(279, 79, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:24:39'),
(280, 79, 'Processing', 'processing', 'SIC25120982220645', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rina Wati', '0878-9988-7766', '2025-12-11 21:24:51', 4, '2025-12-09 14:24:51'),
(281, 79, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:24:51'),
(282, 79, 'Shipped', 'in_transit', 'SIC25120982220645', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:24:58'),
(283, 79, 'Delivered', 'delivered', 'SIC25120982220645', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:25:06'),
(284, 79, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:25:09'),
(285, 80, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:26:52'),
(286, 80, 'Processing', 'processing', 'ANT25120925262117', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Ahmad Ridwan', '0813-9876-5432', '2025-12-15 21:26:58', 4, '2025-12-09 14:26:58'),
(287, 80, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:26:58'),
(288, 80, 'Shipped', 'in_transit', 'ANT25120925262117', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:27:07'),
(289, 80, 'Delivered', 'delivered', 'ANT25120925262117', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:27:14'),
(290, 80, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:27:48'),
(291, 81, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:29:26'),
(292, 81, 'Processing', 'processing', 'JNE25120951339710', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Budi Santoso', '0812-3456-7890', '2025-12-11 21:29:35', 4, '2025-12-09 14:29:35'),
(293, 81, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:29:35'),
(294, 81, 'Shipped', 'in_transit', 'JNE25120951339710', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:29:48'),
(295, 81, 'Delivered', 'delivered', 'JNE25120951339710', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:29:55'),
(296, 81, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:29:59'),
(297, 82, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:31:56'),
(298, 81, 'Delivered', 'delivered', 'JNE25120951339710', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:31:59'),
(299, 81, 'Delivered', 'delivered', 'JNE25120951339710', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:32:07'),
(300, 82, 'Processing', 'processing', 'JNE25120938927665', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Rudi Hartono', '0822-4455-6677', '2025-12-15 21:32:16', 4, '2025-12-09 14:32:16'),
(301, 82, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:32:16'),
(302, 82, 'Shipped', 'in_transit', 'JNE25120938927665', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:32:23'),
(303, 82, 'Delivered', 'delivered', 'JNE25120938927665', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:32:29'),
(304, 82, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:32:43'),
(305, 83, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:37:47'),
(306, 83, 'Processing', 'processing', 'JNE25120911975894', 'Tracking number dan kurir telah di-assign otomatis oleh sistem.', NULL, 'Indah Permata', '0877-5566-7788', '2025-12-11 21:37:57', 4, '2025-12-09 14:37:57'),
(307, 83, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:37:57'),
(308, 83, 'Shipped', 'in_transit', 'JNE25120911975894', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:38:10'),
(309, 83, 'Delivered', 'delivered', 'JNE25120911975894', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:38:19'),
(310, 83, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:38:23'),
(311, 81, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:39:01'),
(312, 84, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:52:26'),
(313, 84, 'Processing', 'processing', NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:52:36'),
(314, 84, 'Shipped', 'in_transit', NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:52:49'),
(315, 84, 'Delivered', 'delivered', NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:52:58'),
(316, 84, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:53:04'),
(317, 85, 'Pending', NULL, NULL, 'Customer uploaded payment proof. Waiting for admin confirmation.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:57:00'),
(318, 85, 'Processing', 'processing', 'JNT25120957031765', 'Tracking number telah di-generate otomatis. Kurir akan di-assign saat status Shipped.', NULL, NULL, NULL, '2025-12-16 21:57:07', 4, '2025-12-09 14:57:07'),
(319, 85, 'Processing', NULL, NULL, 'Payment confirmed and verified by admin. Order is now being processed.', NULL, NULL, NULL, NULL, 4, '2025-12-09 14:57:07'),
(320, 85, 'Shipped', 'in_transit', NULL, 'Kurir telah di-assign otomatis. Paket dalam perjalanan.', NULL, 'Joko Widodo', '0838-6677-8899', NULL, 4, '2025-12-09 14:57:17'),
(321, 85, 'Shipped', 'in_transit', 'JNT25120957031765', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:57:17'),
(322, 85, 'Delivered', 'delivered', 'JNT25120957031765', NULL, NULL, NULL, NULL, NULL, 4, '2025-12-09 14:57:24'),
(323, 85, 'Completed', 'completed', NULL, 'Pesanan dikonfirmasi telah diterima oleh customer.', NULL, NULL, NULL, NULL, 6, '2025-12-09 14:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(8, 8, 12, '', 80000.00, 1, 0.00),
(9, 9, 14, '', 2900000.00, 1, 0.00),
(10, 10, 15, '', 135000.00, 1, 0.00),
(11, 11, 23, '', 130000.00, 1, 0.00),
(12, 12, 23, '', 130000.00, 1, 0.00),
(13, 13, 23, '', 130000.00, 1, 0.00),
(14, 14, 22, '', 180000.00, 1, 0.00),
(15, 14, 23, '', 130000.00, 1, 0.00),
(16, 15, 22, '', 180000.00, 1, 0.00),
(17, 15, 20, '', 120000.00, 1, 0.00),
(18, 16, 21, '', 215000.00, 1, 0.00),
(19, 17, 21, '', 215000.00, 1, 0.00),
(20, 18, 21, '', 215000.00, 1, 0.00),
(21, 19, 21, '', 215000.00, 1, 0.00),
(22, 20, 22, '', 180000.00, 1, 0.00),
(23, 21, 22, '', 180000.00, 1, 0.00),
(24, 22, 15, '', 135000.00, 1, 0.00),
(25, 23, 21, '', 215000.00, 1, 0.00),
(26, 24, 20, '', 120000.00, 1, 0.00),
(27, 25, 21, '', 215000.00, 1, 0.00),
(28, 26, 20, '', 120000.00, 1, 0.00),
(29, 27, 22, '', 180000.00, 1, 0.00),
(30, 28, 21, '', 215000.00, 1, 0.00),
(31, 29, 22, '', 180000.00, 1, 0.00),
(32, 30, 16, '', 225000.00, 1, 0.00),
(33, 30, 12, '', 80000.00, 1, 0.00),
(34, 30, 23, '', 130000.00, 1, 0.00),
(35, 31, 21, '', 215000.00, 1, 0.00),
(36, 32, 21, '', 215000.00, 1, 0.00),
(37, 33, 22, '', 180000.00, 1, 0.00),
(38, 34, 15, '', 135000.00, 1, 0.00),
(39, 35, 23, '', 130000.00, 1, 0.00),
(40, 36, 15, '', 135000.00, 1, 0.00),
(41, 37, 20, '', 120000.00, 1, 0.00),
(42, 38, 23, '', 130000.00, 1, 0.00),
(43, 39, 21, '', 215000.00, 1, 0.00),
(44, 40, 21, '', 215000.00, 1, 0.00),
(45, 41, 21, '', 215000.00, 1, 0.00),
(46, 42, 22, '', 180000.00, 1, 0.00),
(47, 43, 22, '', 180000.00, 1, 0.00),
(48, 44, 21, '', 215000.00, 1, 0.00),
(49, 45, 20, '', 120000.00, 1, 0.00),
(50, 46, 23, '', 130000.00, 1, 0.00),
(51, 47, 18, '', 150000.00, 1, 0.00),
(52, 48, 22, '', 180000.00, 1, 0.00),
(53, 49, 22, '', 180000.00, 1, 0.00),
(54, 50, 23, '', 130000.00, 1, 0.00),
(55, 51, 22, '', 180000.00, 1, 0.00),
(56, 52, 20, '', 120000.00, 1, 0.00),
(57, 53, 22, '', 180000.00, 1, 0.00),
(58, 54, 22, '', 180000.00, 1, 0.00),
(59, 55, 22, '', 180000.00, 1, 0.00),
(60, 56, 21, '', 215000.00, 1, 0.00),
(61, 57, 19, '', 125000.00, 1, 0.00),
(62, 58, 16, '', 225000.00, 1, 0.00),
(63, 59, 20, '', 120000.00, 1, 0.00),
(64, 60, 21, '', 215000.00, 1, 0.00),
(65, 61, 22, '', 180000.00, 1, 0.00),
(66, 62, 23, '', 130000.00, 1, 0.00),
(67, 63, 21, '', 215000.00, 1, 0.00),
(68, 64, 18, '', 150000.00, 1, 0.00),
(69, 65, 22, '', 180000.00, 1, 0.00),
(70, 66, 23, '', 130000.00, 1, 0.00),
(71, 67, 21, '', 215000.00, 1, 0.00),
(72, 68, 21, '', 215000.00, 1, 0.00),
(73, 69, 22, '', 180000.00, 1, 0.00),
(74, 70, 21, '', 215000.00, 1, 0.00),
(75, 71, 22, '', 180000.00, 1, 0.00),
(76, 72, 14, '', 2900000.00, 1, 0.00),
(77, 73, 22, '', 180000.00, 1, 0.00),
(78, 74, 20, '', 120000.00, 1, 0.00),
(79, 75, 22, '', 180000.00, 1, 0.00),
(80, 76, 20, '', 120000.00, 1, 0.00),
(81, 77, 16, '', 225000.00, 1, 0.00),
(82, 78, 21, '', 215000.00, 1, 0.00),
(83, 79, 12, '', 80000.00, 1, 0.00),
(84, 80, 19, '', 125000.00, 1, 0.00),
(85, 81, 17, '', 130000.00, 1, 0.00),
(86, 82, 15, '', 135000.00, 1, 0.00),
(87, 83, 18, '', 150000.00, 1, 0.00),
(88, 84, 14, '', 2900000.00, 1, 0.00),
(89, 85, 23, '', 130000.00, 1, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `reset_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`reset_id`, `user_id`, `email`, `reset_code`, `expires_at`, `created_at`) VALUES
(11, 8, 'holingradluis04@gmail.com', '491237', '2025-12-09 07:50:15', '2025-12-09 00:45:15');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `condition_item` enum('Excellent','Very Good','Good','Fair') DEFAULT 'Good',
  `image_url` varchar(255) DEFAULT NULL,
  `image_url_2` varchar(255) DEFAULT NULL,
  `image_url_3` varchar(255) DEFAULT NULL,
  `image_url_4` varchar(255) DEFAULT NULL,
  `image_url_10` varchar(255) DEFAULT NULL,
  `image_url_9` varchar(255) DEFAULT NULL,
  `image_url_8` varchar(255) DEFAULT NULL,
  `image_url_7` varchar(255) DEFAULT NULL,
  `image_url_6` varchar(255) DEFAULT NULL,
  `image_url_5` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_sold` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `original_price`, `size`, `condition_item`, `image_url`, `image_url_2`, `image_url_3`, `image_url_4`, `image_url_10`, `image_url_9`, `image_url_8`, `image_url_7`, `image_url_6`, `image_url_5`, `category`, `is_active`, `is_featured`, `is_sold`, `created_at`, `updated_at`) VALUES
(12, 'Tate X Signature | Mens Longsleeve Original Cream', '● Size : M (P 65 cm x L 55 cm)\r\n● Very Good Condition\r\n● Made in Korean\r\n● Minus : Noda kecil namun tidak keliatan.\r\nEnak bgt kalau dipake dan cocok bgt buat ngedate sm pacar.\r\n● Sudah dilaundry\r\n● Ready, langsung saja co', 80000.00, 120000.00, NULL, 'Very Good', 'product_1764404649.webp', 'product_1764404649_2.webp', 'product_1764404649_3.webp', 'product_1764404649_4.webp', NULL, NULL, NULL, NULL, NULL, NULL, 'Sweater', 1, 1, 1, '2025-11-29 08:24:09', '2025-12-09 14:24:32'),
(14, 'Ricch & Repeat Reworked ZipUp Hoodie', 'Each patch pulled from past drops and reborn into a new identity. This hand-altered zip-up features distressed detailing, splattered accents, and layered appliqués stitched across a sun-washed charcoal base. Every hoodie carries different cutouts and placement, making no two alike\r\n\r\nTrue to size fit\r\n\r\nVintage-washed charcoal heavyweight cotton\r\n\r\nMixed Ricchezza appliqués repurposed from archived tees\r\n\r\nReverse-appliqué distressing and hand-done splatter finish\r\n\r\nDouble kangaroo pockets\r\n\r\nFull-zip closure with tonal metal hardware\r\n\r\nOversized relaxed fit\r\n\r\nEach piece is uniquely reworked by hand\r\n\r\nRICCHEZZA inside tag detailing\r\n\r\n100% Cotton\r\n\r\nDry Clean Only', 2900000.00, NULL, NULL, 'Excellent', 'product_1764487847_1.png', 'product_1764487847_2.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sweater', 1, 0, 1, '2025-11-30 07:30:47', '2025-12-09 14:52:19'),
(15, 'Crop Boxy Shirt / Work / Custom', 'Size M boxy fit\r\nP : 62\r\nL : 51\r\n', 135000.00, NULL, NULL, 'Very Good', 'product_1764493753_1.webp', 'product_1764493753_2.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Shirt', 1, 0, 1, '2025-11-30 09:09:13', '2025-12-09 14:31:50'),
(16, 'Carhartt Vintage Y2K Casual', 'Size L 14-16 Setara Dewasa S Lokal\r\n•Panjang 69 Cm\r\n•Lebar Dada 48 Cm\r\n•Panjang Lengan Pundak Ke Ujung Tangan 54 Cm\r\n•Kondisi Barang Used 99% LIKE NEW Muluss 🔥', 225000.00, NULL, NULL, 'Excellent', 'product_1764503073_1.webp', 'product_1764503074_2.webp', 'product_1764503074_3.webp', 'product_1764503074_4.webp', NULL, NULL, NULL, NULL, 'product_1764503074_6.webp', 'product_1764503074_5.webp', 'Shirt', 1, 1, 1, '2025-11-30 11:44:34', '2025-12-09 14:10:33'),
(17, 'Grunge Indie Casual Shirt Tan Black', '❤️‍🔥Vintage Gotcha Surf Japanese Clothing Brand Checked Flannel Double Pocket Long Shirt\r\n❤️‍🔥Size XXLARGE\r\n❤️‍🔥Good (Excellent Condition) 9/10 likeee newww bangettt anggap saja kamu beli baru freeen\r\n❤️‍🔥Made in China 🇨🇳', 130000.00, NULL, NULL, 'Excellent', 'product_1764503885.webp', 'product_1764503885_2.webp', 'product_1764503885_3.webp', 'product_1764503885_4.webp', NULL, NULL, NULL, NULL, 'product_1764503293_6.webp', 'product_1764503293_5.webp', 'Shirt', 1, 1, 1, '2025-11-30 11:48:13', '2025-12-09 14:29:21'),
(18, 'Grateful Dead 2008 Band Longsleeve Tee', 'Grateful Dead 2008 Band Longsleeve Tee\r\nTag official Grateful Dead\r\nDetail silahkan slide foto\r\n.\r\nPxl:64x45 cm\r\nSize: M\r\nDouble stitch\r\n\r\nKondisi :\r\n\r\n- Good Condition\r\n', 150000.00, NULL, NULL, 'Good', 'product_1764503410_1.webp', 'product_1764503410_2.webp', 'product_1764503411_3.webp', 'product_1764503411_4.webp', NULL, NULL, NULL, NULL, NULL, NULL, 'T-Shirt', 1, 0, 1, '2025-11-30 11:50:11', '2025-12-09 14:37:37'),
(19, 'Y2K Grafity Vintage Tee ', 'Size fit on Medium\r\nPanjang : 68 cm\r\nLebar : 52 cm\r\n-\r\nGood condition\r\nMinus bercak noda samar dibelakang\r\n-\r\nBarang sudah dilaundry dan siap pakai\r\nHanya tersedia 1 pcs | jangan sampai kelewatan\r\n\r\n#goth #afflication #skater #punk #cyber #emo\r\nemo grunge gothic skate punk JNCO southpole mma elite tapout buckaroo xzavier vintage tshirt kaos baju skate boxy sk8 baggy skena streetwear grafitty SOHK\r\n \r\n\r\n', 125000.00, NULL, NULL, 'Good', 'product_1764503819.webp', 'product_1764503819_2.webp', 'product_1764503819_3.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'T-Shirt', 1, 0, 1, '2025-11-30 11:51:37', '2025-12-09 14:26:41'),
(20, 'Crv Boxy Crop Shirt Dark Yellow ', 'Crv Boxy Crop Shirt\r\n-\r\nSize fit Medium\r\nPanjang : 62 cm\r\nLebar : 56 cm\r\nVery good condition\r\n-\r\nBarang sudah dilaundry dan siap pakai\r\nReady to order selama postingan masih terbit\r\n\r\nnot streetwear skena Y2K kalcer boxy pinterest baggy jeans jort vintage plaid open collar veterano flanell formal plaid casual skater stussy dickies carhartt work shirt', 120000.00, NULL, NULL, 'Excellent', 'product_1764503750.webp', 'product_1764503769_2.webp', 'product_1764503769_3.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Shirt', 1, 1, 1, '2025-11-30 11:52:47', '2025-12-09 14:05:09'),
(21, 'Presen Knitwear Pattern Half Button Grandpa', 'Presen knitwear vintage pattern half button grandpa sweater\r\nSize L boxy (panjang: 50 cm - lebar: 57 cm)\r\nJadikan patokan detail ukuran diatas (toleransi ukuran 2-4cm)\r\n\r\nKondisi: boxy croped, vintage look, great pattern & colorway, good condition (9/10)', 215000.00, NULL, NULL, 'Excellent', 'product_1764503706.webp', 'product_1764503670_2.webp', 'product_1764503670_3.webp', 'product_1764503706_4.webp', NULL, NULL, NULL, NULL, NULL, NULL, 'Sweater', 1, 1, 1, '2025-11-30 11:54:30', '2025-12-09 14:18:45'),
(22, 'Vintage Sweater Woman Multicolor', '141861\r\nUnknown Knitwear Made in Italy\r\nPanjang : 65cm\r\nLingkar dada : 120cm', 180000.00, NULL, NULL, 'Excellent', 'product_1764504151_1.webp', 'product_1764504151_2.webp', 'product_1764504151_3.webp', 'product_1764504152_4.webp', NULL, NULL, NULL, NULL, NULL, NULL, 'Sweater', 1, 1, 1, '2025-11-30 12:02:32', '2025-12-09 13:52:51'),
(23, 'Vintage Y2K Sweater Woman Multicolor ', 'Funky Floral Turtle Neck Wool Crop Sweater\r\n\r\n135k\r\n\r\nsize Xxs-Xs\r\nld\r\np\r\ncondition 98%\r\n \r\n\r\n', 130000.00, 122000.00, NULL, 'Excellent', 'product_1764654160.webp', 'product_1764654160_2.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sweater', 1, 1, 1, '2025-11-30 12:04:05', '2025-12-09 14:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `full_address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`address_id`, `user_id`, `recipient_name`, `phone`, `full_address`, `city`, `province`, `postal_code`, `latitude`, `longitude`, `is_default`, `created_at`) VALUES
(1, 6, 'Andre Abdilillah Ahwien', '081336019251', 'Jln. Petemon I No. 81 RT. 08/RW. 09', 'Surabaya', 'Jawa Timur', '60252', 0.00000000, 0.00000000, 1, '2025-11-30 06:19:50'),
(2, 5, 'Gilang Ramadhan', '08123456789', 'Jln. Petemon I ', 'Surabaya', 'Jawa Timur', '60252', 0.00000000, 0.00000000, 1, '2025-11-30 09:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_couriers`
--

CREATE TABLE `shipping_couriers` (
  `courier_id` int(11) NOT NULL,
  `courier_code` varchar(20) NOT NULL,
  `courier_name` varchar(100) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_couriers`
--

INSERT INTO `shipping_couriers` (`courier_id`, `courier_code`, `courier_name`, `logo_url`, `is_active`, `created_at`) VALUES
(1, 'jne', 'JNE Express', NULL, 1, '2025-12-09 03:20:49'),
(2, 'jnt', 'J&T Express', NULL, 1, '2025-12-09 03:20:49'),
(3, 'sicepat', 'SiCepat Express', NULL, 1, '2025-12-09 03:20:49'),
(4, 'anteraja', 'AnterAja', NULL, 1, '2025-12-09 03:20:49'),
(5, 'pickup', 'Ambil Sendiri', NULL, 1, '2025-12-09 03:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_services`
--

CREATE TABLE `shipping_services` (
  `service_id` int(11) NOT NULL,
  `courier_id` int(11) NOT NULL,
  `service_code` varchar(50) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `base_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estimated_days_min` int(11) DEFAULT 1,
  `estimated_days_max` int(11) DEFAULT 3,
  `is_active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_services`
--

INSERT INTO `shipping_services` (`service_id`, `courier_id`, `service_code`, `service_name`, `description`, `base_cost`, `estimated_days_min`, `estimated_days_max`, `is_active`, `display_order`, `created_at`) VALUES
(1, 1, 'REG', 'JNE Regular', 'Layanan reguler dengan harga ekonomis', 15000.00, 3, 4, 1, 2, '2025-12-09 03:20:49'),
(2, 1, 'YES', 'JNE YES', 'Yakin Esok Sampai - Garansi pengiriman cepat', 25000.00, 1, 2, 1, 1, '2025-12-09 03:20:49'),
(3, 1, 'OKE', 'JNE OKE', 'Ongkos Kirim Ekonomis untuk pengiriman hemat', 12000.00, 4, 6, 1, 3, '2025-12-09 03:20:49'),
(4, 2, 'EZ', 'J&T Express Economy', 'Layanan ekonomis dengan harga terjangkau', 12000.00, 3, 5, 1, 4, '2025-12-09 03:20:49'),
(5, 2, 'REG', 'J&T Regular', 'Layanan standar J&T Express', 15000.00, 2, 4, 1, 5, '2025-12-09 03:20:49'),
(6, 3, 'REG', 'SiCepat REG', 'Regular Service dengan tracking real-time', 15000.00, 2, 3, 1, 6, '2025-12-09 03:20:49'),
(7, 3, 'HALU', 'SiCepat HALU', 'Hari itu sampai - Layanan same day', 18000.00, 1, 2, 1, 7, '2025-12-09 03:20:49'),
(8, 4, 'REG', 'AnterAja Regular', 'Layanan reguler AnterAja', 14000.00, 2, 4, 1, 8, '2025-12-09 03:20:49'),
(9, 4, 'NEXT', 'AnterAja Next Day', 'Pengiriman keesokan hari', 20000.00, 1, 2, 1, 9, '2025-12-09 03:20:49'),
(10, 5, 'STORE', 'Ambil di Toko', 'Gratis ongkir - Ambil langsung di toko kami', 0.00, 0, 0, 1, 10, '2025-12-09 03:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `tracking_statuses`
--

CREATE TABLE `tracking_statuses` (
  `status_id` int(11) NOT NULL,
  `status_code` varchar(50) NOT NULL,
  `status_name` varchar(100) NOT NULL,
  `status_name_id` varchar(100) NOT NULL,
  `icon_svg` text DEFAULT NULL,
  `color` varchar(20) DEFAULT '#6B7280',
  `step_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tracking_statuses`
--

INSERT INTO `tracking_statuses` (`status_id`, `status_code`, `status_name`, `status_name_id`, `icon_svg`, `color`, `step_order`, `is_active`, `description`, `created_at`) VALUES
(1, 'order_placed', 'Order Placed', 'Pesanan Dibuat', NULL, '#6B7280', 1, 1, 'Order successfully created by customer', '2025-12-09 03:20:49'),
(2, 'payment_confirmed', 'Payment Confirmed', 'Pembayaran Dikonfirmasi', NULL, '#10B981', 2, 1, 'Payment has been verified by admin', '2025-12-09 03:20:49'),
(3, 'processing', 'Being Packed', 'Pesanan Dikemas', NULL, '#F59E0B', 3, 1, 'Product is being packed in warehouse', '2025-12-09 03:20:49'),
(4, 'picked_up', 'Picked Up by Courier', 'Diserahkan ke Kurir', NULL, '#3B82F6', 4, 1, 'Package has been picked up by courier', '2025-12-09 03:20:49'),
(5, 'in_sorting', 'At Sorting Center', 'Di Sorting Center', NULL, '#8B5CF6', 5, 1, 'Package is at courier sorting center', '2025-12-09 03:20:49'),
(6, 'in_transit', 'In Transit', 'Dalam Perjalanan', NULL, '#06B6D4', 6, 1, 'Package is on the way to destination city', '2025-12-09 03:20:49'),
(7, 'arrived_destination', 'Arrived at Destination', 'Tiba di Kota Tujuan', NULL, '#14B8A6', 7, 1, 'Package has arrived at destination hub', '2025-12-09 03:20:49'),
(8, 'out_for_delivery', 'Out for Delivery', 'Dikirim ke Alamat', NULL, '#6366F1', 8, 1, 'Courier is delivering to customer address', '2025-12-09 03:20:49'),
(9, 'delivered', 'Delivered', 'Pesanan Diterima', NULL, '#22C55E', 9, 1, 'Package successfully delivered to customer', '2025-12-09 03:20:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `created_at`, `updated_at`, `profile_picture`, `birth_date`, `email_verified`, `is_active`, `verified_at`) VALUES
(4, 'admin', 'admin@retroloved.com', '202cb962ac59075b964b07152d234b70', 'Admin RetroLoved', '081234567890', 'Surabaya, Indonesia', 'admin', '2025-10-28 10:20:20', '2025-10-29 09:34:49', NULL, NULL, 0, 1, NULL),
(5, 'gilang', 'gilang@gmail.com', '202cb962ac59075b964b07152d234b70', 'Gilang Ramadhan', '081234567891', 'Jakarta, Indonesia', 'customer', '2025-10-28 10:20:20', '2025-10-28 10:20:20', NULL, NULL, 0, 1, NULL),
(6, 'andre', 'andre@gmail.com', '202cb962ac59075b964b07152d234b70', 'Andre Abdilillah Ahwien', '081234567892', 'Bandung, Indonesia', 'customer', '2025-10-28 10:20:20', '2025-12-08 05:03:57', NULL, '2008-07-27', 0, 1, NULL),
(7, 'omenjai123', 'omen@gmail.com', '6cc52b687c9ca0ce3cdf367008808eb2', 'Omen', NULL, NULL, 'customer', '2025-12-01 03:59:10', '2025-12-09 02:18:49', NULL, NULL, 0, 0, NULL),
(8, 'devonmaul21', 'holingradluis04@gmail.com', 'c33687a93aa8e8666eadd8b29e8253a3', 'Devon Yohannes', NULL, NULL, 'customer', '2025-12-08 11:03:55', '2025-12-09 02:18:59', NULL, NULL, 0, 0, NULL),
(9, 'gilangomatantenk', 'gilangrmdhnn189@gmail.com', '8ae920b63829a97a265dc062079b835e', 'Gilang Ramadhan', NULL, NULL, 'customer', '2025-12-09 01:38:19', '2025-12-09 02:19:03', NULL, NULL, 1, 0, '2025-12-09 01:38:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_page_visits`
--
ALTER TABLE `admin_page_visits`
  ADD PRIMARY KEY (`visit_id`),
  ADD UNIQUE KEY `unique_user_page` (`user_id`,`page_name`),
  ADD KEY `idx_user_page` (`user_id`,`page_name`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `contact_support`
--
ALTER TABLE `contact_support`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `contact_support_replies`
--
ALTER TABLE `contact_support_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_support_id` (`support_id`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_sent_at` (`sent_at`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`verification_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_code` (`verification_code`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_user_notifications` (`user_id`,`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_orders_status_detail` (`current_status_detail`),
  ADD KEY `idx_orders_shipping_service` (`shipping_service_id`),
  ADD KEY `idx_orders_estimated_delivery` (`estimated_delivery_date`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_history_status_detail` (`status_detail`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_is_sold` (`is_sold`),
  ADD KEY `idx_active_sold` (`is_active`,`is_sold`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_is_featured` (`is_featured`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_user_addresses` (`user_id`),
  ADD KEY `idx_default_address` (`user_id`,`is_default`);

--
-- Indexes for table `shipping_couriers`
--
ALTER TABLE `shipping_couriers`
  ADD PRIMARY KEY (`courier_id`),
  ADD UNIQUE KEY `courier_code` (`courier_code`),
  ADD KEY `idx_courier_code` (`courier_code`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `shipping_services`
--
ALTER TABLE `shipping_services`
  ADD PRIMARY KEY (`service_id`),
  ADD UNIQUE KEY `unique_service` (`courier_id`,`service_code`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `tracking_statuses`
--
ALTER TABLE `tracking_statuses`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `status_code` (`status_code`),
  ADD KEY `idx_step_order` (`step_order`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_page_visits`
--
ALTER TABLE `admin_page_visits`
  MODIFY `visit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `contact_support`
--
ALTER TABLE `contact_support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `contact_support_replies`
--
ALTER TABLE `contact_support_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping_couriers`
--
ALTER TABLE `shipping_couriers`
  MODIFY `courier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `shipping_services`
--
ALTER TABLE `shipping_services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tracking_statuses`
--
ALTER TABLE `tracking_statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_shipping_service` FOREIGN KEY (`shipping_service_id`) REFERENCES `shipping_services` (`service_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `shipping_services`
--
ALTER TABLE `shipping_services`
  ADD CONSTRAINT `shipping_services_ibfk_1` FOREIGN KEY (`courier_id`) REFERENCES `shipping_couriers` (`courier_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
