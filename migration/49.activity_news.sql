CREATE TABLE `activity_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
  `second_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '副標',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '內文',
  `main_content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '主要內文',
  `image` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '圖片連結',
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'T' COMMENT '是否上架',
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  `create_by` int(10) unsigned NOT NULL,
  `update_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
