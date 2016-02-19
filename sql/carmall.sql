-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-02-17 10:41:23
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `carmall`
--

-- --------------------------------------------------------

--
-- 表的结构 `group_info`
--

CREATE TABLE IF NOT EXISTS `group_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `group_info`
--

INSERT INTO `group_info` (`id`, `group_id`, `group_name`) VALUES
(1, 387231, '工具'),
(2, 875049, '产品'),
(3, 708460, '测试分组');

-- --------------------------------------------------------

--
-- 表的结构 `pic_info`
--

CREATE TABLE IF NOT EXISTS `pic_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `pic_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `default` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pic_name` (`pic_name`(250))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- 转存表中的数据 `pic_info`
--

INSERT INTO `pic_info` (`id`, `product_id`, `pic_name`, `default`) VALUES
(62, 302257, 'thumb56aed8b11bb41.png', 0),
(61, 302257, 'thumb56aed8b119fe8.jpg', 0),
(60, 302257, 'thumb56aed8b115d7f.png', 1),
(59, 135367, 'thumb56aed77b7e08d.png', 0),
(58, 135367, 'thumb56aed77b7d0ed.jpg', 0),
(57, 135367, 'thumb56aed77b7926c.png', 1);

-- --------------------------------------------------------

--
-- 表的结构 `product_info`
--

CREATE TABLE IF NOT EXISTS `product_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `product_info`
--

INSERT INTO `product_info` (`id`, `product_id`, `title`, `content`, `group_id`) VALUES
(14, 135367, '测试测试测试测试测试测试', '测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试', 387231),
(15, 302257, '测试测试测试测试测试测试', '测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试测试', 387231);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` varchar(40) NOT NULL,
  `uid` int(6) NOT NULL,
  `last_ip` varchar(19) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`, `last_login`, `uid`, `last_ip`) VALUES
(1, 'lero', 'e10adc3949ba59abbe56e057f20f883e', '2016-02-17 17:11:43', 402760, '127.0.0.1'),
(2, 'test', 'e10adc3949ba59abbe56e057f20f883e', '2012-07-00', 616839, '0'),
(3, 'test', 'e10adc3949ba59abbe56e057f20f883e', '2012-07-00', 627736, ''),
(4, 'test', 'e10adc3949ba59abbe56e057f20f883e', '2012-07-00', 235870, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
