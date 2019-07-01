CREATE TABLE `personal_calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `empolyee_id` varchar(32) NOT NULL COMMENT '打工者ID',
  `start_time` datetime NOT NULL COMMENT '行程開始時間',
  `end_time` datetime NOT NULL COMMENT '行程結束時間',
  `status` int(1) NOT NULL COMMENT '行程是否正常使用 0:行程未使用 1:行程已使用 3:行程取消',
  `remark` text NOT NULL COMMENT '備註',
  `builder` int(11) NOT NULL COMMENT '建立者',
  `builder_type` int(1) NOT NULL,
  `canceler` int(11) NOT NULL DEFAULT '0' COMMENT '取消者',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  `modify_time` datetime NOT NULL COMMENT '異動時間',
  `tobill` tinyint(1) NOT NULL DEFAULT '0',
  `canceler_type` int(1) NOT NULL DEFAULT '1' COMMENT '0：管理者 1：使用者',
  `content` VARCHAR(32) NULL,
  `public` VARCHAR(7) NOT NULL DEFAULT 'PRIVATE',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `wenhsun`.`personal_calendar`
CHANGE COLUMN `empolyee_id` `employee_id` VARCHAR(32) NOT NULL COMMENT '打工者ID' ;

ALTER TABLE `wenhsun`.`personal_calendar`
CHANGE COLUMN `builder` `builder` VARCHAR(32) NOT NULL COMMENT '建立者' ;

