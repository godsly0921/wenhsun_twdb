CREATE TABLE `leave_apply` (
  `application_id` varchar(32) NOT NULL,
  `employee_id` varchar(32) NOT NULL,
  `leave_type` varchar(32) NOT NULL,
  `leave_status` varchar(10) NOT NULL,
  `leave_start_date` date NOT NULL,
  `leave_end_date` date NOT NULL,
  `leave_minutes` int(11) NOT NULL,
  `leave_memo` text NOT NULL,
  `leave_file_location` varchar(256),
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `leave_apply_hist` (
  `application_hist_id` varchar(32) NOT NULL,
  `act_employee_id` varchar(32) NOT NULL,
  `leave_status` varchar(10) NOT NULL,
  `create_at` datetime NOT NULL,
PRIMARY KEY (`application_hist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;