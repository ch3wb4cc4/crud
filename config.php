<?php

// config =========================================


// declarations
$GLOBALS['error_reporting'] = false;
$GLOBALS['data'] = '';
$GLOBALS['console'] = '';
$GLOBALS['output'] = '';
$GLOBALS['language'] = 'de';
$GLOBALS['security_check_limit'] = 32;
$GLOBALS['security_check'] = false;
$GLOBALS['stringtable'] = array();
$GLOBALS['module'] = '';
$GLOBALS['tmp'] = '';
$GLOBALS['url'] = '';
$GLOBALS['url_current'] = '';
$GLOBALS['template'] = '';


// environment
switch($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']){

	case "domain.de/index.php":
		$GLOBALS['error_reporting'] = true;
		$GLOBALS['host'] = '';
		$GLOBALS['user'] = '';
		$GLOBALS['pass'] = '';
		$GLOBALS['database'] = '';
		$GLOBALS['tld'] = 'https://domain.de/';
		$GLOBALS['url_dataprotection'] = $GLOBALS['tld'].'#';
		$GLOBALS['url_imprint'] = $GLOBALS['tld'].'#';
		$GLOBALS['url_contact'] = $GLOBALS['tld'].'#';
		$GLOBALS['template_path'] = 'assets/templates/default/';
		break;

	default: // localhost
		$GLOBALS['error_reporting'] = true;
		$GLOBALS['host'] = 'localhost';
		$GLOBALS['user'] = 'root';
		$GLOBALS['pass'] = 'empty';
		$GLOBALS['database'] = 'app';
		$GLOBALS['tld'] = 'http://localhost/app';
		$GLOBALS['url_dataprotection'] = $GLOBALS['tld'].'#';
		$GLOBALS['url_imprint'] = $GLOBALS['tld'].'#';
		$GLOBALS['url_contact'] = $GLOBALS['tld'].'#';
		$GLOBALS['template_path'] = 'assets/templates/default/';
		break;

}


// development
if($GLOBALS['error_reporting'] == false){
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}else{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}


// tables
$GLOBALS['t_security_log'] = '000_security_log';




