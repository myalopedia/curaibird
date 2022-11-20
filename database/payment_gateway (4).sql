-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 20, 2022 at 03:20 PM
-- Server version: 5.7.33
-- PHP Version: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payment_gateway`
--

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `id_va` varchar(100) NOT NULL,
  `external_id` varchar(100) NOT NULL,
  `expiration_date` varchar(100) NOT NULL,
  `bank_code` varchar(100) NOT NULL,
  `nama` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `payment_name` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  `fee_merchant` int(11) NOT NULL,
  `fee_customer` int(11) NOT NULL,
  `total_fee` int(11) NOT NULL,
  `amount_received` int(11) NOT NULL,
  `pay_code` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `expired_time` int(11) NOT NULL,
  `data_tripay` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `cancel_at` timestamp NULL DEFAULT NULL,
  `delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_xendit`
--

CREATE TABLE `transaksi_xendit` (
  `id` int(11) NOT NULL,
  `xendit_id` varchar(100) DEFAULT NULL,
  `external_id` varchar(100) DEFAULT NULL,
  `bank_code` varchar(100) DEFAULT NULL,
  `merchant_code` varchar(100) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `is_single_use` varchar(100) DEFAULT NULL,
  `is_closed` varchar(100) DEFAULT NULL,
  `expected_amount` int(11) DEFAULT NULL,
  `suggested_amount` int(11) DEFAULT NULL,
  `expiration_date` varchar(100) DEFAULT NULL,
  `description` text,
  `status` varchar(100) DEFAULT NULL,
  `data_xendit` text,
  `active_at` timestamp NULL DEFAULT NULL,
  `inactive_at` timestamp NULL DEFAULT NULL,
  `pending_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi_xendit`
--

INSERT INTO `transaksi_xendit` (`id`, `xendit_id`, `external_id`, `bank_code`, `merchant_code`, `account_number`, `name`, `currency`, `is_single_use`, `is_closed`, `expected_amount`, `suggested_amount`, `expiration_date`, `description`, `status`, `data_xendit`, `active_at`, `inactive_at`, `pending_at`, `paid_at`, `create_at`, `update_at`, `delete_at`) VALUES
(5, '6313237380c709231c8fb4f6', 'va-20220903165043', 'BCA', '10766', '107669999528877', 'Supri', 'IDR', '1', '1', 235500, 235500, '2053-09-02T17:00:00.000Z', NULL, 'PENDING', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903165043\",\"bank_code\":\"BCA\",\"merchant_code\":\"10766\",\"name\":\"Supri\",\"account_number\":\"107669999528877\",\"suggested_amount\":235500,\"expected_amount\":235500,\"is_single_use\":true,\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"6313237380c709231c8fb4f6\"}', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '2022-09-03 09:50:43', '2022-10-03 13:00:02', '2022-10-03 13:00:02'),
(6, '63132cd7473e75a2450579fb', 'va-20220903173046', 'BRI', '13282', '13282939726499001', 'Supri', 'IDR', '1', '1', 14500, 14500, '2053-09-02T17:00:00.000Z', 'Sprei, ', 'PENDING', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903173046\",\"bank_code\":\"BRI\",\"merchant_code\":\"13282\",\"name\":\"Supri\",\"account_number\":\"13282939726499001\",\"suggested_amount\":14500,\"expected_amount\":14500,\"is_single_use\":true,\"description\":\"Sprei, \",\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"63132cd7473e75a2450579fb\"}', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL, '2022-09-03 10:30:47', '2022-10-03 13:00:01', '2022-10-03 13:00:01'),
(7, '631334e980c709eb088fb9fc', 'va-20220903180512', 'BCA', '10766', '107669999948310', 'Supri', 'IDR', '1', '1', 244500, 244500, '2053-09-02T17:00:00.000Z', '', 'ACTIVE', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903180512\",\"bank_code\":\"BCA\",\"merchant_code\":\"10766\",\"name\":\"Supri\",\"account_number\":\"107669999948310\",\"suggested_amount\":244500,\"expected_amount\":244500,\"is_single_use\":true,\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"631334e980c709eb088fb9fc\"}', '2022-09-03 11:06:24', NULL, NULL, NULL, '2022-09-03 11:05:13', '2022-10-03 13:00:00', '2022-10-03 13:00:00'),
(8, '63133719b472dc856813ca06', 'va-20220903181432', 'PERMATA', '8214', '8214939722212840', 'Mamat Dangak', 'IDR', '1', '0', 24500, 0, '2053-09-02T17:00:00.000Z', '', 'PENDING', '{\"is_closed\":false,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903181432\",\"bank_code\":\"PERMATA\",\"merchant_code\":\"8214\",\"name\":\"Mamat Dangak\",\"account_number\":\"8214939722212840\",\"expected_amount\":24500,\"is_single_use\":true,\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"63133719b472dc856813ca06\"}', NULL, NULL, NULL, NULL, '2022-09-03 11:14:33', '2022-10-03 12:59:59', '2022-10-03 12:59:59'),
(9, '63134a08d9aed8c332741c5a', 'va-20220903193519', 'BRI', '13282', '13282939728181451', 'Rezal Wahyu Pratama', 'IDR', '1', '1', 14500, 14500, '2053-09-02T17:00:00.000Z', 'Sprei, ', 'PAID', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903193519\",\"bank_code\":\"BRI\",\"merchant_code\":\"13282\",\"name\":\"Rezal Wahyu Pratama\",\"account_number\":\"13282939728181451\",\"suggested_amount\":14500,\"expected_amount\":14500,\"is_single_use\":true,\"description\":\"Sprei, \",\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"63134a08d9aed8c332741c5a\"}', '2022-09-03 12:35:21', NULL, NULL, '2022-09-03 12:45:06', '2022-09-03 12:35:20', '2022-10-03 12:59:58', '2022-10-03 12:59:58'),
(10, '631361e580c70904978fc76f', 'va-20220903211709', 'BNI', '8808', '8808999995415978', 'XDT-asda', 'IDR', '1', '1', 18327, NULL, '2053-09-02T17:00:00.000Z', '', 'ACTIVE', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903211709\",\"bank_code\":\"BNI\",\"merchant_code\":\"8808\",\"name\":\"XDT-asda\",\"account_number\":\"8808999995415978\",\"expected_amount\":18327,\"is_single_use\":true,\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"631361e580c70904978fc76f\"}', '2022-09-03 14:17:12', NULL, '2022-09-03 14:17:09', NULL, '2022-09-03 14:17:09', '2022-10-03 12:59:57', '2022-10-03 12:59:57'),
(11, '631361f91e91971a0158570a', 'va-20220903211728', 'BCA', '10766', '107669999792772', 'asda', 'IDR', '1', '1', 19767, 19767, '2053-09-02T17:00:00.000Z', '', 'ACTIVE', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903211728\",\"bank_code\":\"BCA\",\"merchant_code\":\"10766\",\"name\":\"asda\",\"account_number\":\"107669999792772\",\"suggested_amount\":19767,\"expected_amount\":19767,\"is_single_use\":true,\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"631361f91e91971a0158570a\"}', '2022-09-03 14:17:31', NULL, '2022-09-03 14:17:29', NULL, '2022-09-03 14:17:29', '2022-10-03 12:59:56', '2022-10-03 12:59:56'),
(12, '6313622d80c709fbc78fc78a', 'va-20220903211820', 'MANDIRI', '88908', '889089999707962', 'Supri', 'IDR', '1', '1', 5127, NULL, '2053-09-02T17:00:00.000Z', '', 'ACTIVE', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903211820\",\"bank_code\":\"MANDIRI\",\"merchant_code\":\"88908\",\"name\":\"Supri\",\"account_number\":\"889089999707962\",\"expected_amount\":5127,\"is_single_use\":true,\"expiration_date\":\"2053-09-02T17:00:00.000Z\",\"id\":\"6313622d80c709fbc78fc78a\"}', '2022-09-03 14:18:22', NULL, '2022-09-03 14:18:21', NULL, '2022-09-03 14:18:21', '2022-10-03 12:59:55', '2022-10-03 12:59:55'),
(13, 'c611865b-777a-41b1-b82a-ecbf95894330', 'va-20220903211946', 'SAHABAT_SAMPOERNA', '40102', '4010299993619699', 'Ikan', 'IDR', '1', '1', 6459, NULL, '2053-09-03T14:19:47.530Z', '', 'ACTIVE', '{\"id\":\"c611865b-777a-41b1-b82a-ecbf95894330\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903211946\",\"account_number\":\"4010299993619699\",\"bank_code\":\"SAHABAT_SAMPOERNA\",\"merchant_code\":\"40102\",\"name\":\"Ikan\",\"is_closed\":true,\"expected_amount\":6459,\"expiration_date\":\"2053-09-03T14:19:47.530Z\",\"is_single_use\":true,\"status\":\"PENDING\",\"currency\":\"IDR\"}', '2022-09-03 14:19:49', NULL, '2022-09-03 14:19:48', NULL, '2022-09-03 14:19:48', '2022-10-03 12:59:54', '2022-10-03 12:59:54'),
(14, '1f7304c0-1635-41f5-8340-7ea3647ceb2b', 'va-20220903212220', 'CIMB', '93490', '9349099995953751', 'Supri', 'IDR', '1', '1', 16106, NULL, '2053-09-03T14:22:21.008Z', '', 'ACTIVE', '{\"id\":\"1f7304c0-1635-41f5-8340-7ea3647ceb2b\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903212220\",\"account_number\":\"9349099995953751\",\"bank_code\":\"CIMB\",\"merchant_code\":\"93490\",\"name\":\"Supri\",\"is_closed\":true,\"expected_amount\":16106,\"expiration_date\":\"2053-09-03T14:22:21.008Z\",\"is_single_use\":true,\"status\":\"PENDING\",\"currency\":\"IDR\"}', '2022-09-03 14:22:23', NULL, '2022-09-03 14:22:21', NULL, '2022-09-03 14:22:21', '2022-10-03 12:59:53', '2022-10-03 12:59:53'),
(15, '5840689f-2770-4b88-bcad-d88e1a8132ca', 'va-20220903212424', 'BSI', '9347', '934799990024582', 'Supri', 'IDR', '1', '1', 17217, NULL, '2053-09-03T14:24:24.829Z', '', 'ACTIVE', '{\"id\":\"5840689f-2770-4b88-bcad-d88e1a8132ca\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903212424\",\"account_number\":\"934799990024582\",\"bank_code\":\"BSI\",\"merchant_code\":\"9347\",\"name\":\"Supri\",\"is_closed\":true,\"expected_amount\":17217,\"expiration_date\":\"2053-09-03T14:24:24.829Z\",\"is_single_use\":true,\"status\":\"PENDING\",\"currency\":\"IDR\"}', '2022-09-03 14:24:28', NULL, '2022-09-03 14:24:24', NULL, '2022-09-03 14:24:24', '2022-10-03 12:59:52', '2022-10-03 12:59:52'),
(16, 'c2e0492e-106d-4375-99de-88388b136cfd', 'va-20220903212443', 'BJB', '1234', '1234999957637294', 'Supri', 'IDR', '1', '1', 6106, 6106, '2053-09-03T14:24:44.002Z', 'Sprei, ', 'ACTIVE', '{\"id\":\"c2e0492e-106d-4375-99de-88388b136cfd\",\"owner_id\":\"6304db38de0dfcd8ce84b066\",\"external_id\":\"va-20220903212443\",\"account_number\":\"1234999957637294\",\"bank_code\":\"BJB\",\"merchant_code\":\"1234\",\"name\":\"Supri\",\"suggested_amount\":6106,\"is_closed\":true,\"expected_amount\":6106,\"expiration_date\":\"2053-09-03T14:24:44.002Z\",\"is_single_use\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"description\":\"Sprei, \"}', '2022-09-03 14:25:03', NULL, '2022-09-03 14:24:44', NULL, '2022-09-03 14:24:44', '2022-10-03 12:59:51', '2022-10-03 12:59:51'),
(17, '633ade8ec40c1072104add95', 'va-20221003200721', 'BCA', '10766', '107669999949623', 'Alopedia', 'IDR', '1', '1', 14995, 14995, '2053-10-02T17:00:00.000Z', '', 'PENDING', '{\"is_closed\":true,\"status\":\"PENDING\",\"currency\":\"IDR\",\"owner_id\":\"63394f649d70803cff1a819a\",\"external_id\":\"va-20221003200721\",\"bank_code\":\"BCA\",\"merchant_code\":\"10766\",\"name\":\"Alopedia\",\"account_number\":\"107669999949623\",\"suggested_amount\":14995,\"expected_amount\":14995,\"is_single_use\":true,\"expiration_date\":\"2053-10-02T17:00:00.000Z\",\"id\":\"633ade8ec40c1072104add95\"}', NULL, NULL, '2022-10-03 13:07:24', NULL, '2022-10-03 13:07:24', '2022-10-03 13:07:24', NULL),
(18, 'DS13568KL1RC365ONRV418', 'DS13568KL1RC365ONRV418', 'NC', 'DS13568', '9920221006000002476', 'Supri', 'IDR', '0', '0', 10000, 10000, '10', '[{\"name\":\"Jasa Alopedia\",\"price\":\"10000\",\"quantity\":1}]', '00', '{\"merchantCode\":\"DS13568\",\"reference\":\"DS13568KL1RC365ONRV418\",\"paymentUrl\":\"https:\\/\\/sandbox.duitku.com\\/topup\\/topupdirectv2.aspx?ref=NCPRX4LIX7PLDC31M\",\"vaNumber\":\"9920221006000002476\",\"amount\":\"10000\",\"statusCode\":\"00\",\"statusMessage\":\"SUCCESS\"}', NULL, NULL, '2022-10-06 13:57:44', NULL, '2022-10-06 13:57:44', '2022-10-06 13:57:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `create_at`, `update_at`) VALUES
(1, 'admin', 'admin', '$2y$10$GFTLdMmV1TI91f44yHB0ROmS30xNCNf5E8L3AjIp6r4SnYsomezk6', '2022-08-16 16:42:27', '2022-08-16 16:53:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_xendit`
--
ALTER TABLE `transaksi_xendit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaksi_xendit`
--
ALTER TABLE `transaksi_xendit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
