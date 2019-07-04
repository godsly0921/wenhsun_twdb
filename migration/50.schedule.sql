-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 07 月 04 日 20:10
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
-- 資料表結構 `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `empolyee_id` varchar(32) NOT NULL COMMENT '員工ID',
  `store_id` int(1) NOT NULL COMMENT '館別 1:一般館舍 2:茶館',
  `in_out` int(1) NOT NULL COMMENT '場別 1:內場 2:外場 0:不分',
  `class` varchar(2) CHARACTER SET utf8mb4 NOT NULL COMMENT '班別 A、B',
  `start_time` datetime NOT NULL COMMENT '排班開始時間',
  `end_time` datetime NOT NULL COMMENT '排班結束時間',
  `status` int(1) NOT NULL COMMENT '排班是否正常使用 0:排班未使用 1:排班已使用 3:排班取消',
  `remark` text DEFAULT NULL COMMENT '備註',
  `builder` varchar(32) NOT NULL COMMENT '預約者',
  `builder_type` int(1) NOT NULL,
  `canceler` int(11) NOT NULL DEFAULT 0 COMMENT '取消者',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  `modify_time` datetime NOT NULL COMMENT '異動時間',
  `tobill` tinyint(1) NOT NULL DEFAULT 0,
  `canceler_type` int(1) NOT NULL DEFAULT 1 COMMENT '0：管理者 1：使用者'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='排班';

--
-- 傾印資料表的資料 `schedule`
--

INSERT INTO `schedule` (`id`, `empolyee_id`, `store_id`, `in_out`, `class`, `start_time`, `end_time`, `status`, `remark`, `builder`, `builder_type`, `canceler`, `create_time`, `modify_time`, `tobill`, `canceler_type`) VALUES
(1, 'EP2019050053', 1, 0, 'A', '2019-07-05 09:30:00', '2019-07-05 18:30:00', 3, '', '1', 0, 1, '2019-07-05 03:46:47', '2019-07-05 03:46:47', 0, 1),
(2, 'EP2019050053', 2, 0, 'B', '2019-07-05 12:00:00', '2019-07-05 21:00:00', 3, '', '1', 0, 1, '2019-07-05 04:07:13', '2019-07-05 04:07:13', 0, 1),
(3, 'EP2019050046', 2, 0, 'A', '2019-07-05 09:30:00', '2019-07-05 18:30:00', 0, '', '1', 0, 0, '2019-07-05 04:07:26', '2019-07-05 04:07:26', 0, 1),
(4, 'EP2019050035', 2, 0, 'B', '2019-07-06 12:00:00', '2019-07-06 21:00:00', 0, '', '1', 0, 0, '2019-07-05 04:09:00', '2019-07-05 04:09:00', 0, 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
