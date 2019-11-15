<?php
try{
/*
        $app='/home/webmaster/kotear/capturar_svn.sh';
        echo 'Cambiando de directorio'."<br>";
        chdir('/home/webmaster/kotear');
        echo 'Removiendo versi&oacute;n anterior'."<br>";
        exec('rm trunk -R',$result);
        echo 'Extrayendo nueva versi&oacute;n'."<br>";
        exec('svn export svn://192.168.1.69/kotear/trunk ./trunk');
        echo 'Brindando permisos'."<br>";
        exec('chmod a+rw trunk/var -R');
        exec('chmod a+rw trunk/public/img/captchas -R');
        echo 'Se termino con satisfacci&oacute;n la actualizaci&oacute;n <br/> <a href="kotear/">Ir a la p&aacute;gina de Kotear</a>';
 */
        //$result = exec('/home/webmaster/kotear/capturar_svn.sh');
        chdir('/home/webmaster/kotear');
        exec('svn export svn://192.168.1.69/kotear/trunk /home/webmaster/kotear/trunk');
        exec('chmod a+rw /home/webmaster/kotear/trunk/var -R');
        exec('chmod a+rw /home/webmaster/kotear/trunk/public/img/captchas -R');
        //print_r($result);
//      $resString=exec('svn export svn://192.168.1.69/kotear/trunk ; chmod a+rw trunk/var ; chmod a+rw trunk/public/img/captchas -R',$result);
//        print_r($result);
//    } else echo 'Aplicacion no existe';
} catch (Exception $e){
    echo $e->getMessage();
}
?>
