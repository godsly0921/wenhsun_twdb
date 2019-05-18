-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 18 日 19:57
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
-- 資料表結構 `member_plan`
--

CREATE TABLE `member_plan` (
  `member_plan_id` int(12) NOT NULL COMMENT '流水號',
  `order_item_id` int(12) NOT NULL COMMENT '訂單訂購項目 PK',
  `date_start` datetime DEFAULT NULL COMMENT '方案開始時間',
  `date_end` datetime DEFAULT NULL COMMENT '方案結束時間',
  `amount` int(4) NOT NULL COMMENT '下載額度',
  `remain_amount` int(4) NOT NULL COMMENT '剩餘額度',
  `status` int(1) NOT NULL COMMENT '方案狀態 ( 0：未啟用 1：使用中 2：已結束 )'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='會員月下載方案' ROW_FORMAT=COMPACT;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `member_plan`
--
ALTER TABLE `member_plan`
  ADD PRIMARY KEY (`member_plan_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `member_plan`
--
ALTER TABLE `member_plan`
  MODIFY `member_plan_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
