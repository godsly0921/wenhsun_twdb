-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2020 年 04 月 14 日 03:15
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
-- 資料庫： `wenhsun_new`
--

-- --------------------------------------------------------

--
-- 資料表結構 `api_manage`
--

CREATE TABLE `api_manage` (
  `id` int(12) NOT NULL,
  `api_key` varchar(64) CHARACTER SET utf8 NOT NULL,
  `api_password` varchar(16) CHARACTER SET utf8 NOT NULL,
  `api_token` varchar(256) CHARACTER SET utf8 DEFAULT NULL,
  `token_createtime` datetime NOT NULL DEFAULT current_timestamp(),
  `createtime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `api_manage`
--

INSERT INTO `api_manage` (`id`, `api_key`, `api_password`, `api_token`, `token_createtime`, `createtime`) VALUES
(1, '179a58c05297d3393a35f5f728c593c63e2c7a91ff00feb7acff204909a8616b', '506fe544c06b85d6', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsInJlcXVlc3RfdGltZSI6IjIwMjAtMDQtMTQgMTE6MDU6MTYifQ._DwC-bGBqyZmUQv16_YRx3_fWmTfZGw4EQANyobOfEQ', '2020-04-14 11:05:16', '2020-04-14 01:46:40');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `api_manage`
--
ALTER TABLE `api_manage`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `api_manage`
--
ALTER TABLE `api_manage`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
