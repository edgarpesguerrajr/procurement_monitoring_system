-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 09:11 AM
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
-- Database: `pms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `project_id`, `user_id`, `comment`, `date_created`) VALUES
(1, 14, 1, 'Hello', '2025-11-24 08:30:55'),
(2, 14, 7, 'Hello admin, sige men', '2025-11-24 08:43:35'),
(3, 14, 1, 'sir hindi pa po nakakapirma si \r\n\r\n- person 1\r\n- person 2', '2025-11-24 08:46:50'),
(7, 14, 1, 'Curly apostrophe — “It’s a test — café — © 2025 — 中文 — ñ”\r\n', '2025-11-24 12:57:57'),
(11, 14, 6, 'budget officer test comment @ \' \" \" \' ! ? / = - ) ( * & ^ % $ # > < . , ` ~', '2025-11-25 08:14:35'),
(15, 20, 1, 'bangtagal ah ah aaaaaa', '2025-11-25 15:26:56'),
(16, 20, 1, 'wait lang boss, kokontrata na \r\nborat', '2025-11-25 15:27:10'),
(17, 20, 1, 'pag wala pa sa isang araw\r\n\r\n\r\nsasabog lahat', '2025-11-25 15:27:22'),
(18, 20, 1, 'The dog jumps over the fox that is sleeping soundly under the tree. Then a fruit fell off beao', '2025-11-25 15:27:48'),
(19, 20, 1, 'Laptop pc building dog cat mouse keyboard keypad tablet phone cellphone iphone android ios personal computer tumblr mirror paper notebook book ballpen marker writer typewriting printer scanner paper cutter', '2025-11-25 15:28:45'),
(20, 20, 1, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------', '2025-11-25 15:29:33'),
(22, 21, 8, 'yorme, baka namannnnnnnnnn yung sa mpo ninakaw na ', '2025-11-25 15:37:46'),
(23, 21, 8, 'aray koooo 22', '2025-11-25 15:37:53'),
(24, 21, 8, 'portable power', '2025-11-25 15:38:03'),
(25, 21, 8, 'ilang % na yung akin men charge\r\n', '2025-11-25 15:38:12'),
(26, 21, 8, 'check ko wait', '2025-11-25 15:38:17'),
(27, 21, 8, '80% na pre', '2025-11-25 15:38:27');

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(30) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `manager_id` int(30) NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `pr_no` varchar(100) DEFAULT NULL,
  `particulars` text DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `mop` varchar(50) DEFAULT NULL,
  `received_bac_first` date DEFAULT NULL,
  `received_gso_first` date DEFAULT NULL,
  `procurement_type` varchar(50) DEFAULT NULL,
  `remarks_pr_no` varchar(255) DEFAULT NULL,
  `philgeps_posting` varchar(50) DEFAULT NULL,
  `supplier` varchar(200) DEFAULT NULL,
  `contract_cost` decimal(15,2) DEFAULT NULL,
  `received_bac_second` date DEFAULT NULL,
  `bac_reso_no` varchar(100) DEFAULT NULL,
  `bac_reso_date` date DEFAULT NULL,
  `received_gso_second` date DEFAULT NULL,
  `po_no` varchar(100) DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `air_no` varchar(100) DEFAULT NULL,
  `air_date` date DEFAULT NULL,
  `received_bo` date DEFAULT NULL,
  `return_gso_completion` date DEFAULT NULL,
  `received_bac_third` date DEFAULT NULL,
  `rfq_no` varchar(100) DEFAULT NULL,
  `reposting` varchar(50) DEFAULT NULL,
  `returned_gso_abstract` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `status`, `start_date`, `end_date`, `manager_id`, `user_ids`, `date_created`, `pr_no`, `particulars`, `amount`, `mop`, `received_bac_first`, `received_gso_first`, `procurement_type`, `remarks_pr_no`, `philgeps_posting`, `supplier`, `contract_cost`, `received_bac_second`, `bac_reso_no`, `bac_reso_date`, `received_gso_second`, `po_no`, `po_date`, `air_no`, `air_date`, `received_bo`, `return_gso_completion`, `received_bac_third`, `rfq_no`, `reposting`, `returned_gso_abstract`) VALUES
(14, 2, '2025-12-01', '2025-01-29', 0, '', '2025-11-20 13:57:33', '0096', 'Food for \"Balikbayan Day 2025\", January 24, 2025 (6:00AM) -Tanay Municipal Grounds.', 12.00, 'repeat', '2025-01-16', '2025-01-16', 'consolidated', '18', 'With Posting', 'Gillan Marie Catering Services', 87650.00, '2025-01-21', '01-0005', '2025-01-20', '0000-00-00', '2025-01-0003-100', '2025-01-21', '100-2025-0004', '2025-01-27', '2025-01-28', '2025-01-29', '0000-00-00', '', 'With Reposting', '0000-00-00'),
(20, 4, '2025-10-30', '2025-12-05', 0, '', '2025-11-25 15:26:18', '01014-898', 'Budget for IMS Laptops to Make Systems and Programs', 312.00, 'svp', '2025-11-06', '2025-11-11', 'consolidated', '0000-121234', 'With Posting', 'ASUSTeK ', 400.00, '2025-11-22', '1999-0478', '2025-11-23', '2025-11-26', '0024-989', '2025-11-27', '0809-341', '2025-11-28', '2025-11-30', '2025-12-03', '2025-11-16', '111-0345', 'With Reposting', '2025-11-19'),
(21, 1, '2025-12-31', '2026-03-25', 0, '', '2025-11-25 15:36:33', '123', 'Server Improvement for 2026', 500000.00, 'lease', '2026-01-06', '2026-01-09', 'single', '009-132', 'Without Posting', 'GIGABYTE', 700000.00, '2026-01-12', '0065-997', '2026-01-14', '2026-01-17', '002-214', '2026-01-19', '00123-121', '2026-01-21', '2026-01-25', '2026-03-02', '0000-00-00', '', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Procurement Management System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', 'wallpaper.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1 = admin, 2 = staff',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `type`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 1, '2020-11-26 10:57:04'),
(6, 'Budget', 'Office', 'bo@user.com', '6ad14ba9986e3615423dfca256d04e3f', 2, '2025-11-21 13:28:28'),
(7, 'Rowell', 'Polinio', 'rowell@user.com', '6ad14ba9986e3615423dfca256d04e3f', 2, '2025-11-21 16:54:08'),
(8, 'Fundador', 'Candaza', 'Fudz@user.com', '6ad14ba9986e3615423dfca256d04e3f', 2, '2025-11-25 15:33:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
