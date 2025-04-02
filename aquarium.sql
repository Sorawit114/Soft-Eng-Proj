-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Apr 02, 2025 at 07:38 PM
-- Server version: 5.7.44
-- PHP Version: 8.2.27

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
  `name` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_520_ci NOT NULL,
  `location` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `province` varchar(255) COLLATE utf8_unicode_520_ci NOT NULL,
  `activity` varchar(255) COLLATE utf8_unicode_520_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ticket_quantity` int(11) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `name`, `image`, `location`, `province`, `activity`, `price`, `created_at`, `ticket_quantity`, `description`) VALUES
(1, 'โชว์นางเงือก 1', '../uploads/nSW9kXzuMfkhLMYQHz0b.jpg', 'อควาเรีย ภูเก็ต (Aquaria Phuket) ', 'Phuket', 'ชมสัตว์น้ำ', 700.00, '2025-03-14 07:38:11', 100, 'เปิดให้เข้าชม : \r\n10.30-19.00 น. \r\n\r\nแผนที่ตั้ง \r\nhttps://www.google.com/maps/place/Aquaria+Phuket/@7.8891078,98.3651054,16.39z/data=!4m6!3m5!1s0x305031244348e8ab:0xf4a10de74d8b010b!8m2! \r\n3d7.8887188!4d98.3658599!16s%2Fg%2F11fk147yg_?hl=en&amp;amp;amp;amp;amp;amp;entry=ttu&amp;amp;amp;amp;amp;amp;g_ep=EgoyMDI1MDMxMS4wIKXMDSoASAFQAw%3D%3D \r\n\r\n\r\n'),
(4, 'ดูแมวน้ำ', '../uploads/1741938740_pexels-matej-bizjak-2148520448-30417733.jpg', 'Chiangmai Zoo Aquarium', 'Chiang Mai', 'ชมสัตว์น้ำ,โชว์ให้อาหารสัตว์น้ำ,การแสดงสัตว์น้ำ', 350.00, '2025-03-14 07:52:20', 89, 'Chiangmai Zoo Aquarium'),
(6, 'ดูปลา', '../uploads/1741944329_underwater.jpg', 'อควาเรีย ภูเก็ต (Aquaria Phuket) ', 'Phuket', 'ชมสัตว์น้ำ', 200.00, '2025-03-14 09:25:29', 100, 'พิพิธภัณฑ์สัตว์น้ำที่ใหญ่ที่สุดในประเทศไทยเลยก็ว่าได้ ซึ่งมีมวลน้ำทะเลกว่า 7 ล้านลิตร สัตว์น้ำนานาชนิดกว่า 51,000 ตัว จาก 300 สายพันธุ์ ที่จัดภายใต้ \n\nคอนเซ็ปต์ “An Ocean of Myth and Legend” กับ 7 โซนเต็มไปเลย '),
(7, 'ดูแมงกระพุน', '../uploads/1741944410_8929102.jpg', 'SeaLife Bangkok Ocean World ซีไลฟ์ แบงคอก ', 'Bangkok', 'ชมสัตว์น้ำ', 150.00, '2025-03-14 09:26:50', 100, 'เปิดให้เข้าชม : 10.00-20.00 น. \n\nที่ตั้ง :  ชั้น B1 สยามพารากอน 991 ถนนพระราม 1 เขตปทุมวัน กรุงเทพฯ \n\n\n\n'),
(8, 'Aquaria Phuket', '../uploads/1741938496_pexels-pixabay-34809.jpg', 'อควาเรีย ภูเก็ต (Aquaria Phuket) ', 'Phuket', 'ชมสัตว์น้ำ โชว์ให้อาหารสัตว์น้ำ การแสดงสัตว์น้ำ', 600.00, '2025-03-14 07:48:16', 95, 'พิพิธภัณฑ์สัตว์น้ำที่ใหญ่ที่สุดในประเทศไทยเลยก็ว่าได้ ซึ่งมีมวลน้ำทะเลกว่า 7 ล้านลิตร สัตว์น้ำนานาชนิดกว่า 51,000 ตัว จาก 300 สายพันธุ์ ที่จัดภายใต้   คอนเซ็ปต์ “An Ocean of Myth and Legend” กับ 7 โซนเต็มไปเลย '),
(9, 'Aquaria Phuket', '../uploads/1741944805_Male_whale_shark_at_Georgia_Aquarium.jpg', 'อควาเรีย ภูเก็ต (Aquaria Phuket) ', 'Phuket', 'ชมสัตว์น้ำ', 150.00, '2025-03-14 09:33:25', 100, 'พิพิธภัณฑ์สัตว์น้ำที่ใหญ่ที่สุดในประเทศไทยเลยก็ว่าได้ ซึ่งมีมวลน้ำทะเลกว่า 7 ล้านลิตร สัตว์น้ำนานาชนิดกว่า 51,000 ตัว จาก 300 สายพันธุ์ ที่จัดภายใต้   คอนเซ็ปต์ “An Ocean of Myth and Legend” กับ 7 โซนเต็มไปเลย '),
(10, 'Sealife', '../uploads/1741945228_8929161.jpg', 'SeaLife Bangkok Ocean World ซีไลฟ์ แบงคอก ', 'Bangkok', 'ชมสัตว์น้ำ', 200.00, '2025-03-14 09:40:28', 100, 'เปิดให้เข้าชม : 10.00-20.00 น.   ที่ตั้ง :  ชั้น B1 สยามพารากอน 991 ถนนพระราม 1 เขตปทุมวัน กรุงเทพฯ '),
(11, 'โชว์ระบำใต้น้ำ', '../uploads/1741945363_pexels-w-w-299285-889929.jpg', 'อควาเรีย ภูเก็ต (Aquaria Phuket) ', 'Phuket', 'การแสดงสัตว์น้ำ', 250.00, '2025-03-14 09:42:43', 100, 'พิพิธภัณฑ์สัตว์น้ำที่ใหญ่ที่สุดในประเทศไทยเลยก็ว่าได้ ซึ่งมีมวลน้ำทะเลกว่า 7 ล้านลิตร สัตว์น้ำนานาชนิดกว่า 51,000 ตัว จาก 300 สายพันธุ์ ที่จัดภายใต้ \r\n\r\nคอนเซ็ปต์ “An Ocean of Myth and Legend” กับ 7 โซนเต็มไปเลย '),
(12, 'ดูโลมา', '../uploads/1741945471_pexels-pixabay-64219.jpg', 'SeaLife Bangkok Ocean World ซีไลฟ์ แบงคอก ', 'Bangkok', 'ชมสัตว์น้ำ', 300.00, '2025-03-14 09:44:31', 99, 'เปิดให้เข้าชม : 10.00-20.00 น.   ที่ตั้ง :  ชั้น B1 สยามพารากอน 991 ถนนพระราม 1 เขตปทุมวัน กรุงเทพฯ ');

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `province_id` int(11) NOT NULL,
  `province_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`province_id`, `province_name`) VALUES
(1, 'Bangkok'),
(2, 'Chiang Mai'),
(3, 'Phuket'),
(4, 'Khon Kaen'),
(5, 'Amnat Charoen'),
(6, 'Ang Thong'),
(7, 'Bueng Kan'),
(8, 'Buri Ram'),
(9, 'Chachoengsao'),
(10, 'Chaiyaphum'),
(11, 'Chanthaburi'),
(12, 'Chonburi'),
(13, 'Chumphon'),
(14, 'Kalasin'),
(15, 'Kamphaeng Phet'),
(16, 'Kanchanaburi'),
(17, 'Khon Kaen'),
(18, 'Krabi'),
(19, 'Lampang'),
(20, 'Lamphun'),
(21, 'Loei'),
(22, 'Lopburi'),
(23, 'Mae Hong Son'),
(24, 'Maha Sarakham'),
(25, 'Mukdahan'),
(26, 'Nakhon Nayok'),
(27, 'Nakhon Pathom'),
(28, 'Nakhon Phanom'),
(29, 'Nakhon Ratchasima'),
(30, 'Nakhon Sawan'),
(31, 'Nan'),
(32, 'Narathiwat'),
(33, 'Nong Bua Lamphu'),
(34, 'Nong Khai'),
(35, 'Nonthaburi'),
(36, 'Pathum Thani'),
(37, 'Pattani'),
(38, 'Phang Nga'),
(39, 'Phatthalung'),
(40, 'Phayao'),
(41, 'Phetchabun'),
(42, 'Phetchaburi'),
(43, 'Phichit'),
(44, 'Phitsanulok'),
(45, 'Phra Nakhon Si Ayutthaya'),
(46, 'Prachuap Khiri Khan'),
(47, 'Ranong'),
(48, 'Ratchaburi'),
(49, 'Rayong'),
(50, 'Roi Et'),
(51, 'Sa Kaeo'),
(52, 'Sakon Nakhon'),
(53, 'Samut Prakan'),
(54, 'Samut Sakhon'),
(55, 'Samut Songkhram'),
(56, 'Saraburi'),
(57, 'Satun'),
(58, 'Sing Buri'),
(59, 'Sukhothai'),
(60, 'Surat Thani'),
(61, 'Surin'),
(62, 'Suphan Buri'),
(63, 'Tachileik'),
(64, 'Tak'),
(65, 'Trang'),
(66, 'Trat'),
(67, 'Ubon Ratchathani'),
(68, 'Udon Thani'),
(69, 'Uthai Thani'),
(70, 'Uttaradit'),
(71, 'Yala'),
(72, 'Yasothon');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_520_ci NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `user_id`, `content`, `rating`, `created_at`, `event_id`) VALUES
(7, 6, 'สนุกจริงๆเบย', 4, '2025-03-29 12:25:08', 6),
(9, 1, 'ร้อนนิดๆ', 3, '2025-03-29 07:25:08', 11),
(10, 7, 'ข้าวอร่อยมากกกก', 5, '2025-03-29 07:25:08', 8),
(13, 6, 'staff สวยมาก', 5, '2025-04-30 12:35:08', 10),
(15, 6, 'staff สวยมาก', 5, '2025-04-30 12:35:08', 10),
(16, 7, 'sadasd', 4, '2025-04-02 07:52:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_code` varchar(50) COLLATE utf8_unicode_520_ci NOT NULL,
  `ticket_date` date NOT NULL,
  `ticket_quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) COLLATE utf8_unicode_520_ci DEFAULT 'รอตรวจสอบ',
  `slip_image` varchar(255) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `used` varchar(255) COLLATE utf8_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id`, `user_id`, `event_id`, `ticket_code`, `ticket_date`, `ticket_quantity`, `total_price`, `created_at`, `status`, `slip_image`, `used`) VALUES
(6, 5, 7, '0720250414', '2025-04-14', 4, 600.00, '2025-03-24 14:34:24', 'รอตรวจสอบ', NULL, NULL),
(12, 7, 12, '1220250415', '2025-04-15', 6, 1800.00, '2025-03-29 06:20:49', 'รอตรวจสอบ', NULL, NULL),
(14, 7, 8, '0820250409', '2025-04-09', 2, 1200.00, '2025-03-29 06:26:49', 'อนุมัติ', NULL, NULL),
(15, 7, 8, '0820250421', '2025-04-21', 3, 1800.00, '2025-03-29 06:51:07', 'อนุมัติ', 'slip_67e79873cfcc3.png', 'ยังไม่ได้ใช้งาน'),
(16, 6, 6, '0820250525', '2025-05-25', 3, 1800.00, '2025-03-29 06:51:07', 'อนุมัติ', 'slip_67e79873cfcc3.png', 'ใช้งานแล้ว'),
(19, 7, 8, '0820250417', '2025-04-17', 3, 1800.00, '2025-03-31 14:27:13', 'อนุมัติ', 'slip_67eaaa0a6d925.png', 'ใช้งานแล้ว'),
(22, 7, 8, '08202504212428', '2025-04-21', 2, 1200.00, '2025-03-31 14:43:44', 'รอตรวจสอบ', NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_520_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `gender` varchar(15) COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_520_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_520_ci NOT NULL,
  `position` char(1) COLLATE utf8_unicode_520_ci NOT NULL DEFAULT 'm',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_520_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `gender`, `dob`, `email`, `password`, `position`, `created_at`) VALUES
(1, 'Thanapon', 'Thanapon', 'Thungwaen', 'male', '2001-09-21', 'Sin@hotmail.com', '$2y$10$mEHrV/vCq85ZE7KRZI/ZKOhuCxmVAG0LHSbPLKwC4T3byEmdVSQEO', 'a', '2025-02-27 15:31:19'),
(2, 'Sin', 'sdsd', 'sdsd', 'male', '2025-03-05', 'sss@hotmail.com', '$2y$10$FZS1QGIzSoO62FTIYPG0TeLNhPHy.hiCmYUUBOQA8io5wD.U9xsmW', 'a', '2025-02-27 16:15:26'),
(4, 'D', 'Dong', NULL, NULL, NULL, 'D@hotmail.com', '$2y$10$3IZPLeaBuFWSGBgqCkEtWOXTEuvpznUCXqEAmTHg.gSLpIv3rV3tm', 'm', '2025-03-01 11:01:51'),
(6, 'Tachiki', 'Tachiki', 'Nomu', 'ชาย', '2003-06-18', 'Tachiki@gmail.com', '$2y$10$e14ymEpXO0KQ0uzi/VvDq.pNBY2WnHy.RKeo6HYgTi0ABky3PGgp.', 'm', '2025-03-29 05:48:51'),
(7, 'M', 'Millennium', 'Blueeyes', 'ชาย', '1982-10-26', 'M@gmail.com', '$2y$10$rCQeYSdrUXbhNU0B5KL9AOZnXytYqdCk1LYM1Dq7gGr7Up5Pwxd8e', 'm', '2025-03-29 05:50:01'),
(8, 'test', 'test', 'test', 'male', '0000-00-00', 'test@gmail.com', '$2y$10$KJS83wKNgSudkgyDNpnNSuB799GdKMhwjjUBnzsCm4N4lC4vleau6', 'm', '2025-04-01 12:19:32'),
(9, 'testadmin', 'testadmin', 'testadmin', 'อื่นๆ', '2025-04-25', 'testadmin@gmail.com', '$2y$10$xcYJqIc7eYP5LpGSDBR7e.6DD8LhTBWxZ3DcJqg5JqxBqLR9.ofyK', 'a', '2025-04-01 12:28:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_review_user` (`user_id`);

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
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `province_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
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
