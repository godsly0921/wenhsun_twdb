-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2020 年 06 月 29 日 15:20
-- 伺服器版本： 10.3.14-MariaDB
-- PHP 版本： 7.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `hr_management`
--

-- --------------------------------------------------------

--
-- 資料表結構 `special_leave_year`
--

CREATE TABLE `special_leave_year` (
  `id` int(12) NOT NULL,
  `employee_id` varchar(12) NOT NULL COMMENT 'FK employee.id員工id',
  `start_date` date NOT NULL COMMENT '特休開始時間(yyyy-mm-dd)',
  `end_date` date NOT NULL COMMENT '特休結束時間(yyyy-mm-dd)',
  `seniority` float NOT NULL COMMENT '年資(月)',
  `special_leave` int(6) NOT NULL COMMENT '特休假總數(分鐘)',
  `is_close` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否已結算(0:否 1:是)',
  `memo` text DEFAULT NULL COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='曆年制(每個員工年年擁有的假)';

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `special_leave_year`
--
ALTER TABLE `special_leave_year`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `special_leave_year`
--
ALTER TABLE `special_leave_year`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
