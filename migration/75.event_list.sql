-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2020 年 11 月 04 日 05:40
-- 伺服器版本： 10.3.14-MariaDB
-- PHP 版本： 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 資料庫： `wenhsun_new`
--

-- --------------------------------------------------------

--
-- 資料表結構 `event_list`
--

CREATE TABLE `event_list` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL COMMENT '作者 id',
  `year` char(4) NOT NULL DEFAULT '' COMMENT '年',
  `month` char(2) DEFAULT NULL COMMENT '月',
  `day` char(2) DEFAULT NULL COMMENT '日',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '事件標題',
  `description` text DEFAULT NULL COMMENT '事件說明',
  `image_link` varchar(512) NOT NULL DEFAULT '' COMMENT '圖庫圖片',
  `create_at` datetime NOT NULL COMMENT '建立時間',
  `update_at` datetime NOT NULL COMMENT '更新時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `event_list`
--
ALTER TABLE `event_list`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `event_list`
--
ALTER TABLE `event_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
