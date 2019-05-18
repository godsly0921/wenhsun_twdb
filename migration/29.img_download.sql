-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 18 日 19:58
-- 伺服器版本： 10.3.14-MariaDB
-- PHP 版本： 7.2.17

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
-- 資料表結構 `img_download`
--

CREATE TABLE `img_download` (
  `img_download_id` int(12) NOT NULL COMMENT '流水號',
  `orders_item_id` int(12) NOT NULL COMMENT '訂單訂購項目 PK',
  `download_method` int(1) NOT NULL COMMENT '下載方式 ( 1：點數 2：月方案 )',
  `single_id` int(12) NOT NULL COMMENT '圖片編號',
  `size_type` varchar(20) NOT NULL COMMENT '尺寸類型',
  `cost` decimal(4,2) NOT NULL COMMENT '花費點數/張數',
  `authorization` varchar(100) NOT NULL COMMENT '授權對象',
  `authorization_no` varchar(50) NOT NULL COMMENT '授權號碼',
  `download_datetime` datetime NOT NULL COMMENT '下載時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='下載記錄';

--
-- 傾印資料表的資料 `img_download`
--

INSERT INTO `img_download` (`img_download_id`, `orders_item_id`, `download_method`, `single_id`, `size_type`, `cost`, `authorization`, `authorization_no`, `download_datetime`) VALUES
(1, 1, 2, 5, 'S', '1.00', 'test', 'A201905190001', '2019-05-19 03:36:00'),
(2, 1, 2, 5, 'M', '1.00', 'test', 'A201905190002', '2019-05-19 03:36:00');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `img_download`
--
ALTER TABLE `img_download`
  ADD PRIMARY KEY (`img_download_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `img_download`
--
ALTER TABLE `img_download`
  MODIFY `img_download_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
