-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2025 at 02:27 PM
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
  `event_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ticket_quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `name`, `image`, `location`, `activity`, `price`, `created_at`, `ticket_quantity`) VALUES
(1, 'Sealife', 'uploads/1741937891_pexels-nguyen-tran-327588-1703516.jpg', 'Bangkok', 'ชมสัตว์น้ำ', 500.00, '2025-03-14 07:38:11', 100),
(4, 'Chiangmai Zoo Aquarium', 'uploads/1741938740_pexels-matej-bizjak-2148520448-30417733.jpg', 'ChiangMai', 'การแสดงสัตว์น้ำ', 350.00, '2025-03-14 07:52:20', 100),
(6, 'Aquaria Phuket', 'uploads/1741944329_underwater.jpg', 'Phuket', 'ชมสัตว์น้ำ', 200.00, '2025-03-14 09:25:29', 100),
(7, 'Sealife', 'uploads/1741944410_8929102.jpg', 'Bangkok', 'ชมสัตว์น้ำ', 150.00, '2025-03-14 09:26:50', 100),
(8, 'Aquaria Phuket', 'uploads/1741938496_pexels-pixabay-34809.jpg', 'Phuket', 'ชมสัตว์น้ำ โชว์ให้อาหารสัตว์น้ำ การแสดงสัตว์น้ำ', 600.00, '2025-03-14 07:48:16', 100),
(9, 'Aquaria Phuket', 'uploads/1741944805_Male_whale_shark_at_Georgia_Aquarium.jpg', 'Phuket', 'ชมสัตว์น้ำ', 150.00, '2025-03-14 09:33:25', 100),
(10, 'Sealife', 'uploads/1741945228_8929161.jpg', 'Bangkok', 'ชมสัตว์น้ำ', 200.00, '2025-03-14 09:40:28', 100),
(11, 'Aquaria Phuket', 'uploads/1741945363_pexels-w-w-299285-889929.jpg', 'Phuket', 'ชมสัตว์น้ำ', 250.00, '2025-03-14 09:42:43', 100),
(12, 'Sealife', 'uploads/1741945471_pexels-pixabay-64219.jpg', 'Bangkok', 'ชมสัตว์น้ำ การแสดงสัตว์น้ำ', 300.00, '2025-03-14 09:44:31', 100);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `user_id`, `content`, `rating`, `created_at`, `event_id`) VALUES
(7, 6, 'สนุกจริงๆเบย', 4, '2025-03-29 12:25:08', 6),
(8, 7, 'สนุกจริงๆเบย', 5, '2025-03-29 07:25:08', 3),
(9, 1, 'ร้อนนิดๆ', 3, '2025-03-29 07:25:08', 11),
(10, 7, 'ข้าวอร่อยมากกกก', 5, '2025-03-29 07:25:08', 8),
(11, 6, 'น้องกระโดดกัดค่ะ ', 2, '2025-03-29 12:35:08', 7);

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
(6, 5, 7, '0720250414', '2025-04-14', 4, 600.00, '2025-03-24 14:34:24', 'รอตรวจสอบ', NULL, NULL),
(12, 7, 12, '1220250415', '2025-04-15', 6, 1800.00, '2025-03-29 06:20:49', 'รอตรวจสอบ', NULL, NULL),
(14, 7, 8, '0820250409', '2025-04-09', 2, 1200.00, '2025-03-29 06:26:49', 'ไม่อนุมัติ', NULL, NULL),
(15, 7, 8, '0820250421', '2025-04-21', 3, 1800.00, '2025-03-29 06:51:07', 'อนุมัติ', 'slip_67e79873cfcc3.png', 'ยังไม่ได้ใช้งาน'),
(16, 6, 6, '0820250525', '2025-05-25', 3, 1800.00, '2025-03-29 06:51:07', 'อนุมัติ', 'slip_67e79873cfcc3.png', 'ใช้งานแล้ว');

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
  `creatde_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `gender`, `dob`, `email`, `password`, `position`, `creatde_at`) VALUES
(1, 'Thanapon', 'Thanapon', 'Thungwaen', 'male', '2001-09-21', 'Sin@hotmail.com', '$2y$10$mEHrV/vCq85ZE7KRZI/ZKOhuCxmVAG0LHSbPLKwC4T3byEmdVSQEO', 'a', '2025-02-27 15:31:19'),
(2, 'Sin', 'sdsd', 'sdsd', 'male', '2025-03-05', 'sss@hotmail.com', '$2y$10$FZS1QGIzSoO62FTIYPG0TeLNhPHy.hiCmYUUBOQA8io5wD.U9xsmW', 'a', '2025-02-27 16:15:26'),
(4, 'D', NULL, NULL, NULL, NULL, 'D@hotmail.com', '$2y$10$3IZPLeaBuFWSGBgqCkEtWOXTEuvpznUCXqEAmTHg.gSLpIv3rV3tm', 'b', '2025-03-01 11:01:51'),
(6, 'Tachiki', 'Tachiki', 'Nomu', 'ชาย', '2003-06-18', 'Tachiki@gmail.com', '$2y$10$e14ymEpXO0KQ0uzi/VvDq.pNBY2WnHy.RKeo6HYgTi0ABky3PGgp.', 'm', '2025-03-29 05:48:51'),
(7, 'M', 'Millennium', 'Blueeyes', 'ชาย', '1982-10-26', 'M@gmail.com', '$2y$10$rCQeYSdrUXbhNU0B5KL9AOZnXytYqdCk1LYM1Dq7gGr7Up5Pwxd8e', 'm', '2025-03-29 05:50:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
