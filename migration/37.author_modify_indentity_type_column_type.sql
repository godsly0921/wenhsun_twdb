ALTER TABLE author DROP COLUMN `identity_type`;
ALTER TABLE `author` ADD COLUMN `identity_type` json DEFAULT NULL AFTER service;

ALTER TABLE `wenhsun`.`author`
CHANGE COLUMN `birth_year` `birth_year` VARCHAR(4) NULL DEFAULT NULL ;