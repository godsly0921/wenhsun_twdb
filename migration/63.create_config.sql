CREATE TABLE `config` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `config_name` VARCHAR(100) NULL DEFAULT '設定名稱',
  `config_value` VARCHAR(100) NULL DEFAULT '設定值',
  `remark` VARCHAR(200) NULL DEFAULT '說明',
  PRIMARY KEY (`id`));
  
ALTER TABLE `config` 
ADD UNIQUE INDEX `config_name_UNIQUE` (`config_name` ASC);