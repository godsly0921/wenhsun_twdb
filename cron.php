<?php

date_default_timezone_set('Asia/Taipei');

$yii    = dirname(__FILE__).'/framework/yii.php';
$config = dirname(__FILE__).'/protected/config/console.php'; 
// Works as of PHP 7
// 需使用請假系統、計算特休的角色代碼
// 2 文訊主管  
// 5 文訊正職
// 26 文訊人事主管／會計
// 27 社長
// 33 文訊檢視作家銀行
// 40 紀州庵正職
// 44 紀州庵正職－行銷企劃
// 45 紀州庵正職－茶館
define('CHECKROLE', array( 2,5,26,27,33,40,44,45 ));
require_once($yii);
require dirname(__FILE__) . '/vendor/autoload.php';

Yii::createConsoleApplication( $config )->run();