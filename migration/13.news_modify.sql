DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵',
  `new_title` varchar(30) NOT NULL COMMENT '標題',
  `new_content` text NOT NULL COMMENT '內容',
  `image_name` varchar(256) NULL DEFAULT NULL  COMMENT '附件檔名',
  `new_image` varchar(256) NULL DEFAULT NULL COMMENT '附件網址',
  `new_createtime` datetime NOT NULL COMMENT '建立時間',
  `new_type` int(1) NOT NULL DEFAULT '0' COMMENT '是否顯示前台',
  `sort` int(3) NOT NULL DEFAULT '999' COMMENT '排序',
  `builder` int(11) NOT NULL COMMENT '建檔人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 COMMENT='公告';