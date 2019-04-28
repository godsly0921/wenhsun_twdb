CREATE TABLE `attendance_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `day` DATE NOT NULL,
  `first_time` datetime NOT NULL,
  `last_time` datetime NOT NULL,
  `abnormal_type` int(1) NOT NULL,
  `abnormal` varchar (64),
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `attendance_record` ADD UNIQUE (id);