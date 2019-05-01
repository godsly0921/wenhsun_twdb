-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: Apr 02, 2019, 08:58 PM
-- 伺服器版本: 1.0.119
-- PHP 版本: 5.6.28

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫: `wenhsun`
--

-- --------------------------------------------------------

--
-- 資料表格式： `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `user_account` varchar(28) NOT NULL COMMENT '帳號',
  `password` varchar(128) NOT NULL COMMENT '密碼',
  `account_name` varchar(28) NOT NULL COMMENT '帳號使用者名稱',
  `account_group` int(5) NOT NULL COMMENT '帳號擁有群組',
  `make_time` datetime DEFAULT NULL COMMENT '建立時間',
  `account_type` int(1) NOT NULL DEFAULT '1' COMMENT '帳號狀態',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='帳戶權限' AUTO_INCREMENT=43 ;

--
-- 列出以下資料庫的數據： `account`
--

INSERT INTO `account` (`id`, `user_account`, `password`, `account_name`, `account_group`, `make_time`, `account_type`) VALUES
(1, 'kevin', '9d5e3ecdeb4cdb7acfd63075ae046672', '郭信良', 1, '2014-06-06 15:10:01', 0);

-- --------------------------------------------------------

--
-- 資料表格式： `bill`
--

CREATE TABLE IF NOT EXISTS `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `in_id` int(11) NOT NULL COMMENT '進入資料id',
  `out_id` int(11) NOT NULL COMMENT '離開資料id',
  `applicable` text NOT NULL COMMENT '所有可用優惠',
  `discount` int(11) NOT NULL COMMENT '最優惠之優惠方案',
  `o_price` int(11) NOT NULL COMMENT '原價',
  `d_price` int(11) NOT NULL COMMENT '打折過後價格',
  `percentage` int(11) NOT NULL COMMENT '折扣數',
  `dev_id` int(10) NOT NULL COMMENT '儀器ID',
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  `status` int(10) NOT NULL DEFAULT '0' COMMENT '是否付款',
  `bill_record_id` varchar(11) NOT NULL DEFAULT '0' COMMENT '每期帳單記錄PK',
  `receive` int(11) NOT NULL COMMENT '收款項之人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=722 ;

--
-- 列出以下資料庫的數據： `bill`
--


-- --------------------------------------------------------

--
-- 資料表格式： `bill_collection_refund`
--

CREATE TABLE IF NOT EXISTS `bill_collection_refund` (
  `bill_collection_refund_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '使用者編號',
  `collection_refund_amount` int(8) NOT NULL COMMENT '收退款金額',
  `collection_or_refund` int(1) NOT NULL DEFAULT '1' COMMENT '收退款屬性(0：退款；1：收款)',
  `collection_refund_type` int(1) NOT NULL DEFAULT '1' COMMENT '收退款方式(1：現金；2：轉帳；3：其他)',
  `handman_member_id` int(11) NOT NULL COMMENT '經手人編號',
  `handman_member_type` int(1) NOT NULL COMMENT '經手人身份(0：管理者 1：使用者)',
  `createtime` datetime NOT NULL,
  `memo` text NOT NULL COMMENT '備註',
  `checkout` int(1) NOT NULL DEFAULT '0' COMMENT '是否結帳(0：否；1：是)	',
  `bill_record_id` int(11) NOT NULL DEFAULT '0' COMMENT '每期帳單記錄PK',
  PRIMARY KEY (`bill_collection_refund_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='教授收款及扣帳' AUTO_INCREMENT=8 ;

--
-- 列出以下資料庫的數據： `bill_collection_refund`
--


-- --------------------------------------------------------

--
-- 資料表格式： `bill_door`
--

CREATE TABLE IF NOT EXISTS `bill_door` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '會員ID',
  `in_id` int(11) NOT NULL COMMENT '進門資料ID',
  `out_id` int(11) NOT NULL COMMENT '出門資料ID',
  `o_price` int(11) NOT NULL COMMENT '價格',
  `door_id` int(11) NOT NULL COMMENT '門ID',
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  `status` varchar(11) NOT NULL DEFAULT '0' COMMENT '付款狀態',
  `bill_record_id` int(11) NOT NULL DEFAULT '0' COMMENT '每期帳單記錄PK',
  `receive` varchar(11) NOT NULL DEFAULT '0' COMMENT '收款經手人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122070 ;

--
-- 列出以下資料庫的數據： `bill_door`
--


-- --------------------------------------------------------

--
-- 資料表格式： `bill_merge`
--

CREATE TABLE IF NOT EXISTS `bill_merge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '使用者ID',
  `name` varchar(50) NOT NULL COMMENT '使用者姓名',
  `door_in` int(11) NOT NULL COMMENT '門禁帳單進',
  `door_out` int(11) NOT NULL COMMENT '門禁帳單出',
  `dev_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `ispay` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- 列出以下資料庫的數據： `bill_merge`
--


-- --------------------------------------------------------

--
-- 資料表格式： `bill_other_fee`
--

CREATE TABLE IF NOT EXISTS `bill_other_fee` (
  `bill_other_fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '使用者編號',
  `fee_amount` int(8) NOT NULL COMMENT '費用金額',
  `fee_create_time` datetime NOT NULL COMMENT '費用產生日期',
  `createtime` datetime NOT NULL COMMENT '記錄時間',
  `checkout` int(1) NOT NULL DEFAULT '0' COMMENT '是否結帳(0：否；1：是)',
  `bill_record_id` int(11) NOT NULL DEFAULT '0' COMMENT '每期帳單記錄PK',
  `create_member_id` int(11) NOT NULL COMMENT '記錄建立者',
  `create_member_type` int(1) NOT NULL COMMENT '記錄建立者身份',
  `memo` text NOT NULL COMMENT '備註',
  PRIMARY KEY (`bill_other_fee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='其他費用' AUTO_INCREMENT=9 ;

--
-- 列出以下資料庫的數據： `bill_other_fee`
--


-- --------------------------------------------------------

--
-- 資料表格式： `bill_record`
--

CREATE TABLE IF NOT EXISTS `bill_record` (
  `bill_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '使用者編號',
  `opening_balance` int(8) NOT NULL COMMENT '上期餘額(上一筆的本期餘額 + 上一筆的本期收退款金額)',
  `other_fee` int(8) NOT NULL COMMENT '本期其他費',
  `device_fee` int(8) NOT NULL COMMENT '本期機台費',
  `door_fee` int(8) NOT NULL COMMENT '本期門禁費',
  `ending_balance` int(8) NOT NULL COMMENT '本期餘額(上期餘額-本期其他費-本期機台費-本期門禁費)',
  `collection_refund` int(8) NOT NULL COMMENT '本期收退款金額',
  `receive_member_id` int(11) NOT NULL COMMENT '收款人',
  `receive_member_type` int(1) NOT NULL DEFAULT '0' COMMENT '收款人身份(0：管理者 1：使用者)	',
  `checkout_time` datetime NOT NULL COMMENT '結帳日期',
  `createtime` datetime NOT NULL COMMENT '收款時間',
  PRIMARY KEY (`bill_record_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='每期帳單記錄' AUTO_INCREMENT=40 ;

--
-- 列出以下資料庫的數據： `bill_record`
--


-- --------------------------------------------------------

--
-- 資料表格式： `black_record`
--

CREATE TABLE IF NOT EXISTS `black_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `use_date` date NOT NULL COMMENT '使用日期',
  `use_period` varchar(64) NOT NULL COMMENT '使用時段',
  `user_name` varchar(32) NOT NULL COMMENT '使用者姓名',
  `user_id` varchar(11) NOT NULL COMMENT '使用者ID',
  `card_number` varchar(10) NOT NULL COMMENT '卡號',
  `device_name` varchar(128) NOT NULL COMMENT '儀器名稱',
  `device_id` int(11) NOT NULL COMMENT '儀器ID',
  `occupied_periods` varchar(64) NOT NULL COMMENT '被佔用時段',
  `be_occupied` varchar(32) NOT NULL COMMENT '被佔用人',
  `be_occupied_id` int(11) NOT NULL COMMENT '被佔用人ID',
  `remark` varchar(256) NOT NULL,
  `category` int(1) NOT NULL DEFAULT '0' COMMENT '黑名單分類：0表示佔用預約人時段 1表示應付帳款匯繳清超過50,000以上',
  `delete_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '刪除時間',
  `delete_reason` varchar(50) NOT NULL COMMENT '刪除原因',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

--
-- 列出以下資料庫的數據： `black_record`
--


-- --------------------------------------------------------

--
-- 資料表格式： `calculation_fee`
--

CREATE TABLE IF NOT EXISTS `calculation_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `device_id` int(11) NOT NULL COMMENT '儀器ID',
  `level_one_id` int(11) NOT NULL COMMENT '第一層ID',
  `base_minute` int(11) NOT NULL COMMENT '基數分鐘',
  `base_charge` int(11) NOT NULL COMMENT '每個基數收費',
  `start_base_charge` float NOT NULL COMMENT '開機費基數',
  `max_use_base` float NOT NULL COMMENT '最大使用基數',
  `unused_base` float NOT NULL COMMENT '未使用基數每個基數收費',
  `builder` int(11) NOT NULL COMMENT '建檔人',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  `edit_time` datetime NOT NULL COMMENT '刪除時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='儀器計算方式設定' AUTO_INCREMENT=181 ;

--
-- 列出以下資料庫的數據： `calculation_fee`
--


-- --------------------------------------------------------

--
-- 資料表格式： `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '診別ID',
  `name` varchar(32) NOT NULL COMMENT '特殊狀況分類名稱',
  `level` int(3) NOT NULL COMMENT '等級',
  `sort` int(3) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 列出以下資料庫的數據： `category`
--


-- --------------------------------------------------------

--
-- 資料表格式： `change_bill_apply`
--

CREATE TABLE IF NOT EXISTS `change_bill_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mem_id` int(11) NOT NULL,
  `des` text NOT NULL,
  `status` int(11) NOT NULL COMMENT '0未審核 , 1審核通過 ,2審核失敗,3繳費完成',
  `bill_mon` datetime NOT NULL,
  `agreeer` int(11) NOT NULL,
  `gap` int(11) NOT NULL COMMENT '扣除價格',
  `create_date` datetime NOT NULL,
  `receive` int(11) NOT NULL DEFAULT '0' COMMENT '收款人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 列出以下資料庫的數據： `change_bill_apply`
--


-- --------------------------------------------------------

--
-- 資料表格式： `clsreason`
--

CREATE TABLE IF NOT EXISTS `clsreason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `builder` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- 列出以下資料庫的數據： `clsreason`
--


-- --------------------------------------------------------

--
-- 資料表格式： `dbbackup`
--

CREATE TABLE IF NOT EXISTS `dbbackup` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(256) NOT NULL COMMENT '備份名稱',
  `file_size` varchar(32) NOT NULL COMMENT '檔案大小',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3455 ;

--
-- 列出以下資料庫的數據： `dbbackup`
--


-- --------------------------------------------------------

--
-- 資料表格式： `devclose`
--

CREATE TABLE IF NOT EXISTS `devclose` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `devid` int(11) NOT NULL,
  `startc` datetime NOT NULL,
  `endc` datetime NOT NULL,
  `reason` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `builder` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- 列出以下資料庫的數據： `devclose`
--


-- --------------------------------------------------------

--
-- 資料表格式： `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_date` date NOT NULL COMMENT '購買日期',
  `available_year` int(4) NOT NULL COMMENT '可用年限',
  `codenum` varchar(100) CHARACTER SET utf8 NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `en_name` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '儀器英文名稱',
  `position` int(11) NOT NULL COMMENT '放置地點',
  `status` tinyint(1) NOT NULL COMMENT '目前狀態',
  `attention_item` varchar(512) CHARACTER SET utf8 NOT NULL COMMENT '注意事項',
  `ip` varchar(39) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `station` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '站號',
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '1送電 0關電',
  `use_name` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '使用者姓名',
  `last_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `use_id` int(11) DEFAULT NULL COMMENT '使用人的ＩＤ',
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- 列出以下資料庫的數據： `device`
--

INSERT INTO `device` (`id`, `purchase_date`, `available_year`, `codenum`, `name`, `en_name`, `position`, `status`, `attention_item`, `ip`, `station`, `type`, `use_name`, `last_time`, `use_id`, `create_date`, `edit_date`) VALUES
(12, '0000-00-00', 10, 'DE-0007', '射頻濺鍍系統 (RF-Sputter)', 'RF-Sputter', 17, 5, '                                                                                                    ', '192.168.0.180', '54', 0, '李培菁', '2019-03-28 13:50:16', 97, '2018-05-15 11:39:41', '2018-07-06 10:12:44'),
(13, '0000-00-00', 10, 'DE-0008', '直流式真空濺鍍系統 (DC-Sputter)', 'DC Sputter', 17, 5, '                                                                                ', '192.168.0.180', '53', 0, '測試用', '2019-03-31 05:05:22', 258, '2018-05-15 11:41:18', '2018-07-06 10:13:01'),
(14, '0000-00-00', 10, 'DE-0009', '電子槍真空蒸鍍系統 I (E-Gun)', 'E-Gun Deposition System', 17, 5, '                                                                                                                                                                                                                                                                    ', '192.168.0.180', '56', 0, '測試用', '2019-03-28 13:37:02', 258, '2018-05-15 11:42:33', '2018-10-22 10:17:03'),
(21, '0000-00-00', 10, 'ET-0005', '光阻去除系統 (O2 Plasma)', 'O2 Plasma Cleaning System', 17, 5, '                                                                                ', '192.168.0.180', '52', 0, '李培菁', '2019-03-29 08:50:15', 97, '2018-05-15 11:52:19', '2018-07-06 10:19:57'),
(29, '0000-00-00', 10, 'MS-0002', '真空型掃描式探針顯微鏡 (SPA300HV)', 'High Vacuum SPM_SPA-300HV', 24, 5, '                                                                                ', '192.168.0.180', '62', 1, '劉恩惠', '2019-03-21 11:43:30', 148, '2018-05-15 12:01:10', '2018-07-06 12:26:45'),
(30, '0000-00-00', 10, 'MS-0003', '鍍白金機', '', 23, 5, '                                                                                                                                            ', '192.168.0.180', '65', 1, '劉恩惠', '2019-03-15 08:53:37', 148, '2018-05-15 12:02:16', '2018-07-06 12:29:07'),
(32, '0000-00-00', 10, 'MS-0006', '太陽能電池入射光子轉換效率量測系統 (IPCE)', 'Incident photon conversion efficiency', 24, 5, '                                                                                                    ', '192.168.0.180', '64', 1, '劉恩惠', '2019-03-15 08:53:41', 148, '2018-05-15 12:05:46', '2018-07-06 12:28:28'),
(34, '0000-00-00', 10, 'MS-0005', '太陽模擬光量測系統 (Solar Simulator)', 'Solar Simulator System', 24, 5, '                                                                                                    ', '192.168.0.180', '63', 1, '劉恩惠', '2019-03-15 08:53:45', 148, '2018-05-15 13:33:47', '2018-07-06 12:27:15'),
(38, '0000-00-00', 10, 'OL-0003', '奈米壓印系統 (NX2000)', 'Imprint_NX2000', 16, 5, '                                                            ', '192.168.0.180', '60', 0, '黃如君', '2019-03-29 14:46:29', 151, '2018-05-15 13:56:19', '2018-07-06 10:33:32'),
(40, '0000-00-00', 10, 'OL-0005', '奈米壓印系統 (EVG620)', 'Imprint_EVG620', 16, 5, '                                                            ', '192.168.0.180', '59', 0, '宋明穎', '2019-03-21 14:13:41', 252, '2018-05-15 14:26:07', '2018-07-06 10:34:06'),
(43, '0000-00-00', 10, 'DE-0001', '紫外光臭氧清洗機 (UV-Ozone)', 'UV-Ozone', 17, 5, '                                                                                ', '192.168.0.180', '51', 0, '測試用', '2019-04-01 15:17:36', 258, '2018-06-14 09:30:36', '2019-02-28 09:11:00'),
(44, '0000-00-00', 10, 'DE-0010', '電子槍真空蒸鍍系統 II (E-Gun)', 'E-Gun', 17, 2, '                                                                                                                                                                                                                                                                    ', '192.168.0.180', '57', 0, '林孟賢', '2019-04-02 18:19:26', 255, '2018-06-14 09:36:45', '2019-01-04 10:08:02'),
(45, '0000-00-00', 10, 'DE-0006', '高分子化學氣相沈積系統 (PDS)', 'PDS', 17, 5, '                                                            ', '192.168.0.180', '58', 0, '測試用', '2019-03-28 08:20:20', 258, '2018-06-14 09:39:23', '2018-07-06 12:24:08'),
(46, '0000-00-00', 10, ' MS-0004 ', '聚焦離子束系統 (FIB)', 'FIB', 21, 5, '                                                            ', '192.168.0.180', '61', 0, '戴依馨', '2019-01-08 19:01:09', 251, '2018-07-06 10:59:18', '2018-07-06 12:26:05'),
(47, '0000-00-00', 10, 'ZZ-0003', '快速熱退火系統 (RTP)', 'RTP', 17, 5, '                                        ', '192.168.0.180', '55', 0, '測試用', '2019-03-28 13:49:34', 258, '2018-07-16 15:51:41', '2018-07-16 15:52:39'),
(48, '0000-00-00', 10, 'MS-0007', '場發式電子顯微鏡 (JSM7000F)', 'SEM', 23, 5, '                                                            ', '192.168.0.180', '66', 1, '劉恩惠', '2019-03-15 08:53:32', 148, '2018-07-16 15:55:06', '2019-03-19 11:01:45'),
(49, '0000-00-00', 1, 'E-beam', '電子束微影系統 ( E-beam )', 'E-beam', 25, 5, '                                        ', '192.168.0.180', '67', 0, '', NULL, NULL, '2019-03-29 22:49:08', '2019-03-29 22:50:15');

-- --------------------------------------------------------

--
-- 資料表格式： `device_permission`
--

CREATE TABLE IF NOT EXISTS `device_permission` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '0:機台 1:門',
  `name` varchar(32) NOT NULL COMMENT '儀器ID',
  `weeks` text NOT NULL,
  `start_hors` varchar(2) NOT NULL,
  `start_minute` varchar(2) NOT NULL,
  `end_hors` varchar(2) NOT NULL,
  `end_minute` varchar(2) NOT NULL,
  `builder` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `edit_time` datetime NOT NULL COMMENT '上一個管理者修改時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `device_permission`
--


-- --------------------------------------------------------

--
-- 資料表格式： `device_record`
--

CREATE TABLE IF NOT EXISTS `device_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day_num` varchar(20) NOT NULL COMMENT '每日編號',
  `use_date` datetime NOT NULL,
  `station` varchar(20) NOT NULL COMMENT '工作站號',
  `num` varchar(20) NOT NULL COMMENT '號碼',
  `name` varchar(100) NOT NULL COMMENT '名稱',
  `dep1` varchar(100) NOT NULL COMMENT '部門1',
  `dep2` varchar(100) NOT NULL COMMENT '部門2',
  `wnum` varchar(100) NOT NULL COMMENT '工號',
  `des` text NOT NULL COMMENT '描述',
  `detail` varchar(50) NOT NULL COMMENT '詳細說明',
  `card` varchar(50) NOT NULL COMMENT '卡號',
  `tobill` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否完成轉換',
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1160 ;

--
-- 列出以下資料庫的數據： `device_record`
--


-- --------------------------------------------------------

--
-- 資料表格式： `device_status`
--

CREATE TABLE IF NOT EXISTS `device_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- 列出以下資料庫的數據： `device_status`
--


-- --------------------------------------------------------

--
-- 資料表格式： `door`
--

CREATE TABLE IF NOT EXISTS `door` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '門禁名稱',
  `en_name` varchar(100) NOT NULL COMMENT '門禁名稱EN',
  `position` varchar(20) NOT NULL COMMENT '放置地點',
  `status` int(10) NOT NULL COMMENT '門禁狀態',
  `station` varchar(20) NOT NULL COMMENT '站號',
  `ip` varchar(200) NOT NULL COMMENT 'ip位置',
  `price` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  `builder` int(11) NOT NULL COMMENT '建檔人',
  `sendway` varchar(100) NOT NULL DEFAULT 'tcp',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- 列出以下資料庫的數據： `door`
--


-- --------------------------------------------------------

--
-- 資料表格式： `door_abnormal`
--

CREATE TABLE IF NOT EXISTS `door_abnormal` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `date` date NOT NULL COMMENT '分析日期',
  `user_name` varchar(32) NOT NULL COMMENT '使用者姓名',
  `station_name` varchar(128) NOT NULL COMMENT '站號名稱',
  `card_number` varchar(20) NOT NULL COMMENT '卡號',
  `card_time` datetime NOT NULL COMMENT '刷卡時間',
  `exception_description` varchar(256) NOT NULL COMMENT '異常說明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='門禁異常記錄表' AUTO_INCREMENT=2 ;

--
-- 列出以下資料庫的數據： `door_abnormal`
--


-- --------------------------------------------------------

--
-- 資料表格式： `door_group_permission`
--

CREATE TABLE IF NOT EXISTS `door_group_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `door_group_permission_number` int(4) NOT NULL COMMENT '門組編號',
  `door_group_permission_name` varchar(20) NOT NULL COMMENT '門組名稱',
  `door_group_permission_list` text NOT NULL COMMENT '可使用門組',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='權限' AUTO_INCREMENT=32 ;

--
-- 列出以下資料庫的數據： `door_group_permission`
--


-- --------------------------------------------------------

--
-- 資料表格式： `door_permission`
--

CREATE TABLE IF NOT EXISTS `door_permission` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1:門',
  `name` varchar(32) NOT NULL COMMENT '時段名稱',
  `weeks` text,
  `start_hors` varchar(2) NOT NULL,
  `start_minute` varchar(2) NOT NULL,
  `end_hors` varchar(2) NOT NULL,
  `end_minute` varchar(2) NOT NULL,
  `builder` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `edit_time` datetime NOT NULL COMMENT '上一個管理者修改時間',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `door_permission`
--


-- --------------------------------------------------------

--
-- 資料表格式： `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `group_number` int(4) NOT NULL COMMENT '權限編號',
  `group_name` varchar(20) NOT NULL COMMENT '權限名稱',
  `group_list` text NOT NULL COMMENT '權限表',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='權限' AUTO_INCREMENT=26 ;

--
-- 列出以下資料庫的數據： `group`
--

INSERT INTO `group` (`id`, `group_number`, `group_name`, `group_list`) VALUES
(1, 1, '管理者', '1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,19,20,21,22,23,24,27,32,33,34,35,36,38,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,117,118,119,120,121,122,123,124,124,125,126,127,128,129,130,131,132,133,134'),
(2, 2, '教授/主管', '3,7,74,75,76,81,83,84'),
(3, 4, '技術人員', '3,6,7,9,10,10,11,11,32,35,36,74,75,76,77,81,82,83,84,85,86,87,88,89,92,93,94,96,97,98,99,100,101,102,103,104,105,106,107,108,113,114,115'),
(4, 3, '行政人員', '3,6,7,9,10,11,32,35,36,74,75,76,77,81,82,83,84,85,86,87,88,89,92,93,94,96,97,98,99,100,101,102,103,104,105,106,107,108,113,114,115'),
(5, 7, '使用者', '4,48,74,75,76,81,83,84,113,114'),
(6, 9, '測試用', '1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,19,20,21,22,23,24,27,32,33,34,35,36,38,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,117,118,119,120,121,122,123,124,124,125,126,127,128,129,130,131,132,134'),
(7, 8, '專用卡', '11,108,113,114,123');

-- --------------------------------------------------------

--
-- 資料表格式： `level_one_discount`
--

CREATE TABLE IF NOT EXISTS `level_one_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `device_id` int(11) NOT NULL COMMENT '儀器ID',
  `level` text NOT NULL,
  `professor` text NOT NULL COMMENT '教授',
  `weeks` text NOT NULL,
  `start_hors` varchar(2) NOT NULL,
  `start_minute` varchar(2) NOT NULL,
  `end_hors` varchar(2) NOT NULL,
  `end_minute` varchar(2) NOT NULL,
  `discount` int(3) NOT NULL,
  `builder` int(11) NOT NULL,
  `discount_start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '折扣開始時間',
  `discount_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '折扣結束時間',
  `create_time` datetime NOT NULL,
  `edit_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1305 ;

--
-- 列出以下資料庫的數據： `level_one_discount`
--


-- --------------------------------------------------------

--
-- 資料表格式： `local`
--

CREATE TABLE IF NOT EXISTS `local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- 列出以下資料庫的數據： `local`
--


-- --------------------------------------------------------

--
-- 資料表格式： `mail`
--

CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `mail_server` varchar(256) NOT NULL COMMENT '伺服器IP或網域',
  `sender` varchar(256) NOT NULL COMMENT '寄件人',
  `addressee_1` varchar(256) NOT NULL COMMENT '收件人1',
  `addressee_2` varchar(256) NOT NULL COMMENT '收件人2',
  `addressee_3` varchar(256) NOT NULL COMMENT '收件人3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 列出以下資料庫的數據： `mail`
--


-- --------------------------------------------------------

--
-- 資料表格式： `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '會員流水編號',
  `stop_card_remark` varchar(256) CHARACTER SET utf8 NOT NULL COMMENT '備註原因',
  `stop_card_people` int(11) NOT NULL COMMENT '復卡人',
  `account` varchar(50) NOT NULL COMMENT '會員帳號',
  `password` varchar(64) NOT NULL COMMENT '會員密碼',
  `name` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '會員姓名',
  `sex` tinyint(1) NOT NULL COMMENT '性別',
  `phone1` varchar(18) CHARACTER SET utf16 NOT NULL COMMENT '年齡',
  `phone2` varchar(18) CHARACTER SET utf8 NOT NULL COMMENT '手機',
  `email1` varchar(255) NOT NULL COMMENT '信箱',
  `email2` varchar(255) NOT NULL,
  `address` text CHARACTER SET utf8 NOT NULL COMMENT '地址',
  `status` tinyint(1) NOT NULL COMMENT '狀態',
  `invalidation_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '作廢日期(無效日期)',
  `create_date` datetime NOT NULL COMMENT '註冊時間',
  `edit_date` datetime NOT NULL COMMENT '異動時間',
  `user_group` int(3) NOT NULL COMMENT '角色',
  `year` varchar(4) CHARACTER SET utf8 NOT NULL COMMENT '生日年',
  `month` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT '生日月',
  `day` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT '生日日',
  `tel_no1` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '電話',
  `tel_no2` varchar(20) CHARACTER SET utf8 NOT NULL,
  `card_number` varchar(20) NOT NULL,
  `grp_lv1` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '第一層分類',
  `grp_lv2` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '分類第二層',
  `professor` int(11) NOT NULL COMMENT '指導教授',
  `stop_card_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '停卡日期',
  `device_permission` varchar(256) CHARACTER SET utf8 NOT NULL DEFAULT '{}' COMMENT '使用者儀器權限，這邊存放json供解析',
  `device_permission_type` text CHARACTER SET utf8 COMMENT '機台權限',
  `door` varchar(3) NOT NULL DEFAULT '001' COMMENT '最多255',
  `time` varchar(2) NOT NULL DEFAULT '1' COMMENT '最多64',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=282 ;

--
-- 列出以下資料庫的數據： `member`
--

INSERT INTO `member` (`id`, `stop_card_remark`, `stop_card_people`, `account`, `password`, `name`, `sex`, `phone1`, `phone2`, `email1`, `email2`, `address`, `status`, `invalidation_date`, `create_date`, `edit_date`, `user_group`, `year`, `month`, `day`, `tel_no1`, `tel_no2`, `card_number`, `grp_lv1`, `grp_lv2`, `professor`, `stop_card_datetime`, `device_permission`, `device_permission_type`, `door`, `time`) VALUES
(35, '系統自動停權(佔用他人預約時段十次)', 0, 'H111222000', 'e67c10a4c8fbfc0c400e047bb9a056a1', '郭信良S', 1, '0981806529', '0981806529', 'godsly0921@gmail.com', 'godsly0921@gmail.com', 'sadasd', 1, '0000-00-00', '2018-02-07 16:40:04', '2019-01-07 19:50:28', 7, '1990', '12', '10', '0981806529', '0981806529', '1111122222', '1', '7', 36, '2019-03-21 00:50:07', '["57","56","55","54","53","52","51"]', '{"66":"1","65":"3","64":"3","63":"3","62":"3","61":"3","60":"3","59":"3","58":"3","57":"3","56":"3","55":"3","54":"3","53":"3","52":"3","51":"3"}', '022', '3');

-- --------------------------------------------------------

--
-- 資料表格式： `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `new_language` char(5) NOT NULL COMMENT '語言',
  `new_title` varchar(30) NOT NULL COMMENT '標題',
  `new_content` text NOT NULL COMMENT '內容',
  `new_origin` varchar(256) NOT NULL COMMENT '來源或網址',
  `new_author` varchar(30) NOT NULL COMMENT '作者',
  `image_name` varchar(256) CHARACTER SET utf8mb4 NOT NULL COMMENT '圖片檔名',
  `new_image` varchar(256) NOT NULL COMMENT '圖片網址',
  `new_createtime` datetime NOT NULL COMMENT '建立時間',
  `new_type` int(1) NOT NULL DEFAULT '0' COMMENT '是否顯示前台',
  `sort` int(3) NOT NULL DEFAULT '999' COMMENT '排序',
  `builder` int(11) NOT NULL COMMENT '建檔人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='新聞動態' AUTO_INCREMENT=22 ;

--
-- 列出以下資料庫的數據： `news`
--


-- --------------------------------------------------------

--
-- 資料表格式： `news_view`
--

CREATE TABLE IF NOT EXISTS `news_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `create_time` datetime NOT NULL,
  `edit_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 列出以下資料庫的數據： `news_view`
--


-- --------------------------------------------------------

--
-- 資料表格式： `power`
--

CREATE TABLE IF NOT EXISTS `power` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `power_number` int(4) NOT NULL COMMENT '功能編號',
  `power_name` varchar(50) NOT NULL COMMENT '功能名稱',
  `power_controller` varchar(100) DEFAULT NULL COMMENT 'Controller位置',
  `power_master_number` int(4) NOT NULL COMMENT '系統編號',
  `power_range` int(3) NOT NULL COMMENT '排序',
  `power_display` int(1) NOT NULL DEFAULT '1' COMMENT '是否於前台顯示1為是0為否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='權限功能' AUTO_INCREMENT=228 ;

--
-- 列出以下資料庫的數據： `power`
--

INSERT INTO `power` (`id`, `power_number`, `power_name`, `power_controller`, `power_master_number`, `power_range`, `power_display`) VALUES
(36, 33, '功能列表', 'power/index', 1, 0, 1),
(37, 34, '功能新增', 'power/create', 1, 1, 0),
(40, 120, '角色列表', 'group/index', 1, 4, 1),
(41, 13, '角色新增', 'group/create', 1, 5, 0),
(42, 14, '角色修改', 'group/update', 1, 6, 0),
(43, 15, '角色刪除', 'group/delete', 1, 7, 0),
(44, 16, '系統管理帳號列表', 'account/index', 1, 8, 1),
(45, 17, '系統管理帳號新增', 'account/create', 1, 9, 0),
(46, 19, '系統管理帳號修改', 'account/update', 1, 10, 0),
(47, 20, '系統管理帳號刪除', 'account/delete', 1, 11, 0),
(48, 21, '系統列表', 'system/index', 1, 12, 1),
(49, 22, '系統新增', 'system/create', 1, 13, 0),
(50, 23, '系統修改', 'system/update', 1, 14, 0),
(116, 24, '系統刪除', 'system/delete', 1, 15, 0),
(118, 118, '功能修改', 'power/update', 1, 2, 0),
(119, 119, '功能刪除', 'power/delete', 1, 3, 0),
(120, 27, '員工列表', 'member/list', 2, 0, 1),
(126, 32, '公布欄消息列表', 'news/index', 3, 0, 1),
(127, 35, '公布欄消息更新', 'news/update', 3, 1, 0),
(128, 36, '公布欄消息刪除', 'news/delete', 3, 3, 0),
(131, 38, '單位分類及名稱設定', 'labset/list', 2, 0, 1),
(138, 45, '儀器資料設定', 'device/list', 2, 0, 1),
(140, 47, '特殊狀況管理列表', 'specialcase/index', 4, 0, 1),
(141, 48, '特殊狀況管理新增', 'specialcase/create', 4, 0, 0),
(142, 49, '特殊狀況管理刪除', 'specialcase/delete', 4, 0, 0),
(143, 50, '特殊狀況管理修改', 'specialcase/update', 4, 0, 0),
(147, 54, '儀器狀態名稱刪除', 'devicestatus/delete', 2, 0, 0),
(148, 55, '儀器資料修改', 'device/update', 2, 0, 1),
(149, 56, '儀器資料刪除', 'device/delete', 2, 0, 0),
(150, 57, '儀器資料新增', 'device/new', 2, 0, 0),
(152, 59, '員工新增', 'member/create', 2, 0, 0),
(153, 60, '員工資料修改', 'member/update', 2, 0, 0),
(154, 61, '員工刪除', 'member/delete', 2, 0, 0),
(155, 62, '地點名稱列表', 'local/index', 2, 0, 1),
(156, 63, '地點名稱修改', 'local/update', 2, 0, 0),
(157, 64, '地點名稱新增', 'local/create', 2, 0, 0),
(158, 65, '地點名稱刪除', 'local/delete', 2, 0, 0),
(159, 66, '儀器計費方式列表', 'calculationfee/index', 5, 0, 1),
(160, 67, '儀器計費方式批次新增', 'calculationfee/create', 5, 0, 0),
(161, 68, '儀器計費方式修改', 'calculationfee/update', 5, 0, 0),
(162, 69, '儀器計費方式刪除', 'calculationfee/delete', 5, 0, 0),
(167, 74, '實驗室儀器預約查詢', 'reservation/index', 3, 0, 1),
(168, 75, '實驗室儀器預約取消', 'reservation/update', 3, 0, 0),
(169, 76, '實驗室儀器預約新增', 'reservation/create', 3, 0, 0),
(174, 81, '消息公布', 'news/list', 3, 4, 1),
(184, 91, '特殊權限設定(預約)-查詢條件設定', 'reservation/get_special_list', 1, 0, 0),
(187, 94, '門禁記錄明細表', 'doorrec/report', 2, 5, 1),
(189, 96, '門禁記錄統計表', 'doorcount/report', 2, 6, 1),
(190, 97, '門禁異常記錄表', 'record/abnormal_list', 2, 0, 1),
(191, 98, '門禁異常紀錄表查詢', 'record/get_abnormal_list', 2, 0, 0),
(202, 109, '門組管理列表', 'door/list', 6, 0, 1),
(203, 110, '門禁計費方式設定修改', 'door/update', 6, 0, 0),
(204, 111, '門禁計費方式設定刪除', 'door/delete', 6, 0, 0),
(205, 112, '門禁計費方式設定新增', 'door/create', 6, 0, 0),
(211, 121, '公布欄消息新增', 'news/create', 3, 0, 0),
(219, 128, '門禁時段設定修改', 'doorpermission/time_group_update', 2, 7, 0);

-- --------------------------------------------------------

--
-- 資料表格式： `record`
--

CREATE TABLE IF NOT EXISTS `record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mem_num` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '員工編號',
  `info_num` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT '訊息別',
  `reader_num` varchar(3) CHARACTER SET utf8 NOT NULL COMMENT '卡機站號',
  `start_five` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '卡號前5碼',
  `end_five` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '卡號後5碼',
  `is_record` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT '是否列入考勤',
  `shiftID` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT '班別',
  `flashDate` datetime NOT NULL COMMENT '感應日期',
  `flashTime` varchar(8) CHARACTER SET utf8 NOT NULL COMMENT '感應時間',
  `memol` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '備註',
  `capfilename` varchar(25) CHARACTER SET utf8 NOT NULL COMMENT '保留',
  `attendance` varchar(3) CHARACTER SET utf8 NOT NULL COMMENT '考勤班別',
  `ctrlmode` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT '管制模式',
  `doorgroup` varchar(3) CHARACTER SET utf8 NOT NULL COMMENT '門組編號',
  `timezone` varchar(3) CHARACTER SET utf8 NOT NULL COMMENT '時段編號',
  `floorgroup` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '樓控群組',
  `homeID` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '住戶編號',
  `seriano` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '指定編號',
  `name` varchar(15) CHARACTER SET utf8 NOT NULL COMMENT '姓名',
  `second` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT '卡機開門是否為0秒',
  `doorstatus` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT '門位狀態',
  `departmentNo` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT '部門編號',
  `DayNightClass` varchar(1) CHARACTER SET utf8 NOT NULL COMMENT '其他保留',
  `PlateNo` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '車號',
  `Temperature` varchar(5) CHARACTER SET utf8 NOT NULL COMMENT '其他程式用',
  `ClientNo` varchar(2) CHARACTER SET utf8 NOT NULL COMMENT '子機編號',
  `Preserve` varchar(8) CHARACTER SET utf8 NOT NULL COMMENT '預留字元',
  `tobill` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41851 ;

--
-- 列出以下資料庫的數據： `record`
--


-- --------------------------------------------------------

--
-- 資料表格式： `reservation`
--

CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `device_id` int(3) NOT NULL COMMENT '裝置ID',
  `start_time` datetime NOT NULL COMMENT '預約開始時間',
  `end_time` datetime NOT NULL COMMENT '預約結束時間',
  `status` int(1) NOT NULL COMMENT '預約是否正常使用 0:預約未使用 1:預約已使用 3:預約取消',
  `remark` text NOT NULL COMMENT '備註',
  `builder` int(11) NOT NULL COMMENT '預約者',
  `builder_type` int(1) NOT NULL,
  `canceler` int(11) NOT NULL DEFAULT '0' COMMENT '取消者',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  `modify_time` datetime NOT NULL COMMENT '異動時間',
  `tobill` tinyint(1) NOT NULL DEFAULT '0',
  `canceler_type` int(1) NOT NULL DEFAULT '1' COMMENT '0：管理者 1：使用者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='儀器預約' AUTO_INCREMENT=298 ;

--
-- 列出以下資料庫的數據： `reservation`
--


-- --------------------------------------------------------

--
-- 資料表格式： `special_case`
--

CREATE TABLE IF NOT EXISTS `special_case` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(18) NOT NULL COMMENT '標題',
  `member_id` int(11) NOT NULL COMMENT '申請人',
  `application_time` datetime NOT NULL COMMENT '申請時間',
  `category` int(3) NOT NULL COMMENT '狀況分類',
  `approval_status` int(1) NOT NULL COMMENT '審核狀態',
  `approval_time` datetime NOT NULL COMMENT '審核時間',
  `approval_account_id` int(11) NOT NULL COMMENT '審核人ID',
  `member_ip` varchar(64) NOT NULL COMMENT '申請人IP',
  `msg` varchar(512) NOT NULL COMMENT '聯繫訊息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- 列出以下資料庫的數據： `special_case`
--


-- --------------------------------------------------------

--
-- 資料表格式： `system`
--

CREATE TABLE IF NOT EXISTS `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `system_number` int(4) NOT NULL COMMENT '系統編號',
  `system_name` varchar(50) NOT NULL COMMENT '系統名稱',
  `system_controller` varchar(100) DEFAULT NULL COMMENT '系統Controller',
  `system_type` int(1) NOT NULL COMMENT '系統狀態',
  `system_range` int(3) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='權限功能' AUTO_INCREMENT=41 ;

--
-- 列出以下資料庫的數據： `system`
--

INSERT INTO `system` (`id`, `system_number`, `system_name`, `system_controller`, `system_type`, `system_range`) VALUES
(1, 1, '權限管理模組', NULL, 1, 1),
(2, 2, '員工管理系統', NULL, 1, 2),
(3, 3, '公布欄管理模組', NULL, 1, 3),
(4, 4, '作家資料管理系統', NULL, 1, 4),
(5, 5, '薪資作業管理', NULL, 1, 5),
(6, 6, '公文收發管理', NULL, 1, 6);

-- --------------------------------------------------------

--
-- 資料表格式： `user_grp`
--

CREATE TABLE IF NOT EXISTS `user_grp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '單位名稱',
  `isroot` tinyint(1) NOT NULL COMMENT '是否為根分類',
  `parents` int(11) NOT NULL COMMENT '父分類',
  `builder` int(11) NOT NULL COMMENT '建立人',
  `sort` int(11) NOT NULL COMMENT '排序',
  `layer` int(5) NOT NULL COMMENT '層別',
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=93 ;

--
-- 列出以下資料庫的數據： `user_grp`
--

INSERT INTO `user_grp` (`id`, `name`, `isroot`, `parents`, `builder`, `sort`, `layer`, `create_date`, `edit_date`) VALUES
(1, '校內', 1, 0, 38, 1, 1, '2018-04-17 00:00:00', '2018-04-17 00:00:00'),
(2, '國(交大、中央、陽明)', 1, 0, 38, 0, 1, '2018-04-17 14:10:54', '2018-04-17 14:10:54'),
(3, '產', 1, 0, 38, 0, 1, '2018-04-18 18:26:17', '2018-04-18 18:26:17'),
(5, '學', 1, 0, 38, 0, 1, '2018-05-11 11:17:30', '2018-05-11 11:17:30'),
(6, 'CNMM', 0, 1, 38, 0, 2, '2018-05-16 10:49:28', '2019-02-26 19:05:01'),
(7, '物理所', 0, 1, 38, 0, 2, '2018-05-16 10:50:14', '2018-05-16 10:50:14'),
(8, '化工所', 0, 1, 38, 0, 2, '2018-05-16 10:51:18', '2018-06-28 14:39:50'),
(9, '材料所', 0, 1, 38, 0, 2, '2018-05-16 10:51:37', '2018-05-16 10:51:37'),
(10, '動機所', 0, 1, 38, 0, 2, '2018-05-16 10:51:46', '2018-05-16 10:51:46'),
(11, '電子所', 0, 1, 38, 0, 2, '2018-05-16 10:51:56', '2018-05-16 10:51:56'),
(12, '工科所', 0, 1, 38, 0, 2, '2018-05-16 10:52:11', '2018-05-16 10:52:11'),
(13, '光電所', 0, 1, 38, 0, 2, '2018-05-16 10:52:20', '2018-05-16 10:52:20'),
(14, '化學所', 0, 1, 38, 0, 2, '2018-05-16 10:52:28', '2018-05-16 10:52:28'),
(15, '生醫所', 0, 1, 38, 0, 2, '2018-05-16 10:52:38', '2018-05-16 10:52:38'),
(16, '奈微所', 0, 1, 38, 0, 2, '2018-05-16 10:52:47', '2018-05-16 10:52:47'),
(18, '交大台南光電學院', 0, 2, 38, 0, 2, '2018-05-16 10:53:08', '2018-05-16 10:53:08'),
(19, '交大生物科技所', 0, 2, 38, 0, 2, '2018-05-16 10:53:16', '2018-05-16 10:53:16'),
(20, '交大光電所', 0, 2, 38, 0, 2, '2018-05-16 10:53:24', '2018-05-16 10:53:24'),
(21, '交大材料所', 0, 2, 38, 0, 2, '2018-05-16 10:53:32', '2018-05-16 10:53:32'),
(22, '交大奈米所', 0, 2, 38, 0, 2, '2018-05-16 10:53:39', '2018-05-16 10:53:39'),
(23, '交大物理所', 0, 2, 38, 0, 2, '2018-05-16 10:53:49', '2018-05-16 10:53:49'),
(24, '交大電子所', 0, 2, 38, 0, 2, '2018-05-16 10:53:58', '2018-05-16 10:53:58'),
(25, '交大電子物理所', 0, 2, 38, 0, 2, '2018-05-16 10:54:06', '2018-05-16 10:54:06'),
(26, '交大電控所', 0, 2, 38, 0, 2, '2018-05-16 10:54:16', '2018-05-16 10:54:16'),
(27, '交大機械所', 0, 2, 38, 0, 2, '2018-05-16 10:54:24', '2018-05-16 10:54:24'),
(28, '交大應用化學系', 0, 2, 38, 0, 2, '2018-05-16 10:54:32', '2018-05-16 10:54:32'),
(29, '中央光電科學與工程學系', 0, 2, 38, 0, 2, '2018-05-16 10:54:42', '2018-05-16 10:54:42'),
(30, '陽明醫學工程研究所', 0, 2, 38, 0, 2, '2018-05-16 10:54:50', '2018-05-16 10:54:50'),
(31, '國立成功大學光電所', 0, 5, 38, 0, 2, '2018-05-16 10:55:00', '2018-05-16 10:55:00'),
(32, '淡江物理所材料所', 0, 5, 38, 0, 2, '2018-05-16 10:55:09', '2018-05-16 10:55:09'),
(33, '淡江物理所物理所', 0, 5, 38, 0, 2, '2018-05-16 10:55:24', '2018-05-16 10:55:24'),
(34, '國立成功大學電機所', 0, 5, 38, 0, 2, '2018-05-16 10:55:33', '2018-05-16 10:55:33'),
(35, '國立臺灣師範大學光電科技研究所', 0, 5, 38, 0, 2, '2018-05-16 10:55:50', '2018-05-16 10:55:50'),
(36, 'NDL國家奈米元件實驗室', 0, 5, 38, 0, 2, '2018-05-16 10:56:03', '2018-05-16 10:56:03'),
(37, '中原大學化工系', 0, 5, 38, 0, 2, '2018-05-16 10:56:11', '2018-05-16 10:56:11'),
(38, '中山大學電機工程研究所', 0, 5, 38, 0, 2, '2018-05-16 10:56:18', '2018-05-16 10:56:18'),
(39, '台大材料所', 0, 5, 38, 0, 2, '2018-05-16 10:56:27', '2018-05-16 10:56:27'),
(40, '台灣科技大學電子所', 0, 5, 38, 0, 2, '2018-05-16 10:56:35', '2018-05-16 10:56:35'),
(41, '中央研究院應用科學中心', 0, 5, 38, 0, 2, '2018-05-16 10:56:42', '2018-05-16 10:56:42'),
(42, '工研院材化所', 0, 3, 38, 0, 2, '2018-05-16 10:56:53', '2018-05-16 10:56:53'),
(44, '自強基金會', 0, 3, 38, 0, 2, '2018-05-16 10:57:07', '2018-05-16 10:57:07'),
(45, '漢積科技股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 10:57:15', '2018-05-16 10:57:15'),
(47, '國碩科技工業股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 10:57:29', '2018-05-16 10:57:29'),
(48, '威華微機電製程開發部', 0, 3, 38, 0, 2, '2018-05-16 10:57:39', '2018-05-16 10:57:39'),
(50, '生科所', 0, 1, 38, 0, 2, '2018-05-16 10:57:59', '2018-07-17 14:20:45'),
(52, '暨南大學電機工程', 0, 5, 38, 0, 2, '2018-05-16 10:58:24', '2018-05-16 10:58:24'),
(67, '國立聯合大學機械系', 0, 5, 38, 0, 2, '2018-05-16 11:01:07', '2018-05-16 11:01:07'),
(69, '鑫晶鑽科技股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 11:01:23', '2018-05-16 11:01:23'),
(70, '元智大學光電所', 0, 5, 38, 0, 2, '2018-05-16 11:01:34', '2018-05-16 11:01:34'),
(72, '許仲伯', 0, 3, 38, 0, 2, '2018-05-16 11:01:53', '2018-05-16 11:01:53'),
(73, '醫工所', 0, 1, 38, 0, 2, '2018-05-16 11:02:02', '2018-05-16 11:02:02'),
(74, '醫環系', 0, 1, 38, 0, 2, '2018-05-16 11:02:10', '2018-05-16 11:02:10'),
(75, '逢甲大學材料所', 0, 5, 38, 0, 2, '2018-05-16 11:02:19', '2018-05-16 11:02:19'),
(78, '友達光電股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 11:02:46', '2018-05-16 11:02:46'),
(81, '技鼎股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 11:03:42', '2018-05-16 11:03:42'),
(82, '安可光電股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 11:03:51', '2018-05-16 11:03:51'),
(83, '體學生物科技股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 11:03:59', '2018-05-16 11:03:59'),
(85, '錼新科技股份有限公司', 0, 3, 38, 0, 2, '2018-05-16 11:04:13', '2018-05-16 11:04:13'),
(86, '陳建亨(暨南大學電機所)', 0, 5, 38, 0, 2, '2018-05-16 11:04:24', '2018-06-28 14:52:20'),
(87, '鉅福科技有限公司', 0, 3, 38, 0, 2, '2018-05-16 11:11:46', '2018-05-16 11:11:46'),
(88, '工研院量測中心', 0, 3, 40, 0, 2, '2018-07-17 15:28:19', '2019-02-28 00:13:23'),
(89, '瑟斯科技有限公司', 0, 3, 40, 0, 2, '2018-08-16 10:02:21', '2018-08-16 10:02:21'),
(90, '智晶光電股份有限公司', 0, 3, 40, 0, 2, '2018-08-16 10:15:06', '2018-08-16 10:15:06'),
(91, '明新科技大學光電系', 0, 5, 40, 0, 2, '2018-08-20 08:27:45', '2018-08-20 08:27:45'),
(92, '光程研創股份有限公司', 0, 3, 55, 0, 2, '2018-09-12 16:03:39', '2018-09-12 16:09:37');

ALTER TABLE `wenhsun`.`news`
DROP COLUMN `new_origin`,

ALTER TABLE `wenhsun`.`news`
DROP COLUMN `new_language`,

