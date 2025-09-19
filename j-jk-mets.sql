-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2025 at 01:02 PM
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
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `min_quantity` int(11) DEFAULT NULL,
  `category` varchar(50) NOT NULL COMMENT 'reagents,\r\ncalibrators,\r\ncontrols,\r\nelectrolyte,\r\nconsumables',
  `reagent_img` varchar(255) NOT NULL,
  `item_description` varchar(255) DEFAULT NULL,
  `test_kit` varchar(100) DEFAULT NULL,
  `packaging` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reagents`
--

INSERT INTO `reagents` (`reagent_id`, `reagent_type`, `reagent_name`, `quantity`, `unit`, `min_quantity`, `category`, `reagent_img`, `item_description`, `test_kit`, `packaging`) VALUES
(1, 'chemistry', 'hello world', 10, 'ft', 1, 'consumables', './images/no_available_img.jpg', '', '', NULL),
(2, 'chemistry', 'abcdefg', 5, 'Units', 1, 'reagents', './images/no_available_img.jpg', '', '', NULL),
(3, 'chemistry', 'testing 2', 123, 'cm', 1, 'calibrators', './images/no_available_img.jpg', '', '', NULL),
(4, 'chemistry', 'image-testing', 123, 'Units', 1, 'controls', './images/no_available_img.jpg', '', '', NULL),
(5, 'chemistry', 'testin', 1234, 'Units', 1, 'consumables', './images/no_available_img.jpg', '', '', NULL),
(6, 'chemistry', 'image-testin', 123, 'Units', 123, 'reagents', '', '', '', NULL),
(7, 'chemistry', 'image', 123, 'kg', 1, 'electrolyte', './product_img/img_68c035ffa74640.81130042.png', '', '', NULL),
(8, 'chemistry', '6999', 1, 'in', 20, 'consumables', './product_img/img_68c13d4f9df6d8.49735014.png', '', '', NULL),
(9, 'chemistry', 'testingAlertReagent', 10, 'yd', 3, 'consumables', './product_img/img_68c4212f37d537.75553195.jpg', '', '', NULL),
(10, 'chemistry', 'omegalol', 1, 'Units', 1, 'reagents', './product_img/img_68c439257e40c1.71388137.jpg', '1', '1', '1'),
(11, 'chemistry', 'testing', 0, '', 5, 'reagents', './product_img/img_68cd068c919215.58265284.png', 'testing', 'testing', 'testing'),
(12, 'chemistry', 'edit-good', 0, '', 4567, 'controls', 'uploads/1758279527_m0bv61rf4im51.png', 'success', 'success', 'success');

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
(1, 'admin', '', 'admin', 'super_admin');

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
