-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2015 at 06:43 PM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amazon`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(100) NOT NULL,
  `referer_id` varchar(100) NOT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(100) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `title` varchar(2000) NOT NULL,
  `image` varchar(500) NOT NULL,
  `category` varchar(1000) NOT NULL,
  `brand_name` varchar(500) NOT NULL,
  `review_count` bigint(20) DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(100) NOT NULL,
  `referer_id` varchar(100) NOT NULL,
  `upc` varchar(100) DEFAULT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(100) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `rating` float DEFAULT NULL,
  `date` datetime NOT NULL,
  `text` text NOT NULL,
  `title` varchar(300) NOT NULL,
  `helpful_yes` bigint(20) NOT NULL,
  `helpful_total` bigint(20) NOT NULL,
  `verified_purchaser` tinyint(1) NOT NULL,
  `image_count` int(11) DEFAULT NULL,
  `video` tinyint(1) DEFAULT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(100) NOT NULL,
  `referer_id` varchar(100) NOT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(100) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `location` varchar(300) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `review_count` bigint(20) DEFAULT NULL,
  `ranking` bigint(20) DEFAULT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(100) NOT NULL,
  `referer_id` varchar(100) NOT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
