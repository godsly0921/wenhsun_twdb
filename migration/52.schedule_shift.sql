-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 07 月 04 日 20:11
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
-- 資料庫： `wenhsun_hr`
--

-- --------------------------------------------------------

--
-- 資料表結構 `schedule_shift`
--

CREATE TABLE `schedule_shift` (
  `shift_id` int(12) NOT NULL COMMENT '流水號',
  `store_id` int(1) NOT NULL COMMENT '館別 1:一般館舍 2:茶館',
  `in_out` int(1) NOT NULL COMMENT '場別 1:內場 2:外場 0:不分',
  `class` varchar(2) NOT NULL COMMENT '班別 A、B',
  `is_special` int(1) NOT NULL COMMENT '是否為特殊上班時間 0:否 1:是',
  `start_time` time NOT NULL COMMENT '上班時間',
  `end_time` time NOT NULL COMMENT '下班時間',
  `start_date` date DEFAULT NULL COMMENT '特殊上班時間開始日期',
  `end_date` date DEFAULT NULL COMMENT '特殊上班時間結束日期',
  `update_time` datetime NOT NULL COMMENT '更新時間',
  `update_id` int(12) NOT NULL COMMENT '更新人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='班次表';

--
-- 傾印資料表的資料 `schedule_shift`
--

INSERT INTO `schedule_shift` (`shift_id`, `store_id`, `in_out`, `class`, `is_special`, `start_time`, `end_time`, `start_date`, `end_date`, `update_time`, `update_id`) VALUES
(1, 2, 0, 'A', 0, '09:30:00', '18:30:00', NULL, NULL, '2019-07-04 23:32:31', 1),
(2, 2, 0, 'B', 0, '12:00:00', '21:00:00', NULL, NULL, '2019-07-04 00:00:00', 1),
(3, 1, 0, 'B', 0, '12:30:00', '21:30:00', NULL, NULL, '2019-07-04 00:00:00', 1),
(4, 1, 0, 'A', 0, '09:30:00', '18:30:00', NULL, NULL, '2019-07-04 00:00:00', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `schedule_shift`
--
ALTER TABLE `schedule_shift`
  ADD PRIMARY KEY (`shift_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `schedule_shift`
--
ALTER TABLE `schedule_shift`
  MODIFY `shift_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
