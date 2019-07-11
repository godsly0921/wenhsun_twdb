DROP TABLE IF EXISTS `attendance_record`;

CREATE TABLE `attendance_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(12) NOT NULL,
  `day` date NOT NULL,
  `first_time` datetime NOT NULL,
  `last_time` datetime NOT NULL,
  `abnormal_type` int(1) NOT NULL,
  `abnormal` varchar(64) DEFAULT NULL,
  `take` int(2) NOT NULL DEFAULT '0',
  `reply_description` varchar(128) DEFAULT NULL,
  `reply_update_at` datetime NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;

ALTER TABLE `wenhsun`.`attendance_record`
CHANGE COLUMN `first_time` `first_time` VARCHAR(64) NULL DEFAULT NULL ,
CHANGE COLUMN `last_time` `last_time` VARCHAR(64) NULL DEFAULT NULL ;





