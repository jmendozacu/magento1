-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: May 19, 2016 at 02:06 PM
-- Server version: 5.5.40
-- PHP Version: 5.5.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev_114_royyoungchemist_dev_fgct_net`
--

-- --------------------------------------------------------

--
-- Table structure for table `iwd_storelocator`
--

CREATE TABLE IF NOT EXISTS `iwd_storelocator` (
  `entity_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `country_id` varchar(3) NOT NULL,
  `region_id` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(15) NOT NULL,
  `stores` varchar(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `desc` longtext,
  `latitude` varchar(10) NOT NULL,
  `longitude` varchar(10) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8;


--
-- Table structure for table `iwd_storelocator_store`
--

CREATE TABLE IF NOT EXISTS `iwd_storelocator_store` (
  `entity_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `locatorstore` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `iwd_storelocator`
--
ALTER TABLE `iwd_storelocator`
  ADD PRIMARY KEY (`entity_id`);

--
-- Indexes for table `iwd_storelocator_store`
--
ALTER TABLE `iwd_storelocator_store`
  ADD PRIMARY KEY (`entity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `iwd_storelocator`
--
ALTER TABLE `iwd_storelocator`
  MODIFY `entity_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=130;
--
-- AUTO_INCREMENT for table `iwd_storelocator_store`
--
ALTER TABLE `iwd_storelocator_store`
  MODIFY `entity_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=130;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
