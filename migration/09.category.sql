-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 01 日 15:07
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
-- 資料表結構 `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '單位名稱',
  `isroot` tinyint(1) NOT NULL COMMENT '是否為根分類',
  `parents` int(11) NOT NULL COMMENT '父分類',
  `builder` int(11) NOT NULL COMMENT '建立人',
  `sort` int(11) NOT NULL COMMENT '排序',
  `layer` int(5) NOT NULL COMMENT '層別',
  `status` int(1) NOT NULL DEFAULT 1 COMMENT '分類狀態 0：停用 1：啟用',
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `category`
--

INSERT INTO `category` (`category_id`, `name`, `isroot`, `parents`, `builder`, `sort`, `layer`, `status`, `create_date`, `edit_date`) VALUES
(1, '個人專輯', 1, 0, 1, 1, 1, 1, '2019-04-15 01:19:32', '0000-00-00 00:00:00'),
(4, '個人', 0, 1, 1, 1, 2, 1, '2019-04-18 16:53:36', '0000-00-00 00:00:00'),
(5, '合照', 0, 1, 1, 2, 2, 1, '2019-04-18 16:53:44', '0000-00-00 00:00:00'),
(6, '書影', 0, 1, 1, 3, 2, 1, '2019-04-18 16:53:54', '0000-00-00 00:00:00'),
(7, '作品', 0, 1, 1, 4, 2, 1, '2019-04-18 16:54:08', '0000-00-00 00:00:00'),
(8, '書信', 0, 1, 1, 5, 2, 1, '2019-04-18 16:54:16', '0000-00-00 00:00:00'),
(9, '手稿', 0, 1, 1, 6, 2, 1, '2019-04-18 16:54:25', '0000-00-00 00:00:00'),
(10, '其他', 0, 1, 1, 7, 2, 1, '2019-04-18 16:54:37', '0000-00-00 00:00:00');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
