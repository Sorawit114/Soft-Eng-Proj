-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 07:20 PM
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
-- Database: `aquarium`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `image`, `location`, `activity`, `price`, `created_at`) VALUES
(1, 'Sealife', 'uploads/1741937891_pexels-nguyen-tran-327588-1703516.jpg', 'Bangkok', 'ชมสัตว์น้ำ', 500.00, '2025-03-14 07:38:11'),
(4, 'Chiangmai Zoo Aquarium', 'uploads/1741938740_pexels-matej-bizjak-2148520448-30417733.jpg', 'ChiangMai', 'การแสดงสัตว์น้ำ', 350.00, '2025-03-14 07:52:20'),
(6, 'Aquaria Phuket', 'uploads/1741944329_underwater.jpg', 'Phuket', 'ชมสัตว์น้ำ', 200.00, '2025-03-14 09:25:29'),
(7, 'Sealife', 'uploads/1741944410_8929102.jpg', 'Bangkok', 'ชมสัตว์น้ำ', 150.00, '2025-03-14 09:26:50'),
(8, 'Aquaria Phuket', 'uploads/1741938496_pexels-pixabay-34809.jpg', 'Phuket', 'ชมสัตว์น้ำ โชว์ให้อาหารสัตว์น้ำ การแสดงสัตว์น้ำ', 600.00, '2025-03-14 07:48:16'),
(9, 'Aquaria Phuket', 'uploads/1741944805_Male_whale_shark_at_Georgia_Aquarium.jpg', 'Phuket', 'ชมสัตว์น้ำ', 150.00, '2025-03-14 09:33:25'),
(10, 'Sealife', 'uploads/1741945228_8929161.jpg', 'Bangkok', 'ชมสัตว์น้ำ', 200.00, '2025-03-14 09:40:28'),
(11, 'Aquaria Phuket', 'uploads/1741945363_pexels-w-w-299285-889929.jpg', 'Phuket', 'ชมสัตว์น้ำ', 250.00, '2025-03-14 09:42:43'),
(12, 'Sealife', 'uploads/1741945471_pexels-pixabay-64219.jpg', 'Bangkok', 'ชมสัตว์น้ำ การแสดงสัตว์น้ำ', 300.00, '2025-03-14 09:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_code` varchar(50) NOT NULL,
  `ticket_date` date NOT NULL,
  `ticket_quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'รอตรวจสอบ',
  `slip_image` varchar(255) DEFAULT NULL,
  `used` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id`, `user_id`, `event_id`, `ticket_code`, `ticket_date`, `ticket_quantity`, `total_price`, `created_at`, `status`, `slip_image`, `used`) VALUES
(5, 5, 11, '1120250329', '2025-03-29', 4, 1000.00, '2025-03-23 21:56:01', 'รอตรวจสอบ', NULL, NULL),
(6, 5, 7, '0720250414', '2025-04-14', 4, 600.00, '2025-03-24 14:34:24', 'รอตรวจสอบ', NULL, NULL),
(7, 5, 8, '0820250416', '2025-04-16', 3, 1800.00, '2025-03-24 20:00:23', 'อนุมัติ', 'slip_67e412fdbeccd.png', 'ยังไม่ได้ใช้งาน');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `position` char(1) NOT NULL DEFAULT 'm',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `gender`, `dob`, `email`, `password`, `position`, `created_at`) VALUES
(1, 'Thanapon', 'Thanapon', 'Thungwaen', 'male', '2001-09-21', 'Sin@hotmail.com', '$2y$10$mEHrV/vCq85ZE7KRZI/ZKOhuCxmVAG0LHSbPLKwC4T3byEmdVSQEO', 'a', '2025-02-27 15:31:19'),
(2, 'Sin', 'sdsd', 'sdsd', 'male', '2025-03-05', 'sss@hotmail.com', '$2y$10$FZS1QGIzSoO62FTIYPG0TeLNhPHy.hiCmYUUBOQA8io5wD.U9xsmW', 'a', '2025-02-27 16:15:26'),
(4, 'D', NULL, NULL, NULL, NULL, 'D@hotmail.com', '$2y$10$3IZPLeaBuFWSGBgqCkEtWOXTEuvpznUCXqEAmTHg.gSLpIv3rV3tm', 'm', '2025-03-01 11:01:51'),
(5, 'M', NULL, NULL, NULL, NULL, 'M@gmail.com', '$2y$10$QBTb//b034M4UBnU9cJzNOJQymp.1cbJneSohHnpRyiLXjsq1THHS', 'm', '2025-03-14 14:11:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_code` (`ticket_code`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
