CREATE TABLE `employee_seats` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `seat_name` varchar(64) NOT NULL DEFAULT '',
  `seat_number` varchar(8) NOT NULL DEFAULT '',
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `employee_seats` ADD UNIQUE (seat_number, seat_name);