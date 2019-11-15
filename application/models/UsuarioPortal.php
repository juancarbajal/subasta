<?php
/**
 * Descripción Corta
 *
 * Descripción Larga
 *
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer el archivo LICENSE
 * @version    1.0
 * @since      Archivo disponible desde su version 1.0
 */
require_once 'Base/UsuarioPortal.php';
/**
 * Descripción Corta
 * Descripción Larga
 * @category
 * @package
 * @subpackage
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 * @version    Release: @package_version@
 * @link
 */
class UsuarioPortal 
    extends Base_UsuarioPortal
{
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function insert ($data)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $data['suscripcionNews'] = ($data['suscripcionNews'] == 1) ? 1 : 0;            
            
            $result = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_USUARIO_INS ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                array($data['apodo'] ,
                    $data['clave'] ,
                    $data['nombre'] ,
                    $data['apellido'] ,
                    $data['tipodocumento'] ,
                    $data['numerodocumento'] ,
                    $data['email'] ,
                    $data['ubigeo'] ,
                    $data['telefono'] ,
                    '' ,
                    $data['telefono2'] ,
                    '',
                    $data['suscripcionNews'] ,
                    $data['cod_conf']
                )
            );
            $this->getAdapter()->commit();
            return $result[0];
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();
            return $result[0];
        }
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function update ($data, $key)
    {
        //print_r($data);
        //echo 'llave' . $key;
        /*$this->getAdapter()->beginTransaction();
        try {*/
        $data['suscripcionNews'] = ($data['suscripcionNews'] == 1) ? 1 : 0;
        $result = $this->getAdapter()->fetchAll(
            'EXEC KO_SP_USUARIO_UPD ?, ?, ?, ?, ?, ?, ?, ?, ?',
            array($key ,
                $data['nombre'] ,
                $data['apellido'] ,
                $data['ubigeo'] ,
                $data['ciudad'] ,
                $data['telefono'] ,
                $data['telefono2'] ,
                '' ,
                $data['suscripcionNews']
            )
        );
        //$this->getAdapter()->commit();
        return $result[0];
        /*} catch (Exception $e) {
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();
        }*/
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function existeApodo ($apodo)
    {
        try {
            $result = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_USUARIO_VERIFICA_APODO ?', array($apodo)
            );
            return ($result[0]->K_ERROR != 0);
        } catch (Exception $e) {
            return false;
        }
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function existeEmail ($email)
    {
        try {
            $result = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_USUARIO_VERIFICA_EMAIL ?', array($email)
            );
            return ($result[0]->K_ERROR != 0);
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            return false;
        }
    } //end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function existeDoc ($numDoc)
    {
        try {
            $result = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_USUARIO_VERIFICA_NUM_DOC ?', array($numDoc)
            );
            return ($result[0]->K_ERROR != 0);
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            return false;
        }
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function updEmail ($idUsuario, $email)
    {
        return $this->getAdapter()->fetchAll("EXEC KO_SP_USUARIO_EMAIL_UPD ?, ? ", array($idUsuario, $email));
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function setEmail ($idUsuario, $email)
    {
        return $this->updEMail($idUsuario, $email);
    } // end function
    public function getEmail($idUsuario)
    {
        return $this->getAdapter()->fetchOne(
            "SELECT EMAIL FROM KO_USUARIO WHERE ID_USR = ?", array($idUsuario)
        );
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function find ($key)
    {
        $data = $this->getAdapter()->fetchAll("EXEC KO_SP_USUARIO_SEL ?", array($key));
        return $data[0];
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function findAuth ($key)
    {
        //$data = $this->getAdapter()->fetchAll("SELECT * FROM KO_VW_USUARIO_PORTAL WHERE ID_USR = ?",
        //array($key));
        $data = $this->getAdapter()->fetchAll(
            "SELECT ID_USR,TIPO_USUARIO_DESC,ID_TIPO_USUARIO,ICON,APODO,APEL,NOM,EMAIL,FONO1,
            ID_ESTADO_USUARIO,ID_UBIGEO,NOM_UBIGEO,ID_TIPO_DOC,NRO_DOC FROM KO_VW_USUARIO_PORTAL 
            WHERE ID_USR = ?", array($key)
        );
        return $data[0];
    } //end function

    /**
     * Buscar usuario por apodo
     * @param string $apodo Apodo del Usuario a buscar
     * @return array Datos del usuario encontrado
     */
    public function findByApodo ($apodo, $spp = 0)
    {
        if ($spp == 0) {
            $data = $this->getAdapter()->fetchAll(
                "SELECT * FROM KO_VW_USUARIO_PORTAL WHERE APODO = '$apodo' OR EMAIL = '$apodo'"
            );
        } elseif ($spp == 1) {
            $data = $this->getAdapter()->fetchAll("EXEC KO_SP_USUARIO_APODO_SEL ?", array($apodo));
        }
        return $data[0];
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function validarUsuario ($apodo, $clave)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_USUARIO_LOGEO ?, ?", array($apodo , $clave)
        );
        return $result[0];
    } // end functio

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getPassword ($key)
    {    // $pass=$this->getAdapter()->fetchOne("SELECT PASSWORD FROM KO_USUARIO WHERE ID_USR=?",array($key));
        // $pass=$this->getAdapter()->fetchOne("EXEC KO_SP_DESENCRIPTAR_ENC ?",array($pass));
        // return $pass;
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function setPassword ($key, $password)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXEC KO_SP_USUARIO_CLAVE_UPD ?, ?", array($key , $password)
        );
        return $result[0];
    } //end function

    /**
     * Visualiza el tipo de usuario
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getTipoUsuario()
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('TipoUsuarioLista')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_TIPO_USUARIO_SEL');
            $cache->save($result, 'TipoUsuarioLista');
        }
        return $result;
    }

    /**
     * Visualiza al usuario asignado como vendedor de la semana
     * Usuario Activo
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getVendedorSemana()
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('VendedorSemana')) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_USUARIO_VENDEDOR_SEMANA");
            $cache->save($result, 'VendedorSemana');
        }
        return $result;
    }

    /**
     * Visualiza las N publicaciones de un usuario ID
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getUsuarioPublicaciones ($idUsuario, $nroResultados)
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('UsuarioAvisos' . $idUsuario)) {
            $result = $this->getAdapter()->fetchAll(
                "EXECUTE KO_SP_USUARIO_PUBLICACION_SEL ?, ?", array($idUsuario, $nroResultados)
            );
            $cache->save($result, 'UsuarioAvisos' . $idUsuario);
        }
        return $result;
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function cambioEstadoPorApodo ($apodo, $nuevoEstado)
    {
        $idUsuario = $this->getAdapter()->fetchOne(
            'SELECT ID_USR FROM KO_USUARIO_PORTAL WHERE APODO = ? ', array($apodo)
        );
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_USUARIO_ESTADO_UPD ?, ?', array($idUsuario , $nuevoEstado)
        );
        return $result;
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function cambioEstadoRegistro ($apodo)
    {
        $idUsuario = $this->getAdapter()->fetchOne(
            'SELECT ID_USR FROM KO_USUARIO_PORTAL WHERE APODO = ? ', array($apodo)
        );
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_USUARIO_ESTADO_UPD ?, 2, 1', array($idUsuario)
        );
        return $result;
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function setCodConfPassword ($idUsuario, $codigoConfirmacion)
    {
        $this->getAdapter()->query(
            "UPDATE KO_USUARIO_PORTAL SET COD_CONF_PASS = '$codigoConfirmacion' WHERE ID_USR = $idUsuario"
        );
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getCodConfPassword ($idUsuario)
    {
        return $this->getAdapter()->fetchOne(
            "SELECT COD_CONF_PASS FROM KO_USUARIO_PORTAL WHERE ID_USR = ?", array($idUsuario)
        );
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function setCodConf ($idUsuario, $codigoConfirmacion)
    {
        $this->getAdapter()->query(
            "UPDATE KO_USUARIO_PORTAL SET COD_CONF = '$codigoConfirmacion' WHERE ID_USR = $idUsuario"
        );
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getCodConf ($idUsuario)
    {
        return $this->getAdapter()->fetchOne(
            "SELECT COD_CONF FROM KO_USUARIO_PORTAL WHERE ID_USR = ?", array($idUsuario)
        );
    } // end function

    /**
     * Asignar código de confirmación para el cambio de correo
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function setCodConfEmailNuevo($idUsuario, $codigoConfirmacion)
    {
        $this->getAdapter()->query(
            "UPDATE KO_USUARIO_PORTAL SET COD_CONF_EMAIL_NUEVO = '$codigoConfirmacion' 
            WHERE ID_USR = $idUsuario"
        );
    }

    /**
     * Capturar código de confirmación para el cambio de correo
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getCodConfEmailNuevo ($idUsuario)
    {
        return $this->getAdapter()->fetchOne(
            "SELECT COD_CONF_EMAIL_NUEVO FROM KO_USUARIO_PORTAL WHERE ID_USR = ?", array($idUsuario)
        );
    }

    /**
     * Asigna el campo EMAIL_NUEVO
     * @param integer $idUsuario Identificador de Usuario
     * @param string $emailNuevo Correo electronico nuevo
     * @uses Clase::metodo()
     * @return type desc
     */
    public function setEmailNuevo($idUsuario, $emailNuevo)
    {
        //echo $emailNuevo;
        $this->getAdapter()->query(
            "UPDATE KO_USUARIO_PORTAL SET EMAIL_NUEVO = '$emailNuevo' WHERE ID_USR = $idUsuario"
        );
        return true;
    }

    /**
     * Captura el campo EMAIL_NUEVO
     * @param integer $idUsuario Identificador de Usuario
     * @return string Correo electronico
     */
    function getEmailNuevo($idUsuario)
    {
        return $this->getAdapter()->fetchOne(
            'SELECT EMAIL_NUEVO FROM KO_USUARIO_PORTAL WHERE ID_USR = ?', $idUsuario
        );
    }

    /**
     * Confirmación de cambio de correo nuevo
     * @param integer $idUsuario Identeificador de Usuario
     * @return Resultado de cambio de correo
     */
    function confirmarEmailNuevo($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll("EXEC KO_SP_CAMBIAR_CORREO_RET ?", array($idUsuario));
        return $result[0];
    }

    /**
     * Extrae el Id de Usuario por el apodo ingresado
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function getIdUsuarioPorApodo($apodo)
    {
        return $this->getAdapter()->fetchOne(
            'SELECT ID_USR FROM KO_USUARIO_PORTAL WHERE APODO = ?', array($apodo)
        );
    }

    /**
     * Obtiene el email de cualquier usuario
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    /* public function extrarEmail ($idUsuario)
    {
        $retorno = $this->getAdapter()->fetchAll("EXEC KO_SP_USUARIO_EMAIL_QRY ?", $idUsuario);
        return $retorno;
    }/*/


    /**
     * @param string $apodo
     * @return unknown
     */
    public function extraerEmail ($apodo)
    {
        $retorno = $this->getAdapter()->fetchAll("EXEC KO_SP_USUARIO_EMAIL_QRY ?", $apodo);
        return $retorno;
    }

    public function perteneceEmail($apodo, $email)
    {
        return $this->getAdapter()->fetchOne(
            "EXECUTE KO_SP_USUARIO_PERTENECE_MAIL_RET ?, ?", array($apodo, $email)
        );
    }

    /**
     * Validacion para la baja de usuario.
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function verificarDatosCancelacion ($apodo, $email, $clave)
    {
        $retorno = $this->getAdapter()->fetchAll(
            "EXEC KO_SP_USUARIO_VAL ?, ?, ? ", array($apodo , $email , $clave)
        );
        return $retorno;
    }

    /**
     * Validacion para la baja de usuario.
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function verificarApodoSuspencion ($apodo)
    {
        $retorno = $this->getAdapter()->fetchAll("EXEC KO_SP_USUARIO_EMAIL_QRY ? ", $apodo);
        return $retorno;
    }

    /**
     * Verifica el apodo del usuario a suspender.
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function verificarDeuda($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll("EXEC KO_SP_USUARIO_BAJA_VAL ? ", $idUsuario);
        return $result;
    }

    /**
     * Lista de Productos activos del usuario.
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function ventasActivas($idUsuario)
    {
        require_once 'Aviso.php';
        $aviso = new Aviso();
        return $aviso->listarVentasActivas(array('K_ID_USR' => $idUsuario));
    }

    /**
     * Registra al Usuario suspendido en la tabla notificacion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function registrarNotificacion($apodo , $comentario)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXEC KO_SP_NOTIFICACION_INS ? , ?", array($apodo, $comentario)
        );
        return $result;
    }

    /**
     * @param integer $idUsuario
     * @return void
     */
    public function estadoBaja($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            "update KO_USUARIO_PORTAL set id_estado_usuario=5 where ID_USR = ? ", $idUsuario
        );
        return $result;
    }

    public function darBaja($idUsuario, $estadoUsuario, $estadoAviso)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXEC IN_SP_USUARIO_BAJA ?, ?, ?", array($idUsuario, $estadoUsuario, $estadoAviso)
        );
        return $result;
    }

    /**
     * @param string $cadena
     * @return void
     */
    public function moderacionSuspension($cadena)
    {
        $result = $this->getAdapter()->fetchAll("EXEC KO_SP_MODERACION_VAL ? ", $cadena);
        return $result;
    }

    /**
     * Verifica si el usuario tiene deudas en Kotear Pagos
     * @param integer $idUsuario
     * @return boolean
     */
    public function tieneDeudaKotearPagos($idUsuario)
    {
        $result = $this->getAdapter()->fetchOne('EXEC KO_SP_USUARIO_ESTADO_CUENTA_QRY ?', array($idUsuario));
        return ($result > 0);
    }
    
    function getPuntaje($idUsuario)
    {
        return $this->getAdapter()->fetchOne('EXEC KO_SP_REPUTACION_PUNTAJE_USR_QRY(?)', array($idUsuario));
    }

    /**
     * Datos de envio de Correo de Confirmación
     * @param string $idUsuario
     */
    function getCorreoConfirmacion($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll('EXEC KO_SP_USUARIO_CORREO_CONFIRM_QRY ?', array($idUsuario));
        return $result[0];
    }

    /**
     * Permite conocer las preguntas sin contestar del usuario
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function preguntasSinContestar($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            'select  dbo.KO_FN_CANT_PREG_SIN_CONSTESTAR_USR ( ? ) as result', array($idUsuario)
        );
        return $result[0]->result;
    }

    /**
     * Permite conocer las calificaciones pendientes del usuario
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function numeroCalificacionesPendientes($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            'select (select dbo.KO_FN_CANT_SINCALIFICAR_IDUSR_COMPRADOR(?)) +
            (select dbo.KO_FN_CANT_SINCALIFICAR_IDUSR_VENDEDOR(?)) as result',
            array($idUsuario, $idUsuario)
        );
        return $result[0]->result;
    }

    /**
     * Permite conocer el numero de avisos por caducar
     * @param int $idUsuario desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function avisosPorCaducar($idUsuario, $dias)
    {
        $result = $this->getAdapter()->fetchAll(
            'select dbo.KO_FN_AVISO_CANT_DIAS_CADUCIDAD ( ?, ?) as result', array($idUsuario, $dias)
        );
        return $result[0]->result;
    }

    /**
     * Permite conocer el numero de avisos por caducar
     * @param int $idUsuario desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function pendienteDePago($idUsuario)
    {
        $response = $this->getAdapter()->fetchAll('KO_SY_ESTADO_CUENTA_TOTAL ? ', array($idUsuario));
        return $response[0]->Total;
        //return 0;
    }

    /**
     * Permite conocer el numero de avisos por caducar
     * @param int $idUsuario desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function nivelSuspension($idUsuario)
    {        
        return $this->getAdapter()->fetchAll('EXEC KO_SP_NIVEL_SUSPENCION_QRY ?', array($idUsuario));
    }

    /**
     * Permite conocer el numero de avisos por caducar
     * @param int $idUsuario desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function getEstado($idUsuario)
    {
        return $this->getAdapter()->fetchOne('EXEC KO_SP_USUARIO_ESTADO_QRY ?', array($idUsuario));
    }

}