CREATE TABLE `salary_report_batch` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(12) NOT NULL,
  `month` float NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `salary_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(12) NOT NULL,
  `salary` float NOT NULL,
  `draft_allowance` float NOT NULL,
  `traffic_allowance` float NOT NULL,
  `overtime_wage` float NOT NULL,
  `project_allowance` float NOT NULL,
  `taxable_salary_total` float NOT NULL,
  `tax_free_overtime_wage` float NOT NULL,
  `salary_total` float NOT NULL,
  `health_insurance` float NOT NULL,
  `labor_insurance` float NOT NULL,
  `pension` float NOT NULL,
  `deduction_total` float NOT NULL,
  `real_salary` float NOT NULL,
  `status` varchar(3) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
