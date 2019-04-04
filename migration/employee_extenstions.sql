CREATE TABLE `employee_extensions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ext_number` INT(8) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `employee_extensions` ADD UNIQUE (ext_number);