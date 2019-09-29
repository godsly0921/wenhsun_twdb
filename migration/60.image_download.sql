ALTER TABLE `img_download` ADD `member_id` INT(12) NOT NULL COMMENT '會員編號' AFTER `img_download_id`;

ALTER TABLE `member_address_book` ADD `nationality` CHAR(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '國籍' AFTER `email`;
ALTER TABLE `member_address_book` CHANGE `country` `country` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '縣市';
ALTER TABLE `member_address_book` ADD `town` VARCHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '鄉鎮' AFTER `country`;
ALTER TABLE `member_address_book` CHANGE `country` `country` VARCHAR(100) NULL DEFAULT NULL COMMENT '國家';
ALTER TABLE `member_address_book` CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '電子郵件', CHANGE `address` `address` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '地址', CHANGE `invoice_number` `invoice_number` INT(10) NULL DEFAULT NULL COMMENT '發票號碼', CHANGE `invoice_title` `invoice_title` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '發票抬頭';
ALTER TABLE `member_address_book` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '姓名', CHANGE `codezip` `codezip` INT(4) NULL COMMENT '郵遞區號';

ALTER TABLE `orders`  ADD `name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '姓名'  AFTER `order_status`,  ADD `mobile` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '電話'  AFTER `name`,  ADD `email` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '電子郵件'  AFTER `mobile`,  ADD `nationality` CHAR(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '國籍'  AFTER `email`,  ADD `country` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '縣市'  AFTER `nationality`,  ADD `town` VARCHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '鄉鎮'  AFTER `country`,  ADD `codezip` INT(4) NULL COMMENT '郵遞區號'  AFTER `town`,  ADD `address` VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '地址'  AFTER `codezip`,  ADD `invoice_category` INT(1) NOT NULL COMMENT '發票類型 ( 0：捐贈 1：二聯 2：三聯 )'  AFTER `address`,  ADD `invoice_number` INT(10) NULL COMMENT '發票號碼'  AFTER `invoice_category`,  ADD `invoice_title` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '發票抬頭'  AFTER `invoice_number`;
ALTER TABLE `orders` DROP `address_book_id`;
ALTER TABLE `orders` CHANGE `pay_type` `pay_type` INT(1) NULL COMMENT '付款方式 ( 1：信用卡 2：超商繳款 3：超商代碼 4：ATM 轉帳 )';
ALTER TABLE `orders_item` CHANGE `order_detail_status` `order_detail_status` INT(1) NOT NULL COMMENT '訂單詳細狀態 ( 0：取消訂單 1：已開通 2：已退款 3:訂購中 )';
ALTER TABLE `orders` ADD `receive_date` DATETIME NULL DEFAULT NULL COMMENT '收到款項時間' AFTER `order_datetime`;
ALTER TABLE `orders_item` CHANGE `cost_total` `cost_total` DECIMAL(8,2) NOT NULL COMMENT '訂單總額';

ALTER TABLE `orders` ADD `pay_method` INT(1) NOT NULL DEFAULT '1' COMMENT '付款方式 ( 1:綠界 2:土銀 )' AFTER `receive_date`;
ALTER TABLE `order_Orders` CHANGE `feedback` `feedback` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '金流Feedback參數';