<?php

require_once 'Base/Aviso.php';

class Aviso extends Base_Aviso
{
    public $codAviso;

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getDatosAvisoEditar($idDestaque)
    {
        return $this->getAdapter()
            ->fetchRow(
                "SELECT TOP 1 AD.ID_AVISO, A.EST, D.ID_DESTAQUE, A.ID_TIPO_PRODUCTO, A.TIT,
                    A.PRECIO, A.HTML, A.ID_MONEDA, AC.ID_CATEGORIA
                FROM KO_AVISO A
                JOIN KO_AVISO_DESTAQUE AD ON AD.ID_AVISO=A.ID_AVISO
                JOIN KO_DESTAQUE D ON AD.ID_DESTAQUE=D.ID_DESTAQUE
                JOIN KO_AVISO_CATEGORIA AC ON AC.ID_AVISO=A.ID_AVISO
                WHERE AD.ID_AVISO =? AND D.EST='1' ORDER BY D.ID_DESTAQUE DESC", $idDestaque
            );
    }
    
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getDatos($idAviso)
    {
        //OBTENER LOS DATOS DE LA TABLA KO_AVISO;
        return $this->getAdapter()->fetchAll("SELECT * FROM KO_AVISO WHERE ID_AVISO = ?", $idAviso);
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function existAviso($idAviso, $idUsuario)
    {
        //OBTENER LOS DATOS DE LA TABLA KO_AVISO Y DE LAS TABLAS CON RELACION DE UNO A MUCHOS
        $result = $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_AVISO_USR_SEL ?,?", array($idAviso, $idUsuario)
        );
        return $result[0];
    }

    /**
     * Verifica la existencia de CIP
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function verificarGeneracionCIP($idAviso, $idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_AVISO_DESTAQUE_CIP_QRY ?,?", array($idAviso, $idUsuario)
        );
        return $result[0];
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getListaDatosActivos($activo, $usr_id)
    {
        //OBTENER LOS DTOS REQUERIDOS VALIDANDO EL USUARIO Y EL CODIGO DEL AVISO
        return $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_AVISO_EST_SEL ? , ?", array($activo, $usr_id)
        );
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getListaMedioPago()
    {
        //ME DEVUELVE UN ARRAY CON LOS MEDIOS DE PAGO PARA ESE
        //return $this->getAdapter()->fetchAll("SELECT * FROM KO_AVISO_MEDIO_PAGO WHERE ID_AVISO = ?",
        //    $this->codAviso);
        return $this->getAdapter()->fetchAll(
            "EXEC KO_SP_AVISO_MEDIO_PAGO_ID_QRY ?", array($this->codAviso)
        );
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function registroDatos($arrayDatos, $arrayDestaque, $arrayFotos, $idcategoria, $mediopago,
        $usuario, &$idAviso, &$transaccion, $estModeracion, $cantidadFotos, &$listdestaq)
    {
        if (count($this->find($idAviso)) <= 0) {
            // Aviso nuevo
            unset($arrayDatos['ID_AVISO']);
            if ($this->setDatos($arrayDatos, $idAviso, $idcategoria, $mediopago, $estModeracion) == true) {
                try{
                    //Se inserto correctamente el aviso
                    $this->setFotos($arrayFotos, $usuario, $idAviso, $cantidadFotos);
                    // En caso se registre un cargo se obtiene la cadena de cargos
                    $transaccion = $this->setDestaque(
                        $idAviso, implode(',', $arrayDestaque), $arrayDatos["TEXT_IMPRESO"],
                        $arrayDatos["TIT_IMPRESO"]
                    );
                } catch( Exception $e) {
                    
                }
                
                return true;
            } else {
                return false;
            }
        } else {
            // Edicion de aviso
            if (
                $this->updateDatos(
                    $arrayDatos, $idcategoria, $mediopago, $estModeracion, $transaccion, $idAviso,
                    $arrayDestaque, $arrayFotos[0]
                ) == true
            ) {
                // Se actualizo correctamente el aviso, sus destaques, obtenemos codigos de cargos
                $this->setFotos($arrayFotos, $usuario, $idAviso, $cantidadFotos);
                $idAviso = $idAviso;
                return true;
            } else {
                return false;
            }
        }
    }

  
    /** Ander
     * Registra los datos de un aviso nuevo
     * 
     */
    public function guardarAviso($input)
    {
        $array[]    = $input['K_ID_TIPO_PRODUCTO'];
        $array[]    = $input['K_TIT'];
//        $array[]    = '';//$input['K_SUBTIT'];
        $array[]    = $input['K_TAG'];
//        $array[]    = '1';//$input['K_STOCK'];
        $array[]    = $input['K_PRECIO'];
        $array[]    = $input['K_HTML'];
        $array[]    = $input['K_IMG_DEF'];
        $array[]    = $input['K_EST'];
        $array[]    = $input['K_URL'];
//        $array[]    = '1';//$input['K_ID_TIPO_AVISO'];
//        $array[]    = '1';//$input['K_ID_DURACION'];
//        $array[]    = '1';//$input['K_ID_REPUBLICACION'];
        $array[]    = $input['K_ID_MONEDA'];
        $array[]    = $input['K_ID_USR'];
//        $array[]    = '0';//$input['K_VISITAS'];
        $array[]    = $input['K_ID_UBIGEO'];
        $array[]    = $input['K_ID_CATEGORIA'];
        $array[]    = $input['K_ID_MEDIO_PAGO'];
//        $array[]    = '0';//$input['K_FLAG_MODERACION'];
//        $array[]    = '0';//$input['K_FLAG_MODERACION_ADMIN'];
//        $array[]    = '0';//$input['K_REPUBLICADO_CONTADOR'];
        $array[]    = '1';//$input['P_DESTAQUE_POST_PAGO'];
        
        try {
            $return = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_AVISO_INS ?, ?, ?, ?, ?, 
                 ?, ?, ?, ?, ?, 
                 ?, ?, ?, ? ',
                $array
            );
            // Retorna el codigo del aviso
            return $return[0]->MSJ;
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function setCategoria($categoria, $idAviso)
    {
        $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_CATEGORIA_INS ?,?', array($idAviso, $categoria));
    }

//    /**
//     * Permite registrar las fotos de un nuevo aviso
//     * @param type name desc
//     * @uses Clase::metodo()
//     * @return type desc
//     */
//    function setFotos($fotos, $usuario, $aviso, $nroFotos) {
//        $nuevasFotos = array();
//        if (is_array($fotos)) {
//            $i = 0;
//            foreach ($fotos as $index => $valor):
//                if ($valor === 0) {
//                    unset($fotos[$index]);
//                }
//                if ($i < $nroFotos) {
//                    array_push($nuevasFotos, $valor);
//                    $i++;
//                }
//            endforeach;
//            $this->getAdapter()->fetchAll('EXECUTE KO_SP_FOTO_AVISO_INS ?, ?, ?',
//                    array(
//                        implode(',', $nuevasFotos),
//                        $usuario,
//                        $aviso
//                    )
//            );
//        }
//    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function setIdAviso($codAviso)
    {
        $this->codAviso = $codAviso;
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function setMedioPago($medioPago, $idAviso)
    {
        return $this->getAdapter()->fetchAll(
            'EXEC KO_SP_AVISO_MEDIO_PAGO_UPD ?, ?', array($idAviso, $medioPago)
        );
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getFoto($idAviso, $prioridad='')
    {
        //echo $idAviso. ' ' . $prioridad;
        $salida = $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_FOTO_AVISO_SEL ?, ?", array($idAviso, $prioridad)
        );
        return $salida;
    }

    /**
     * Permite obtener los destaques activos de un aviso
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getDestaquesAviso($idAviso)
    {
        $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_AVISO_DESTAQUE_SEL ?', array($idAviso));
        return $result;
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function updStock($idAviso, $valUpd)
    {
        $where = $this->getAdapter()->quoteInto('ID_AVISO = ?', $idAviso);
        $this->getAdapter()->query(
            "UPDATE {$this->_name} SET STOCK=STOCK + ({$valUpd}) WHERE {$this->_primary}={$idAviso}"
        );
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function setTransaccionDestaque($idaviso, $est, $flag, $destaque)
    {
        $response = ($this->getAdapter()->fetchAll(
            'EXECUTE  KO_SP_TRANSACCION_DESTAQUE_INS ?, ?, ?, ?, ? ',
            array('',
                $est,
                $flag,
                implode(',', $destaque),
                $idaviso
            )
        ));
        return (explode(',', $response[0]->computed));
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getTransaccionDestaque($idTransaccion)
    {
        $response = $this->getAdapter()->fetchAll(
            'EXECUTE  KO_SP_TRANSACCION_DESTAQUE_SEL ?', array($idTransaccion)
        );
        return $response[0];
    }

    public function registrarCargo($array)
    {
        $response = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_REGISTRAR_CARGO_INS2 ?,?,?,?,?,?,?,?,?', $array
        );
        //print_r($response);
        return $response[0];
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function generarTransaccionKotearPagos($idAviso, $idUsuario)
    {
        $response = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SY_REGISTRAR_TRANSACCION_KOTEAR ?,?', array($idAviso, $idUsuario)
        );
        return $response[0]->IdTransaccion;
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function deleteAviso($aviso, $usuario)
    {
        $response = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_AVISO_DEL ?, ?', array($aviso, $usuario)
        );
        return $response[0];
    }

    public function getCategoriaAviso($idCategoria)
    {
        $response = $this->getAdapter()->fetchAll('EXECUTE KO_SP_AGRUPADOR_SEL ?', array($idCategoria));
        $categ = $response[0];
        $nivel = array(
            array($categ->L1, $categ->L1_NOM, $categ->L1_ADUL)
            , array($categ->L2, $categ->L2_NOM, $categ->L2_ADUL)
            , array($categ->L3, $categ->L3_NOM, $categ->L3_ADUL)
            , array($categ->L4, $categ->L4_NOM, $categ->L4_ADUL));
        return $nivel;
    }

    /**
     * Obtiene los N avisos mas contactados del portal
     * @param integer $nroResultados Numero de registros que deseamos obtener
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getloMasContactado($nroResultados)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('LoMasContactado')) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_AVISO_MAX_CONTACTADOS ?", $nroResultados);
            $cache->save($result, 'LoMasContactado');
        }        
        return $result;
    }

    
    /**
     * Obtiene los N avisos que saldran en la seccion de ofertas imperdibles
     * @param integer $nroResultados Numero de registros que deseamos obtener
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getofertasImperdibles($nroResultados)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('OfertasImperdibles')) {
            $result = $this->getAdapter()->fetchAll(
                "EXECUTE KO_SP_AVISO_DESTACADOS_SEL_M7 ?", $nroResultados
            );
            $cache->save($result, 'OfertasImperdibles');
        }        
        return $result;
    }
    
    
    
    public function moderarAviso($titAviso)
    {
        $response = $this->getAdapter()->fetchAll("EXECUTE KO_SP_MODERACION_VAL  ?", array($titAviso));
        return $response[0];
    }

    /**
     * Lista de ventas activas de acuerdo al aviso seleccionado
     * @param integer $idAviso
     */
    function getVentasActivasAviso($idAviso, $count = 4)
    {
        //echo $idAviso. ' ' . $count;
        return $this->getAdapter()->fetchAll(
            'EXEC KO_SP_AVISO_VENTAS_ACTIVAS_SEL ?, ?', array($idAviso, $count)
        );
    }
    
    /**
     * @param lista Asunto
     * @return boolean Se logro activar todos los avisos
     */
    function listarMotivo()
    {
        return $this->getAdapter()->fetchAll(
            'SELECT  ID_MOTIVO, TIT from KO_MOTIVO WHERE ID_TIPO_MOTIVO=7 AND  EST=1'
        );
    }

    /**
     *
     * @param lista Asunto
     * @return boolean Se logro activar todos los avisos
     */
    function insertarMotivo($idUsuario, $comentario, $apodo, $idMotivo, $idNotificacion, $idAviso)
    {
        $retorno = $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_NOTIFICACION_INSERTAR ? , ? , ? , ? , ?, ? ",
            array($idUsuario,
                $comentario,
                $apodo,
                $idMotivo,
                $idNotificacion,
                $idAviso)
        );
        return $retorno[0];
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getOfertanteSubasta($idAviso, $idUsr, $estado)
    {
        return $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_AVISO_SUBASTA_USUARIO ?, ?, ? ', array($idAviso, $idUsr, $estado)
        );
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getAvisoSeguimiento($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll('SELECT DBO.KO_FN_CONT_COMPRAS_SEG (?)', array($idUsuario));
        return $result[0]->computed;
        //return $seg['0']->computed;
    }

    /**
     * Permite activar un aviso que ha sido desactivado por el usuario
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function activarAviso($idAviso, $idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_AVISO_ACTIVAR_USR ?, ?', array($idAviso, $idUsuario)
        );
        return $result[0];
    }

    /**
     * Permite retirar destaques de un aviso pendiente de pago
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function retirarDestaquesAviso($idAviso, $idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_AVISO_RETIRAR_DESTAQUE ?, ?', array($idAviso, $idUsuario)
        );
        return $result[0];
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function desactivarAviso($idAviso, $idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_AVISO_DESACTIVAR_USR ?,?', array($idAviso, $idUsuario)
        );
        return $result[0];
    }

    public function actualizarHtml($idAviso, $html)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_MIG_UPDATE_AVISO_HTML ?,?', array($idAviso, $html)
        );
        return $result;
    }

    public function getServicioGratuito()
    {
        return $this->getAdapter()->fetchOne(
            "SELECT ISNULL(VALOR1,0) FROM KO_PARAMETRO WHERE COD_PARAMETRO='FBP'"
        );
    }

    /**
     * Flag de cobro de destaque
     * @return integer 1: si se cobra destaque 0: si no se cobra destaque
     * */
    function getFlagCobroDestaque()
    {
        return $this->getAdapter()->fetchOne("SELECT VALOR1 FROM KO_PARAMETRO WHERE COD_PARAMETRO='FCD'");
    }

//end function getFlagCobroDestaque

    /**
     * Flag de cobro de comisión
     * return integer 1: si se cobra comisión 0: si no se cobra comisión
     * */
    function getFlagCobroComision()
    {
        return $this->getAdapter()->fetchOne("SELECT VALOR1 FROM KO_PARAMETRO WHERE COD_PARAMETRO='FCD'");
    }

//end function getFlagCobroComision

    /**
     * Extrae el número de calificaciones pendiente de calificacion de un aviso
     * @param integer $idAviso Identificador de aviso
     * @return integer Cantidad de calificaciones pendientes de calificar de una
     * aviso
     * */
    function getCantCalificacionesPendientes($idAviso)
    {
        if (
            $this->getAdapter()->fetchOne(
                "SELECT ID_TIPO_AVISO FROM KO_AVISO WHERE ID_AVISO=?", array($idAviso)
            ) == 2
        ) {
            return $this->getAdapter()->fetchOne(
                "SELECT COUNT(1) FROM KO_OFERTA O INNER JOIN KO_AVISO A ON O.ID_AVISO=A.ID_AVISO WHERE 
                    (A.FEC_FIN>=GETDATE() AND A.EST=1)AND O.ID_AVISO=?", array($idAviso)
            );
        } else {
            return $this->getAdapter()->fetchOne("SELECT  DBO.KO_FN_AVISO_CANT_CALIFICAR($idAviso)");
        }
    }
    
    public function getAvisoPorUsuario($idUsuario, $idAviso)
    {
        $aviso = $this->fetchRow('ID_USR = ' . $idUsuario . ' AND ID_AVISO = ' . $idAviso);
        if (!empty($aviso)) return TRUE;
        else return FALSE;
    }

    /** Ander
     * Registra los datos de un aviso nuevo
     * 
     */
    public function guardarAvisoPublicacion($input)
    {   
        $array[]    = $input['K_ID_TIPO_PRODUCTO'];
        $array[]    = $input['K_TIT'];
        $array[]    = $input['K_TAG'];
        $array[]    = $input['K_PRECIO'];
        $array[]    = $input['K_HTML'];
        //$array[]    = $input['K_IMG_DEF'];
        
        $array[]    = $input['K_EST'];
        $array[]    = $input['K_URL'];
        $array[]    = $input['K_ID_MONEDA'];
        $array[]    = $input['K_ID_USR'];
        $array[]    = $input['K_ID_UBIGEO'];
        $array[]    = $input['K_ID_CATEGORIA'];
        $array[]    = $input['K_ID_MEDIO_PAGO'];
        $array[]    = $input['P_DESTAQUE_POST_PAGO'];
        
        $array[]    = $input['K_ID_DESTAQUE'];
        $array[]    = $input['K_TEXT_IMPRESO'];
        $array[]    = $input['K_TIT_IMPRESO'];
        
        $array[]    = $input['K_FOTOS'];
        
        $array[]    = $input['K_MEDIO_PAGO'];
        $array[]    = $input['K_MONTO'];
        $array[]    = $input['K_ID_TIPO_FACTURA'];
                
        try {
            $return = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_AVISO_PUBLICACION_INS ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?, 
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?',
                $array
            );
            // Retorna el codigo del aviso
            return $return[0];
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    /** Ander
     * actualiza
     * 
     */
    public function actualizaAviso($input)
    {
        $array[]    = $input['K_ID_AVISO'];
        $array[]    = $input['K_ID_TIPO_PRODUCTO'];
        $array[]    = $input['K_TIT'];
        $array[]    = $input['K_TAG'];
        $array[]    = $input['K_PRECIO'];
        $array[]    = $input['K_HTML'];
        $array[]    = $input['K_EST'];
        $array[]    = $input['K_URL'];
        $array[]    = $input['K_ID_DURACION'];
        $array[]    = $input['K_ID_MONEDA'];
        $array[]    = $input['K_ID_USR'];
        $array[]    = $input['K_ID_UBIGEO'];
        $array[]    = $input['K_ID_CATEGORIA'];
        $array[]    = $input['K_ID_MEDIO_PAGO'];
        $array[]    = $input['P_DESTAQUE_POST_PAGO'];
        
        try {
            $return = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_AVISO_UPD ?, ?, ?, ?, ?, 
                                         ?, ?, ?, ?, ?, 
                                         ?, ?, ?, ?, ?',
                $array
            );
            // Retorna el codigo del aviso
            return $return[0];
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }

    /** Ander
     * Lista las Ventas no Activas del Usuario Logeado
     * @param integer $nroResultados Numero de registros que deseamos obtener
     * @uses Clase::metodo()
     * @return type desc
     */
    public function listarVentasActivas($input)
    {
        $array[]    = $input['K_ID_USR'];
        $array[]    = empty($input['K_ID_CATEGORIA'])?0:$input['K_ID_CATEGORIA'];
        $array[]    = empty($input['K_FILTRO'])?0:$input['K_FILTRO'];
        $array[]    = empty($input['K_TIT'])?'':$input['K_TIT'];
        $array[]    = empty($input['K_FILTRO_FECHA'])?'0':$input['K_FILTRO_FECHA'];
        $array[]    = empty($input['K_NUM_PAGINA'])?'1':$input['K_NUM_PAGINA'];
        $array[]    = empty($input['K_NUM_REGISTROS'])?'30':$input['K_NUM_REGISTROS'];
        
        return $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_VENTAS_ACTIVAS ?".str_repeat(",?", (count($array)-1)), $array
        );
    }
    
    /** Ander
     * Lista las Ventas no Activas del Usuario Logeado
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function listarVentasNoActivas($input)
    {
        $array[]    = $input['K_ID_USR'];
        $array[]    = empty($input['K_ID_CATEGORIA'])?0:$input['K_ID_CATEGORIA'];
        $array[]    = empty($input['K_FILTRO'])?0:$input['K_FILTRO'];
        $array[]    = empty($input['K_TIT'])?'':$input['K_TIT'];
        $array[]    = empty($input['K_FILTRO_FECHA'])?'0':$input['K_FILTRO_FECHA'];
        $array[]    = empty($input['K_NUM_PAGINA'])?'1':$input['K_NUM_PAGINA'];
        $array[]    = empty($input['K_NUM_REGISTROS'])?'30':$input['K_NUM_REGISTROS'];
        
        return $this->getAdapter()->fetchAll(
            "EXEC KO_SP_VENTAS_NO_ACTIVAS ?".str_repeat(",?", (count($array)-1)), $array
        );
    }
    
    /** Ander
     * Lista las Ventas no Activas del Usuario Logeado
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function listarPendientePago($input)
    {
        $array[]    = $input['K_ID_USR'];
        $array[]    = empty($input['K_ID_CATEGORIA'])?0:$input['K_ID_CATEGORIA'];
        $array[]    = empty($input['K_FILTRO'])?0:$input['K_FILTRO'];
        $array[]    = empty($input['K_TIT'])?'':$input['K_TIT'];
        $array[]    = empty($input['K_FILTRO_FECHA'])?'0':$input['K_FILTRO_FECHA'];
        $array[]    = empty($input['K_NUM_PAGINA'])?'1':$input['K_NUM_PAGINA'];
        $array[]    = empty($input['K_NUM_REGISTROS'])?'30':$input['K_NUM_REGISTROS'];
        
        return $this->getAdapter()->fetchAll(
            "EXEC KO_SP_VENTAS_PENDIENTES ?".str_repeat(",?", (count($array)-1)), 
            $array
        );
    }

    /**
     * Listado de Preguntas Recibidas - modulo MIS VENTAS
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function listarPreguntasRecibidas($input)
    {
        $array[]    = $input['K_ID_USR'];
        $array[]    = empty($input['K_PARAM'])?0:$input['K_PARAM'];
        $array[]    = empty($input['K_NUM_PAGINA'])?'1':$input['K_NUM_PAGINA'];
        $array[]    = empty($input['K_NUM_REGISTROS'])?'30':$input['K_NUM_REGISTROS'];
        return $this->getAdapter()->fetchAll(
            "EXEC KO_SP_PREGUNTAS_CATEGORIA_PROCESO_VENTAS_QRY ?".str_repeat(",?", (count($array)-1)), 
            $array
        );
    }

    /**
     * Listado de Preguntas Recibidas - modulo MIS VENTAS
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function listarPreguntasRealizadas($input)
    {
        $array[]    = $input['K_ID_USR'];
        $array[]    = empty($input['K_PARAM'])?0:$input['K_PARAM'];
        $array[]    = empty($input['K_NUM_PAGINA'])?'1':$input['K_NUM_PAGINA'];
        $array[]    = empty($input['K_NUM_REGISTROS'])?'30':$input['K_NUM_REGISTROS'];
        
        return $this->getAdapter()->fetchAll(
            "EXEC KO_SP_PREGUNTAS_CATEGORIA_PROCESO_COMPRAS_QRY ?".str_repeat(",?", (count($array)-1)), 
            $array
        );
    }
    
}
