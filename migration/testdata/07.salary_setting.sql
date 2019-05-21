DELETE FROM `power` WHERE power_controller LIKE 'salary%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('70', '員工薪資設定', 'salary/employees/index', '5', '0', '1'),
('71', '員工薪資編輯頁面', 'salary/employees/edit', '5', '0', '0'),
('72', '員工薪資修改', 'salary/employees/update', '5', '0', '0'),
('80', '薪資報表', 'salary/report/index', '5', '0', '1'),
('81', '薪資報表批次內容清單', 'salary/report/batch', '5', '0', '0'),
('82', '薪資員工查看', 'salary/report/employee', '5', '0', '0'),
('83', '薪資設定更新', 'salary/report/update', '5', '0', '0'),
('84', '薪資寄信', 'salary/report/email', '5', '0', '0'),
('85', '薪資匯出', 'salary/report/export', '5', '0', '0')
;
