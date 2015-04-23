-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 27, 2012 at 04:39 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `server_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `ml_banners`
--

CREATE TABLE IF NOT EXISTS `ml_banners` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `banner_image` varchar(110) NOT NULL,
  `image_alttag` varchar(70) NOT NULL,
  `description` varchar(150) NOT NULL,
  `date_created` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ml_banners`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_blocks`
--

CREATE TABLE IF NOT EXISTS `ml_blocks` (
  `block_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `block_type` enum('N','Y') NOT NULL DEFAULT 'N',
  `block_title` varchar(100) NOT NULL,
  `identifier` varchar(200) NOT NULL,
  `block_text` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `alt_tag` varchar(200) NOT NULL,
  `islink` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`block_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `ml_blocks`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_block_pages`
--

CREATE TABLE IF NOT EXISTS `ml_block_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `page_text` text NOT NULL,
  `page_url` varchar(255) NOT NULL,
  `head_title` varchar(200) NOT NULL,
  `head_keywords` varchar(255) NOT NULL,
  `head_description` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ml_block_pages`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_contents`
--

CREATE TABLE IF NOT EXISTS `ml_contents` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `page_text` text NOT NULL,
  `head_title` varchar(255) NOT NULL,
  `head_keywords` varchar(255) NOT NULL,
  `head_description` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ml_contents`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_contents_tmp`
--

CREATE TABLE IF NOT EXISTS `ml_contents_tmp` (
  `tmp_id` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  `page_title` varchar(250) NOT NULL,
  `page_text` text NOT NULL,
  `head_title` varchar(200) NOT NULL,
  `head_keywords` varchar(255) NOT NULL,
  `head_description` varchar(255) NOT NULL,
  PRIMARY KEY (`tmp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ml_contents_tmp`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_menus`
--

CREATE TABLE IF NOT EXISTS `ml_menus` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `menu_label` varchar(100) NOT NULL,
  `menu_url` varchar(200) NOT NULL,
  `menu_types` varchar(50) NOT NULL,
  `leftmenu_sort_order` int(11) NOT NULL,
  `footermenu_sort_order` int(11) NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `ml_menus`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_module_pages`
--

CREATE TABLE IF NOT EXISTS `ml_module_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `page_title` varchar(100) NOT NULL,
  `page_text` text NOT NULL,
  `head_title` varchar(100) NOT NULL,
  `head_keywords` varchar(250) NOT NULL,
  `head_description` varchar(255) NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ml_module_pages`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_news`
--

CREATE TABLE IF NOT EXISTS `ml_news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `news_title` varchar(100) NOT NULL,
  `news_short_text` varchar(200) NOT NULL,
  `news_detail_text` text NOT NULL,
  `news_img` varchar(100) NOT NULL,
  `alt_tag` varchar(100) NOT NULL,
  `news_date` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ml_news`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_newsletter`
--

CREATE TABLE IF NOT EXISTS `ml_newsletter` (
  `letter_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subscribed` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `ip_address` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`letter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ml_newsletter`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_portfolio`
--

CREATE TABLE IF NOT EXISTS `ml_portfolio` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `alt_tag` varchar(150) NOT NULL,
  `short_description` varchar(210) NOT NULL,
  `full_description` text NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ml_portfolio`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_settings`
--

CREATE TABLE IF NOT EXISTS `ml_settings` (
  `domain_id` int(11) NOT NULL,
  `variable_key` varchar(30) NOT NULL,
  `variable_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ml_settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `ml_testimonials`
--

CREATE TABLE IF NOT EXISTS `ml_testimonials` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `data_text` text NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `sort_order` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ml_testimonials`
--


-- --------------------------------------------------------

--
-- Table structure for table `system_domains`
--

CREATE TABLE IF NOT EXISTS `system_domains` (
  `domain_id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_url` varchar(150) NOT NULL,
  `cp_username` varchar(80) NOT NULL,
  `cp_password` varchar(80) NOT NULL,
  `ftp_username` varchar(80) NOT NULL,
  `ftp_password` varchar(80) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `access_enable` enum('Y','N') NOT NULL DEFAULT 'Y',
  `security_key` varchar(250) NOT NULL,
  PRIMARY KEY (`domain_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `system_domains`
--


-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE IF NOT EXISTS `system_settings` (
  `variable_key` varchar(30) NOT NULL,
  `variable_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`variable_key`, `variable_value`) VALUES
('db_info', '{"host":"localhost","username":"kamran","password":""}');

-- --------------------------------------------------------

--
-- Table structure for table `system_users`
--

CREATE TABLE IF NOT EXISTS `system_users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `firstname` varchar(60) NOT NULL,
  `lastname` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `active` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


-- --------------------------------------------------------

--
-- Table structure for table `system_users_log`
--

CREATE TABLE IF NOT EXISTS `system_users_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sessid` varchar(255) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `system_users_log`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
