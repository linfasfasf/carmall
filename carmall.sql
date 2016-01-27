-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 01 月 27 日 16:19
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `carmall`
--

-- --------------------------------------------------------

--
-- 表的结构 `pic_info`
--

CREATE TABLE IF NOT EXISTS `pic_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `pic_name` varchar(255) NOT NULL,
  `default` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=33 ;

--
-- 转存表中的数据 `pic_info`
--

INSERT INTO `pic_info` (`id`, `product_id`, `pic_name`, `default`) VALUES
(11, 197816, '56a8de7d0045a.jpg', 1),
(10, 197816, '56a8de7cf20ad.jpg', 1),
(9, 197816, '56a8de7cf027b.jpg', 1),
(12, 400053, '56a8ded9462d7.jpg', 1),
(13, 400053, '56a8ded947f0d.jpg', 1),
(14, 400053, '56a8ded94a483.jpg', 1),
(15, 603990, '56a8df9541fe7.jpg', 1),
(16, 603990, '56a8df9543eb7.jpg', 0),
(17, 603990, '56a8df9545f2b.jpg', 0),
(18, 220389, '56a8dfe7806d2.jpg', 1),
(19, 220389, '56a8dfe78266a.jpg', 0),
(20, 220389, '56a8dfe784efd.jpg', 0),
(21, 669174, '56a8e02fb27b0.jpg', 1),
(22, 669174, '56a8e02fb4787.jpg', 0),
(23, 669174, '56a8e02fb72aa.jpg', 0),
(24, 407055, '56a8e0506bc26.jpg', 1),
(25, 407055, '56a8e0506e214.jpg', 0),
(26, 407055, '56a8e0507114d.jpg', 0),
(27, 101523, '56a8e09f0f439.jpg', 1),
(28, 101523, '56a8e09f11077.jpg', 0),
(29, 101523, '56a8e09f13712.jpg', 0),
(30, 950861, '56a8ea1c32943.jpg', 1),
(31, 950861, '56a8ea1c34813.jpg', 0),
(32, 950861, '56a8ea1c37069.jpg', 0);

-- --------------------------------------------------------

--
-- 表的结构 `product_info`
--

CREATE TABLE IF NOT EXISTS `product_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `product_info`
--

INSERT INTO `product_info` (`id`, `product_id`, `title`, `image_url`) VALUES
(1, 10001, '厂家直销车志酷车载吸尘器 手提式大功率干湿两用除尘器汽车礼品', ''),
(2, 10002, '汽车伸缩式蜡拖 蜡掸 蜡把 除尘掸子 伸缩蜡把 汽车刷 质量稳定', ''),
(3, 950861, '&lt;span style=&quot;font-size: 13.12px; background-color: rgb(182, 198, 215);&quot;&gt;厂家直销车志酷车载吸尘器 手提式大功率干湿两用除尘器汽车礼品&lt;/span&gt;', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
