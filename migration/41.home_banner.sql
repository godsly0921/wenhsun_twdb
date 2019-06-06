-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 06 月 06 日 08:35
-- 伺服器版本： 10.3.14-MariaDB
-- PHP 版本： 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `wenhsun`
--

-- --------------------------------------------------------

--
-- 資料表結構 `home_banner`
--

CREATE TABLE `home_banner` (
  `home_banner_id` int(11) NOT NULL COMMENT '流水號',
  `image` varchar(100) NOT NULL COMMENT 'banner 路徑',
  `link` varchar(512) DEFAULT NULL COMMENT '超連結',
  `title` varchar(50) DEFAULT NULL COMMENT '圖片標題',
  `alt` varchar(50) DEFAULT NULL COMMENT '替代文字',
  `sort` int(1) NOT NULL COMMENT '排序',
  `update_time` datetime NOT NULL COMMENT '更新時間',
  `update_account_id` int(12) NOT NULL COMMENT '更新人員ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='輪播圖';

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `home_banner`
--
ALTER TABLE `home_banner`
  ADD PRIMARY KEY (`home_banner_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `home_banner`
--
ALTER TABLE `home_banner`
  MODIFY `home_banner_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
