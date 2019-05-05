-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 05 日 16:44
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
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `product_id` int(12) NOT NULL COMMENT '流水號',
  `product_name` varchar(100) NOT NULL COMMENT '產品名稱',
  `coupon_id` int(12) NOT NULL COMMENT '優惠折扣ID,FK coupon，若無則填 0',
  `pic_point` float(6,2) NOT NULL COMMENT '點數,若為自由載則填 0',
  `pic_number` int(6) NOT NULL COMMENT '張數,若為點數則填 0',
  `product_type` int(1) NOT NULL DEFAULT 1 COMMENT '產品類型 ( 1：點數  2：自由載 30 天  3：自由載 90 天  4：自由載 360 天 )',
  `price` int(6) NOT NULL COMMENT '產品售價',
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '產品狀態 ( 0：停用 1：啟用 )',
  `create_time` datetime NOT NULL COMMENT '上架時間',
  `create_account_id` int(12) NOT NULL COMMENT '上架人員',
  `update_time` datetime DEFAULT NULL COMMENT '更新時間',
  `update_account_id` int(12) DEFAULT NULL COMMENT '更新人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='產品';

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
