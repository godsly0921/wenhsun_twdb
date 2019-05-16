ALTER TABLE `employee`
ADD COLUMN department varchar(128) AFTER seat_num;

ALTER TABLE `employee`
ADD COLUMN `position` varchar(128) AFTER seat_num;