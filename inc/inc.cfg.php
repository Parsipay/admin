<?php
define('biiq_PATH',         dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('UNITY_PATH',        biiq_PATH.'unity'.DIRECTORY_SEPARATOR);
define('LIBRARY_PATH',      biiq_PATH.'libs'.DIRECTORY_SEPARATOR);
define('HANDLERS_PATH',     biiq_PATH.'hndl'.DIRECTORY_SEPARATOR);
define('VIEWS_PATH',        biiq_PATH.'views'.DIRECTORY_SEPARATOR);
define('INC_PATH',          biiq_PATH.'inc'.DIRECTORY_SEPARATOR);
define('LOGS_DIR',          biiq_PATH);
define('LOGS_FILE',         biiq_PATH.'php_error.log');
ini_set("error_log",        LOGS_FILE);

// public path
define('PROTOCOL', 'http');

$domain =  'localhost';
$allowed_domains = ['localhost'];
$port = '';
if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80){
    $port = ':'.$_SERVER['SERVER_PORT'];
}
//error_log(print_r($_SERVER, true));
if(isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], $allowed_domains)){
    $domain = $_SERVER['SERVER_NAME'];
}
define('DOMAIN', $domain);
define('SUBDOMAIN', '');
define('SITE', PROTOCOL.'://'.SUBDOMAIN.DOMAIN.$port.'/');
define('ASSETS_PATH', SITE.'assets/');
define('Maintenance', false);
define('APPLICATION_VERSION', '0.0.1');
// Zang bezan az daftar
?>