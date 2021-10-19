<?php
// Site
$_['site_url']           = HTTP_SERVER;
$_['site_ssl']           = HTTPS_SERVER;

// Url
$_['url_autostart']      = false;

// Database
$_['db_autostart']       = true;
$_['db_engine']          = DB_DRIVER; // mpdo, mysqli or pgsql
$_['db_hostname']        = DB_HOSTNAME;
$_['db_username']        = DB_USERNAME;
$_['db_password']        = DB_PASSWORD;
$_['db_database']        = DB_DATABASE;
$_['db_port']            = DB_PORT;

// Session
$_['session_autostart']  = false;
$_['session_engine']     = 'db';
$_['session_name']       = 'OCSESSID';

// Template
$_['template_engine']    = 'twig';
$_['template_directory'] = '';
$_['template_cache']     = true;

// Autoload Libraries
$_['library_autoload']   = array();

// Actions
$_['action_pre_action']  = array(
	'startup/session',
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/maintenance',
	'startup/seo_url'
);

// Action Events
$_['action_event'] = array(
	'controller/*/before' => array(
		'event/language/before'
	),
	'controller/*/after' => array(
		'event/language/after'
	),	
	'view/*/before' => array(
		500  => 'event/theme',
		998  => 'event/language',
	),
	'language/*/after' => array(
		'event/translation'
	),
	//'view/*/before' => array(
	//	1000  => 'event/debug/before'
	//),
	//'controller/*/after'  => array(
	//	'event/debug/after'
//	)
);

//Customer group
$_['customer_group']            = array(
	'dropship' => 1,
	'vendor' => 2
);

//Giao hang nhanh shipping
$_['ghn_shipping_api'] = array(
	'shop_id' => 81552,
	'develop' => array(
		'url' 	=> 'https://dev-online-gateway.ghn.vn/shiip/public-api',
		'token' => '615c1360-ec80-11eb-9388-d6e0030cbbb7'
	),
	'live' => array(
		'url' 	=> 'https://online-gateway.ghn.vn/shiip/public-api',
		'token' => '73d882a6-ec7a-11eb-9389-f656af98cb33'
	),
	'service_type' => array(
		'', 'express', 'standard', 'saving'

	)
);