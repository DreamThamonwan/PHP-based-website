-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 01:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easyev_charging`
--

-- --------------------------------------------------------

--
-- Table structure for table `charging_stations`
--

CREATE TABLE `charging_stations` (
  `station_id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT 'EV station',
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `description` varchar(300) NOT NULL,
  `cost_per_hr` decimal(6,2) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 1,
  `available` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `charging_stations`
--

INSERT INTO `charging_stations` (`station_id`, `name`, `address`, `city`, `state`, `description`, `cost_per_hr`, `capacity`, `available`) VALUES
(1, 'ThH', 'Cnr Hindmarsh Ave and, Porter St', 'North Wollongong', 'NSW', 'Hello', 80.00, 6, 6),
(2, 'M', 'unit 12 71-83 Smith St', 'Wollongong', 'NSW', 'Sleeping is the best', 150.00, 5, 4),
(3, 'Thamonwan Nitatwichit', 'Porter St', 'North Wollongong', 'NSW', '555', 120.00, 3, 3),
(4, 'TT', '71 Smith St', 'WOLLONGONG', 'NSW', 'I am an old lady', 100.50, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `station_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `total_cost` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`session_id`, `userId`, `station_id`, `created_at`, `start_time`, `end_time`, `total_cost`) VALUES
(1, 2, 1, '2025-05-27 09:43:02', '2025-05-22 11:42:00', '2025-05-27 09:51:36', 9452.00),
(2, 2, 4, '2025-05-27 10:10:40', '2025-05-27 08:10:00', '2025-05-27 10:59:17', 283.07),
(3, 2, 3, '2025-05-27 10:49:01', '2025-05-26 20:48:00', '2025-05-27 11:05:20', 1714.00),
(4, 2, 2, '2025-05-27 10:54:23', '2025-05-26 12:54:00', '2025-05-27 11:03:44', 3322.50),
(5, 2, 1, '2025-05-27 10:59:51', '2025-05-26 12:59:00', '2025-05-27 11:09:44', 1773.33),
(6, 2, 4, '2025-05-27 11:00:57', '2025-05-26 12:00:00', '2025-05-27 11:13:34', 2333.27),
(7, 2, 2, '2025-05-27 11:07:13', '2025-05-14 14:07:00', NULL, NULL),
(8, 2, 3, '2025-05-27 11:08:35', '2025-05-26 13:08:00', '2025-05-27 11:17:04', 2658.00),
(9, 2, 1, '2025-05-27 11:10:04', '2025-05-12 11:10:00', '2025-05-27 11:15:44', 28806.67),
(10, 2, 4, '2025-05-27 11:13:55', '2025-05-26 02:13:00', NULL, NULL),
(11, 4, 4, '2025-05-27 11:38:22', '2025-05-20 13:38:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(60) NOT NULL,
  `role` varchar(15) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `name`, `phone`, `email`, `password`, `role`, `reg_date`) VALUES
(1, 'Thamonwan Nitatwichit', '0420666999', 'thamonwan-ni@hotmail.com', 'ef800207a3648c7c1ef3e9fe544f17f0', 'Administrator', '2025-05-27 08:33:15'),
(2, 'Thamonwan Nitatwichit', '0451039090', 'dream_thamonwan@hotmail.com', '3028879ab8d5c87dc023049fa5bb5c1a', 'Customer', '2025-05-27 09:39:45'),
(3, 'Thamonwan Nitatwichit', '0451039090', 'tn304@uowmail.edu.au', '9c72446df124ddf214b698c1e2312371', 'Administrator', '2025-05-27 11:21:07'),
(4, 'Thamonwan Nitatwichit', '0420666996', 'thamonwan@hotmail.com', 'b7e6923f6de66497d51789db0ef3571d', 'Customer', '2025-05-27 11:37:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `charging_stations`
--
ALTER TABLE `charging_stations`
  ADD PRIMARY KEY (`station_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `station_id` (`station_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `charging_stations`
--
ALTER TABLE `charging_stations`
  MODIFY `station_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`station_id`) REFERENCES `charging_stations` (`station_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
