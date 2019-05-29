CREATE TABLE `salary_report_batch` (
  `batch_id` varchar(20) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`batch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `salary_report` (
  `id` varchar(32) NOT NULL,
  `batch_id` varchar(20) NOT NULL,
  `employee_id` varchar(12) NOT NULL,
  `employee_login_id` varchar(64) NOT NULL,
  `employee_name` varchar(32) NOT NULL,
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
