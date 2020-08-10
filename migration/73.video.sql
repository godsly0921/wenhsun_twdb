-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- 主機： 192.168.1.202
-- 產生時間： 2020 年 08 月 10 日 13:18
-- 伺服器版本： 8.0.15
-- PHP 版本： 7.3.13

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
-- 資料表結構 `video`
--

CREATE TABLE `video` (
  `video_id` int(12) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '影片名稱',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '狀態( 0：停用 1：啟用 99：刪除 )',
  `extension` varchar(6) NOT NULL COMMENT '副檔名',
  `length` int(4) NOT NULL COMMENT '影片長度(秒)',
  `file_size` int(6) NOT NULL COMMENT '檔案大小(KB)',
  `m3u8_url` varchar(100) NOT NULL COMMENT '影音碎檔',
  `description` text NOT NULL COMMENT '影片描述',
  `category` varchar(100) NOT NULL COMMENT '分類',
  `create_at` datetime DEFAULT NULL COMMENT '建立時間',
  `update_at` datetime DEFAULT NULL COMMENT '更新時間',
  `delete_at` datetime DEFAULT NULL COMMENT '刪除時間',
  `last_updated_user` int(8) DEFAULT NULL COMMENT '最後異動的人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='video 影片管理 預設播放器video.js							';

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(12) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
