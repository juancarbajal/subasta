<?php
  error_reporting(E_ERROR) ;
//error_reporting(E_ALL) ;
//ini_set("session.use_trans_s id","0");
//ini_set("session.use_only_cookies","1");

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

//para trabajar localmente
require 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
$inifile = APPLICATION_PATH . "/configs/private.ini";
$enviroment = 'development';
if (is_readable($inifile)) {
    $config = new Zend_Config_Ini($inifile);
    $enviroment = (!empty($config->env))?$config->env:$enviroment;
}   

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $enviroment));
//echo getenv('APPLICATION_ENV');
// Ensure library is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path()
)));
/** Zend_Application */
require_once 'Zend/Application.php';
// Create application, bootstrap, and run
try {

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
} catch (Exception $e){
  echo $e->getMessage();
}

$application->bootstrap()
            ->run();