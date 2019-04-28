SET NAMES utf8 ;
DROP TABLE IF EXISTS `power`;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `power` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `power_number` int(4) NOT NULL COMMENT '功能編號',
  `power_name` varchar(50) NOT NULL COMMENT '功能名稱',
  `power_controller` varchar(100) DEFAULT NULL COMMENT 'Controller位置',
  `power_master_number` int(4) NOT NULL COMMENT '系統編號',
  `power_range` int(3) NOT NULL COMMENT '排序',
  `power_display` int(1) NOT NULL DEFAULT '1' COMMENT '是否於前台顯示1為是0為否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8 COMMENT='權限功能';

LOCK TABLES `power` WRITE;
INSERT INTO `power` VALUES (36,33,'功能列表','power/index',1,0,1),(37,34,'功能新增','power/create',1,1,0),(40,120,'角色列表','group/index',1,4,1),(41,13,'角色新增','group/create',1,5,0),(42,14,'角色修改','group/update',1,6,0),(43,15,'角色刪除','group/delete',1,7,0),(44,16,'系統管理帳號列表','account/index',1,8,1),(45,17,'系統管理帳號新增','account/create',1,9,0),(46,19,'系統管理帳號修改','account/update',1,10,0),(47,20,'系統管理帳號刪除','account/delete',1,11,0),(48,21,'系統列表','system/index',1,12,1),(49,22,'系統新增','system/create',1,13,0),(50,23,'系統修改','system/update',1,14,0),(116,24,'系統刪除','system/delete',1,15,0),(118,118,'功能修改','power/update',1,2,0),(119,119,'功能刪除','power/delete',1,3,0),(120,27,'員工列表','employee/management',2,0,1),(126,32,'公布欄消息列表','news/index',3,0,1),(127,35,'公布欄消息更新','news/update',3,1,0),(128,36,'公布欄消息刪除','news/delete',3,3,0),(131,38,'分機分配表','employee/extensions',2,0,1),(140,47,'作家列表','author',4,0,1),(141,48,'特殊狀況管理新增','specialcase/create',4,0,0),(142,49,'特殊狀況管理刪除','specialcase/delete',4,0,0),(143,50,'特殊狀況管理修改','specialcase/update',4,0,0),(152,59,'員工新增','employee/create',2,0,0),(153,60,'員工資料修改','employee/update',2,0,0),(154,61,'員工刪除','employee/delete',2,0,0),(156,63,'員工座位列表','employee/seats',2,0,1),(159,66,'薪資列表','calculationfee/index',5,0,1),(174,81,'消息公布','news/list',3,4,1),(187,94,'出勤記錄明細表','doorrec/report',2,5,1),(189,96,'每日出缺勤一覽表','attendancerecord/report',2,6,1),(190,97,'出勤日設定列表','attendance/list',2,0,1),(191,98,'出勤異常列表','record/get_abnormal_list',2,0,0),(202,109,'公文列表','door/list',6,0,1),(203,110,'門禁計費方式設定修改','door/update',6,0,0),(204,111,'門禁計費方式設定刪除','door/delete',6,0,0),(205,112,'門禁計費方式設定新增','door/create',6,0,0),(211,121,'公布欄消息新增','news/create',3,0,0),(219,128,'門禁時段設定修改','doorpermission/time_group_update',2,7,0),(228,129,'出勤日新增','attendance/create',2,0,0),(229,130,'出勤日修改','attendance/update',2,0,0);
UNLOCK TABLES;


DROP TABLE IF EXISTS `system`;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `system` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `system_number` int(4) NOT NULL COMMENT '系統編號',
  `system_name` varchar(50) NOT NULL COMMENT '系統名稱',
  `system_controller` varchar(100) DEFAULT NULL COMMENT '系統Controller',
  `system_type` int(1) NOT NULL COMMENT '系統狀態',
  `system_range` int(3) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='權限功能';

LOCK TABLES `system` WRITE;
INSERT INTO `system` VALUES (1,1,'權限管理模組',NULL,1,1),(2,2,'員工管理系統',NULL,1,2),(3,3,'即時公告通知','',1,3),(4,4,'作家資料管理系統','author',1,4),(5,5,'薪資作業管理',NULL,1,5),(6,6,'公文收發管理',NULL,1,6);
UNLOCK TABLES;
