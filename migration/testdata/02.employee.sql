DELETE FROM `power` WHERE power_controller LIKE 'employee%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('70', '員工列表', 'employee/management/index', '2', '0', '1'),
('71', '員工新增頁面', 'employee/management/new', '2', '0', '0'),
('72', '員工新增', 'employee/management/create', '2', '0', '0'),
('73', '員工編輯頁面', 'employee/management/edit', '2', '0', '0'),
('74', '員工更新', 'employee/management/update', '2', '0', '0'),
('75', '員工刪除', 'employee/management/delete', '2', '0', '0'),
('76', '員工更新密碼', 'employee/management/updatepassword', '2', '0', '0'),
('77', '分機列表', 'employee/extensions/index', '2', '0', '1'),
('77', '分機新增頁面', 'employee/extensions/new', '2', '0', '0'),
('77', '分機新增', 'employee/extensions/create', '2', '0', '0'),
('77', '分機編輯頁面', 'employee/extensions/edit', '2', '0', '0'),
('77', '分機更新', 'employee/extensions/update', '2', '0', '0'),
('77', '分機刪除', 'employee/extensions/delete', '2', '0', '0'),
('77', '座位列表', 'employee/seats/index', '2', '0', '1'),
('77', '座位列表', 'employee/seats/new', '2', '0', '0'),
('77', '座位列表', 'employee/seats/create', '2', '0', '0'),
('77', '座位編輯', 'employee/seats/edit', '2', '0', '0'),
('77', '座位更新', 'employee/seats/update', '2', '0', '0'),
('77', '座位刪除', 'employee/seats/delete', '2', '0', '0')
;





