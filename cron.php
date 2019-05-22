<?php

$yii    = dirname(__FILE__).'/framework/yii.php';
$config = dirname(__FILE__).'/protected/config/console.php'; 

require_once($yii);
require dirname(__FILE__) . '/vendor/autoload.php';

Yii::createConsoleApplication( $config )->run();