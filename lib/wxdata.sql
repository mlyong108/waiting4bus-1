-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 18 日 05:16
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `wxdata`
--

-- --------------------------------------------------------

--
-- 表的结构 `baidukey`
--

CREATE TABLE IF NOT EXISTS `baidukey` (
  `id` bigint(20) NOT NULL,
  `key` text,
  `times` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `baidukey`
--

INSERT INTO `baidukey` (`id`, `key`, `times`) VALUES
(1, '60f3e3376f8d6fb787110b9e16293f93', 0);

-- --------------------------------------------------------

--
-- 表的结构 `busline`
--

CREATE TABLE IF NOT EXISTS `busline` (
  `id` bigint(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `upstations` int(10) NOT NULL,
  `downstations` int(10) NOT NULL,
  `upstart` time NOT NULL,
  `upend` time NOT NULL,
  `downstart` time NOT NULL,
  `downend` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `favstation`
--

CREATE TABLE IF NOT EXISTS `favstation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) DEFAULT NULL,
  `cmdnum` int(11) NOT NULL,
  `createtime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `linestationid` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `linestation`
--

CREATE TABLE IF NOT EXISTS `linestation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lineId` bigint(15) NOT NULL,
  `index` int(4) NOT NULL,
  `direction` int(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `x` double NOT NULL,
  `y` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='线路的站点表；站点指某条具体线路的某发车方向上的某站；' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usercode` tinytext,
  `intime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `usercode`, `intime`) VALUES
(1, 'ssdfsd', '2012-12-17 10:00:13');

-- --------------------------------------------------------

--
-- 表的结构 `userlog`
--

CREATE TABLE IF NOT EXISTS `userlog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) DEFAULT NULL,
  `querycontent` text,
  `querytime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `contenttype` varchar(11) DEFAULT NULL,
  `upstarttime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `upendtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `downstarttime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `downendtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
