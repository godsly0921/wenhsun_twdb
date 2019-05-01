-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 01 日 14:24
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
-- 資料表結構 `single_size`
--

CREATE TABLE `single_size` (
  `single_size_id` int(12) NOT NULL COMMENT '流水號',
  `single_id` int(12) NOT NULL COMMENT '圖片編號',
  `size_type` varchar(20) NOT NULL COMMENT '尺寸類型',
  `size_description` varchar(30) NOT NULL COMMENT '尺寸描述',
  `dpi` int(12) DEFAULT NULL COMMENT '解析度',
  `mp` varchar(20) DEFAULT NULL COMMENT '像素尺寸',
  `w_h` varchar(20) DEFAULT NULL COMMENT '圖片尺寸',
  `print_w_h` varchar(20) DEFAULT NULL COMMENT '列印尺寸',
  `file_size` int(30) DEFAULT NULL COMMENT '檔案大小',
  `ext` varchar(10) DEFAULT NULL COMMENT '檔案格式',
  `sale_twd` int(6) DEFAULT NULL COMMENT '銷售價格-台幣',
  `sale_point` float(4,2) DEFAULT NULL COMMENT '銷售價格-點數'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `single_size`
--
ALTER TABLE `single_size`
  ADD PRIMARY KEY (`single_size_id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `single_size`
--
ALTER TABLE `single_size`
  MODIFY `single_size_id` int(12) NOT NULL AUTO_INCREMENT COMMENT '流水號';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
