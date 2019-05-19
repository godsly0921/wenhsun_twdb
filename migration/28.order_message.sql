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
-- 資料表結構 `order_message`
--

CREATE TABLE `order_message` (
  `order_message_id` int(12) NOT NULL COMMENT '流水號',
  `order_id` varchar(14) NOT NULL COMMENT '訂單編號',
  `message` text NOT NULL COMMENT '留言內容',
  `create_time` datetime NOT NULL COMMENT '留言時間',
  `reply_account_id` int(12) DEFAULT NULL COMMENT '回覆人員 ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='訂單留言';

--
-- 傾印資料表的資料 `order_message`
--

INSERT INTO `order_message` (`order_message_id`, `order_id`, `message`, `create_time`, `reply_account_id`) VALUES
(1, 'P201905180001', 'test data', '2019-05-19 03:00:00', NULL),
(2, 'P201905180001', 'test', '2019-05-19 03:31:09', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `order_message`
--
ALTER TABLE `order_message`
  ADD PRIMARY KEY (`order_message_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `order_message`
--
ALTER TABLE `order_message`
  MODIFY `order_message_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
