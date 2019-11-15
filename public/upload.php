<?php
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/models'),
    get_include_path()
)));
require_once 'Zend/Application.php';
require_once 'Zend/Session.php';
require_once 'Zend/Auth.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
        	session_start();
        $auth = Zend_Auth::getInstance();
        $imd = $application->bootstrap()->getOption('fileshare');//paso el array de la configuracion de las imagenes

        if(isset($_POST)) {
        $imageManagement = new Devnet_ImageManagement($imd['host'],
                                                     $imd['username'],
                                                     $imd['password'],
                                                     $imd['thumbs'],
                                                     $imd['thumbnails'],
                                                     $imd['image'],
                                                     $imd['original'],
                                                     $imd['img']);//instancio la clase de administracion de imagenes en otro servidor de archivos
        $imageManagement->openFtp();

   if (is_uploaded_file ($_FILES["Filedata"]['tmp_name'])) {
   $extension = explode('.', strtolower($_FILES['Filedata']['name']));
	            $num = count($extension) - 1;
	            if (!($extension[$num] == 'jpg' || $extension[$num] == 'gif')) {
                    $error_imagen = '-1';//Archivo invalido;
                } elseif($_FILES['Filedata']['size'] > 1048576) {
                    $error_imagen = '0';//Archivo supero el tama&ntilde;o requerido(1MB)
                } else {
                    $nombreFichero = time() .
                                     '.' .
                                     $extension[$num];
                }
                if ($error_imagen  == '') {
                    $error_imagen =1;
					$local = $_FILES['Filedata']['name'];
					$remoto = $_FILES['Filedata']['tmp_name'];
			                $ruta = '/home/apkotear/public/original/'
                                    . $_POST['SESSID']//'6'//$iduser->ID_USR
                                    . '/'
                                    . $nombreFichero;
                            $imageManagement->upImage($ruta, $remoto);
                            //fopen($imd['url'] .'/TransformImage.php?nomfichero=' . $nombreFichero . '&fileuser='
                          //. $_POST['SESSID'],'r');
            }
        }
    }
   if($nombreFichero != ''){
   echo  "ID:$nombreFichero,URL:". $imd['url'] . '/'.$imd['original'] . '/' . $_POST['SESSID'] . "/$nombreFichero,STATUS:1";
   } else {
   echo    "STATUS:$error_imagen";
   }

   exit(0);
?>