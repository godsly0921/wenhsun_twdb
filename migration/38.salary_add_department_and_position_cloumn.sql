ALTER TABLE `salary_report`
ADD COLUMN `employee_department` varchar(128) AFTER employee_name;

ALTER TABLE `salary_report`
ADD COLUMN `employee_position` varchar(128) AFTER employee_department;
