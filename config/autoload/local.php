<?php
return array(
	'db' => array(
		'dsn' => 'mysql:dbname=naiche_wantu;host=10.0.0.222',
		'username' => 'naiche',
		'password' => 'naiche321',
	),
	
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions'       => true,
	),
	
	'phpSettings' => array(
		'display_startup_errors'        => true,
		'display_errors'                => true,
		'error_reporting'               => E_ALL & ~E_NOTICE,
		'max_execution_time'            => 30,
	    
	    'session.cookie_httponly'       => false,
	    'session.cookie_secure'         => false,
	    'expose_php'                    => true,
	),
    
	//Config Host's Api
	'config_host' => array(
		'env' => 'local',
	),
);