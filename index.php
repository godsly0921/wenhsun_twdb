<?php

date_default_timezone_set('Asia/Taipei');

require __DIR__ . '/vendor/autoload.php';

ini_set('mbstring.internal_encoding','UTF-8');
//避免中文亂碼
header('Content-Type: text/html; charset=utf-8');
//載入Yii Framework
$yii=dirname(__FILE__).'/framework/yii.php';
//載入上傳圖片檔案路徑
$image=dirname(__FILE__).'/assets/uploads/';
$root_dir=dirname(__FILE__);
define ('ROOT_DIR',$root_dir);
define ('ROOT_HTTP','http://203.69.216.186/wenhsun_hr');
define ('IMAGES_STORAGE_DIR',$image);
define ('IMAGES_SHOW_DIR','/assets/uploads/');
define ('mysql_bak',dirname(__FILE__).'/assets/site/mysql_bak/');
define('API_DOMAIN','https://api.twdb.com.tw');
define('API_DOWNLOAD_PATH','/download/image/');
//載入上傳圖片檔案路徑
$file=dirname(__FILE__).'/assets/uploads/file/';
define ('FILE_STORAGE_DIR',$file);
define ('FILE_DOWNLOAD_DIR',$file);
define ('FILE_SHOW_DIR','/assets/uploads/file/');
$photograph_storage=dirname(__FILE__).'/image_storage/';
define ('PHOTOGRAPH_STORAGE_DIR',$photograph_storage);
define ('DATA_PATH', dirname(__FILE__). "/data/");
define('WATERMARK', dirname(__FILE__).'/assets/image/watermark.png');
define('HOMEBANNER', dirname(__FILE__).'/assets/image/banner/');
define('HOMEBANNER_SHOW', '/assets/image/banner/');
define('PICCOLUMN', dirname(__FILE__).'/assets/image/piccolumn/');
define('PICCOLUMN_SHOW', '/assets/image/piccolumn/');
define('HOMEAD', dirname(__FILE__).'/assets/AD/');
define('PHOTOGRAPH_STORAGE_URL', 'image_storage/O/');
define('M3U8_STORAGE_URL', 'image_storage/video/');
define('DOMAIN', 'https://www.twdb.com.tw/');
//define('DOMAIN', 'http://localhost:8080/wenhsun_hr/'); //測試環境
define('ABOUT_IMAGE', '/assets/image/about/');
define('ABOUT_IMAGE_UPLOAD', dirname(__FILE__) . '/assets/image/about/');
define('NEWS_IMAGE', '/assets/image/news/');
define('NEWS_IMAGE_UPLOAD', dirname(__FILE__) . '/assets/image/news/');
//載入系統設定檔
$config=dirname(__FILE__).'/protected/config/main.php';
//Yii Debug models開啟
defined('YII_DEBUG') or define('YII_DEBUG',true);
//Yii Debug models等級
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
//編輯工具設定路徑
define('CK_PATH','/gjftamc/assets/');
define('_PATH','/gjftamc/assets/');
define('MONGO','mongodb://localhost:27017');
define("GOOGLE_CLINT_ID", "425795626455-j4g8m5n8aenev94agt8bh4j11dv0sg1h.apps.googleusercontent.com");
define("GOOGLE_CLINT_SECRET", "fglkDb5yLTSVwcl8QzVLbe_F");

define("FB_APP_ID", "411928326084757");
define("FB_APP_SECRET", "ebd2032b93bb163fbdc4f6d4f42a9a54");
define("FB_GRAPH_VERSION", "v2.2");

define("AnnualLeaveFiscalYearClose", "04");
require_once("code.inc.php");
require_once($yii);
Yii::createWebApplication($config)->run();