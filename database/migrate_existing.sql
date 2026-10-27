-- Run once on an EXISTING parking_db created from the previous parking_db.sql.
-- (Fresh install? Just import parking_db.sql instead.)
USE `parking_db`;

-- 1. one active (UNPAID/PAID) reservation per slot
ALTER TABLE `reservations` DROP INDEX `uq_slot_time_active`, DROP COLUMN `active_slot_key`;
ALTER TABLE `reservations`
  ADD COLUMN `active_slot_key` int GENERATED ALWAYS AS (if((`status` in ('UNPAID','PAID')),`slot_id`,NULL)) STORED,
  ADD UNIQUE KEY `uq_slot_active` (`active_slot_key`);

-- 2. rates inserted from the admin page need a default updated_at
ALTER TABLE `parking_rates` MODIFY `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- 3. location picker
CREATE TABLE `user_locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `address` varchar(500) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

-- 4. vehicle plate numbers must be globally unique, including concurrent inserts.
-- Resolve any rows returned by this query before applying the unique index.
SELECT `plate_number`, COUNT(*) AS `claims`
FROM `vehicles`
GROUP BY `plate_number`
HAVING COUNT(*) > 1;

ALTER TABLE `vehicles`
  DROP INDEX `uq_user_plate`,
  ADD UNIQUE KEY `uq_plate_number` (`plate_number`);
