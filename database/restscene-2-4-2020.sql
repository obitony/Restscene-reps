-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 04, 2020 at 05:42 PM
-- Server version: 5.7.23
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restscene`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `m_id` int(11) NOT NULL,
  `acc_no` varchar(250) NOT NULL,
  `acc_type` varchar(250) NOT NULL,
  `acc_holder_name` varchar(250) NOT NULL,
  `bank_name` varchar(250) NOT NULL,
  `bank_sift_code` varchar(20) NOT NULL,
  UNIQUE KEY `mem_id` (`m_id`),
  UNIQUE KEY `mem_acc_no` (`acc_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `f_name` varchar(250) NOT NULL,
  `m_name` varchar(250) NOT NULL,
  `l_name` varchar(250) NOT NULL,
  `pwd` varchar(250) NOT NULL,
  `mem_type` enum('guest','host') NOT NULL COMMENT 'this field holds the type of member wither a host or a guest',
  `status1_email` varchar(12) NOT NULL COMMENT 'status field to check whether an email is verified',
  `status2_phone` varchar(12) NOT NULL COMMENT 'status to check if members phone number is verified or not',
  `date_created` datetime NOT NULL COMMENT 'the date the user signed up',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`m_id`) REFERENCES `members` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
