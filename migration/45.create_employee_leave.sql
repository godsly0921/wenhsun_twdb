CREATE TABLE `employee_leave` (
  `id` varchar(32) NOT NULL,
  `employee_id` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL,
  `minutes` int(11) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY(`employee_id`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;