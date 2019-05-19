-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 18 日 19:56
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
-- 資料表結構 `member_address_book`
--

CREATE TABLE `member_address_book` (
  `address_book_id` int(12) NOT NULL COMMENT '通訊錄 ID',
  `member_id` int(12) NOT NULL COMMENT '會員編號',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `mobile` varchar(50) NOT NULL COMMENT '電話',
  `email` varchar(100) NOT NULL COMMENT '電子郵件',
  `country` int(12) NOT NULL COMMENT '國家',
  `codezip` int(4) NOT NULL COMMENT '郵遞區號',
  `address` varchar(256) NOT NULL COMMENT '地址',
  `invoice_category` int(1) NOT NULL COMMENT '發票類型 ( 0：捐贈 1：二聯 2：三聯 )',
  `invoice_number` int(10) DEFAULT NULL COMMENT '發票號碼',
  `invoice_title` varchar(100) DEFAULT NULL COMMENT '發票抬頭'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='會員通訊錄';

--
-- 傾印資料表的資料 `member_address_book`
--

INSERT INTO `member_address_book` (`address_book_id`, `member_id`, `name`, `mobile`, `email`, `country`, `codezip`, `address`, `invoice_category`, `invoice_number`, `invoice_title`) VALUES
(1, 1, 'test', '0975291219', 'test@gmail.com', 217, 106, '台北市大安區信義路四段380號8樓之1', 2, 89684152, '典匠資訊股份有限公司');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `member_address_book`
--
ALTER TABLE `member_address_book`
  ADD PRIMARY KEY (`address_book_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `member_address_book`
--
ALTER TABLE `member_address_book`
  MODIFY `address_book_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '通訊錄 ID', AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
