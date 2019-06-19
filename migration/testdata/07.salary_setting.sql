DELETE FROM `power` WHERE power_controller LIKE 'salary%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('136', '員工薪資設定', 'salary/employees/index', '5', '0', '1'),
('137', '員工薪資編輯頁面', 'salary/employees/edit', '5', '0', '0'),
('138', '員工薪資修改', 'salary/employees/update', '5', '0', '0'),
('139', '薪資報表', 'salary/report/index', '5', '0', '1'),
('140', '薪資報表批次內容清單', 'salary/report/batch', '5', '0', '0'),
('141', '薪資員工查看', 'salary/report/employee', '5', '0', '0'),
('142', '薪資設定更新', 'salary/report/update', '5', '0', '0'),
('143', '薪資寄信', 'salary/report/email', '5', '0', '0'),
('144', '單一員工薪資寄信', 'salary/report/emailsingle', '5', '0', '0'),
('145', '薪資匯出', 'salary/report/export', '5', '0', '0'),
('146', '產生薪資報表頁面', 'salary/report/new', '5', '0', '0'),
('147', '產生薪資報表', 'salary/report/create', '5', '0', '0')
;
