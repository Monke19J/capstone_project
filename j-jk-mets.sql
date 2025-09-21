-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2025 at 06:33 PM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `j-jk-mets`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `reagent_id` int(11) NOT NULL,
  `alert_condition` varchar(50) NOT NULL,
  `min_quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_id`, `reagent_id`, `alert_condition`, `min_quantity`, `created_at`) VALUES
(1, 9, 'above', 3, '2025-09-12 13:33:35'),
(2, 10, 'equal', 1, '2025-09-12 15:15:49'),
(3, 11, 'equal', 5, '2025-09-19 07:30:20'),
(4, 12, 'equal', 1234, '2025-09-19 10:36:48');

-- --------------------------------------------------------

--
-- Table structure for table `alert_receivers`
--

CREATE TABLE `alert_receivers` (
  `receiver_id` int(11) NOT NULL,
  `alert_id` int(11) NOT NULL,
  `receiver_type` varchar(50) DEFAULT NULL,
  `receiver_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alert_receivers`
--

INSERT INTO `alert_receivers` (`receiver_id`, `alert_id`, `receiver_type`, `receiver_value`) VALUES
(1, 1, 'self', NULL),
(2, 1, 'all_sales', NULL),
(3, 1, 'all_engineering', NULL),
(4, 1, 'everyone', NULL),
(5, 1, 'user', 'testingEngineer'),
(6, 2, 'self', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reagents`
--

CREATE TABLE `reagents` (
  `reagent_id` int(11) NOT NULL,
  `reagent_type` varchar(50) NOT NULL COMMENT 'chemistry,\r\nimmunology,\r\nhematology',
  `reagent_name` varchar(150) NOT NULL,
  `min_quantity` int(11) DEFAULT NULL,
  `category` varchar(50) NOT NULL COMMENT 'reagents,\r\ncalibrators,\r\ncontrols,\r\nelectrolyte,\r\nconsumables',
  `reagent_img` varchar(255) NOT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `test_kit` varchar(100) DEFAULT NULL,
  `packaging` text DEFAULT NULL,
  `reagent_status` varchar(50) NOT NULL DEFAULT 'active' COMMENT 'active, inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reagents`
--

INSERT INTO `reagents` (`reagent_id`, `reagent_type`, `reagent_name`, `min_quantity`, `category`, `reagent_img`, `item_description`, `test_kit`, `packaging`, `reagent_status`) VALUES
(1, 'chemistry', 'Reagent 1', 1, 'calibrators', 'uploads/1758471995_1.png', '', '', '', 'active'),
(2, 'chemistry', 'Reagent 2', 1, 'reagents', 'uploads/1758472004_2.png', '', '', '', 'active'),
(3, 'chemistry', 'Reagent 3', 1, 'calibrators', 'uploads/1758472012_3.png', '', '', '', 'active'),
(4, 'chemistry', 'Reagent 4', 1, 'controls', 'uploads/1758472019_4.png', '', '', '', 'active'),
(5, 'chemistry', 'Reagent 5', 1, 'consumables', 'uploads/1758472033_5.png', '', '', '', 'active'),
(6, 'chemistry', 'Reagent 6', 123, 'reagents', 'uploads/1758472051_6.png', '', '', '', 'active'),
(7, 'chemistry', 'Reagent 7', 1, 'electrolyte', 'uploads/1758472073_7.png', '', '', '', 'active'),
(8, 'chemistry', 'Reagent 8', 20, 'consumables', 'uploads/1758472085_8.png', '', '', '', 'active'),
(9, 'chemistry', 'Reagent 10', 3, 'consumables', 'uploads/1758472101_10.png', '', '', '', 'active'),
(10, 'chemistry', 'omegalol', 1, 'reagents', './product_img/img_68c439257e40c1.71388137.jpg', '1', '1', '1', 'inactive'),
(11, 'chemistry', 'testing', 5, 'reagents', './product_img/img_68cd068c919215.58265284.png', 'testing', 'testing', 'testing', 'active'),
(12, 'chemistry', 'edit-good', 4567, 'controls', 'uploads/1758279527_m0bv61rf4im51.png', 'success', 'success', 'success', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `reagent_stock`
--

CREATE TABLE `reagent_stock` (
  `stock_id` int(11) NOT NULL,
  `reagent_id` int(11) NOT NULL,
  `lot_no` varchar(100) NOT NULL,
  `distributor` varchar(100) NOT NULL,
  `date_arrived` date NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stock_status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reagent_stock`
--

INSERT INTO `reagent_stock` (`stock_id`, `reagent_id`, `lot_no`, `distributor`, `date_arrived`, `expiry_date`, `quantity`, `created_at`, `updated_at`, `stock_status`) VALUES
(1, 1, '1', 'Distributor A', '2025-09-20', '2025-11-20', 51, '2025-09-20 15:12:36', '2025-09-21 16:13:54', 'active'),
(2, 1, '2', 'Distributor B', '2025-09-21', '2025-12-29', 16, '2025-09-21 05:09:44', '2025-09-21 16:13:54', 'active'),
(3, 11, '1234', 'Distributor A', '2025-09-21', '2025-12-31', 50, '2025-09-21 09:32:38', '2025-09-21 09:32:38', 'active'),
(4, 1, '3', 'Distributor A', '2025-09-21', '2025-11-21', 40, '2025-09-21 12:32:23', '2025-09-21 16:13:54', 'active'),
(5, 2, '69420', 'Distributor A', '2025-09-22', '2025-09-22', 100, '2025-09-21 16:01:47', '2025-09-21 16:13:54', 'active'),
(6, 12, '1234', 'Distributor A', '2025-09-30', '2025-09-30', 10, '2025-09-21 16:28:59', '2025-09-21 16:29:07', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `stock_history`
--

CREATE TABLE `stock_history` (
  `history_id` int(11) NOT NULL,
  `reagent_id` int(11) NOT NULL,
  `stock_id` int(11) DEFAULT NULL,
  `action_type` enum('add','remove') NOT NULL,
  `quantity` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `date_action` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_history`
--

INSERT INTO `stock_history` (`history_id`, `reagent_id`, `stock_id`, `action_type`, `quantity`, `client_id`, `date_action`, `created_at`, `remarks`) VALUES
(1, 1, NULL, 'add', 10, 0, '2025-09-21', '2025-09-20 17:48:26', NULL),
(2, 1, NULL, 'add', 20, 0, '2025-09-21', '2025-09-20 18:03:17', NULL),
(3, 1, NULL, 'add', 10, 0, '2025-09-05', '2025-09-20 18:03:43', NULL),
(4, 1, NULL, 'remove', 40, 0, '2025-09-22', '2025-09-20 18:04:08', NULL),
(5, 1, NULL, 'add', 10, NULL, '2025-09-21', '2025-09-20 18:05:29', NULL),
(6, 1, NULL, 'remove', 10, NULL, '2025-09-08', '2025-09-20 18:05:47', NULL),
(7, 1, NULL, 'add', 10, NULL, '2025-09-04', '2025-09-20 18:06:25', NULL),
(8, 1, NULL, 'add', 21, NULL, '2025-09-01', '2025-09-20 18:15:12', NULL),
(9, 1, NULL, 'remove', 30, NULL, '2025-09-21', '2025-09-20 18:15:33', NULL),
(14, 1, NULL, 'add', 21, NULL, '2025-09-21', '2025-09-20 18:43:49', NULL),
(15, 1, NULL, 'add', 4, 0, '2025-09-21', '2025-09-21 05:10:15', NULL),
(16, 1, NULL, 'add', 4, NULL, '2025-09-21', '2025-09-21 05:10:34', NULL),
(17, 1, NULL, 'add', 4, 0, '2025-09-21', '2025-09-21 05:15:33', NULL),
(18, 1, NULL, 'add', 4, NULL, '2025-09-21', '2025-09-21 05:15:51', NULL),
(19, 1, NULL, 'add', 4, NULL, '2025-09-02', '2025-09-21 12:02:46', NULL),
(20, 1, 4, 'add', 20, 0, '2025-09-21', '2025-09-21 12:34:45', NULL),
(21, 1, 4, 'remove', 20, 0, '2025-09-21', '2025-09-21 12:36:30', NULL),
(22, 1, 4, 'add', 15, NULL, '2025-09-30', '2025-09-21 15:29:16', NULL),
(23, 1, 4, 'add', 15, NULL, '2025-09-30', '2025-09-21 15:29:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL COMMENT 'sales\r\nengineer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `user_type`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$e83PxKhAxCeIq4YivZgXkOlS.HeAQ.L2KJJdKsFlCs88s5y7l5EOC', 'super_admin'),
(2, 'engineer', 'engineer@gmail.com', '$2y$10$DinM9dAr7nmeJF/uxh3kReNDbBKgP8Yo/MpjGU2zrAAPriQmbEcmu', 'engineer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `reagent_id` (`reagent_id`);

--
-- Indexes for table `alert_receivers`
--
ALTER TABLE `alert_receivers`
  ADD PRIMARY KEY (`receiver_id`),
  ADD KEY `alert_id` (`alert_id`);

--
-- Indexes for table `reagents`
--
ALTER TABLE `reagents`
  ADD PRIMARY KEY (`reagent_id`);

--
-- Indexes for table `reagent_stock`
--
ALTER TABLE `reagent_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `fk_reagent` (`reagent_id`);

--
-- Indexes for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `reagent_id` (`reagent_id`),
  ADD KEY `stock_id` (`stock_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `alert_receivers`
--
ALTER TABLE `alert_receivers`
  MODIFY `receiver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reagents`
--
ALTER TABLE `reagents`
  MODIFY `reagent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `reagent_stock`
--
ALTER TABLE `reagent_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`reagent_id`) REFERENCES `reagents` (`reagent_id`);

--
-- Constraints for table `alert_receivers`
--
ALTER TABLE `alert_receivers`
  ADD CONSTRAINT `alert_receivers_ibfk_1` FOREIGN KEY (`alert_id`) REFERENCES `alerts` (`alert_id`);

--
-- Constraints for table `reagent_stock`
--
ALTER TABLE `reagent_stock`
  ADD CONSTRAINT `fk_reagent` FOREIGN KEY (`reagent_id`) REFERENCES `reagents` (`reagent_id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_history`
--
ALTER TABLE `stock_history`
  ADD CONSTRAINT `stock_history_ibfk_1` FOREIGN KEY (`reagent_id`) REFERENCES `reagents` (`reagent_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_history_ibfk_2` FOREIGN KEY (`stock_id`) REFERENCES `reagent_stock` (`stock_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
