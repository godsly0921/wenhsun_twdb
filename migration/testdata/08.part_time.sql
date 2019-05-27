DELETE FROM `power` WHERE power_controller LIKE 'salary%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('146', '兼職排班表', 'parttime/index', '2', '0', '1'),
('147', '兼職排班表API事件', 'parttime/getevents', '2', '0', '0'),
('148', '兼職排班表新增', 'parttime/detele', '2', '0', '0'),
('149', '兼職排班表刪除', 'parttime/create', '2', '0', '0'),
('150', '兼職排班表API刪除1', 'parttime/cancelparttimebycalendar', '2', '0', '0'),
('151', '兼職排班表API刪除2', 'parttime/cancelparttime', '2', '0', '0');
