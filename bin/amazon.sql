
CREATE DATABASE reviews;
USE reviews;


--
-- Table structure for table `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(300) NOT NULL,
  `referer_id` varchar(300) NOT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `items`
--

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `title` varchar(300) NOT NULL,
  `image` varchar(300) NOT NULL,
  `category` varchar(300) NOT NULL,
  `brand_name` varchar(300) NOT NULL,
  `review_count` varchar(100) NOT NULL,
  `rating` varchar(100) NOT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(300) NOT NULL,
  `referer_id` varchar(300) NOT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `item_id` varchar(300) NOT NULL,
  `user_id` varchar(300) NOT NULL,
  `rating` varchar(100) NOT NULL,
  `review_date` datetime NOT NULL,
  `review_text` text NOT NULL,
  `title` varchar(300) NOT NULL,
  `helpful_yes` varchar(100) NOT NULL,
  `helpful_total` varchar(100) NOT NULL,
  `verified_purchaser` tinyint(1) NOT NULL,
  `image_count` int(11) NOT NULL,
  `video` tinyint(1) NOT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(300) NOT NULL,
  `referer_id` varchar(300) NOT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `recid` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `location` varchar(300) NOT NULL,
  `name` varchar(300) NOT NULL,
  `review_count` varchar(100) NOT NULL,
  `ranking` varchar(100) NOT NULL,
  `state` varchar(300) NOT NULL,
  `referer_type` varchar(300) NOT NULL,
  `referer_id` varchar(300) NOT NULL,
  `run_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `collection_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`recid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
