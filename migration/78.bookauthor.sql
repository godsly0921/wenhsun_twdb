ALTER TABLE `book_author` ADD `display_frontend` INT(1) NOT NULL DEFAULT '0' COMMENT '是否顯示於前端（0：否 1：是）' AFTER `status`;

ALTER TABLE `book_author_gallery` ADD `sort` INT(2) NOT NULL DEFAULT '1' COMMENT '排序(值愈小愈前面)' AFTER `captions`;
ALTER TABLE `book_author` ADD `bookauthor_sort` INT(2) NOT NULL DEFAULT '1' COMMENT '排序(值愈小愈前面)' AFTER `present_job`;