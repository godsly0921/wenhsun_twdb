-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 06 月 30 日 18:31
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
-- 資料庫： `wenhsun`
--

-- --------------------------------------------------------

--
-- 資料表結構 `piccolumn`
--

CREATE TABLE `piccolumn` (
  `piccolumn_id` int(12) NOT NULL COMMENT '流水號',
  `pic` varchar(100) NOT NULL COMMENT '小圖',
  `title` varchar(256) NOT NULL COMMENT '標題',
  `date_start` date NOT NULL COMMENT '專欄活動開始日期',
  `date_end` date NOT NULL COMMENT '專欄活動結束日期',
  `time_desc` varchar(256) NOT NULL COMMENT '專欄活動時間',
  `address` varchar(256) NOT NULL COMMENT '地址',
  `content` text NOT NULL COMMENT '專欄內文',
  `recommend_single_id` varchar(256) DEFAULT NULL,
  `publish_start` datetime NOT NULL COMMENT '專欄公佈開始時間',
  `publish_end` datetime NOT NULL COMMENT '專欄公佈結束時間',
  `status` int(1) NOT NULL COMMENT '公佈狀態 1:發佈 0:不發佈',
  `update_time` datetime NOT NULL COMMENT '更新時間',
  `update_id` int(12) NOT NULL COMMENT '更新人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='圖片專欄';

--
-- 傾印資料表的資料 `piccolumn`
--

INSERT INTO `piccolumn` (`piccolumn_id`, `pic`, `title`, `date_start`, `date_end`, `time_desc`, `address`, `content`, `recommend_single_id`, `publish_start`, `publish_end`, `status`, `update_time`, `update_id`) VALUES
(1, '/assets/image/piccolumn/201906302345175d18d90d5fb65.jpg', '【原鄉與越境――洛夫•吳晟•席慕蓉的詩情畫意】', '2019-03-07', '2019-06-30', '週一~五:10:00-18:00(星期六日、國定假日休館)', '文藝資料研究及服務中心 長廊\r\n(台北市中正區中山南路11號B2)', '<p><span style=\"font-size:18px\">活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說<br />\r\n活動說明活動說明活動說明活動說明活動說明活動說明活動說明<br />\r\n活動說明活動說明活動說明活動說明活動說明</span></p>\r\n\r\n<p><img alt=\"\" src=\"/wenhsun_hr/assets/ckfinder/userfiles/images/pic1-100.jpg\" style=\"width:50%\" /><img alt=\"\" src=\"/wenhsun_hr/assets/ckfinder/userfiles/images/pic2-100.jpg\" style=\"width:50%\" /></p>\r\n\r\n<p><span style=\"font-size:18px\">活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說<br />\r\n活動說明活動說明活動說明活動說明活動說明活動說明活動說明<br />\r\n活動說明活動說明活動說明活動說明活動說明</span></p>\r\n', '71,72,73', '2019-03-07 00:00:00', '2019-06-30 00:00:00', 1, '2019-07-01 02:06:41', 1),
(2, '/assets/image/piccolumn/201906302345175d18d90d5fb65.jpg', '【原鄉與越境――洛夫•吳晟•席慕蓉的詩情畫意】', '2019-03-07', '2019-06-30', '週一~五:10:00-18:00(星期六日、國定假日休館)', '文藝資料研究及服務中心 長廊\r\n(台北市中正區中山南路11號B2)', '<p>活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說<br />\r\n活動說明活動說明活動說明活動說明活動說明活動說明活動說明<br />\r\n活動說明活動說明活動說明活動說明活動說明</p>\r\n\r\n<p><img alt=\"\" src=\"/wenhsun_hr/assets/ckfinder/userfiles/images/pic1-100.jpg\" style=\"width:50%\" /><img alt=\"\" src=\"/wenhsun_hr/assets/ckfinder/userfiles/images/pic2-100.jpg\" style=\"width:50%\" /></p>\r\n\r\n<p>活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說明活動說<br />\r\n活動說明活動說明活動說明活動說明活動說明活動說明活動說明<br />\r\n活動說明活動說明活動說明活動說明活動說明</p>\r\n', '71,72,73', '2019-03-07 00:00:00', '2019-06-30 00:00:00', 1, '2019-07-01 02:03:42', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `piccolumn`
--
ALTER TABLE `piccolumn`
  ADD PRIMARY KEY (`piccolumn_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `piccolumn`
--
ALTER TABLE `piccolumn`
  MODIFY `piccolumn_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
