ALTER TABLE `salary_report`
ADD COLUMN `memo` text AFTER pension;

ALTER TABLE `salary_report`
ADD COLUMN `other_plus` int(10) AFTER memo;

ALTER TABLE `salary_report`
ADD COLUMN `other_minus` int(10) AFTER other_plus;
