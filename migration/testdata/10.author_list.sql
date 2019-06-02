DELETE FROM `power` WHERE power_controller LIKE 'author%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('154', '作家欄位筆名', 'author/pen_name', '4', '0', '0'),
('155', '作家欄位作家姓名', 'author/author_name', '4', '0', '0'),
('156', '作家欄位出生年', 'author/birth_year', '4', '0', '0'),
('157', '作家欄位服務單位', 'author/service', '4', '0', '0'),
('158', '作家欄位職稱', 'author/job_title', '4', '0', '0'),
('159', '作家欄位住家/郵遞區號/地址', 'author/address', '4', '0', '0'),
('160', '作家欄位身分類型', 'author/identity_type', '4', '0', '0')
('161', '作家欄位備註', 'author/memo', '4', '0', '0')
;





