ALTER TABLE author DROP COLUMN `identity_type`;
ALTER TABLE `author` ADD COLUMN `identity_type` json DEFAULT NULL AFTER service;