DELETE FROM `power` WHERE power_controller LIKE 'leave%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('200', '休假管理', 'leave/index', '7', '0', '1'),
('201', '休假申請表單', 'leave/new', '7', '0', '1'),
('202', '休假申請命令', 'leave/apply', '7', '0', '0')
;
