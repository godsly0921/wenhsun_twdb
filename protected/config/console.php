<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.ext.*',
		'application.components.*',
        'application.service.*',
        'application.utils.*',
		'application.extensions.sftp.*',
		'application.extensions.phpexcel.*',
	),

	// application components
	'components'=>array(
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=wenhsun',
			'emulatePrepare' => true,
			'username' => 'admin',
			'password' => 'Cute0921',
			'charset' => 'utf8',
		),
		'db2'=>array(
			'class'=>'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=wenhsun',
			'emulatePrepare' => true,
			'username' => 'admin',
			'password' => 'Cute0921',
			'charset' => 'BIG5',
		),		
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);