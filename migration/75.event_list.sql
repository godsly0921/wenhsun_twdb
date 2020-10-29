CREATE TABLE `wenhsun`.`event_list` (
    `id` INT NOT NULL AUTO_INCREMENT ,
    `author_id` INT NOT NULL COMMENT '作者 id';
    `year` CHAR(4) NOT NULL DEFAULT '' COMMENT '年' ,
    `month` CHAR(2) NOT NULL DEFAULT '' COMMENT '月' ,
    `day` CHAR(2) NOT NULL DEFAULT '' COMMENT '日' ,
    `title` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '事件標題' ,
    `description` TEXT NOT NULL DEFAULT '' COMMENT '事件說明' ,
    `image_link` VARCHAR(512) NOT NULL DEFAULT '' COMMENT '圖庫圖片' ,
    `create_at` DATETIME NOT NULL COMMENT '建立時間' ,
    `update_at` DATETIME NOT NULL COMMENT '更新時間' ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
