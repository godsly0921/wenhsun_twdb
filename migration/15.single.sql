-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 01 日 14:23
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
-- 資料表結構 `single`
--

CREATE TABLE `single` (
  `single_id` int(11) NOT NULL COMMENT '圖片編號',
  `photo_name` varchar(100) NOT NULL COMMENT '圖片原始檔名',
  `ext` varchar(6) NOT NULL COMMENT '檔案格式',
  `dpi` int(4) DEFAULT NULL COMMENT '解析度',
  `color` varchar(12) DEFAULT NULL COMMENT '色彩',
  `direction` int(1) DEFAULT NULL COMMENT '圖片方向( 垂直V=1，水平H=2，正方S=3 )',
  `author_id` int(4) DEFAULT NULL COMMENT '作者編號',
  `photo_source` int(4) DEFAULT NULL COMMENT '入藏來源',
  `category_id` varchar(100) DEFAULT NULL COMMENT '照片類型( 編號 )',
  `filming_date` date DEFAULT NULL COMMENT '拍攝日期',
  `filming_location` varchar(100) DEFAULT NULL COMMENT '拍攝地點',
  `filming_name` varchar(100) DEFAULT NULL COMMENT '攝影名稱',
  `store_status` int(1) NOT NULL DEFAULT 1 COMMENT '保存狀況(1：良好；2：輕度破損；3：嚴重破損)',
  `people_info` varchar(256) DEFAULT NULL COMMENT '人物資訊',
  `object_name` varchar(100) DEFAULT NULL COMMENT '物件名稱',
  `keyword` text DEFAULT NULL COMMENT '圖片關鍵字(用半形逗號區隔)',
  `index_limit` int(1) NOT NULL DEFAULT 0 COMMENT '索引使用限制(0：不開放；1：開放；2：限制)',
  `original_limit` int(1) NOT NULL DEFAULT 0 COMMENT '原件使用限制(0：不開放；1：開放；2：限閱；3：限印)',
  `photo_limit` int(1) NOT NULL DEFAULT 0 COMMENT '影像使用限制(0：不開放；1：開放；2：限文訊內部使用)',
  `description` text DEFAULT NULL COMMENT '內容描述',
  `publish` int(1) NOT NULL DEFAULT 0 COMMENT '是否上架(0：否；1：是)',
  `copyright` int(1) NOT NULL DEFAULT 0 COMMENT '著作權審核狀態(0：不通過；1：通過)',
  `memo1` text DEFAULT NULL COMMENT '備註一',
  `memo2` text DEFAULT NULL COMMENT '備註二',
  `create_time` datetime NOT NULL COMMENT '上架時間',
  `create_account_id` int(12) NOT NULL COMMENT '上架人員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `single`
--
ALTER TABLE `single`
  ADD PRIMARY KEY (`single_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `single`
--
ALTER TABLE `single`
  MODIFY `single_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '圖片編號';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
