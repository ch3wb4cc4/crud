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
$GLOBALS['url'] = '';
$GLOBALS['url_current'] = '';


// environment
switch($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']){
                    
	default: // localhost
        $GLOBALS['error_reporting'] = true;
		$GLOBALS['host'] = '';
        $GLOBALS['user'] = '';
        $GLOBALS['pass'] = '';
        $GLOBALS['database'] = '';
        $GLOBALS['url_root'] = '';
        $GLOBALS['url_dataprotection'] = $GLOBALS['url_root'].'#';
        $GLOBALS['url_imprint'] = $GLOBALS['url_root'].'#';
        $GLOBALS['url_contact'] = $GLOBALS['url_root'].'#';
        $GLOBALS['system_email'] = 'email@domain.de';
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
$GLOBALS['t_users'] = '000_users';
$GLOBALS['t_permissions'] = '000_permissions';




