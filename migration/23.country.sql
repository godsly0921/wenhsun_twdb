-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2019 年 05 月 18 日 14:08
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
-- 資料庫： `imagedj_web`
--

-- --------------------------------------------------------

--
-- 資料表結構 `common_Country`
--

CREATE TABLE `common_Country` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `name_tw` varchar(100) NOT NULL COMMENT '國別中文',
  `name_eng` varchar(100) NOT NULL COMMENT '國別英文'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `common_Country`
--

INSERT INTO `common_Country` (`id`, `name_tw`, `name_eng`) VALUES
(1, '亞森欣島', 'Ascension Island'),
(2, '安道爾侯國', 'Andorra'),
(3, '阿拉伯聯合大公國', 'Unuted Arab Emirates'),
(4, '阿富汗', 'Afghanistan'),
(5, '安圭拉島', 'Anguilla'),
(6, '阿爾巴尼亞', 'Albania'),
(7, '亞美尼亞', 'Armenia'),
(8, '荷屬安地列斯群島', 'Netherlands Antilles'),
(9, '安哥拉', 'Angola'),
(10, '南極洲', 'Antartica'),
(11, '阿根廷', 'Argentina'),
(12, '奧地利', 'Austria'),
(13, '澳大利亞', 'Australia'),
(14, '阿盧巴島', 'Aruba'),
(15, '亞塞拜然共和國', 'Azerbaijan'),
(16, '波士尼亞和赫芝格微納', 'Bosnia and Herzegovina'),
(17, '巴貝多', 'Barbados'),
(18, '孟加拉', 'Bangladesh'),
(19, '比利時', 'Belgium'),
(20, '布基納法索', 'Burkina Faso'),
(21, '保加利亞', 'Bulgaria'),
(22, '巴林', 'Bahrain'),
(23, '蒲隆地', 'Burundi'),
(24, '貝南', 'Benin'),
(25, '百慕達', 'Bermuda'),
(26, '汶萊', 'Brunei Darussalam'),
(27, '波利維亞', 'Bolivia'),
(28, '巴西', 'Brazil'),
(29, '巴哈馬', 'Bahamas'),
(30, '不丹', 'Bhutan'),
(31, '鮑威島', 'Bouvet Island'),
(32, '白俄羅斯', 'Byelorussian SSR'),
(33, '貝里斯', 'Belize'),
(34, '加拿大', 'Canada'),
(35, '可可斯群島', 'Cocos(Keeling) Islands'),
(36, '剛果民主共和國', 'Congo,Democratic People\'s Requblic'),
(37, '中非共和國', 'Central African Republic'),
(38, '剛果', 'Republic of Congo'),
(39, '瑞士', 'Switzerland'),
(40, '象牙海岸', 'Lvory Coast'),
(41, '科克群島', 'Cook Islands'),
(42, '智利', 'Chile'),
(43, '喀麥隆', 'Cameroon'),
(44, '中國', 'China'),
(45, '哥倫比亞', 'Colombia'),
(46, '哥斯大黎加', 'Costa Rica'),
(47, '古巴', 'Cuba'),
(48, '維德角', 'Cap Verde'),
(49, '聖誕島', 'Christmas Island'),
(50, '賽普勒斯', 'Cyprus'),
(51, '捷克', 'Czech Republic'),
(52, '德國', 'Germany'),
(53, '吉布地', 'Djibouti'),
(54, '丹麥', 'Denmark'),
(55, '多米尼亞', 'Dominica'),
(56, '多明尼加共和國', 'Dominican Republic'),
(57, '阿爾及利亞', 'Algeria'),
(58, '厄瓜多爾', 'Ecuador'),
(59, '愛沙尼亞', 'Estonia'),
(60, '埃及', 'Egypt'),
(61, '西撒哈拉', 'Western Sahara'),
(62, '埃立特里亞', 'Eritrea'),
(63, '西班牙', 'Spain'),
(64, '衣索比亞', 'Ethiopia'),
(65, '芬蘭', 'Finland'),
(66, '斐濟', 'Fiji'),
(67, '福克蘭群島', 'Faljland Islands(Malvina)'),
(68, '麥克羅尼西亞', 'Micronesia,Federal State of'),
(69, '法羅群島', 'Faroe Islands'),
(70, '法國', 'France'),
(71, '加彭', 'Gabon'),
(72, '格瑞納達', 'Grenada'),
(73, '喬治亞', 'Georgia'),
(74, '法屬圭亞那', 'French Guiana'),
(75, '根西島', 'Guernsey'),
(76, '迦納', 'Ghana'),
(77, '直布羅陀', 'Gibraltar'),
(78, '格林蘭', 'Greenland'),
(79, '甘比亞', 'Gambia'),
(80, '幾內亞', 'Guinea'),
(81, '哥德普落', 'Guadeloupe'),
(82, '赤道幾內亞', 'Equatorial Guinea'),
(83, '希臘', 'Greece'),
(84, '南喬治亞和南三明治群島', 'South Georgia and the South Sandwuch Islands'),
(85, '瓜地馬拉', 'Guatemala'),
(86, '關島', 'Guam'),
(87, '幾內亞比索', 'Guinea-Bissau'),
(88, '蓋亞納', 'Guyana'),
(89, '香港', 'Hong Kong'),
(90, '赫德-麥唐納群島', 'Heard and McDonald Islands'),
(91, '宏都拉斯', 'Honduras'),
(92, '克羅埃西亞', 'Croatia/Hrvatska'),
(93, '海地', 'Haiti'),
(94, '匈牙利', 'Hungary'),
(95, '印尼共和國', 'Indonesia'),
(96, '愛爾蘭', 'Ireland'),
(97, '以色列', 'Israel'),
(98, '曼島', 'Isle of Man'),
(99, '印度', 'India'),
(100, '英聯邦的印度洋領域', 'British Indian Ocean Territory'),
(101, '伊拉克', 'Iraq'),
(102, '伊朗', 'Iran (Islamic Republic of)'),
(103, '冰島', 'Iceland'),
(104, '義大利', 'Italy'),
(105, '澤西島', 'Jersey'),
(106, '牙買加', 'Jamaica'),
(107, '約旦王國', 'Jordan'),
(108, '日本', 'Japan'),
(109, '肯亞共和國', 'Kenya'),
(110, '吉爾吉斯斯坦', 'kyrgyzstan'),
(111, '柬埔寨', 'Cambodia'),
(112, '吉里巴斯', 'Kiribati'),
(113, '科摩羅', 'Comoros'),
(114, '聖基次吉尼維斯群島', 'Saint Kitts and Nevis'),
(115, '南韓', 'Korea,Democratia People\'s Republic'),
(116, '韓國', 'Korea, Republic of'),
(117, '科威特', 'Kuwait'),
(118, '開曼群島', 'Cayman Islands'),
(119, '哈薩克', 'Kazakhstan'),
(120, '寮國', 'Lao People\'s Democratic Republic'),
(121, '黎巴嫩', 'Lebanon'),
(122, '聖露西亞', 'Saint Lucia'),
(123, '列之敦斯登', 'Liechtenstein'),
(124, '斯里蘭卡', 'Sri Lanka'),
(125, '賴比瑞亞', 'Liberia'),
(126, '賴索扥', 'Lesotho'),
(127, '立陶宛', 'Lithuania'),
(128, '盧森堡', 'Luxembourg'),
(129, '拉脫維亞', 'Latvia'),
(130, '利比亞', 'Libyan Arab Jamahiriya'),
(131, '摩洛哥', 'Morocco'),
(132, '摩納哥', 'Monaco'),
(133, '摩爾達維亞', 'Moldova,Republic of'),
(134, '馬達加斯加', 'Madagascar'),
(135, '馬紹爾群島', 'Marshall Islands'),
(136, '馬其頓', 'Macedonia,Former Yugoslav Republic'),
(137, '馬利', 'Mali'),
(138, '緬甸', 'Myanmar'),
(139, '蒙古', 'Mongolia'),
(140, '澳門', 'Macau'),
(141, '北馬里亞納群島', 'Northern Mariana Islands'),
(142, '馬丁尼克島', 'Martinique'),
(143, '茅利塔尼亞', 'Mauritania'),
(144, '蒙特塞拉特島', 'Montserrat'),
(145, '馬爾他', 'Malta'),
(146, '摩里西斯', 'Mauritius'),
(147, '馬爾地夫', 'Maldives'),
(148, '馬拉威', 'Malawi'),
(149, '墨西哥', 'Mexico'),
(150, '馬來西亞', 'Malaysia'),
(151, '莫三比克', 'Mozambique'),
(152, '那米比亞', 'Namibia'),
(153, '新喀里多尼亞島', 'New Caledonia'),
(154, '尼日', 'Niger'),
(155, '諾福克島', 'Norfolk Islands'),
(156, '奈及利亞', 'Nigeria'),
(157, '尼加拉瓜', 'Nicaragua'),
(158, '荷蘭', 'Netherlands'),
(159, '挪威', 'Norway'),
(160, '尼泊爾', 'Nepal'),
(161, '諾魯', 'Nauru'),
(162, '尼烏亞島', 'Niue'),
(163, '紐西蘭', 'New Zealand'),
(164, '阿曼', 'Oman'),
(165, '巴拿馬', 'Panama'),
(166, '祕魯', 'Peru'),
(167, '法屬波里尼西亞', 'French Polynesia'),
(168, '巴布亞新幾內亞', 'Papua New Guinea'),
(169, '菲律賓', 'Philippines'),
(170, '巴基斯坦', 'Pakistan'),
(171, '波蘭', 'Poland'),
(172, '聖彼爾及米可龍群島', 'St. Pierre and Miquelon'),
(173, '皮特肯島', 'Pitcairn Island'),
(174, '波多黎各', 'Puerto Rico'),
(175, '巴勒斯坦', 'Palestinian Territories'),
(176, '葡萄牙', 'Portugal'),
(177, '帛琉群島', 'Palau'),
(178, '巴拉圭', 'Paraguay'),
(179, '卡達', 'Qatar'),
(180, '留尼旺島', 'Reunion Island'),
(181, '羅馬尼亞', 'Romania'),
(182, '俄羅斯聯邦', 'Russian Federation'),
(183, '盧安達', 'Rwanda'),
(184, '沙烏地阿拉伯王國', 'Saudi Arabia'),
(185, '所羅門群島', 'Solomon Islands'),
(186, '塞席爾群島', 'Seychelles'),
(187, '蘇丹', 'Sudan'),
(188, '瑞典', 'Sweden'),
(189, '新加坡', 'Singapore'),
(190, '聖赫勒拿島', 'St. Helena'),
(191, '斯拉維尼亞', 'Slovenia'),
(192, '冷岸群島及斯瓦爾巴特-堅麥因群島', 'Svalbard and Jan Maven Islands'),
(193, '斯洛伐克', 'Slovak Republic'),
(194, '獅子山', 'Sierra Leone'),
(195, '聖馬利諾共和國', 'San Marino'),
(196, '塞內加爾', 'Senegal'),
(197, '索馬利亞', 'Somalia'),
(198, '蘇利南', 'Suriname'),
(199, '聖多美及普林西比', 'Sao Tome and Principe'),
(200, '薩爾瓦多', 'El Salvador'),
(201, '敘利亞', 'Syrian Arab Republic'),
(202, '史瓦濟蘭', 'Swaziland'),
(203, '土克斯及開卡斯群島', 'Turks and Ciacos Islands'),
(204, '查德', 'Chad'),
(205, '法屬南方領土', 'French Southern Territories'),
(206, '多哥', 'Togo'),
(207, '泰國', 'Thailand'),
(208, '塔吉克', 'Tajikistan'),
(209, '扥客勞群島', 'Tokelau'),
(210, '土庫曼蘇維埃社會主義共和國', 'Turkmenistan'),
(211, '突尼西亞', 'Tunisia'),
(212, '東加王國', 'Tonga'),
(213, '東帝汶', 'East Timor'),
(214, '土耳其', 'Turkey'),
(215, '千里達共和國', 'Trinidad and Tobago'),
(216, '土瓦魯', 'Tuvalu'),
(217, '台灣', 'Taiwan'),
(218, '坦尚尼亞', 'Tanzania'),
(219, '烏克蘭', 'Ukraine'),
(220, '烏干達', 'Uganda'),
(221, '聯合王國，英國', 'United kingdom'),
(222, '美國邊遠島嶼', 'US Minor Outlying Islands'),
(223, '美國', 'United States'),
(224, '烏拉圭', 'Uruguay'),
(225, '烏茲別克', 'Uzbekistan'),
(226, '梵蒂岡 (羅馬教庭)', 'Holy See (City Vatican State)'),
(227, '聖文森及格瑞那丁', 'Saint Vincent and Grenadines'),
(228, '委內瑞拉', 'Venezuela'),
(229, '英屬維京群島', 'Virgin Islands (British)'),
(230, '美屬維京群島', 'Virgin Islands (USA)'),
(231, '越南', 'Vietnam'),
(232, '萬那社', 'Vanuatu'),
(233, '沃利斯和富圖納群島', 'Wallis and Futun Island'),
(234, '西薩摩亞', 'Western Samoa'),
(235, '葉門', 'Yemen'),
(236, '梅約特', 'Mayotte'),
(237, '南斯拉夫', 'Yugoslavia'),
(238, '南非', 'South Africa'),
(239, '尚比亞', 'Zambia'),
(240, '薩伊', 'Zaire'),
(241, '辛巴威', 'Zimbabwe');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `common_Country`
--
ALTER TABLE `common_Country`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動增長(AUTO_INCREMENT)
--

--
-- 使用資料表自動增長(AUTO_INCREMENT) `common_Country`
--
ALTER TABLE `common_Country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=242;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
