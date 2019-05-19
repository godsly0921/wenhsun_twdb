<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'',
    'timeZone' => 'Asia/Taipei',
	// preloading 'log' component
	'preload'=>array('log'),
    'defaultController' => 'Admin',
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.ext.*',
		'application.components.*',
        'application.service.*',
        'application.repo.*',
        'application.vo.*',
        'application.utils.*',
		'application.extensions.sftp.*',
		'application.extensions.phpexcel.*',
	),
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'0921',
			//'ipFilters'=>array(...a list of IPs...),
			'newFileMode'=>0666,
			'newDirMode'=>0777,
			'generatorPaths'=>array(
				'common.gii',   // a path alias
			),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		'sftp'=>array(
			'class'=>'SftpComponent',
			'host'=>'127.0.0.1',
			'port'=>22,
			'username'=>'root',
			'password'=>'isgoodtime1234!@#$',
		),

		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,//隱藏index.php
			'caseSensitive'=>false,
			
			'rules'=> [
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			],
		),


		'db'=>array(
			'connectionString' => 'mysql:host=db;dbname=wenhsun',
			'emulatePrepare' => true,
			'username' => 'admin',
			'password' => 'admin',
			'charset' => 'utf8',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, info, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'godsly0921@gmail.com',
	),
);
