DROP TABLE `wenhsun`.`member`;

CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT '帳號',
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '密碼',
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '姓名',
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `gender` char(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '性別',
  `birthday` date NOT NULL COMMENT '生日',
  `phone` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '電話',
  `mobile` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '手機',
  `member_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '一般' COMMENT '會員類型/VIP',
  `account_type` varchar(12) COLLATE utf8_unicode_ci NOT NULL COMMENT '會員來源',
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `nationality` char(2) COLLATE utf8_unicode_ci NOT NULL COMMENT '國籍',
  `county` varchar(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '縣市',
  `town` varchar(36) COLLATE utf8_unicode_ci NOT NULL COMMENT '鄉鎮',
  `address` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT '地址',
  `active_point` float NOT NULL COMMENT '生效點數',
  `inactive_point` float NOT NULL COMMENT '失效點數',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `create_by` int(11) NOT NULL DEFAULT '0',
  `update_by` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name_UNIQUE` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
