DELETE FROM `power` WHERE power_controller LIKE 'author%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('90', '作家列表', 'author/index', '4', '0', '1'),
('91', '作家新增頁面', 'author/new', '4', '0', '0'),
('92', '作家新增', 'author/create', '4', '0', '0'),
('93', '作家編輯頁面', 'author/edit', '4', '0', '0'),
('94', '作家更新', 'author/update', '4', '0', '0'),
('95', '作家刪除', 'author/delete', '4', '0', '0'),
('96', '作家詳細頁面', 'author/view', '4', '0', '0')
;





