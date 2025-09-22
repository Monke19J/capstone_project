-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2025 at 07:04 PM
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
(1, 1, 'equal', 100, '2025-09-22 08:31:37'),
(2, 2, 'equal', 100, '2025-09-22 09:00:02'),
(3, 3, 'equal', 200, '2025-09-22 09:00:35'),
(4, 4, 'equal', 400, '2025-09-22 09:11:04'),
(5, 5, 'equal', 40, '2025-09-22 10:09:09'),
(6, 6, 'equal', 20, '2025-09-22 10:13:36');

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
  `test_kit` varchar(100) DEFAULT '0',
  `packaging` text DEFAULT NULL,
  `reagent_status` varchar(50) NOT NULL DEFAULT 'active' COMMENT 'active, inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reagents`
--

INSERT INTO `reagents` (`reagent_id`, `reagent_type`, `reagent_name`, `min_quantity`, `category`, `reagent_img`, `item_description`, `test_kit`, `packaging`, `reagent_status`) VALUES
(1, 'chemistry', 'Chemistry 1', 100, 'reagents', './product_img/img_68d109697531f7.44508997.png', 'chestry 1', '12345', '', 'active'),
(2, 'chemistry', 'Chemistry 2', 100, 'controls', './product_img/img_68d110128f3b28.82347067.png', 'Chemistry2', '', '', 'active'),
(3, 'hematology', 'Hematology 3', 200, 'calibrators', './product_img/img_68d110332d8826.86056325.png', 'Hematology 3', '', '', 'active'),
(4, 'hematology', 'Hematology 4', 400, 'calibrators', './product_img/img_68d112a82c2dd4.40350797.png', 'Hematology 4', '', '', 'active'),
(5, 'hematology', 'Hematology 5', 40, 'calibrators', './product_img/img_68d12045c6e066.36270195.png', 'Hematology 5', '', '', 'active'),
(6, 'immunology', 'Immunology 6', 20, 'controls', './product_img/img_68d1215089ffc3.76021987.png', 'Immunology 6', '', '', 'active');

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
(1, 1, '1', 'Distributor A', '2025-09-22', '2025-11-22', 100, '2025-09-22 10:09:57', '2025-09-22 10:12:25', 'active'),
(2, 4, '111', 'Distributor B', '2025-09-23', '2026-03-23', 100, '2025-09-22 10:10:59', '2025-09-22 10:10:59', 'active'),
(3, 6, '3', 'Distributor A', '2025-09-24', '2026-05-11', 500, '2025-09-22 10:14:37', '2025-09-22 10:14:37', 'active'),
(4, 6, '1234', 'Distributor A', '2025-10-25', '2027-09-22', 1000, '2025-09-22 10:37:21', '2025-09-22 10:37:21', 'active');

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
(1, 1, 1, 'add', 500, 0, '2025-09-22', '2025-09-22 10:09:57', NULL),
(2, 4, 2, 'add', 100, 0, '2025-09-23', '2025-09-22 10:10:59', NULL),
(3, 1, 1, 'remove', 400, 0, '2025-09-24', '2025-09-22 10:12:25', NULL),
(4, 6, 3, 'add', 500, 0, '2025-09-24', '2025-09-22 10:14:37', NULL),
(5, 6, 4, 'add', 1000, 0, '2025-10-25', '2025-09-22 10:37:21', NULL);

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
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `alert_receivers`
--
ALTER TABLE `alert_receivers`
  MODIFY `receiver_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reagents`
--
ALTER TABLE `reagents`
  MODIFY `reagent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reagent_stock`
--
ALTER TABLE `reagent_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_history`
--
ALTER TABLE `stock_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
