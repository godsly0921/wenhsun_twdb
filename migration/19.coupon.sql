-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 13 日 16:34
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
-- 資料表結構 `coupon`
--

CREATE TABLE `coupon` (
  `coupon_id` int(12) NOT NULL COMMENT '流水號',
  `coupon_name` varchar(100) NOT NULL COMMENT '優惠活動名稱',
  `coupon_code` varchar(50) NOT NULL COMMENT '優惠折扣代號',
  `coupon_pic` int(6) NOT NULL COMMENT '張數',
  `start_time` datetime NOT NULL COMMENT '開始時間',
  `end_time` datetime NOT NULL COMMENT '結束時間',
  `status` int(1) NOT NULL COMMENT '優惠狀態 ( 0：停用 1：啟用 )',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  `create_account_id` int(12) NOT NULL COMMENT '建立人員',
  `update_time` datetime DEFAULT NULL COMMENT '更新時間',
  `update_account_id` int(12) DEFAULT NULL COMMENT '更新人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='優惠';

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`coupon_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `coupon`
--
ALTER TABLE `coupon`
  MODIFY `coupon_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
