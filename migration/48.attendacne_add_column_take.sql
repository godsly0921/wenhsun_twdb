ALTER TABLE `wenhsun`.`attendance_record`
ADD COLUMN `leave_time` DATETIME NOT NULL AFTER `take`;

ALTER TABLE `wenhsun`.`attendance_record`
ADD COLUMN `leave_minutes` INT(8) NOT NULL AFTER `leave_time`;