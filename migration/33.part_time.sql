CREATE TABLE `part_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `part_time_empolyee_id` varchar(32) NOT NULL COMMENT '打工者ID',
  `start_time` datetime NOT NULL COMMENT '排班開始時間',
  `end_time` datetime NOT NULL COMMENT '排班結束時間',
  `status` int(1) NOT NULL COMMENT '排班是否正常使用 0:排班未使用 1:排班已使用 3:排班取消',
  `remark` text NOT NULL COMMENT '備註',
  `builder` int(11) NOT NULL COMMENT '預約者',
  `builder_type` int(1) NOT NULL,
  `canceler` int(11) NOT NULL DEFAULT '0' COMMENT '取消者',
  `create_time` datetime NOT NULL COMMENT '建立時間',
  `modify_time` datetime NOT NULL COMMENT '異動時間',
  `tobill` tinyint(1) NOT NULL DEFAULT '0',
  `canceler_type` int(1) NOT NULL DEFAULT '1' COMMENT '0：管理者 1：使用者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='排班';