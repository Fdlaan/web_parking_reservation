-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 26, 2026 at 05:53 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parking_db`
--
CREATE DATABASE IF NOT EXISTS `parking_db` DEFAULT CHARACTER SET utf8mb4;
USE `parking_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `floor_code` varchar(5) NOT NULL,
  `sort_order` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `invoice_number` varchar(30) NOT NULL,
  `reservation_id` int NOT NULL,
  `invoice_date` datetime NOT NULL,
  `pay_before` datetime NOT NULL,
  `payment_method` enum('QRIS') NOT NULL DEFAULT 'QRIS',
  `parking_fee` decimal(12,2) NOT NULL,
  `admin_fee` decimal(12,2) NOT NULL,
  `total` decimal(12,2) GENERATED ALWAYS AS ((`parking_fee` + `admin_fee`)) STORED,
  `payment_status` enum('UNPAID','PAID','EXPIRED','REFUNDED') NOT NULL DEFAULT 'UNPAID',
  `qris_payload` text,
  `gateway_ref` varchar(100) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parking_locations`
--

CREATE TABLE `parking_locations` (
  `id` int NOT NULL,
  `mall_code` varchar(10) NOT NULL,
  `name` varchar(150) NOT NULL,
  `region` varchar(100) NOT NULL,
  `address` varchar(500) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parking_rates`
--

CREATE TABLE `parking_rates` (
  `id` int NOT NULL,
  `location_id` int NOT NULL,
  `parking_type` enum('REGULER','VALET') NOT NULL,
  `price_per_slot` decimal(12,2) NOT NULL,
  `admin_fee` decimal(12,2) NOT NULL DEFAULT '2500.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parking_slots`
--

CREATE TABLE `parking_slots` (
  `id` int NOT NULL,
  `floor_id` int NOT NULL,
  `slot_code` varchar(10) NOT NULL,
  `parking_type` enum('REGULER','VALET') NOT NULL DEFAULT 'REGULER',
  `area_block` varchar(10) DEFAULT NULL,
  `pos_row` smallint DEFAULT NULL,
  `pos_col` smallint DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `slot_id` int NOT NULL,
  `vehicle_id` int DEFAULT NULL,
  `plate_number` varchar(12) NOT NULL,
  `parking_type` enum('REGULER','VALET') NOT NULL,
  `reserved_at` datetime NOT NULL,
  `status` enum('COMPLETED','CANCELLED','EXPIRED','PAID','UNPAID') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'UNPAID',
  `cancelled_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_slot_key` int GENERATED ALWAYS AS (if((`status` in ('UNPAID','PAID')),`slot_id`,NULL)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_locations`
--

CREATE TABLE `user_locations` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `address` varchar(500) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `plate_number` varchar(12) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_loc_floor` (`location_id`,`floor_code`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD UNIQUE KEY `reservation_id` (`reservation_id`),
  ADD KEY `idx_inv_status` (`payment_status`,`pay_before`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_op_location` (`location_id`);

--
-- Indexes for table `parking_locations`
--
ALTER TABLE `parking_locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mall_code` (`mall_code`),
  ADD KEY `idx_loc_region` (`region`),
  ADD KEY `idx_loc_geo` (`latitude`,`longitude`);

--
-- Indexes for table `parking_rates`
--
ALTER TABLE `parking_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_loc_type` (`location_id`,`parking_type`);

--
-- Indexes for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_floor_slot` (`floor_id`,`slot_code`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pr_user` (`user_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_slot_active` (`active_slot_key`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `idx_res_user_date` (`user_id`,`reserved_at` DESC),
  ADD KEY `idx_res_slot_date` (`slot_id`,`reserved_at`);

--
-- Indexes for table `user_locations`
--
ALTER TABLE `user_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_plate_number` (`plate_number`),
  ADD KEY `idx_vehicle_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operators`
--
ALTER TABLE `operators`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parking_locations`
--
ALTER TABLE `parking_locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parking_rates`
--
ALTER TABLE `parking_rates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parking_slots`
--
ALTER TABLE `parking_slots`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_locations`
--
ALTER TABLE `user_locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `floors`
--
ALTER TABLE `floors`
  ADD CONSTRAINT `floors_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `parking_locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`);

--
-- Constraints for table `operators`
--
ALTER TABLE `operators`
  ADD CONSTRAINT `operators_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `parking_locations` (`id`);

--
-- Constraints for table `parking_rates`
--
ALTER TABLE `parking_rates`
  ADD CONSTRAINT `parking_rates_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `parking_locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD CONSTRAINT `parking_slots_ibfk_1` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`slot_id`) REFERENCES `parking_slots` (`id`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Seed data (replace the staff passwords after the first login; both are `password`)
--
INSERT INTO `admins` (`name`, `email`, `password_hash`) VALUES
('Admin', 'admin@parkeasy.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO `parking_locations` (`id`, `mall_code`, `name`, `region`, `address`, `latitude`, `longitude`, `image_url`) VALUES
(1, 'GI', 'Grand Indonesia Mall', 'Jakarta Pusat', 'Jalan M.H. Thamrin No.1, RT.1/RW.5, Kelurahan Menteng, Kecamatan Menteng, dan Kelurahan Kebon Melati, Kecamatan Tanah Abang, Jakarta Pusat 10310', -6.195368, 106.820418, 'mall-image/grand-indonesia-mall.jpg');

INSERT INTO `operators` (`location_id`, `name`, `email`, `password_hash`) VALUES
(1, 'Grand Indonesia Operator', 'operator@parkeasy.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO `parking_rates` (`location_id`, `parking_type`, `price_per_slot`, `admin_fee`) VALUES
(1, 'REGULER', 15000, 2500),
(1, 'VALET', 50000, 2500);

INSERT INTO `floors` (`id`, `location_id`, `floor_code`, `sort_order`) VALUES
(1, 1, 'LG', 1), (2, 1, 'GF', 2), (3, 1, '1', 3), (4, 1, '2', 4);

INSERT INTO `parking_slots` (`floor_id`, `slot_code`, `parking_type`, `area_block`) VALUES
(1, 'LG-A01', 'REGULER', 'A'),
(1, 'LG-A02', 'REGULER', 'A'),
(1, 'LG-A03', 'REGULER', 'A'),
(1, 'LG-A04', 'REGULER', 'A'),
(1, 'LG-A05', 'REGULER', 'A'),
(1, 'LG-A06', 'REGULER', 'A'),
(1, 'LG-B01', 'REGULER', 'B'),
(1, 'LG-B02', 'REGULER', 'B'),
(1, 'LG-B03', 'REGULER', 'B'),
(1, 'LG-B04', 'REGULER', 'B'),
(1, 'LG-B05', 'REGULER', 'B'),
(1, 'LG-B06', 'REGULER', 'B'),
(1, 'LG-C01', 'REGULER', 'C'),
(1, 'LG-C02', 'REGULER', 'C'),
(1, 'LG-C03', 'REGULER', 'C'),
(1, 'LG-C04', 'REGULER', 'C'),
(1, 'LG-C05', 'REGULER', 'C'),
(1, 'LG-C06', 'REGULER', 'C'),
(1, 'LG-D01', 'REGULER', 'D'),
(1, 'LG-D02', 'REGULER', 'D'),
(1, 'LG-D03', 'REGULER', 'D'),
(1, 'LG-D04', 'REGULER', 'D'),
(1, 'LG-D05', 'REGULER', 'D'),
(1, 'LG-D06', 'REGULER', 'D'),
(1, 'LG-E01', 'VALET', 'E'),
(1, 'LG-E02', 'VALET', 'E'),
(1, 'LG-E03', 'VALET', 'E'),
(1, 'LG-E04', 'VALET', 'E'),
(1, 'LG-E05', 'VALET', 'E'),
(1, 'LG-E06', 'VALET', 'E'),
(1, 'LG-F01', 'VALET', 'F'),
(1, 'LG-F02', 'VALET', 'F'),
(1, 'LG-F03', 'VALET', 'F'),
(1, 'LG-F04', 'VALET', 'F'),
(1, 'LG-F05', 'VALET', 'F'),
(1, 'LG-F06', 'VALET', 'F'),
(2, 'GF-A01', 'REGULER', 'A'),
(2, 'GF-A02', 'REGULER', 'A'),
(2, 'GF-A03', 'REGULER', 'A'),
(2, 'GF-A04', 'REGULER', 'A'),
(2, 'GF-B01', 'REGULER', 'B'),
(3, '1-A01', 'REGULER', 'A'),
(3, '1-A02', 'REGULER', 'A'),
(3, '1-A03', 'REGULER', 'A'),
(3, '1-A04', 'REGULER', 'A'),
(3, '1-A05', 'REGULER', 'A'),
(3, '1-A06', 'REGULER', 'A'),
(4, '2-A01', 'REGULER', 'A'),
(4, '2-A02', 'REGULER', 'A'),
(4, '2-A03', 'REGULER', 'A'),
(4, '2-A04', 'REGULER', 'A'),
(4, '2-A05', 'REGULER', 'A'),
(4, '2-A06', 'REGULER', 'A');

INSERT INTO `user_locations` (`id`, `name`, `address`, `latitude`, `longitude`) VALUES
(1, 'Kampus UI Depok', 'Pondok Cina, Depok, Jawa Barat', -6.3644, 106.8272),
(2, 'Margonda', 'Jl. Margonda Raya, Depok, Jawa Barat', -6.3720, 106.8340),
(3, 'Pancoran Mas', 'Pancoran Mas, Depok, Jawa Barat', -6.4020, 106.8370),
(4, 'Gambir', 'Gambir, Jakarta Pusat', -6.1754, 106.8272),
(5, 'Thamrin', 'Jl. M.H. Thamrin, Jakarta Pusat', -6.1930, 106.8230),
(6, 'Bundaran HI', 'Menteng, Jakarta Pusat', -6.1944, 106.8230),
(7, 'Senen', 'Senen, Jakarta Pusat', -6.1765, 106.8417),
(8, 'Cempaka Putih', 'Cempaka Putih, Jakarta Pusat', -6.1700, 106.8700),
(9, 'Tanjung Duren', 'Tanjung Duren, Jakarta Barat', -6.1767, 106.7900),
(10, 'Grogol Petamburan', 'Grogol Petamburan, Jakarta Barat', -6.1785, 106.7920),
(11, 'Kembangan', 'Kembangan, Jakarta Barat', -6.1870, 106.7380),
(12, 'Pluit', 'Penjaringan, Jakarta Utara', -6.1200, 106.7900),
(13, 'Daan Mogot', 'Kalideres, Jakarta Barat', -6.1550, 106.7040),
(14, 'Blok M', 'Kebayoran Baru, Jakarta Selatan', -6.2441, 106.7996),
(15, 'Pondok Indah', 'Pondok Indah, Jakarta Selatan', -6.2654, 106.7838),
(16, 'Gandaria', 'Kebayoran Lama, Jakarta Selatan', -6.2440, 106.7836),
(17, 'Menteng Dalam', 'Tebet, Jakarta Selatan', -6.2250, 106.8410),
(18, 'SCBD', 'Sudirman, Jakarta Selatan', -6.2255, 106.8097),
(19, 'Kemang', 'Kemang, Jakarta Selatan', -6.2600, 106.8130),
(20, 'Fatmawati', 'Cilandak, Jakarta Selatan', -6.2910, 106.7970),
(21, 'Cakung', 'Cakung, Jakarta Timur', -6.1750, 106.9500),
(22, 'Rawamangun', 'Rawamangun, Jakarta Timur', -6.1920, 106.8820),
(23, 'Jatinegara', 'Jatinegara, Jakarta Timur', -6.2240, 106.8780),
(24, 'Cililitan', 'Kramat Jati, Jakarta Timur', -6.2600, 106.8650),
(25, 'Pinang Ranti', 'Makasar, Jakarta Timur', -6.2910, 106.8800);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
