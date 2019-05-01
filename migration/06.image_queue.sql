-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 01 日 14:22
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
-- 資料表結構 `image_queue`
--

CREATE TABLE `image_queue` (
  `image_queue_id` int(12) NOT NULL COMMENT '流水號',
  `single_id` int(12) NOT NULL COMMENT '圖片編號',
  `size_type` varchar(20) NOT NULL COMMENT '尺寸類型',
  `queue_status` int(1) NOT NULL DEFAULT 0 COMMENT '佇列處理狀態',
  `create_time` datetime NOT NULL COMMENT '進入佇列時間',
  `done_time` datetime DEFAULT NULL COMMENT '佇列處理時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `image_queue`
--
ALTER TABLE `image_queue`
  ADD PRIMARY KEY (`image_queue_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `image_queue`
--
ALTER TABLE `image_queue`
  MODIFY `image_queue_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
