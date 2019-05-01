DROP TABLE `employee`;

CREATE TABLE `employee` (
  `id` varchar(12) NOT NULL,
  `user_name` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `role` int(11) unsigned NOT NULL,
  `email` varchar(128) NOT NULL,
  `name` varchar(32) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `birth` datetime(1) NOT NULL,
  `person_id` varchar(10) NOT NULL,
  `nationality` varchar(8) NOT NULL,
  `country` varchar(32) NOT NULL,
  `dist` varchar(32) NOT NULL,
  `address` varchar(256) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `enable` varchar(1) NOT NULL,
  `door_card_num` varchar(16) NOT NULL,
  `ext_num` int(11) unsigned NOT NULL,
  `seat_num` int(11) unsigned NOT NULL,
  `bank_name` varchar(128) NOT NULL,
  `bank_code` varchar(3) NOT NULL,
  `bank_branch_name` varchar(64) NOT NULL,
  `bank_branch_code` varchar(8) NOT NULL,
  `bank_account` varchar(18) NOT NULL,
  `bank_account_name` varchar(32) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `employee` ADD UNIQUE (user_name);

ALTER TABLE employee
  ADD FOREIGN KEY (ext_num) REFERENCES employee_extensions (id);

ALTER TABLE employee
  ADD FOREIGN KEY (seat_num) REFERENCES employee_seats (id);