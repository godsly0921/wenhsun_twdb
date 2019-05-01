CREATE TABLE `document_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `document_type` (name, create_at, update_at)
VALUES
('內部公文', SYSDATE(), SYSDATE()),
('外部公文', SYSDATE(), SYSDATE()),
('政府公文', SYSDATE(), SYSDATE());

CREATE TABLE `document` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `receiver` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `send_text_number` varchar(64) NOT NULL,
  `send_text_date` datetime NOT NULL,
  `document_type` int(11) unsigned NOT NULL,
  `document_file` varchar(256) NOT NULL,
  `case_officer` varchar(64),
  `create_at` datetime NOT NULL,
  `update_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

