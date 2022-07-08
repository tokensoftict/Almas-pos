-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 14, 2021 at 05:48 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bashir_1`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `SN` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` tinytext NOT NULL,
  `delete_status` int(11) NOT NULL DEFAULT 0,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`SN`, `branch_name`, `branch_address`, `delete_status`, `created`, `updated`, `last_data_updated`) VALUES
(1, 'Agbo Oba', '', 0, '2021-03-24 11:28:59', '2021-08-14 15:47:17', '2021-08-14 16:47:17');

-- --------------------------------------------------------

--
-- Table structure for table `branch_product`
--

CREATE TABLE `branch_product` (
  `SN` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `SN` int(11) NOT NULL,
  `category` tinytext NOT NULL,
  `tax` int(11) NOT NULL DEFAULT 0,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`SN`, `category`, `tax`, `updated`, `created`, `last_data_updated`) VALUES
(1, 'No TAX', 0, '2021-04-17 14:46:33', '2021-04-17 17:55:32', '2021-04-17 18:55:32');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text COLLATE utf8_bin NOT NULL,
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `credit_payment_history`
--

CREATE TABLE `credit_payment_history` (
  `sn` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `amount` varchar(500) NOT NULL,
  `sales_rep` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `credit_SN` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `credit_id` varchar(100) NOT NULL,
  `reciept_id` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `SN` int(11) NOT NULL,
  `department` varchar(300) NOT NULL,
  `type` enum('Service','Sales','Others') NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`SN`, `department`, `type`, `created`, `updated`, `last_data_updated`) VALUES
(1, 'SALES', 'Sales', '2021-03-17 16:19:20', '2021-04-17 14:44:52', '2021-04-17 18:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

CREATE TABLE `deposit` (
  `deposit_id` varchar(100) NOT NULL,
  `SN` bigint(30) NOT NULL,
  `date_added` date NOT NULL,
  `sales_rep` int(11) NOT NULL,
  `product` text NOT NULL,
  `amount` varchar(500) NOT NULL,
  `reciept_id` varchar(500) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `reason_for_refund` text NOT NULL,
  `deposit_for` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `deposit_status` enum('PENDING-USAGE','USED','REFUND','PENDING') NOT NULL DEFAULT 'PENDING',
  `sales_id` int(11) NOT NULL,
  `refund_for` text NOT NULL,
  `date_refunded` date NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `deposit_payment_history`
--

CREATE TABLE `deposit_payment_history` (
  `sn` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `amount` varchar(500) NOT NULL,
  `sales_rep` int(11) NOT NULL,
  `payment_method` int(11) NOT NULL,
  `deposit_SN` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `deposit_id` varchar(100) NOT NULL,
  `reciept_id` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discount_manager`
--

CREATE TABLE `discount_manager` (
  `SN` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `value` varchar(300) NOT NULL,
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payment_history`
--

CREATE TABLE `invoice_payment_history` (
  `sn` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `amount` varchar(500) NOT NULL,
  `bank` int(11) NOT NULL,
  `description` text NOT NULL,
  `Invoice_SN` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `invoice_id` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `SN` int(11) NOT NULL,
  `manufacturer` tinytext NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `others`
--

CREATE TABLE `others` (
  `SN` int(11) NOT NULL,
  `vat` varchar(20) NOT NULL,
  `scharge` varchar(20) NOT NULL,
  `sname` text NOT NULL,
  `saddress_1` text NOT NULL,
  `saddress_2` text NOT NULL,
  `scontact_no` text NOT NULL,
  `footer_rec` text NOT NULL,
  `footer_rec_service` text NOT NULL,
  `slogo` varchar(250) NOT NULL,
  `track_expiry_date` int(11) NOT NULL,
  `credit_limit` bigint(20) NOT NULL,
  `store_name` varchar(600) NOT NULL,
  `min_qty` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `others`
--

INSERT INTO `others` (`SN`, `vat`, `scharge`, `sname`, `saddress_1`, `saddress_2`, `scontact_no`, `footer_rec`, `footer_rec_service`, `slogo`, `track_expiry_date`, `credit_limit`, `store_name`, `min_qty`, `created`, `updated`, `last_data_updated`) VALUES
(1, '0', '0', 'POS', 'Address Address Address ', 'Address Address Address ', '', '', '<i>...Thanks For Your Patronage </i>', 'http://localhost/bashir_1/store_assets/1626692267-store_logo.png', 34, 5000000, 'Taiwo', 0, '2021-04-04 20:24:45', '2021-07-19 11:57:47', '2021-07-19 11:57:47');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `SN` int(11) NOT NULL,
  `payment_method` varchar(300) NOT NULL,
  `defaults` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_bar_code`
--

CREATE TABLE `product_bar_code` (
  `SN` int(11) NOT NULL,
  `bar_code` tinytext NOT NULL,
  `stock_id` int(11) NOT NULL,
  `date_available` date NOT NULL,
  `added_by` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `recieved_ref` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `SN` bigint(20) NOT NULL,
  `reciept_id` varchar(100) NOT NULL,
  `items` longtext NOT NULL,
  `discount_type` int(11) NOT NULL,
  `discount` decimal(16,2) NOT NULL,
  `total_amount` decimal(16,2) NOT NULL,
  `status` enum('COMPLETE','VOID','HOLD','PICKUP') NOT NULL,
  `total_amount_paid` decimal(16,2) NOT NULL,
  `total_profit` decimal(16,2) NOT NULL,
  `vat_amount` decimal(16,2) NOT NULL,
  `s_charge_amt` decimal(16,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `department` varchar(120) NOT NULL,
  `date` date NOT NULL,
  `sales_time` varchar(10) NOT NULL,
  `payment_type` enum('Direct','Invoice') NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `customer` int(11) NOT NULL,
  `reservation_invoice_link` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `vat` decimal(16,2) NOT NULL,
  `scharge` decimal(16,2) NOT NULL,
  `reason` text NOT NULL,
  `voided_by` int(11) NOT NULL,
  `date_voided` date NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `SN` int(11) NOT NULL,
  `servicecode` varchar(100) NOT NULL,
  `name` varchar(500) NOT NULL,
  `price` decimal(12,3) NOT NULL,
  `category` int(11) NOT NULL,
  `department` varchar(100) NOT NULL,
  `service_type` enum('Hourly Service','Normal Service') NOT NULL DEFAULT 'Normal Service',
  `image` varchar(1000) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `service_category`
--

CREATE TABLE `service_category` (
  `SN` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `vat` int(11) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settingsnoedit`
--

CREATE TABLE `settingsnoedit` (
  `sne_id` int(11) NOT NULL,
  `sne_key` varchar(200) NOT NULL,
  `sne_value` varchar(200) NOT NULL,
  `sne_branchid` int(11) DEFAULT NULL,
  `settingsNoEdit_code` varchar(20) DEFAULT NULL,
  `lastupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settingsnoedit`
--

INSERT INTO `settingsnoedit` (`sne_id`, `sne_key`, `sne_value`, `sne_branchid`, `settingsNoEdit_code`, `lastupdated`) VALUES
(1, 'last_to_online_sync', '2021-08-14 16:41:43', 1, NULL, '2021-08-14 15:41:43'),
(2, 'last_to_offline_sync', '0', 1, NULL, '2021-04-07 05:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `SN` bigint(20) NOT NULL,
  `id_stock` varchar(255) NOT NULL,
  `product_name` text NOT NULL,
  `product_description` longtext NOT NULL,
  `model` tinytext NOT NULL,
  `quantity` int(11) NOT NULL,
  `department` varchar(120) NOT NULL,
  `price` double(16,2) NOT NULL,
  `expired_date` date NOT NULL,
  `cost_price` double(16,2) NOT NULL,
  `date_available` date NOT NULL,
  `image` tinytext NOT NULL,
  `bar_code_code` varchar(600) NOT NULL,
  `product_code` varchar(300) NOT NULL,
  `status` int(11) NOT NULL,
  `manufacturer` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_open_close`
--

CREATE TABLE `stock_open_close` (
  `SN` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `id_stock` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `opening` int(11) NOT NULL,
  `sold` int(11) NOT NULL,
  `closing` int(11) NOT NULL,
  `transfered` int(11) NOT NULL,
  `recieved` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_recieved`
--

CREATE TABLE `stock_recieved` (
  `SN` bigint(11) NOT NULL,
  `recieved_id` varchar(25) NOT NULL,
  `products` longtext NOT NULL,
  `department` varchar(30) NOT NULL,
  `recieved_date` date NOT NULL,
  `branch` varchar(200) NOT NULL,
  `supplier` int(11) NOT NULL,
  `reciever_userfullname` int(11) NOT NULL,
  `transfer_user` varchar(255) NOT NULL,
  `confirm_userfullname` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer`
--

CREATE TABLE `stock_transfer` (
  `SN` bigint(11) NOT NULL,
  `transfer_id` varchar(25) NOT NULL,
  `products` longtext NOT NULL,
  `transfer_date` date NOT NULL,
  `branch` varchar(100) NOT NULL,
  `transfer_user` int(11) NOT NULL,
  `reciever_userfullname` varchar(255) NOT NULL,
  `confirm_userfullname` varchar(255) NOT NULL,
  `branch_id` varchar(200) NOT NULL,
  `note` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SN` bigint(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_address` tinytext NOT NULL,
  `supplier_email` varchar(255) NOT NULL,
  `supplier_phone_number` varchar(16) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SN`, `supplier_name`, `supplier_address`, `supplier_email`, `supplier_phone_number`, `created`, `updated`, `last_data_updated`) VALUES
(1, 'TEKOBO\r\n', '', '', '', '2021-03-17 17:10:18', '2021-04-17 14:44:52', '2021-04-17 18:50:09'),
(2, 'NEW SONG', '', '', '', '2021-07-19 11:39:50', '2021-07-19 11:39:50', '2021-07-19 12:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_invoice`
--

CREATE TABLE `supplier_invoice` (
  `SN` bigint(11) NOT NULL,
  `supplier_id` varchar(25) NOT NULL,
  `products` longtext NOT NULL,
  `recieved_date` date NOT NULL,
  `supplier` int(11) NOT NULL,
  `note` text NOT NULL,
  `amount_paid` decimal(16,2) NOT NULL,
  `total_invoice_amount` decimal(16,2) NOT NULL,
  `bank` int(11) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `status` enum('Pending','Complete') NOT NULL,
  `branch_id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_assets`
--

CREATE TABLE `tbl_assets` (
  `SN` int(11) NOT NULL,
  `assests_name` varchar(500) NOT NULL,
  `status` varchar(100) NOT NULL,
  `category` varchar(120) NOT NULL,
  `department` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `purchase_date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_sold` date NOT NULL,
  `model_number` varchar(200) NOT NULL,
  `purchase_price` decimal(16,2) NOT NULL,
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bank`
--

CREATE TABLE `tbl_bank` (
  `SN` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(11) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cashbook`
--

CREATE TABLE `tbl_cashbook` (
  `SN` int(11) NOT NULL,
  `date_` date NOT NULL,
  `type` varchar(100) NOT NULL,
  `bank` int(11) NOT NULL,
  `amt` int(11) NOT NULL,
  `comment` text NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_credit_sales`
--

CREATE TABLE `tbl_credit_sales` (
  `SN` int(11) NOT NULL,
  `credit_id` varchar(100) NOT NULL,
  `items` longtext NOT NULL,
  `discount_type` int(11) NOT NULL,
  `discount` decimal(16,2) NOT NULL,
  `total_amount` decimal(16,2) NOT NULL,
  `status` enum('COMPLETE','PENDING') NOT NULL,
  `total_amount_paid` decimal(16,2) NOT NULL,
  `total_profit` decimal(16,2) NOT NULL,
  `vat_amount` decimal(16,2) NOT NULL,
  `s_charge_amt` decimal(16,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `sales_time` varchar(10) NOT NULL,
  `payment_type` enum('Direct','Invoice') NOT NULL,
  `payment_method` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `reservation_invoice_link` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `vat` int(11) NOT NULL,
  `scharge` int(11) NOT NULL,
  `reason` text NOT NULL,
  `voided_by` int(11) NOT NULL,
  `date_voided` date NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customers`
--

CREATE TABLE `tbl_customers` (
  `SN` bigint(11) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` tinytext NOT NULL,
  `credit_limit` decimal(16,2) NOT NULL,
  `weeks` int(11) NOT NULL,
  `expired_date` date NOT NULL,
  `date` date NOT NULL,
  `city` varchar(40) NOT NULL,
  `additional_info` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expenses`
--

CREATE TABLE `tbl_expenses` (
  `SN` bigint(11) NOT NULL,
  `expense_date` date NOT NULL,
  `month` varchar(15) NOT NULL,
  `month_number` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expense_total_amount` decimal(10,0) NOT NULL,
  `expense_purpose` text NOT NULL,
  `expense_purpose_title` tinytext NOT NULL,
  `branch_id` int(11) NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_history`
--

CREATE TABLE `tbl_invoice_history` (
  `SN` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `invoice_id` varchar(20) NOT NULL,
  `department` varchar(100) NOT NULL,
  `amount` int(11) NOT NULL,
  `user_created` int(11) NOT NULL,
  `last_modeified_user` int(11) NOT NULL,
  `date_added` date NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `customer` int(11) NOT NULL,
  `invoice_item` mediumtext NOT NULL,
  `reservation_invoice_link` varchar(100) NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `payment_serial` int(11) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `vat` int(11) NOT NULL,
  `scharge` int(11) NOT NULL,
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_name`
--

CREATE TABLE `tbl_name` (
  `product_name` varchar(36) DEFAULT NULL,
  `product_description` varchar(239) DEFAULT NULL,
  `price` varchar(7) DEFAULT NULL,
  `id_stock` varchar(8) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `SN` int(11) NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `cuustomer` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `invoice_id` varchar(60) NOT NULL,
  `type` varchar(100) NOT NULL,
  `payment_type` enum('Invoice','Direct') NOT NULL DEFAULT 'Invoice',
  `department` varchar(100) NOT NULL,
  `invoice_serial` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `user` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `vat` int(11) NOT NULL,
  `scharge` int(11) NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_payrole`
--

CREATE TABLE `tbl_payment_payrole` (
  `SN` int(11) NOT NULL,
  `payment_id` varchar(60) NOT NULL,
  `month` varchar(15) NOT NULL,
  `month_number` varchar(15) NOT NULL,
  `year` varchar(15) NOT NULL,
  `total_pay` varchar(30) NOT NULL,
  `total_staff` varchar(40) NOT NULL,
  `type` varchar(20) NOT NULL,
  `pay_date` date NOT NULL,
  `status` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment_payrole_history`
--

CREATE TABLE `tbl_payment_payrole_history` (
  `SN` int(11) NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `month` varchar(10) NOT NULL,
  `month_no` varchar(10) NOT NULL,
  `year` varchar(10) NOT NULL,
  `salary` varchar(30) NOT NULL,
  `addition` int(11) NOT NULL,
  `deduction` int(11) NOT NULL,
  `loan_deduction` varchar(30) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transfer_recieved`
--

CREATE TABLE `tbl_transfer_recieved` (
  `SN` bigint(20) NOT NULL,
  `tracking_id` varchar(100) NOT NULL,
  `stock_id` bigint(20) NOT NULL,
  `amt_in` bigint(20) NOT NULL,
  `amt_out` bigint(20) NOT NULL,
  `sold` bigint(20) NOT NULL,
  `date_` date NOT NULL,
  `balance` varchar(100) NOT NULL,
  `user` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) COLLATE utf8_bin NOT NULL,
  `lastname` varchar(100) COLLATE utf8_bin NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `bank_name` varchar(400) COLLATE utf8_bin NOT NULL,
  `bank_account_name` varchar(300) COLLATE utf8_bin NOT NULL,
  `bank_account_no` varchar(60) COLLATE utf8_bin NOT NULL,
  `salary` varchar(300) COLLATE utf8_bin NOT NULL,
  `department` varchar(120) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT 1,
  `banned` tinyint(1) NOT NULL DEFAULT 0,
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `branch_id` int(11) NOT NULL,
  `role` enum('Sales Representative','Administrator','Manager','Accountant','Auditor','Stock Officer','Others','Superuser','Service Administrator') COLLATE utf8_bin NOT NULL,
  `created2` datetime NOT NULL DEFAULT current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `bank_name`, `bank_account_name`, `bank_account_no`, `salary`, `department`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `updated`, `modified`, `branch_id`, `role`, `created2`, `last_data_updated`) VALUES
(1, 'Admin', 'istrator', 'admin', 'Wefwef', 'Wefwef', '1233223', '30000', 'SALES', '$2a$08$6nZ/WpXeawimlwmbJJBG3.JLp15zMH7yLfHXtLzh21zDebhyABrcm', 'admin@admin.com', 1, 0, '', NULL, NULL, '', '', '::1', '2021-08-14 16:01:12', '2018-12-17 09:20:31', '2021-08-14 16:01:12', '2021-08-14 15:01:12', 0, 'Administrator', '2021-04-04 20:35:27', '2021-08-14 16:01:12'),
(2, 'YUSUF', 'YUSUF', 'yusuf', 'GTBANK', '00900000', '09000099', '20000', 'Administrator', '$2a$08$6nZ/WpXeawimlwmbJJBG3.JLp15zMH7yLfHXtLzh21zDebhyABrcm', 'yoyforever@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2021-08-14 15:51:00', '2021-07-19 13:45:57', '2021-08-14 15:51:00', '2021-08-14 14:51:00', 0, 'Manager', '2021-07-19 12:45:57', '2021-08-14 15:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_data_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `branch_product`
--
ALTER TABLE `branch_product`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `credit_payment_history`
--
ALTER TABLE `credit_payment_history`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `deposit`
--
ALTER TABLE `deposit`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `deposit_payment_history`
--
ALTER TABLE `deposit_payment_history`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `discount_manager`
--
ALTER TABLE `discount_manager`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `invoice_payment_history`
--
ALTER TABLE `invoice_payment_history`
  ADD PRIMARY KEY (`sn`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `others`
--
ALTER TABLE `others`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `product_bar_code`
--
ALTER TABLE `product_bar_code`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `service_category`
--
ALTER TABLE `service_category`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `settingsnoedit`
--
ALTER TABLE `settingsnoedit`
  ADD PRIMARY KEY (`sne_id`),
  ADD KEY `sne_branchid` (`sne_branchid`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `stock_open_close`
--
ALTER TABLE `stock_open_close`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `stock_recieved`
--
ALTER TABLE `stock_recieved`
  ADD PRIMARY KEY (`SN`),
  ADD UNIQUE KEY `transfer_id` (`recieved_id`);

--
-- Indexes for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  ADD PRIMARY KEY (`SN`),
  ADD UNIQUE KEY `transfer_id` (`transfer_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `supplier_invoice`
--
ALTER TABLE `supplier_invoice`
  ADD PRIMARY KEY (`SN`),
  ADD UNIQUE KEY `transfer_id` (`supplier_id`);

--
-- Indexes for table `tbl_assets`
--
ALTER TABLE `tbl_assets`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_bank`
--
ALTER TABLE `tbl_bank`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_cashbook`
--
ALTER TABLE `tbl_cashbook`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_credit_sales`
--
ALTER TABLE `tbl_credit_sales`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_invoice_history`
--
ALTER TABLE `tbl_invoice_history`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_payment_payrole`
--
ALTER TABLE `tbl_payment_payrole`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_payment_payrole_history`
--
ALTER TABLE `tbl_payment_payrole_history`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `tbl_transfer_recieved`
--
ALTER TABLE `tbl_transfer_recieved`
  ADD PRIMARY KEY (`SN`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_autologin`
--
ALTER TABLE `user_autologin`
  ADD PRIMARY KEY (`key_id`,`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branch_product`
--
ALTER TABLE `branch_product`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `credit_payment_history`
--
ALTER TABLE `credit_payment_history`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deposit`
--
ALTER TABLE `deposit`
  MODIFY `SN` bigint(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposit_payment_history`
--
ALTER TABLE `deposit_payment_history`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_manager`
--
ALTER TABLE `discount_manager`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_payment_history`
--
ALTER TABLE `invoice_payment_history`
  MODIFY `sn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `others`
--
ALTER TABLE `others`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_bar_code`
--
ALTER TABLE `product_bar_code`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `SN` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_category`
--
ALTER TABLE `service_category`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settingsnoedit`
--
ALTER TABLE `settingsnoedit`
  MODIFY `sne_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `SN` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_open_close`
--
ALTER TABLE `stock_open_close`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_recieved`
--
ALTER TABLE `stock_recieved`
  MODIFY `SN` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  MODIFY `SN` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SN` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier_invoice`
--
ALTER TABLE `supplier_invoice`
  MODIFY `SN` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_assets`
--
ALTER TABLE `tbl_assets`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_bank`
--
ALTER TABLE `tbl_bank`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_cashbook`
--
ALTER TABLE `tbl_cashbook`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_credit_sales`
--
ALTER TABLE `tbl_credit_sales`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_customers`
--
ALTER TABLE `tbl_customers`
  MODIFY `SN` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_expenses`
--
ALTER TABLE `tbl_expenses`
  MODIFY `SN` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_invoice_history`
--
ALTER TABLE `tbl_invoice_history`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_payment_payrole`
--
ALTER TABLE `tbl_payment_payrole`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_payment_payrole_history`
--
ALTER TABLE `tbl_payment_payrole_history`
  MODIFY `SN` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_transfer_recieved`
--
ALTER TABLE `tbl_transfer_recieved`
  MODIFY `SN` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
