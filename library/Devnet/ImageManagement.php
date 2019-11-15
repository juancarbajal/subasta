<?php
/**
 * @author njara
 *
 */
class Devnet_ImageManagement
{
    /**
     * @var string
     */
    protected $_host;
    /**
     * @var string
     */
    protected $_username;
    /**
     * @var string
     */
    protected $_password;
    /**
     * @var string
     */
    protected $_cidftp;
    
    /**
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct ($host, $username, $password)
    {
        $this->_host = $host; //host del servidor ftp
        $this->_username = $username; // usuario ftp
        $this->_password = $password; //passwrod ftp
    }
    
    /**
     * @return string
     */
    public function openFtp ()
    {
        $this->_cidftp = ftp_connect($this->_host); // Luego creamos un login al mismo con nuestro usuario y contraseña
        $resultado = ftp_login($this->_cidftp, $this->_username, $this->_password);
        $result = ! ((! $this->_cidftp) || (! $resultado));
        /*
        if ((! $this->_cidftp) || (! $resultado)) {
            $result = "Fallo en la conexión";
            die();
        } else {
            $result = "Conectado.";
        }*/
        ftp_pasv($this->_cidftp, true);
        return $result;
    }
    
    /**
     * Cierra la conexión FTP
     * @return void
     */
    public function closeFtp ()
    {
        if (isset($this->_cidftp))
        ftp_close($this->_cidftp);
        else return false;
    }
    
    /**
     * @param unknown_type $ruta
     * @param unknown_type $file
     */
    public function upImage ($ruta, $file)
    {
        if (ftp_put($this->_cidftp, $ruta, $file, FTP_BINARY)) {
            ftp_chmod($this->_cidftp, 0777, $ruta);
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }
    
    /**
     * @param unknown_type $arrayRuta
     * @param unknown_type $nomdir
     */
    function newDirectory ($arrayRuta, $nomdir)
    {
        foreach ($arrayRuta as $ruta) :
            ftp_chdir($this->_cidftp, "public");
            ftp_chdir($this->_cidftp, $ruta);
            if (! @ftp_chdir($this->_cidftp, $nomdir)) {
                @ftp_mkdir($this->_cidftp, $nomdir);
                @ftp_chmod($this->_cidftp, 0777, $nomdir);
            }
            ftp_chdir($this->_cidftp, "../../");
        endforeach;
    }

    function getPermisos($ruta)
    {
        @ftp_chmod($this->_cidftp, 0777, $ruta);
    }
    
    /**
     * @param unknown_type $ruta
     * @return string|string
     */
    function delete ($ruta)
    {
        if (isset($this->_cidftp))
    	return ftp_delete($this->_cidftp, $ruta);/*
        if (ftp_delete($this->_cidftp, $ruta)) {
            return "se elimino el archivo";
        } else {
            return "no se elimino el archivo";
        }*/
        else return false;
    }
    
    function rename ($ruta, $rutaNueva )
    {
        if (isset($this->_cidftp))
    	return ftp_rename($this->_cidftp, $ruta, $rutaNueva);
//        if (ftp_rename($this->_cidftp, $ruta, $nuevaRuta)
//            echo "se ha renombrado $ruta a $nuevaRuta con &eacute;xito\n";
//        } else {
//            echo "Hubo un problema al renombrar $ruta a $nuevaRuta";
//        }
        else return false;
    }
}