-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 09:06 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ink_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `barcode` varchar(15) NOT NULL,
  `description` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(500) NOT NULL,
  `user_id` varchar(60) NOT NULL,
  `date` datetime NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `barcode`, `description`, `qty`, `amount`, `image`, `user_id`, `date`, `views`) VALUES
(50, '222220889025', 'chocolate cake', 25, 400.00, 'uploads/2c61ab8293a5f3bee51de58278c48ac8e13a0d79_4539.jpg', '13', '2025-07-14 12:55:14', 14),
(53, '2222809715042', 'Bread', 30, 25.00, 'uploads/a641f067b91c3d3de591f3e67470f64067e6845f_6369.jpg', '13', '2025-07-20 14:54:57', 0),
(54, '222239490453', 'Layer Cake', 15, 600.00, 'uploads/e191200011f66d8e90312b96fe510347183010a9_6122.jpeg', '13', '2025-07-20 15:12:20', 9),
(55, '2222942609238', 'whipped Cream Cake', 15, 500.00, 'uploads/966888583ad0508208dbfa92f230c4ffb5712815_8858.jpeg', '13', '2025-07-20 15:13:40', 13),
(56, '2222786608070', 'burger and chips', 10, 90.00, 'uploads/942b5cba956fd84e962efde0cb61a7fd8a0cf7a0_8032.jpeg', '13', '2025-07-20 16:44:20', 21),
(57, '2222116943532', 'drink1', 20, 30.00, 'uploads/7be546a4790db7fb622602079dc277e0711ff27c_1969.jpeg', '13', '2025-08-22 08:42:59', 0),
(58, '2222660365268', 'drink2', 10, 35.00, 'uploads/c03207d9b50a19465054c1e2d37328892e27f8a5_4479.jpeg', '13', '2025-08-22 08:44:29', 0),
(59, '2222575362538', 'drink3', 10, 40.00, 'uploads/790e0919e90d2f46f88930c051049a871ccaa376_4048.jpeg', '13', '2025-08-22 08:45:00', 0),
(60, '2222381466231', 'drink4', 10, 50.00, 'uploads/35b4062f25d7f842d9d7b8a8ef287134802159e5_9899.jpeg', '13', '2025-08-22 08:46:31', 0),
(61, '2222308512130', 'random1', 10, 200.00, 'uploads/a6c01806d767d2759e59e45b6efd867cf391ce4d_4630.jpeg', '13', '2025-08-22 08:47:31', 0),
(62, '2222435076730', 'random2', 10, 250.00, 'uploads/c1a0c3c39dfae49589e09df52da3dd99d60cc03c_1350.jpeg', '13', '2025-08-22 08:49:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `barcode` varchar(15) NOT NULL,
  `receipt_no` int(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` varchar(60) NOT NULL,
  `phone` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `barcode`, `receipt_no`, `description`, `qty`, `amount`, `total`, `date`, `user_id`, `phone`) VALUES
(10, '2222786608070', 1, 'burger and chips', 1, 90.00, 90.00, '2025-07-20 17:02:23', '13', '0973519161'),
(11, '2222942609238', 2, 'whipped Cream Cake', 1, 500.00, 500.00, '2025-07-20 17:03:02', '13', '0973519161'),
(12, '2222942609238', 3, 'whipped Cream Cake', 2, 500.00, 1000.00, '2025-07-20 17:13:38', '13', '0973519161'),
(13, '222239490453', 4, 'Layer Cake', 1, 600.00, 600.00, '2025-07-20 18:15:45', '13', '0973519161'),
(14, '222239490453', 5, 'Layer Cake', 5, 600.00, 3000.00, '2025-07-20 18:25:03', '13', '0973519161'),
(15, '2222942609238', 6, 'whipped Cream Cake', 5, 500.00, 2500.00, '2025-07-20 18:26:11', '13', '0973519161'),
(16, '2222786608070', 7, 'burger and chips', 2, 90.00, 180.00, '2025-07-21 10:11:28', '13', '0973519161'),
(17, '2222942609238', 8, 'whipped Cream Cake', 2, 500.00, 1000.00, '2025-07-21 10:26:40', '13', '0973519161'),
(18, '2222942609238', 9, 'whipped Cream Cake', 2, 500.00, 1000.00, '2025-07-21 19:46:12', '13', '0973519161'),
(19, '2222942609238', 10, 'whipped Cream Cake', 2, 500.00, 1000.00, '2025-07-26 12:33:01', '13', '0973519161'),
(20, '2222942609238', 11, 'whipped Cream Cake', 6, 500.00, 3000.00, '2025-07-26 12:50:05', '13', '0973519161'),
(21, '2222786608070', 12, 'burger and chips', 2, 90.00, 180.00, '2025-07-26 12:55:23', '13', '0973519161'),
(22, '222239490453', 13, 'Layer Cake', 2, 600.00, 1200.00, '2025-07-26 13:08:17', '13', '0973519161'),
(23, '222220889025', 14, 'chocolate cake', 2, 400.00, 800.00, '2025-07-26 14:31:53', '13', '+260980060705'),
(24, '222220889025', 15, 'chocolate cake', 2, 400.00, 800.00, '2025-07-26 14:34:07', '13', '+260980060705'),
(28, '2222942609238', 16, 'whipped Cream Cake', 2, 500.00, 1000.00, '2025-07-26 16:09:41', '13', '0970099422'),
(29, '2222786608070', 17, 'burger and chips', 1, 90.00, 90.00, '2025-07-26 17:11:59', '13', '0973519161'),
(30, '222220889025', 17, 'chocolate cake', 1, 400.00, 400.00, '2025-07-26 17:11:59', '13', '0973519161'),
(31, '2222786608070', 18, 'burger and chips', 1, 90.00, 90.00, '2025-07-26 23:21:45', '13', '0980060705'),
(32, '222220889025', 18, 'chocolate cake', 2, 400.00, 800.00, '2025-07-26 23:21:45', '13', '0980060705'),
(33, '222220889025', 19, 'chocolate cake', 2, 400.00, 800.00, '2025-07-26 23:29:26', '13', '0970099422'),
(34, '2222786608070', 20, 'burger and chips', 1, 90.00, 90.00, '2025-07-27 11:48:14', '13', '0980060705'),
(35, '222220889025', 20, 'chocolate cake', 1, 400.00, 400.00, '2025-07-27 11:48:14', '13', '0980060705'),
(36, '2222942609238', 21, 'whipped Cream Cake', 2, 500.00, 1000.00, '2025-07-27 12:19:16', '13', '0973519161'),
(37, '2222786608070', 22, 'burger and chips', 1, 90.00, 90.00, '2025-07-27 12:25:26', '13', '0973519161'),
(38, '222220889025', 22, 'chocolate cake', 1, 400.00, 400.00, '2025-07-27 12:25:26', '13', '0973519161'),
(39, '2222942609238', 22, 'whipped Cream Cake', 1, 500.00, 500.00, '2025-07-27 12:25:26', '13', '0973519161'),
(40, '222239490453', 22, 'Layer Cake', 1, 600.00, 600.00, '2025-07-27 12:25:26', '13', '0973519161'),
(41, '2222786608070', 23, 'burger and chips', 2, 90.00, 180.00, '2025-07-27 12:26:52', '13', '0973519161'),
(42, '222220889025', 24, 'chocolate cake', 1, 400.00, 400.00, '2025-07-27 12:29:12', '13', '0973519161'),
(43, '222239490453', 24, 'Layer Cake', 2, 600.00, 1200.00, '2025-07-27 12:29:12', '13', '0973519161'),
(44, '222239490453', 25, 'Layer Cake', 2, 600.00, 1200.00, '2025-07-27 12:51:43', '13', '0973519161'),
(45, '2222786608070', 26, 'burger and chips', 2, 90.00, 180.00, '2025-07-27 12:54:44', '13', '0973519161'),
(46, '2222786608070', 27, 'burger and chips', 2, 90.00, 180.00, '2025-07-28 15:56:40', '14', '0980060705'),
(47, '2222786608070', 28, 'burger and chips', 2, 90.00, 180.00, '2025-08-21 15:29:17', '13', '0973519161'),
(48, '222220889025', 28, 'chocolate cake', 2, 400.00, 800.00, '2025-08-21 15:29:17', '13', '0973519161'),
(49, '2222786608070', 29, 'burger and chips', 2, 90.00, 180.00, '2025-08-21 19:46:42', '13', '0968040114'),
(50, '2222942609238', 29, 'whipped Cream Cake', 2, 500.00, 1000.00, '2025-08-21 19:46:42', '13', '0968040114'),
(51, '2222942609238', 30, 'whipped Cream Cake', 1, 500.00, 500.00, '2025-08-21 20:23:55', '13', '0968040114'),
(52, '2222786608070', 31, 'burger and chips', 2, 90.00, 180.00, '2025-08-21 20:34:28', '13', '0968040114'),
(53, '222220889025', 32, 'chocolate cake', 2, 400.00, 800.00, '2025-08-21 21:16:07', '13', '0968040114'),
(54, '222220889025', 33, 'chocolate cake', 2, 400.00, 800.00, '2025-08-21 21:59:34', '13', '0968040114'),
(55, '222239490453', 34, 'Layer Cake', 2, 600.00, 1200.00, '2025-08-21 22:01:57', '13', '0968040114'),
(56, '222220889025', 35, 'chocolate cake', 2, 400.00, 800.00, '2025-08-21 22:16:12', '13', '0968040114'),
(57, '2222786608070', 36, 'burger and chips', 2, 90.00, 180.00, '2025-08-21 22:50:40', '13', '0968040114'),
(58, '2222786608070', 37, 'burger and chips', 2, 90.00, 180.00, '2025-08-21 23:10:07', '13', '0968040114'),
(59, '222239490453', 38, 'Layer Cake', 2, 600.00, 1200.00, '2025-08-21 23:16:08', '13', '0968040114'),
(60, '222239490453', 39, 'Layer Cake', 2, 600.00, 1200.00, '2025-08-21 23:40:39', '13', ''),
(61, '2222786608070', 40, 'burger and chips', 2, 90.00, 180.00, '2025-08-21 23:42:57', '13', ''),
(62, '222220889025', 41, 'chocolate cake', 2, 400.00, 800.00, '2025-08-21 23:53:18', '13', ''),
(63, '2222786608070', 42, 'burger and chips', 2, 90.00, 180.00, '2025-08-21 23:54:52', '13', '0968040114'),
(64, '2222786608070', 43, 'burger and chips', 2, 90.00, 180.00, '2025-08-22 09:40:59', '13', '0968040114');

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `receipt_no` varchar(50) NOT NULL,
  `date_sent` datetime NOT NULL,
  `response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sms_logs`
--

INSERT INTO `sms_logs` (`id`, `phone`, `message`, `receipt_no`, `date_sent`, `response`) VALUES
(2, '+260973519161', 'Receipt No: 13\\nDate: 26th Jul, 2025\\nTotal: $1,200.00\\nThank you for your purchase!', '13', '2025-07-26 13:14:57', 'Failed'),
(10, '+260973519161', 'Receipt No: 13\\nDate: 26th Jul, 2025\\nTotal: $1,200.00\\nThank you for your purchase!', '13', '2025-07-26 14:16:58', 'Success'),
(12, '+26+260980060705', 'Ink Groceries Receipt, Receipt #: 14, Date: 2025-07-26 14:31:53, Description: chocolate cake, Amount K: 400.00', '14', '2025-07-26 14:31:53', 'SMS data sent to Arduino GSM module on COM12.'),
(13, '+26+260980060705', 'Receipt No: 14\\nDate: 26th Jul, 2025\\nTotal: $800.00\\nThank you for your purchase!', '14', '2025-07-26 14:32:18', 'Success'),
(16, '+26+260980060705', 'Receipt No: 15\\nDate: 26th Jul, 2025\\nTotal: $800.00\\nThank you for your purchase!', '15', '2025-07-26 14:35:30', 'Success'),
(18, '+260980060705', 'Ink Groceries Receipt, Receipt #: 16, Date: 2025-07-26 14:39:02, Description: burger and chips, Amount K: 90.00', '16', '2025-07-26 14:39:02', 'SMS data sent to Arduino GSM module on COM12.'),
(19, '+260980060705', 'Receipt No: 16\\nDate: 26th Jul, 2025\\nTotal: $180.00\\nThank you for your purchase!', '16', '2025-07-26 14:40:03', 'Success'),
(20, '+260980060705', 'Receipt No: 16\\nDate: 26th Jul, 2025\\nTotal: $180.00\\nThank you for your purchase!', '16', '2025-07-26 14:40:07', 'Success'),
(21, '+260970099422', 'Ink Groceries Receipt, Receipt #: 17, Date: 2025-07-26 14:53:19, Description: whipped Cream Cake, Amount K: 500.00', '17', '2025-07-26 14:53:19', 'SMS data sent to Arduino GSM module on COM12.'),
(22, '+260970099422', 'Receipt No: 17\\nDate: 26th Jul, 2025\\nTotal: ZMW1,000.00\\nThank you for your purchase!', '17', '2025-07-26 14:53:38', 'Success'),
(23, '+260970099422', 'Receipt No: 17\\nDate: 26th Jul, 2025\\nTotal: ZMW1,000.00\\nThank you for your purchase!', '17', '2025-07-26 14:53:41', 'Success'),
(26, '+260970099422', 'Pos System\nReceipt No: 16\nDate: 26th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- whipped Cream Cake x2 @ K500.00 = K1,000.00\nTOTAL: K1,090.00\nThank you for your purchase!', '16', '2025-07-26 17:03:31', 'Success'),
(27, '+260970099422', 'Pos System\nReceipt No: 16\nDate: 26th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- whipped Cream Cake x2 @ K500.00 = K1,000.00\nTOTAL: K1,090.00\nThank you for your purchase!', '16', '2025-07-26 17:03:34', 'Success'),
(29, '+260973519161', 'Ink Groceries Receipt, Receipt #: 17, Date: 2025-07-26 17:11:59, Description: chocolate cake, Amount K: 400.00', '17', '2025-07-26 17:11:59', 'SMS data sent to Arduino GSM module on COM12.'),
(30, '+260973519161', 'Pos System\nReceipt No: 17\nDate: 26th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- chocolate cake x1 @ K400.00 = K400.00\nTOTAL: K490.00\nThank you for your purchase!', '17', '2025-07-26 17:12:22', 'Success'),
(31, '+260973519161', 'Pos System\nReceipt No: 17\nDate: 26th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- chocolate cake x1 @ K400.00 = K400.00\nTOTAL: K490.00\nThank you for your purchase!', '17', '2025-07-26 17:12:24', 'Success'),
(32, '+260970099422', 'Pos System\nReceipt No: 16\nDate: 26th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- whipped Cream Cake x2 @ K500.00 = K1,000.00\nTOTAL: K1,090.00\nThank you for your purchase!', '16', '2025-07-26 17:13:58', 'Success'),
(33, '+260970099422', 'Pos System\nReceipt No: 16\nDate: 26th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- whipped Cream Cake x2 @ K500.00 = K1,000.00\nTOTAL: K1,090.00\nThank you for your purchase!', '16', '2025-07-26 17:14:00', 'Success'),
(35, '+260980060705', 'Ink Groceries Receipt, Receipt #: 18, Date: 2025-07-26 23:21:45, Description: chocolate cake, Amount K: 400.00', '18', '2025-07-26 23:21:45', 'SMS data sent to Arduino GSM module on COM12.'),
(36, '+260970099422', 'Ink Groceries Receipt, Receipt #: 19, Date: 2025-07-26 23:29:26, Description: chocolate cake, Amount K: 400.00', '19', '2025-07-26 23:29:26', 'SMS data sent to Arduino GSM module on COM12.'),
(37, '+260970099422', 'Pos System\nReceipt No: 19\nDate: 26th Jul, 2025\nItems:\n- chocolate cake x2 @ K400.00 = K800.00\nTOTAL: K800.00\nThank you for your purchase!', '19', '2025-07-26 23:29:53', 'Failed'),
(38, '+260970099422', 'Pos System\nReceipt No: 19\nDate: 26th Jul, 2025\nItems:\n- chocolate cake x2 @ K400.00 = K800.00\nTOTAL: K800.00\nThank you for your purchase!', '19', '2025-07-26 23:29:58', 'Failed'),
(39, '+260980060705', 'Ink Groceries Receipt, Receipt #: 20, Date: 2025-07-27 11:48:14, Description: burger and chips, Amount K: 90.00', '20', '2025-07-27 11:48:14', 'SMS data sent to Arduino GSM module on COM12.'),
(40, '+260980060705', 'Ink Groceries Receipt, Receipt #: 20, Date: 2025-07-27 11:48:14, Description: chocolate cake, Amount K: 400.00', '20', '2025-07-27 11:48:14', 'SMS data sent to Arduino GSM module on COM12.'),
(41, '+260980060705', 'Pos System\nReceipt No: 20\nDate: 27th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- chocolate cake x1 @ K400.00 = K400.00\nTOTAL: K490.00\nThank you for your purchase!', '20', '2025-07-27 11:48:30', 'Success'),
(42, '+260980060705', 'Pos System\nReceipt No: 20\nDate: 27th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- chocolate cake x1 @ K400.00 = K400.00\nTOTAL: K490.00\nThank you for your purchase!', '20', '2025-07-27 11:48:32', 'Success'),
(43, '+260973519161', 'Ink Groceries Receipt, Receipt #: 21, Date: 2025-07-27 12:19:16, Description: whipped Cream Cake, Amount K: 500.00', '21', '2025-07-27 12:19:16', 'SMS data sent to Arduino GSM module on COM12.'),
(44, '+260973519161', 'Pos System\nReceipt No: 21\nDate: 27th Jul, 2025\nItems:\n- whipped Cream Cake x2 @ K500.00 = K1,000.00\nTOTAL: K1,000.00\nThank you for your purchase!', '21', '2025-07-27 12:20:51', 'Success'),
(45, '+260973519161', 'Pos System\nReceipt No: 21\nDate: 27th Jul, 2025\nItems:\n- whipped Cream Cake x2 @ K500.00 = K1,000.00\nTOTAL: K1,000.00\nThank you for your purchase!', '21', '2025-07-27 12:20:55', 'Success'),
(46, '+260973519161', 'Ink Groceries Receipt, Receipt #: 22, Date: 2025-07-27 12:25:26, Description: burger and chips, Amount K: 90.00', '22', '2025-07-27 12:25:26', 'SMS data sent to Arduino GSM module on COM12.'),
(47, '+260973519161', 'Ink Groceries Receipt, Receipt #: 22, Date: 2025-07-27 12:25:26, Description: chocolate cake, Amount K: 400.00', '22', '2025-07-27 12:25:26', 'SMS data sent to Arduino GSM module on COM12.'),
(48, '+260973519161', 'Ink Groceries Receipt, Receipt #: 22, Date: 2025-07-27 12:25:26, Description: whipped Cream Cake, Amount K: 500.00', '22', '2025-07-27 12:25:26', 'SMS data sent to Arduino GSM module on COM12.'),
(49, '+260973519161', 'Ink Groceries Receipt, Receipt #: 22, Date: 2025-07-27 12:25:26, Description: Layer Cake, Amount K: 600.00', '22', '2025-07-27 12:25:26', 'SMS data sent to Arduino GSM module on COM12.'),
(50, '+260973519161', 'Pos System\nReceipt No: 22\nDate: 27th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- chocolate cake x1 @ K400.00 = K400.00\n- whipped Cream Cake x1 @ K500.00 = K500.00\n- Layer Cake x1 @ K600.00 = K600.00\nTOTAL: K1,590.00\nThank you for your purchase!', '22', '2025-07-27 12:25:40', 'Success'),
(51, '+260973519161', 'Pos System\nReceipt No: 22\nDate: 27th Jul, 2025\nItems:\n- burger and chips x1 @ K90.00 = K90.00\n- chocolate cake x1 @ K400.00 = K400.00\n- whipped Cream Cake x1 @ K500.00 = K500.00\n- Layer Cake x1 @ K600.00 = K600.00\nTOTAL: K1,590.00\nThank you for your purchase!', '22', '2025-07-27 12:25:43', 'Success'),
(52, '+260973519161', 'Ink Groceries Receipt, Receipt #: 23, Date: 2025-07-27 12:26:52, Description: burger and chips, Amount K: 90.00', '23', '2025-07-27 12:26:52', 'SMS data sent to Arduino GSM module on COM12.'),
(53, '+260973519161', 'Pos System\nReceipt No: 23\nDate: 27th Jul, 2025\nItems:\n- burger and chips x2 @ K90.00 = K180.00\nTOTAL: K180.00\nThank you for your purchase!', '23', '2025-07-27 12:27:08', 'Success'),
(54, '+260973519161', 'Pos System\nReceipt No: 23\nDate: 27th Jul, 2025\nItems:\n- burger and chips x2 @ K90.00 = K180.00\nTOTAL: K180.00\nThank you for your purchase!', '23', '2025-07-27 12:27:12', 'Success'),
(55, '+260973519161', 'Ink Groceries Receipt, Receipt #: 24, Date: 2025-07-27 12:29:12, Description: chocolate cake, Amount K: 400.00', '24', '2025-07-27 12:29:12', 'SMS data sent to Arduino GSM module on COM12.'),
(56, '+260973519161', 'Ink Groceries Receipt, Receipt #: 24, Date: 2025-07-27 12:29:12, Description: Layer Cake, Amount K: 600.00', '24', '2025-07-27 12:29:12', 'SMS data sent to Arduino GSM module on COM12.'),
(57, '+260973519161', 'Pos System\nReceipt No: 24\nDate: 27th Jul, 2025\nItems:\n- chocolate cake x1 @ K400.00 = K400.00\n- Layer Cake x2 @ K600.00 = K1,200.00\nTOTAL: K1,600.00\nThank you for your purchase!', '24', '2025-07-27 12:29:22', 'Success'),
(58, '+260973519161', 'Pos System\nReceipt No: 24\nDate: 27th Jul, 2025\nItems:\n- chocolate cake x1 @ K400.00 = K400.00\n- Layer Cake x2 @ K600.00 = K1,200.00\nTOTAL: K1,600.00\nThank you for your purchase!', '24', '2025-07-27 12:29:24', 'Success'),
(59, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 24\nDate: 27th Jul, 2025 12:29\n------------------\n• chocolate cake x1 @ K400.00 = K400.00\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,600.00\nThank you!', '24', '2025-07-27 12:50:34', 'Failed'),
(60, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 24\nDate: 27th Jul, 2025 12:29\n------------------\n• chocolate cake x1 @ K400.00 = K400.00\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,600.00\nThank you!', '24', '2025-07-27 12:50:49', 'Failed'),
(61, '+260973519161', 'Ink Groceries Receipt, Receipt #: 25, Date: 2025-07-27 12:51:43, Description: Layer Cake, Amount K: 600.00', '25', '2025-07-27 12:51:43', 'SMS data sent to Arduino GSM module on COM12.'),
(62, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 25\nDate: 27th Jul, 2025 12:51\n------------------\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,200.00\nThank you!', '25', '2025-07-27 12:52:24', 'Failed'),
(63, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 25\nDate: 27th Jul, 2025 12:51\n------------------\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,200.00\nThank you!', '25', '2025-07-27 12:52:39', 'Failed'),
(64, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 25\nDate: 27th Jul, 2025 12:51\n------------------\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,200.00\nThank you!', '25', '2025-07-27 12:53:47', 'Success'),
(65, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 25\nDate: 27th Jul, 2025 12:51\n------------------\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,200.00\nThank you!', '25', '2025-07-27 12:53:49', 'Success'),
(66, '+260973519161', 'Ink Groceries Receipt, Receipt #: 26, Date: 2025-07-27 12:54:44, Description: burger and chips, Amount K: 90.00', '26', '2025-07-27 12:54:44', 'SMS data sent to Arduino GSM module on COM12.'),
(67, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 26\nDate: 27th Jul, 2025 12:54\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n------------------\nTOTAL: K180.00\nThank you!', '26', '2025-07-27 12:54:58', 'Success'),
(68, '+260973519161', '=== POS RECEIPT ===\nReceipt #: 26\nDate: 27th Jul, 2025 12:54\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n------------------\nTOTAL: K180.00\nThank you!', '26', '2025-07-27 12:55:00', 'Success'),
(69, '+260980060705', 'Ink Groceries Receipt, Receipt #: 27, Date: 2025-07-28 15:56:40, Description: burger and chips, Amount K: 90.00', '27', '2025-07-28 15:56:40', 'SMS data sent to Arduino GSM module on COM12.'),
(70, '+260980060705', '=== POS RECEIPT ===\nReceipt #: 27\nDate: 28th Jul, 2025 15:56\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n------------------\nTOTAL: K180.00\nThank you!', '27', '2025-07-28 15:57:06', 'Failed'),
(71, '+260980060705', '=== POS RECEIPT ===\nReceipt #: 27\nDate: 28th Jul, 2025 15:56\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n------------------\nTOTAL: K180.00\nThank you!', '27', '2025-07-28 15:57:21', 'Failed'),
(72, '+260973519161', 'Ink Groceries Receipt, Receipt #: 28, Date: 2025-08-21 15:29:17, Description: burger and chips, Amount K: 90.00', '28', '2025-08-21 15:29:17', 'SMS data sent to Arduino GSM module on COM12.'),
(73, '+260973519161', 'Ink Groceries Receipt, Receipt #: 28, Date: 2025-08-21 15:29:17, Description: chocolate cake, Amount K: 400.00', '28', '2025-08-21 15:29:17', 'SMS data sent to Arduino GSM module on COM12.'),
(74, '+260968040114', 'Ink Groceries Receipt, Receipt #: 29, Date: 2025-08-21 19:46:42, Description: burger and chips, Amount K: 90.00', '29', '2025-08-21 19:46:42', 'SMS data sent to Arduino GSM module on COM12.'),
(75, '+260968040114', 'Ink Groceries Receipt, Receipt #: 29, Date: 2025-08-21 19:46:42, Description: whipped Cream Cake, Amount K: 500.00', '29', '2025-08-21 19:46:42', 'SMS data sent to Arduino GSM module on COM12.'),
(76, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:47:06', 'Failed'),
(77, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:47:21', 'Failed'),
(78, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:50:42', 'Failed'),
(79, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:50:58', 'Failed'),
(80, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:52:03', 'Failed'),
(81, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:52:18', 'Failed'),
(82, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:55:26', 'Failed'),
(83, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 19:55:41', 'Failed'),
(84, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 20:03:25', 'Failed'),
(85, '+260968040114', 'POS RECEIPT\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 20:03:40', 'Failed'),
(86, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n------------------\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 20:22:48', 'Failed'),
(87, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 29\nDate: 21st Aug, 2025 19:46\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n• whipped Cream Cake x2 @ K500.00 = K1,000.00\n------------------\nTOTAL: K1,180.00\nThank you!', '29', '2025-08-21 20:23:03', 'Failed'),
(88, '+260968040114', 'Ink Groceries Receipt, Receipt #: 30, Date: 2025-08-21 20:23:55, Description: whipped Cream Cake, Amount K: 500.00', '30', '2025-08-21 20:23:55', 'SMS data sent to Arduino GSM module on COM12.'),
(89, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 30\nDate: 21st Aug, 2025 20:23\n------------------\n• whipped Cream Cake x1 @ K500.00 = K500.00\n------------------\nTOTAL: K500.00\nThank you!', '30', '2025-08-21 20:24:18', 'Failed'),
(90, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 30\nDate: 21st Aug, 2025 20:23\n------------------\n• whipped Cream Cake x1 @ K500.00 = K500.00\n------------------\nTOTAL: K500.00\nThank you!', '30', '2025-08-21 20:24:33', 'Failed'),
(91, '+260968040114', 'Ink Groceries Receipt, Receipt #: 31, Date: 2025-08-21 20:34:28, Description: burger and chips, Amount K: 90.00', '31', '2025-08-21 20:34:28', 'SMS data sent to Arduino GSM module on COM12.'),
(92, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n------------------\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 20:34:37', 'Success'),
(93, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n------------------\n• burger and chips x2 @ K90.00 = K180.00\n------------------\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 20:34:39', 'Success'),
(94, '+260968040114', 'RECEIPT\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 20:36:29', 'Success'),
(95, '+260968040114', 'RECEIPT\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 20:36:38', 'Success'),
(96, '+260968040114', 'RECEIPT\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 21:14:39', 'Success'),
(97, '+260968040114', 'RECEIPT\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 21:14:51', 'Failed'),
(98, '+260968040114', 'RECEIPT\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 21:15:31', 'Failed'),
(99, '+260968040114', 'RECEIPT\nReceipt #: 31\nDate: 21st Aug, 2025 20:34\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '31', '2025-08-21 21:15:43', 'Failed'),
(100, '+260968040114', 'Ink Groceries Receipt, Receipt #: 32, Date: 2025-08-21 21:16:07, Description: chocolate cake, Amount K: 400.00', '32', '2025-08-21 21:16:07', 'SMS data sent to Arduino GSM module on COM12.'),
(101, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:16:33', 'Failed'),
(102, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:16:45', 'Failed'),
(103, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:17:48', 'Failed'),
(104, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:18:00', 'Failed'),
(105, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:18:11', 'Failed'),
(106, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:18:24', 'Failed'),
(107, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:24:32', 'Success'),
(108, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:24:44', 'Failed'),
(109, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:24:53', 'Success'),
(110, '+260968040114', 'RECEIPT\nReceipt #: 32\nDate: 21st Aug, 2025 21:16\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '32', '2025-08-21 21:25:05', 'Failed'),
(111, '+260968040114', 'Ink Groceries Receipt, Receipt #: 33, Date: 2025-08-21 21:59:34, Description: chocolate cake, Amount K: 400.00', '33', '2025-08-21 21:59:34', 'SMS data sent to Arduino GSM module on COM12.'),
(112, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 33\nDate: 21st Aug, 2025 21:59\n------------------\n• chocolate cake x2 @ K400.00 = K800.00\n------------------\nTOTAL: K800.00\nThank you!', '33', '2025-08-21 21:59:45', 'Success'),
(113, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 33\nDate: 21st Aug, 2025 21:59\n------------------\n• chocolate cake x2 @ K400.00 = K800.00\n------------------\nTOTAL: K800.00\nThank you!', '33', '2025-08-21 21:59:48', 'Success'),
(114, '+260968040114', 'Ink Groceries Receipt, Receipt #: 34, Date: 2025-08-21 22:01:57, Description: Layer Cake, Amount K: 600.00', '34', '2025-08-21 22:01:57', 'SMS data sent to Arduino GSM module on COM12.'),
(115, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 34\nDate: 21st Aug, 2025 22:01\n------------------\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,200.00\nThank you!', '34', '2025-08-21 22:02:05', 'Success'),
(116, '+260968040114', '=== POS RECEIPT ===\nReceipt #: 34\nDate: 21st Aug, 2025 22:01\n------------------\n• Layer Cake x2 @ K600.00 = K1,200.00\n------------------\nTOTAL: K1,200.00\nThank you!', '34', '2025-08-21 22:02:08', 'Success'),
(117, '+260968040114', 'POS RECEIPT\nReceipt #: 34\nDate: 21st Aug, 2025 22:01\n----\n• Layer Cake x2 @ K600.00 = K1,200.00\n----\nTOTAL: K1,200.00\nThank you!', '34', '2025-08-21 22:02:33', 'Success'),
(118, '+260968040114', 'POS RECEIPT\nReceipt #: 34\nDate: 21st Aug, 2025 22:01\n----\n• Layer Cake x2 @ K600.00 = K1,200.00\n----\nTOTAL: K1,200.00\nThank you!', '34', '2025-08-21 22:02:36', 'Success'),
(119, '+260968040114', 'Ink Groceries Receipt, Receipt #: 35, Date: 2025-08-21 22:16:12, Description: chocolate cake, Amount K: 400.00', '35', '2025-08-21 22:16:12', 'SMS data sent to Arduino GSM module on COM12.'),
(120, '+260968040114', 'POS RECEIPT\nReceipt #: 35\nDate: 21st Aug, 2025 22:16\n----\n• chocolate cake x2 @ K400.00 = K800.00\n----\nTOTAL: K800.00\nThank you!', '35', '2025-08-21 22:18:01', 'Success'),
(121, '+260968040114', 'POS RECEIPT\nReceipt #: 35\nDate: 21st Aug, 2025 22:16\n----\n• chocolate cake x2 @ K400.00 = K800.00\n----\nTOTAL: K800.00\nThank you!', '35', '2025-08-21 22:18:04', 'Success'),
(122, '+260968040114', 'POS RECEIPT\nReceipt #: 35\nDate: 21st Aug, 2025 22:16\nItems: 2\n• chocolate cake x2 @ K400.00 = K800.00\n400.00----\nTOTAL: K800.00\nThank you!', '35', '2025-08-21 22:48:11', 'Success'),
(123, '+260968040114', 'POS RECEIPT\nReceipt #: 35\nDate: 21st Aug, 2025 22:16\nItems: 2\n• chocolate cake x2 @ K400.00 = K800.00\n400.00----\nTOTAL: K800.00\nThank you!', '35', '2025-08-21 22:48:15', 'Success'),
(124, '+260968040114', 'Ink Groceries Receipt, Receipt #: 36, Date: 2025-08-21 22:50:40, Description: burger and chips, Amount K: 90.00', '36', '2025-08-21 22:50:40', 'SMS data sent to Arduino GSM module on COM12.'),
(125, '+260968040114', 'POS RECEIPT\nReceipt #: 36\nDate: 21st Aug, 2025 22:50\nItems: 2\n• burger and chips x2 @ K90.00 = K180.00\n90.00----\nTOTAL: K180.00\nThank you!', '36', '2025-08-21 22:50:54', 'Success'),
(126, '+260968040114', 'POS RECEIPT\nReceipt #: 36\nDate: 21st Aug, 2025 22:50\nItems: 2\n• burger and chips x2 @ K90.00 = K180.00\n90.00----\nTOTAL: K180.00\nThank you!', '36', '2025-08-21 22:50:57', 'Success'),
(127, '+260968040114', 'Ink Groceries Receipt, Receipt #: 37, Date: 2025-08-21 23:10:07, Description: burger and chips, Amount K: 90.00', '37', '2025-08-21 23:10:07', 'SMS data sent to Arduino GSM module on COM12.'),
(128, '+260968040114', 'POS RECEIPT\nReceipt #: 37\nDate: 21st Aug, 2025 23:10\n• burger and chips x2 @ K90.00 = K180.00\n----\nTOTAL: K180.00\nThank you!', '37', '2025-08-21 23:10:18', 'Success'),
(129, '+260968040114', 'POS RECEIPT\nReceipt #: 37\nDate: 21st Aug, 2025 23:10\n• burger and chips x2 @ K90.00 = K180.00\n----\nTOTAL: K180.00\nThank you!', '37', '2025-08-21 23:10:22', 'Success'),
(130, '+260968040114', 'Ink Groceries Receipt, Receipt #: 38, Date: 2025-08-21 23:16:08, Description: Layer Cake, Amount K: 600.00', '38', '2025-08-21 23:16:08', 'SMS data sent to Arduino GSM module on COM12.'),
(131, '+260968040114', 'POS RECEIPT\nReceipt #: 38\nDate: 21st Aug, 2025 23:16\n\n• Layer Cake x2 @ K600.00 = K1,200.00\n\nTOTAL: K1,200.00\nThank you!', '38', '2025-08-21 23:16:57', 'Failed'),
(132, '+260968040114', 'POS RECEIPT\nReceipt #: 38\nDate: 21st Aug, 2025 23:16\n\n• Layer Cake x2 @ K600.00 = K1,200.00\n\nTOTAL: K1,200.00\nThank you!', '38', '2025-08-21 23:17:12', 'Failed'),
(133, '+260968040114', 'POS RECEIPT\nReceipt #: 38\nDate: 21st Aug, 2025 23:16\n\n• Layer Cake x2 @ K600.00 = K1,200.00\n\nTOTAL: K1,200.00\nThank you!', '38', '2025-08-21 23:18:53', 'Success'),
(134, '+260968040114', 'POS RECEIPT\nReceipt #: 38\nDate: 21st Aug, 2025 23:16\n\n• Layer Cake x2 @ K600.00 = K1,200.00\n\nTOTAL: K1,200.00\nThank you!', '38', '2025-08-21 23:18:57', 'Success'),
(135, '+26', 'POS RECEIPT\nReceipt #: 39\nDate: 21st Aug, 2025 23:40\n\n• Layer Cake x2 @ K600.00 = K1,200.00\n\nTOTAL: K1,200.00\nThank you!', '39', '2025-08-21 23:40:39', 'SMS data sent to Arduino GSM module on COM12.'),
(136, '+26', 'POS RECEIPT\nReceipt #: 39\nDate: 21st Aug, 2025 23:40\n\n• Layer Cake x2 @ K600.00 = K1,200.00\n\nTOTAL: K1,200.00\nThank you!', '39', '2025-08-21 23:41:46', 'Success'),
(137, '+26', 'POS RECEIPT\nReceipt #: 39\nDate: 21st Aug, 2025 23:40\n\n• Layer Cake x2 @ K600.00 = K1,200.00\n\nTOTAL: K1,200.00\nThank you!', '39', '2025-08-21 23:41:58', 'Failed'),
(138, '+26', 'POS RECEIPT\nReceipt #: 40\nDate: 21st Aug, 2025 23:42\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '40', '2025-08-21 23:42:57', 'SMS data sent to Arduino GSM module on COM12.'),
(139, '+26', 'POS RECEIPT\nReceipt #: 41\nDate: 21st Aug, 2025 23:53\n\n• chocolate cake x2 @ K400.00 = K800.00\n\nTOTAL: K800.00\nThank you!', '41', '2025-08-21 23:53:18', 'SMS data sent to Arduino GSM module on COM12.'),
(140, '+260968040114', 'Ink Groceries Receipt, Receipt #: 42, Date: 2025-08-21 23:54:52, Description: burger and chips, Amount K: 90.00', '42', '2025-08-21 23:54:52', 'SMS data sent to Arduino GSM module on COM12.'),
(141, '+260968040114', 'POS RECEIPT\nReceipt #: 42\nDate: 21st Aug, 2025 23:54\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '42', '2025-08-21 23:55:06', 'Success'),
(142, '+260968040114', 'POS RECEIPT\nReceipt #: 42\nDate: 21st Aug, 2025 23:54\n\n• burger and chips x2 @ K90.00 = K180.00\n\nTOTAL: K180.00\nThank you!', '42', '2025-08-21 23:55:09', 'Success'),
(143, '+260968040114', 'Ink Groceries Receipt, Receipt #: 43, Date: 2025-08-22 09:40:59, Description: burger and chips, Amount K: 90.00', '43', '2025-08-22 09:40:59', 'SMS data sent to Arduino GSM module on COM12.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `role` varchar(20) NOT NULL,
  `gender` varchar(6) NOT NULL DEFAULT 'male',
  `deletable` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `date`, `image`, `role`, `gender`, `deletable`) VALUES
(1, 'Kaembe', 'kaembe@complizam.com', '$2y$10$WDQYreEp8pfUZWjtih2WT.EErMxkhEmI4lJfblSiKmp4n.Yg0ZdDy', '2021-12-28 09:33:15', 'uploads/dcd6f2778c87f0ec661b6a35d4ebe19b122aa4c3_7678.jpeg', 'admin', 'male', 0),
(11, 'RS', 'ryankaundashiliya@gmail.com', '$2y$10$erXZt9xdg9.JdhA0fr59l.Lcy//4lfxa77DAWFUCE2JdgqgBtQ2Ee', '2025-01-07 11:55:32', NULL, 'admin', 'male', 1),
(13, 'musaka', 'billzmusaka@gmail.com', '$2y$10$JPJX3tNmO6e0fRS9iZSHV.6d7Ci03jq0w4EiyvG.V83f9ttWyht.m', '2025-06-22 20:42:49', 'uploads/a9d43d26840e98f0c7ab1992014c1050e877b4d3_4383.jpg', 'admin', 'male', 1),
(14, 'test', 'test@gmail.com', '$2y$10$Y4coibVqj8DLP6gm2IpIBOQ2iCcne1vgbfYZJqFYt3hbzyTAghnTC', '2025-06-26 20:05:25', 'uploads/8eeabd3440b1c213be28361d42c8745c0f175d4c_9516.jpg', 'cashier', 'male', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `description` (`description`),
  ADD KEY `qty` (`qty`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `views` (`views`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`),
  ADD KEY `description` (`description`),
  ADD KEY `receipt_no` (`receipt_no`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `receipt_no` (`receipt_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `date` (`date`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
