DELETE FROM `power` WHERE power_controller LIKE 'document%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('80', '公文列表', 'document/index', '6', '0', '1'),
('81', '公文新增頁面', 'document/new', '6', '0', '0'),
('82', '公文新增', 'document/create', '6', '0', '0'),
('83', '公文編輯頁面', 'document/edit', '6', '0', '0'),
('84', '公文更新', 'document/update', '6', '0', '0'),
('85', '公文刪除', 'document/delete', '6', '0', '0'),
('86', '公文下載', 'document/download', '6', '0', '0')
;





