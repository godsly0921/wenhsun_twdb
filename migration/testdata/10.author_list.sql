DELETE FROM `power` WHERE power_controller LIKE 'author%';

INSERT INTO `power` (power_number, power_name, power_controller, power_master_number, power_range, power_display)
VALUES
('90', '作家列表', 'author/index', '4', '0', '1'),
('91', '作家新增頁面', 'author/new', '4', '0', '0'),
('92', '作家新增', 'author/create', '4', '0', '0'),
('93', '作家編輯頁面', 'author/edit', '4', '0', '0'),
('94', '作家更新', 'author/update', '4', '0', '0'),
('95', '作家刪除', 'author/delete', '4', '0', '0'),
('96', '作家詳細頁面', 'author/view', '4', '0', '0'),
('254', '作家欄位筆名', 'author/pen_name', '4', '0', '0'),
('255', '作家欄位作家姓名', 'author/author_name', '4', '0', '0'),
('256', '作家欄位出生年', 'author/birth_year', '4', '0', '0'),
('257', '作家欄位服務單位', 'author/service', '4', '0', '0'),
('258', '作家欄位職稱', 'author/job_title', '4', '0', '0'),
('259', '作家欄位住家/郵遞區號/地址', 'author/address', '4', '0', '0'),
('260', '作家欄位身分類型', 'author/identity_type', '4', '0', '0'),
('261', '作家欄位備註', 'author/memo', '4', '0', '0'),
('261', '作家匯出', 'author/export', '4', '0', '0')
;





