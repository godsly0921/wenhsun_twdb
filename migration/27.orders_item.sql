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
-- 資料表結構 `orders_item`
--

CREATE TABLE `orders_item` (
  `orders_item_id` int(12) NOT NULL COMMENT '流水號',
  `order_id` varchar(14) NOT NULL COMMENT '訂單編號 FK',
  `coupon_id` int(12) DEFAULT NULL COMMENT '優惠編號 FK',
  `discount` decimal(2,2) DEFAULT NULL COMMENT '折扣金額',
  `cost_total` decimal(6,2) NOT NULL COMMENT '訂單總額',
  `order_category` int(1) NOT NULL COMMENT '訂單類型 ( 1：點數 2：自由載 3：單圖 )',
  `product_id` int(12) NOT NULL COMMENT '產品編號 FK ( 若 order_category = 3 不會有資料 )',
  `single_id` int(12) DEFAULT NULL COMMENT '圖片編號 ( order_category = 3 才會有資料 )',
  `size_type` varchar(20) DEFAULT NULL COMMENT '尺寸類型 ( order_category = 3 才會有資料 )',
  `order_detail_status` int(1) NOT NULL COMMENT '訂單詳細狀態 ( 0：取消訂單 1：已開通 2：已退款 )',
  `memo` varchar(200) DEFAULT NULL COMMENT '備註',
  `memo_create_time` datetime DEFAULT NULL COMMENT '備註建立時間',
  `memo_create_account_id` int(12) DEFAULT NULL COMMENT '備註建立人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='訂單訂購項目';

--
-- 傾印資料表的資料 `orders_item`
--

INSERT INTO `orders_item` (`orders_item_id`, `order_id`, `coupon_id`, `discount`, `cost_total`, `order_category`, `product_id`, `single_id`, `size_type`, `order_detail_status`, `memo`, `memo_create_time`, `memo_create_account_id`) VALUES
(1, 'P201905180001', NULL, '0.00', '5000.00', 2, 3, NULL, NULL, 1, NULL, NULL, NULL);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `orders_item`
--
ALTER TABLE `orders_item`
  ADD PRIMARY KEY (`orders_item_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `orders_item`
--
ALTER TABLE `orders_item`
  MODIFY `orders_item_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
