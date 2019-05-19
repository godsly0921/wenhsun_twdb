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
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(14) NOT NULL COMMENT '訂單編號',
  `member_id` int(12) NOT NULL COMMENT '會員編號',
  `address_book_id` int(12) NOT NULL COMMENT '通訊錄 ID',
  `order_datetime` datetime NOT NULL COMMENT '下訂時間',
  `pay_type` int(1) NOT NULL COMMENT '付款方式 ( 1：信用卡 2：超商繳款 3：超商代碼 4：ATM 轉帳 )',
  `order_status` int(1) NOT NULL COMMENT '訂單狀態 ( 0：取消訂單 1：未結帳 2：已付款 3：已開通 4：已退款 )',
  `pay_feedback` varchar(500) DEFAULT NULL COMMENT '金流 feedback',
  `pya_result` varchar(500) DEFAULT NULL COMMENT '金流 result',
  `memo` varchar(200) DEFAULT NULL COMMENT '備註',
  `memo_create_time` datetime DEFAULT NULL COMMENT '備註建立時間',
  `memo_create_account_id` int(12) DEFAULT NULL COMMENT '備註建立人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='訂單資訊' ROW_FORMAT=COMPACT;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`order_id`, `member_id`, `address_book_id`, `order_datetime`, `pay_type`, `order_status`, `pay_feedback`, `pya_result`, `memo`, `memo_create_time`, `memo_create_account_id`) VALUES
('P201905180001', 1, 1, '2019-05-18 22:02:03', 1, 3, NULL, NULL, NULL, NULL, NULL);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
