-- phpMyAdmin SQL Dump
-- version 2.11.10
-- http://www.phpmyadmin.net
--
-- Host: internal-db.s78390.gridserver.com
-- Generation Time: Feb 17, 2011 at 01:05 AM
-- Server version: 5.1.26
-- PHP Version: 4.4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db78390_luccadev`
--

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `autofills`
--

CREATE TABLE `autofills` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `creditcards`
--

CREATE TABLE `creditcards` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `person_id` varchar(100) NOT NULL,
  `type` varchar(64) DEFAULT NULL,
  `number` varchar(64) DEFAULT NULL,
  `expiration_date_month` int(2) DEFAULT NULL,
  `expiration_date_year` int(4) NOT NULL,
  `security_code` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_locations`
--

CREATE TABLE `inventory_locations` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `short` varchar(10) NOT NULL,
  `contact` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_quantity`
--

CREATE TABLE `inventory_quantity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` int(5) NOT NULL,
  `location` int(5) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` text,
  `description` text,
  `no_available` int(5) DEFAULT NULL,
  `condition` text,
  `variation_id` int(5) DEFAULT NULL,
  `addon_id` int(5) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `sold_date` date DEFAULT NULL,
  `people_id` int(5) DEFAULT NULL,
  `publish_status` tinyint(1) DEFAULT NULL,
  `item_type_id` int(1) DEFAULT NULL,
  `item_category_id` int(1) DEFAULT NULL,
  `status` text NOT NULL,
  `units` varchar(25) DEFAULT NULL,
  `height` varchar(15) DEFAULT NULL,
  `height_2` varchar(15) DEFAULT NULL,
  `width` varchar(15) DEFAULT NULL,
  `depth` varchar(15) DEFAULT NULL,
  `diameter` varchar(15) DEFAULT NULL,
  `materials_and_techniques` varchar(140) DEFAULT NULL,
  `creator` varchar(140) DEFAULT NULL,
  `country_of_origin` varchar(140) DEFAULT NULL,
  `period` varchar(140) DEFAULT NULL,
  `inventory_location_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=728 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='antiques, lucca studio, found' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

CREATE TABLE `item_images` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `filename` text NOT NULL,
  `primary` tinyint(1) NOT NULL,
  `item_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5962 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_types`
--

CREATE TABLE `item_types` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='for categories like: lighting, tables, garden and more, wall' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_variations`
--

CREATE TABLE `item_variations` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `item_id` int(10) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `name` text,
  `quantity` int(10) DEFAULT NULL,
  `primary` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=739 ;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `note` text NOT NULL,
  `status` int(5) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `note_status`
--

CREATE TABLE `note_status` (
  `int` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `short` varchar(10) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`int`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `addon_id` varchar(20) NOT NULL,
  `name` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sku` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Table structure for table `ordered_items`
--

CREATE TABLE `ordered_items` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(64) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_variation_id` int(10) NOT NULL,
  `item_variation_name` varchar(100) DEFAULT NULL,
  `item_variation_description` text NOT NULL,
  `item_variation_price` decimal(10,2) NOT NULL,
  `item_variation_sku` varchar(100) NOT NULL,
  `addon_id` varchar(100) DEFAULT NULL,
  `option_id` int(10) DEFAULT NULL,
  `option_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(5) NOT NULL,
  `addon_name` varchar(100) DEFAULT NULL,
  `option_name` varchar(100) DEFAULT NULL,
  `option_quantity` int(5) DEFAULT NULL,
  `option_sku` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` varchar(100) NOT NULL DEFAULT '0',
  `date` varchar(100) DEFAULT NULL,
  `person_id` varchar(100) DEFAULT NULL,
  `status` text,
  `shipping_type` text,
  `creditcard_id` varchar(100) NOT NULL,
  `store_comments` text,
  `discount` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `id` varchar(100) NOT NULL,
  `first_name` text,
  `last_name` text,
  `address_1` text,
  `address_2` text,
  `city` text,
  `state` varchar(2) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `trade_professional` tinyint(4) DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
