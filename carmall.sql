-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 03 月 09 日 15:30
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
-- 表的结构 `group_info`
--

CREATE TABLE IF NOT EXISTS `group_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `group_info`
--

INSERT INTO `group_info` (`id`, `group_id`, `group_name`) VALUES
(1, 627806, '工具');

-- --------------------------------------------------------

--
-- 表的结构 `pic_info`
--

CREATE TABLE IF NOT EXISTS `pic_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `pic_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `default` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `pic_info`
--

INSERT INTO `pic_info` (`id`, `product_id`, `pic_name`, `default`) VALUES
(1, 553693, 'thumb56e03f6f2d0c6.JPG', 1),
(2, 553693, 'thumb56e03f6f2e140.JPG', 0),
(3, 553693, 'thumb56e03f6f2f21c.JPG', 0),
(4, 553693, 'thumb56e03f6f30318.JPG', 0),
(5, 653092, 'thumb56e03fcda90f9.JPG', 1),
(6, 653092, 'thumb56e03fcdab371.JPG', 0),
(7, 653092, 'thumb56e03fcdada01.JPG', 0),
(8, 653092, 'thumb56e03fcdaf69f.JPG', 0),
(9, 627256, 'thumb56e0401b2442e.JPG', 1),
(10, 627256, 'thumb56e0401b25765.JPG', 0),
(11, 627256, 'thumb56e0401b26948.JPG', 0),
(12, 627256, 'thumb56e0401b27c63.JPG', 0),
(13, 744660, 'thumb56e0405c155eb.JPG', 1),
(14, 744660, 'thumb56e0405c1674b.JPG', 0),
(15, 744660, 'thumb56e0405c17805.JPG', 0),
(16, 744660, 'thumb56e0405c18b85.JPG', 0);

-- --------------------------------------------------------

--
-- 表的结构 `product_info`
--

CREATE TABLE IF NOT EXISTS `product_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `product_info`
--

INSERT INTO `product_info` (`id`, `product_id`, `title`, `content`, `group_id`) VALUES
(1, 553693, '行车记录仪', '商品1介绍', 627806),
(2, 653092, '车载充气泵', '商品2介绍', 627806),
(3, 627256, '青花', '商品3介绍', 627806),
(4, 744660, '扳手', '商品4介绍', 627806);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `last_login` varchar(255) NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`, `uid`, `last_login`, `last_ip`) VALUES
(1, 'rzd', '123321', 123456, '2016-03-09 23:19:39', '127.0.0.1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
