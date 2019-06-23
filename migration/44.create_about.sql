CREATE TABLE `about` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(45) COLLATE utf8_unicode_ci NOT NULL COMMENT '項目',
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '項目說明',
  `image` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '圖片',
  `paragraph` text COLLATE utf8_unicode_ci NOT NULL COMMENT '內文',
  `update_time` datetime NOT NULL,
  `update_account_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `about` (`id`,`title`,`description`,`image`,`paragraph`,`update_time`,`update_account_id`) VALUES (1,'banner','關於我們 banner 圖片 (無內文)','/assets/image/about/about_banner.jpg','','2019-06-23 18:44:16',1);
INSERT INTO `about` (`id`,`title`,`description`,`image`,`paragraph`,`update_time`,`update_account_id`) VALUES (2,'header','關於我們標題','','《文訊》創社緣起123','2019-06-23 18:03:34',1);
INSERT INTO `about` (`id`,`title`,`description`,`image`,`paragraph`,`update_time`,`update_account_id`) VALUES (3,'paragraph1','第一段 (僅內文)','','1983年7月1日，《文訊》由國民黨中央文化工作會創辦。初期的目的在為文藝作家服務，蒐集、整理文學史料，為文學歷史奠基，幾年內就做出了一些成績，頗受文藝界、學界的稱讚。但《文訊》不以此為滿足，每期藉專題企畫的方式，探討不同階段的文學發展，將各個階段的作家作品、學術思想記錄下來，肯定前輩作家的文學表現，也重視文壇新秀的努力創新。\r\n《文訊》不僅致力於文學史料的蒐集、整理及研究，並試圖呈現完整的藝文與出版資訊，報導作家創作與活動。既重視城市文學的繁華典雅，亦從不忽略地方文學 的純樸動人。發行二十多年來，重點始終放在現當代台灣文學整理及研究上，成績粲然可觀，誠為研究當代台灣文學必讀之文學刊物。由於長期的用心經營，我們 獲得文藝界及學界普遍的肯定，已經成為台灣現代文學的資料庫，可說是台灣文學發展的檢驗指標。','2019-06-23 18:40:30',1);
INSERT INTO `about` (`id`,`title`,`description`,`image`,`paragraph`,`update_time`,`update_account_id`) VALUES (4,'paragraph2','第二段','/assets/image/about/image1.jpg','《文訊》所附設的「文藝資料研究及服務中心」自始便立下以「文學史料蒐藏」為核心，進行研究、推廣與服務的使命。三十多年來，搭配每一期《文訊》的專題與專欄規劃，同時以專案式的、系統化的方式蒐集文學史料，目前已累積中文文藝圖書十萬餘冊、已停刊或正在發行之文學雜誌，計七百餘種，約六萬冊、作家及文藝活動照片近四萬餘張、作家手稿六千多份、累積近三十年報紙副刊、文化版、讀書版、上百種文學專題資料卷宗、十萬餘筆作家評論資料、八千餘筆作家學者媒體人的通訊錄，而且這些資料還在持續成長中。','0000-00-00 00:00:00',0);
INSERT INTO `about` (`id`,`title`,`description`,`image`,`paragraph`,`update_time`,`update_account_id`) VALUES (5,'paragraph3','第三段','/assets/image/about/image2.jpg','「台灣文學照片資料庫」即以文訊擁有之龐大作家照片為基底，將一切珍貴台灣文學資源轉化成數位應用，創造出新的價值與意義，更有助於:\r\n\r\n(一) 為台灣文學發展過程的作家創作歷程留下真實的紀錄;\r\n(二) 充實台灣文學發展史中，文學出版社的價值與影響;\r\n(三) 有助於台灣文學社團、文學媒體、期刊的史料搜尋;\r\n(四) 因照片人物、地點等的出現，可以彌補某些斷裂的、失聯的文學歷史。\r\n(五) 本計畫受文化部推動國家文化記憶庫計畫補助建置，期望透過網站分享給社會大眾，並透過數位典藏的再利用，讓大眾得以透過文學影像，喚起對土地、文化、社會的情感，豐富台灣的人文記憶。','2019-06-23 18:21:27',1);

