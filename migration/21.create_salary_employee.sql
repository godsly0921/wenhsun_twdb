DROP TABLE `salary_employee`;

CREATE TABLE `salary_employee` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(12) NOT NULL,
  `salary` float NOT NULL,
  `health_insurance` float NOT NULL,
  `labor_insurance` float NOT NULL,
  `pension` float NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `salary_employee` ADD UNIQUE (employee_id);

