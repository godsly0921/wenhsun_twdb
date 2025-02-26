CREATE TABLE `leave_record` (
  `id` varchar(32) NOT NULL,
  `annual_year` varchar(4) NOT NULL,
  `employee_id` varchar(32) NOT NULL,
  `minutes` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY(annual_year, employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;