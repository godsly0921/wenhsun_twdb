DELETE FROM `power` WHERE power_controller LIKE 'leave%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('200', '休假管理', 'leave/employee/index', '7', '0', '1'),
('201', '人資休假管理', 'leave/manager/index', '7', '0', '1'),
('202', '檢視員工休假歷程', 'leave/manager/hist', '7', '0', '0'),
('203', '刪除員工休假紀錄', 'leave/manager/delete', '7', '0', '0'),
('204', '新增員工休假紀錄', 'leave/manager/create', '7', '0', '0'),
('205', '新增員工休假紀錄表單', 'leave/manager/new', '7', '0', '0'),
('206', '編輯員工休假紀錄表單', 'leave/manager/edit', '7', '0', '0'),
('207', '更新員工休假紀錄表單', 'leave/manager/update', '7', '0', '0')
;
