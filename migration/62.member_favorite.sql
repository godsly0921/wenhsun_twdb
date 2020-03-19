-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 09 月 29 日 19:37
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
-- 資料表結構 `member_favorite`
--

CREATE TABLE `member_favorite` (
  `member_favorite_id` int(11) NOT NULL,
  `member_id` int(12) NOT NULL COMMENT '會員編號',
  `single_id` int(12) NOT NULL COMMENT '圖片編號',
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '狀態(1:加入 2:移除)',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  `update_time` datetime DEFAULT NULL COMMENT '更新時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='會員我的最愛';

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `member_favorite`
--
ALTER TABLE `member_favorite`
  ADD PRIMARY KEY (`member_favorite_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `member_favorite`
--
ALTER TABLE `member_favorite`
  MODIFY `member_favorite_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
