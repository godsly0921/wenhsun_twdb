ALTER TABLE `single` ADD `event_name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '事件名稱' AFTER `object_name`;
ALTER TABLE `single` CHANGE `filming_name` `filming_name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '圖片名稱';

ALTER TABLE `single` CHANGE `photo_source` `photo_source` VARCHAR(100) NULL DEFAULT NULL COMMENT '入藏來源';