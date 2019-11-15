<?php 
set_include_path(implode(PATH_SEPARATOR, 
        			     array('/home/jcarbajal/Sistemas/kotear/library',
        				 '/home/jcarbajal/Sistemas/kotear/application/models',
        				 get_include_path())));
require_once 'library/Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

?>