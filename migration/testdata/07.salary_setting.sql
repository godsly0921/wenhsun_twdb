DELETE FROM `power` WHERE power_controller LIKE 'salary%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('70', '員工薪資設定', 'salary/employees/index', '5', '0', '1'),
('71', '員工薪資編輯頁面', 'salary/employees/edit', '5', '0', '0'),
('72', '員工薪資修改', 'salary/employees/update', '5', '0', '0')
;
