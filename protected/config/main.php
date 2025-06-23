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
    'defaultController' => 'Site',
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
				'v1/service/getimage'=>'api/getimage',//API優先
				'v1/service/getimagedetail'=>'api/getimagedetail',//API優
				'v1/service/reverifytoken'=>'api/reverifytoken',//API優
				'v1/service/getimageauthorization'=>'api/getimageauthorization',//API優
				'v1/service/getdownload'=>'api/getdownload',//API優
				'v1/service/getrequestrecordbyimage'=>'api/getrequestrecordbyimage',//API優
                'v1/service/getrequestrecordbydownload'=>'api/getrequestrecordbydownload',//API優
                'v1/service/getrequestrecordbyimagedetail'=>'api/getrequestrecordbyimagedetail',//API優
                'v1/service/getAuthor'=>'api/getAuthor',//API優
                'v1/service/getAuthorDetail'=>'api/getAuthorDetail',//API優
                'site/ImageInfo/<id:\d+>/<search_type:\d+>'=>'site/ImageInfo',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<keyword>/<page:\d+>/<search_type:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				//'v1/service/getimage'=>'api/getimage',
			],
		),


		'db'=>array(
			'connectionString' => sprintf('mysql:host=%s;port=%d;dbname=%s', getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_DATABASE')),
			'emulatePrepare' => true,
			'username' => getenv('DB_USERNAME'),
			'password' => getenv('DB_PASSWORD'),
			'charset' => 'utf8',
		),
		'db_official'=>array(
			'connectionString' => 'mysql:host=192.168.0.202;dbname=wenhsun_official',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'Cute0921)(@!',
			'charset' => 'utf8',
			// 'autoConnect' => false,
        	'class' => 'CDbConnection'
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
