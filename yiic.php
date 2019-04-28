<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/framework/yiic.php';
$config=dirname(__FILE__).'/protected/config/console3.php';
require __DIR__ . '/vendor/autoload.php';

ini_set('mbstring.internal_encoding','UTF-8');
//避免中文亂碼
header('Content-Type: text/html; charset=utf-8');
//載入上傳圖片檔案路徑
$image=dirname(__FILE__).'/assets/uploads/';
define ('IMAGES_STORAGE_DIR',$image);
define ('IMAGES_SHOW_DIR','/assets/uploads/');
define ('mysql_bak',dirname(__FILE__).'/assets/site/mysql_bak/');


//載入上傳圖片檔案路徑
$file=dirname(__FILE__).'/assets/uploads/file/';
define ('FILE_STORAGE_DIR',$file);
define ('FILE_DOWNLOAD_DIR',$file);
define ('FILE_SHOW_DIR','/assets/uploads/file/');
$photograph_storage=dirname(__FILE__).'/image_storage/';
define ('PHOTOGRAPH_STORAGE_DIR',$photograph_storage);
define('WATERMARK', dirname(__FILE__).'/assets/image/watermark.png');
define('DOMAIN', 'http://localhost:8080/wenhsun_hr/');
//Yii Debug models開啟
defined('YII_DEBUG') or define('YII_DEBUG',true);
//Yii Debug models等級
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
//編輯工具設定路徑
define('CK_PATH','/gjftamc/assets/');
define('_PATH','/gjftamc/assets/');
define('MONGO','mongodb://localhost:27017');
require_once($yiic);
