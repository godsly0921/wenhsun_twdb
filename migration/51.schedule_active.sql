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
-- 資料表結構 `schedule_active`
--

CREATE TABLE `schedule_active` (
  `active_id` int(12) NOT NULL COMMENT '流水號',
  `active_name` varchar(125) NOT NULL COMMENT '活動名稱',
  `active_date` date NOT NULL COMMENT '活動日期',
  `update_date` datetime NOT NULL COMMENT '更新時間',
  `update_id` int(12) NOT NULL COMMENT '更新人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='排班大活動備註';

--
-- 傾印資料表的資料 `schedule_active`
--

INSERT INTO `schedule_active` (`active_id`, `active_name`, `active_date`, `update_date`, `update_id`) VALUES
(1, 'TAAZE', '2019-07-03', '2019-07-03 00:00:00', 1),
(2, 'TAAZE', '2019-07-04', '2019-07-03 00:00:00', 1),
(3, '校刊工作坊', '2019-07-06', '2019-07-03 00:00:00', 1),
(4, '校刊工作坊', '2019-07-07', '2019-07-03 00:00:00', 1),
(5, '讀劇', '2019-07-14', '2019-07-03 00:00:00', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `schedule_active`
--
ALTER TABLE `schedule_active`
  ADD PRIMARY KEY (`active_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `schedule_active`
--
ALTER TABLE `schedule_active`
  MODIFY `active_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
