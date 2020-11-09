ALTER TABLE `book_author` ADD `original_name` VARCHAR(10) NOT NULL DEFAULT '' COMMENT '本名', 
ADD `hometown` VARCHAR(10) NOT NULL DEFAULT '' COMMENT '籍貫',
ADD `birth_year` CHAR(4) NOT NULL DEFAULT '' COMMENT '出生年',
ADD `birth_month` CHAR(2) DEFAULT NULL COMMENT '出生月',
ADD `bitrh_day` CHAR(2) DEFAULT NULL COMMENT '出生日',
ADD `arrive_time` VARCHAR(50) DEFAULT NULL COMMENT '來台時間',
ADD `experience` TEXT DEFAULT NULL COMMENT '學經歷',
ADD `literary_style` TEXT DEFAULT NULL COMMENT '文學風格',
ADD `literary_achievement` TEXT DEFAULT NULL COMMENT '文學成就(得獎經歷)',
ADD `year_of_death` CHAR(4) DEFAULT NULL COMMENT '卒年',
ADD `year_of_month` CHAR(2) DEFAULT NULL COMMENT '卒月',
ADD `year_of_day` CHAR(4) DEFAULT NULL COMMENT '卒日',
ADD `pen_name` VARCHAR(20) DEFAULT NULL COMMENT '筆名',
ADD `literary_genre` VARCHAR(20) DEFAULT NULL COMMENT '創作文類',
ADD `present_job` TEXT DEFAULT NULL COMMENT '現職',
ADD `brief_intro` TEXT DEFAULT NULL COMMENT '簡介';

ALTER TABLE `book_author`  ADD `single_id` INT(12) NOT NULL COMMENT '\"FK，single.single_id 關聯圖庫的圖片\"'  AFTER `author_id`;
ALTER TABLE `book_author` CHANGE `literary_genre` `literary_genre` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '創作文類';
ALTER TABLE `book_author` CHANGE `bitrh_day` `birth_day` CHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '出生日';
UPDATE `book_author` SET birth_year=YEAR(birthday);
UPDATE `book_author` SET birth_month=MONTH(birthday);
UPDATE `book_author` SET bitrh_day=DAY(birthday);
ALTER TABLE `book_author` DROP `birthday`;
ALTER TABLE `book_author` DROP `brief_intro`;
ALTER TABLE `book_author_event` COMMENT = '作者年表' ROW_FORMAT = COMPACT;