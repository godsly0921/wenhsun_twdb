CREATE TABLE `author` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author_name` varchar(32) NOT NULL DEFAULT '',
  `gender` varchar(8) NOT NULL DEFAULT '',
  `birth` date NOT NULL,
  `death` date DEFAULT NULL,
  `job_title` varchar(128) DEFAULT NULL,
  `service` varchar(128) DEFAULT NULL,
  `identity_type` varchar(64) DEFAULT NULL,
  `nationality` varchar(8) DEFAULT NULL,
  `residence_address` varchar(256) DEFAULT NULL,
  `office_address` json DEFAULT NULL,
  `office_phone` json DEFAULT NULL,
  `office_fax` json DEFAULT NULL,
  `email` json DEFAULT NULL,
  `home_address` json DEFAULT NULL,
  `home_phone` json DEFAULT NULL,
  `home_fax` json DEFAULT NULL,
  `mobile` json DEFAULT NULL,
  `social_account` json DEFAULT NULL,
  `memo` text DEFAULT '',
  `identity_number` json DEFAULT NULL,
  `pen_name` json DEFAULT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `author_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `bank_name` varchar(128) NOT NULL,
  `bank_code` INT(3) NOT NULL,
  `branch_name` varchar(64) NOT NULL,
  `bank_account` INT(20) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;