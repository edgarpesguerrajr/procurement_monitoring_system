-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 04:58 AM
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
(29, 36, 6, 'testing by BO\r\n', '2025-12-02 13:45:32'),
(30, 36, 1, 'testing by admin', '2025-12-02 13:45:58'),
(31, 27, 1, 'sample comment', '2025-12-02 16:00:18'),
(32, 27, 6, 'comment ko to', '2025-12-03 10:59:23'),
(33, 36, 1, 'test comment for notification', '2025-12-03 11:02:06'),
(34, 36, 1, 'test', '2025-12-03 11:06:42'),
(35, 37, 1, 'notif try', '2025-12-03 11:15:34'),
(36, 37, 1, '13123123', '2025-12-03 11:22:26');

-- --------------------------------------------------------

--
-- Table structure for table `consolidated`
--

CREATE TABLE `consolidated` (
  `id` int(11) NOT NULL,
  `project_id` int(30) NOT NULL,
  `pr_no` varchar(100) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `particular` text DEFAULT NULL,
  `grand_total` decimal(15,2) DEFAULT NULL,
  `row_order` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consolidated`
--

INSERT INTO `consolidated` (`id`, `project_id`, `pr_no`, `amount`, `particular`, `grand_total`, `row_order`, `created_at`) VALUES
(78, 36, '0017', 469636.00, 'ADVOCACY SHIRT FOR MUNICIPAL POPULATION DEVELOPMENT PROGRAM QUARTERLY CONSULTATIVE MEETING ORIENTATION OF BARANGAY POPULATION VOLUNTEER WORKER  FEBRUARY 2025', 481957.00, 1, '2025-12-03 10:31:35'),
(79, 36, '0101', 12321.00, 'Food for FAMILIARIZATION TOUR: \"Balikbayan and OFWs Day Tour\", January 25, 2025.', 481957.00, 2, '2025-12-03 10:31:35'),
(80, 27, '0018', 80000.00, 'PR1', 80000.00, 1, '2025-12-03 10:44:49'),
(81, 27, '0099', NULL, '', 80000.00, 2, '2025-12-03 10:44:49'),
(84, 51, '1231', 12313.00, 'fafafasda', 135436.00, 1, '2025-12-03 11:56:37'),
(85, 51, '3121', 123123.00, 'asdasdad', 135436.00, 2, '2025-12-03 11:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `actor_id`, `project_id`, `message`, `is_read`, `created_at`) VALUES
(1, 6, 1, 27, 'Administrator  commented on PR no. 0018', 0, '2025-12-02 16:00:18'),
(2, 9, 1, 27, 'Administrator  commented on PR no. 0018', 0, '2025-12-02 16:00:18'),
(3, 123, 45, 67, 'John Doe has an update in PR-001', 0, NULL),
(4, 6, 1, 37, 'Administrator  has an update in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 10:58:19'),
(5, 9, 1, 37, 'Administrator  has an update in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 10:58:19'),
(6, 1, 6, 27, 'Budget Office has a comment in Food for \"Balikbayan Day 2025\", January 24, 2025 (6:00AM) -Tanay Municipal Grounds.', 0, '2025-12-03 10:59:23'),
(7, 9, 6, 27, 'Budget Office has a comment in Food for \"Balikbayan Day 2025\", January 24, 2025 (6:00AM) -Tanay Municipal Grounds.', 0, '2025-12-03 10:59:23'),
(8, 6, 1, 36, 'Administrator  has a comment in Meals for the Masungi Rock Management Council Meeting on January 31, 2025 at JE Camp Resort, Sampaloc, Tanay, Rizal', 0, '2025-12-03 11:02:06'),
(9, 9, 1, 36, 'Administrator  has a comment in Meals for the Masungi Rock Management Council Meeting on January 31, 2025 at JE Camp Resort, Sampaloc, Tanay, Rizal', 0, '2025-12-03 11:02:06'),
(10, 6, 1, 36, 'Administrator  has a comment in Meals for the Masungi Rock Management Council Meeting on January 31, 2025 at JE Camp Resort, Sampaloc, Tanay, Rizal', 0, '2025-12-03 11:06:42'),
(11, 9, 1, 36, 'Administrator  has a comment in Meals for the Masungi Rock Management Council Meeting on January 31, 2025 at JE Camp Resort, Sampaloc, Tanay, Rizal', 0, '2025-12-03 11:06:42'),
(12, 6, 1, 37, 'Administrator  has a comment in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 11:15:34'),
(13, 9, 1, 37, 'Administrator  has a comment in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 11:15:34'),
(14, 6, 1, 37, 'Administrator  has a comment in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 11:22:26'),
(15, 9, 1, 37, 'Administrator  has a comment in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 11:22:26'),
(16, 6, 1, 47, 'Administrator  has an update in sample title', 0, '2025-12-03 11:42:01'),
(17, 9, 1, 47, 'Administrator  has an update in sample title', 0, '2025-12-03 11:42:01'),
(18, 6, 1, 48, 'Administrator  has an update in 312312', 0, '2025-12-03 11:47:03'),
(19, 9, 1, 48, 'Administrator  has an update in 312312', 0, '2025-12-03 11:47:03'),
(20, 6, 1, 37, 'Administrator  has an update in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 11:53:36'),
(21, 9, 1, 37, 'Administrator  has an update in Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 0, '2025-12-03 11:53:36'),
(22, 6, 1, 49, 'Administrator  has an update in sadadasd', 0, '2025-12-03 11:54:00'),
(23, 9, 1, 49, 'Administrator  has an update in sadadasd', 0, '2025-12-03 11:54:00'),
(24, 6, 1, 50, 'Administrator  has an update in Document #50', 0, '2025-12-03 11:55:44'),
(25, 9, 1, 50, 'Administrator  has an update in Document #50', 0, '2025-12-03 11:55:44'),
(26, 6, 1, 50, 'Administrator  has an update in 1231', 0, '2025-12-03 11:55:59'),
(27, 9, 1, 50, 'Administrator  has an update in 1231', 0, '2025-12-03 11:55:59'),
(28, 6, 1, 51, 'Administrator  has an update in title', 0, '2025-12-03 11:56:37'),
(29, 9, 1, 51, 'Administrator  has an update in title', 0, '2025-12-03 11:56:37');

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `id` int(30) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `manager_id` int(30) NOT NULL,
  `user_ids` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `pr_no` varchar(100) DEFAULT NULL,
  `particulars` text DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `mop` varchar(50) DEFAULT NULL,
  `received_bac_first` datetime DEFAULT NULL,
  `received_gso_first` datetime DEFAULT NULL,
  `procurement_type` varchar(50) DEFAULT NULL,
  `philgeps_posting` varchar(50) DEFAULT NULL,
  `supplier` varchar(200) DEFAULT NULL,
  `contract_cost` decimal(15,2) DEFAULT NULL,
  `received_bac_second` datetime DEFAULT NULL,
  `bac_reso_no` varchar(100) DEFAULT NULL,
  `bac_reso_date` date DEFAULT NULL,
  `received_gso_second` datetime DEFAULT NULL,
  `po_no` varchar(100) DEFAULT NULL,
  `po_date` datetime DEFAULT NULL,
  `air_no` varchar(100) DEFAULT NULL,
  `air_date` datetime DEFAULT NULL,
  `received_bo_first` datetime DEFAULT NULL,
  `return_gso_completion` datetime DEFAULT NULL,
  `received_bac_third` datetime DEFAULT NULL,
  `rfq_no` varchar(100) DEFAULT NULL,
  `reposting` varchar(50) DEFAULT NULL,
  `returned_gso_abstract` datetime DEFAULT NULL,
  `received_bo_second` datetime DEFAULT NULL,
  `received_accounting_first` datetime DEFAULT NULL,
  `received_accounting_second` datetime DEFAULT NULL,
  `received_treasury_first` datetime DEFAULT NULL,
  `received_treasury_second` datetime DEFAULT NULL,
  `received_treasury_third` datetime DEFAULT NULL,
  `received_treasury_fourth` datetime DEFAULT NULL,
  `received_mo` datetime DEFAULT NULL,
  `received_admin` datetime DEFAULT NULL,
  `cheque_no` varchar(100) DEFAULT NULL,
  `paid` enum('yes','no') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_list`
--

INSERT INTO `project_list` (`id`, `status`, `start_date`, `manager_id`, `user_ids`, `date_created`, `pr_no`, `particulars`, `amount`, `mop`, `received_bac_first`, `received_gso_first`, `procurement_type`, `philgeps_posting`, `supplier`, `contract_cost`, `received_bac_second`, `bac_reso_no`, `bac_reso_date`, `received_gso_second`, `po_no`, `po_date`, `air_no`, `air_date`, `received_bo_first`, `return_gso_completion`, `received_bac_third`, `rfq_no`, `reposting`, `returned_gso_abstract`, `received_bo_second`, `received_accounting_first`, `received_accounting_second`, `received_treasury_first`, `received_treasury_second`, `received_treasury_third`, `received_treasury_fourth`, `received_mo`, `received_admin`, `cheque_no`, `paid`) VALUES
(27, 0, '2025-01-14', 0, '', '2025-11-28 16:31:08', NULL, 'Food for \"Balikbayan Day 2025\", January 24, 2025 (6:00AM) -Tanay Municipal Grounds.', 80000.00, 'Single Value Procurement', '2025-11-28 16:30:00', '2025-11-28 16:30:00', 'consolidated', 'With Posting', 'Gillan Marie Catering Services', 70000.00, '2025-11-28 16:30:00', '2025-01-0003-100', '2025-12-02', '2025-11-28 16:30:00', '100-2025-0004', '2025-11-28 16:30:00', '2025-2024', '2025-11-28 16:30:00', '2025-11-28 16:30:00', '2025-11-28 16:30:00', '2025-11-28 16:30:00', '2025-07', 'Without Reposting', '2025-11-28 16:30:00', '2025-11-28 16:30:00', '2025-11-28 16:31:00', '2025-11-28 16:31:00', '2025-11-28 16:30:00', '2025-11-28 16:31:00', '2025-11-28 16:31:00', '2025-11-28 16:31:00', '2025-11-28 16:31:00', '2025-11-28 16:31:00', '', 'no'),
(36, 0, '2025-12-02', 0, '', '2025-12-02 10:38:43', NULL, 'Meals for the Masungi Rock Management Council Meeting on January 31, 2025 at JE Camp Resort, Sampaloc, Tanay, Rizal', 481957.00, 'Single Value Procurement', '2025-12-02 15:30:00', '2025-12-02 15:30:00', 'consolidated', 'Without Posting', 'asdasd', 123123123.00, '2025-12-02 15:31:00', '1231', '2025-12-02', '2025-12-02 15:31:00', '123123', '2025-12-02 15:31:00', '123123', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', NULL, '12312', NULL, '2025-12-02 15:30:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '2025-12-02 15:31:00', '123123', 'yes'),
(37, 0, '2025-12-02', 0, '', '2025-12-02 13:24:05', '0098', 'Food For FAMILIARIZATION TOUR: \"Balikbayan And OFWs Day Tour\", January 25, 2025.', 12311.00, 'Single Value Procurement', '2025-12-02 15:41:00', '2025-12-02 15:41:00', 'single', 'With Posting', 'ASUSTek', 10000.00, '2025-12-02 15:41:00', '12313', '2025-12-02', '2025-12-02 15:41:00', '123123', '2025-12-02 15:41:00', '123123', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '123123', 'Without Reposting', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '2025-12-02 15:41:00', '13123123', 'yes'),
(46, 0, '2025-12-03', 0, '', '2025-12-03 10:09:05', '0987', 'food', 1231.00, 'Single Value Procurement', '2025-12-03 10:09:00', '2025-12-03 10:09:00', 'single', 'With Posting', 'qasdasd', 13123123.00, '2025-12-03 10:09:00', '12313', '2025-12-03', '2025-12-03 10:26:00', '123123', '2025-12-03 10:27:00', '123123', '2025-12-03 10:52:00', '2025-12-03 10:52:00', '0000-00-00 00:00:00', '2025-12-03 10:09:00', '123', 'With Reposting', '2025-12-03 10:09:00', '2025-12-03 10:52:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2025-12-03 10:52:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2025-12-03 10:09:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'no'),
(51, 0, '0000-00-00', 0, '', '2025-12-03 11:56:37', NULL, 'title', 135436.00, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'consolidated', '', '', 0.00, '0000-00-00 00:00:00', '', '0000-00-00', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 'no');

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
(1, 'Procurement Monitoring System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', 'wallpaper.jpeg');

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
(9, 'GSO', 'Office', 'gso@user.com', '6ad14ba9986e3615423dfca256d04e3f', 2, '2025-12-02 13:47:16');

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
-- Indexes for table `consolidated`
--
ALTER TABLE `consolidated`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_consolidated_project_id` (`project_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `consolidated`
--
ALTER TABLE `consolidated`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `project_list`
--
ALTER TABLE `project_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consolidated`
--
ALTER TABLE `consolidated`
  ADD CONSTRAINT `fk_consolidated_project` FOREIGN KEY (`project_id`) REFERENCES `project_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
