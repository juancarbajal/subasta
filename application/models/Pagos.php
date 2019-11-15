<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagos
 *
 * @author nazart
 */
require_once 'Base/Pagos.php';
class Pagos extends Base_Pagos
{
    function actUriOperacion($idOperacion,$strUri)
    {
        $response=$this->getAdapter()->fetchAll(
            "EXEC usp_ActualizarOperacionPasarela ?,?", array($idOperacion,$strUri)
        );
        return $response;
    }

    function registroOperacionFallida($idoperacion, $idTransP, $idUsuario, $error, $pstrProcesadoPor='KWE')
    {
        $response=$this->getAdapter()->fetchAll(
            "EXEC usp_ActualizarEstadoOperacionFallida ?,?,?,?,?",
            array($idoperacion, $idTransP, $idUsuario, $error, $pstrProcesadoPor)
        );
        return $response[0]->RESP;
    }

    function registroOperacionExitosa($idoperacion, $idTransP, $idUsuario, $carId, $paramPas,
        $pstrProcesadoPor='KWE')
    {
        $response=$this->getAdapter()->fetchAll(
            "EXEC usp_ActualizarEstadoOperacionExitosa ?,?,?,?,?,?",
            array($idoperacion, 
                $idTransP, 
                $idUsuario, 
                $carId, 
                $paramPas, 
                $pstrProcesadoPor
            )
        );
        return $response[0]->RESP;
    }
    /**
     * Descripcion
     * @param
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getDetalleTransaccion($idd, $idUsr)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXEC usp_ConsultarDetalleTransaccion ?,?", array($idd, $idUsr)
        );
        return $result;
    } // end function
    public function detalleOperacion($idOperacion,$idUsr)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXEC usp_ConsultarTransaccionOperacion ?,?', array($idOperacion, $idUsr)
        );
        return $result;
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function agruparComision($idUsr)
    {
        //echo 'ID_USR : ' . $idUsr ;
        $result = $this->getAdapter()->fetchAll("EXEC usp_RegistrarTransaccionComision ?", array($idUsr));
       // echo 'Resultado : ';
        //print_r($result);
        return (isset($result))?$result[0]->IdTransaccion:null;
        /*
        if (count($result) > 0 ) {
            return ($result[0]->IdTransaccion!=null)?$result[0]->IdTransaccion:null ;
        } else {
           return null;
        } //end if*/
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getDatosOperacion($idd)
    {
        $result = $this->getAdapter()->fetchAll("EXEC usp_ConsultarTransaccionOperacionPE ?", array($idd));
        return $result;
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getComisionesPendientes($idUsr)
    {
        return  $this->getAdapter()->fetchAll('exec usp_ConsultarComisionesAPagar ?', array($idUsr));
    } // end function

    public function getCargosOperacionPa($idd)
    {
        $result = $this->getAdapter()->fetchAll("EXEC usp_ConsultarTransaccionOperacionPA ?", array($idd));
        return $result;
    }
    
    public function registrarRespServPago($idOperacion,$url,$tipo)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXEC usp_RegistrarRespServPago ?,?,?", array($idOperacion, $url, $tipo)
        );
        return $result;
    }
    
    public function getDetalleComisionesPagadas($idUsuario,$tipo,$codigo,$anio)
    {
        $result = $this->getAdapter()->fetchall(
            'exec usp_ConsultarFacDetPortal ?,?,?,?', array($idUsuario, $tipo, $codigo, $anio)
        );
    
        return $result ;
    }
    
    public function getComisionesCabezera($idUsuario,$tipo,$codigo)
    {
        $result =  $this->getAdapter()->fetchall(
            'exec  usp_ConsultarFacCabPortal ?,?,?', array($idUsuario, $tipo, $codigo)
        );
    
        return $result ;
    }
    public function getComisionesPagadas($idUsuario)
    {
        $result =  $this->getAdapter()->fetchall(
            'exec  usp_ConsultarTransaccionesPagadas  ?', array($idUsuario)
        );
        return $result ;
    }
    
    public function getDepatamentoDatosFac($idPais)
    {
        $result = $this->getAdapter()->fetchall('exec usp_ConsultarDepartamentosPorIdPais ?', array($idPais));
        return $result;
    }
    
    public function getDistritoDatosFac($idPais,$idDpto)
    {
        $result = $this->getAdapter()->fetchall(
            'exec usp_ConsultarDistritosPorIdPaisYIdDepartamento ?,?', array($idPais, $idDpto)
        );
        return $result;
    }
    
    public function getCentrosPobladosFac()
    {
        $result = $this->getAdapter()->fetchall('exec usp_ConsultarCentrosPoblados ');
        return $result;
    }
    public function getTipoCalleFac()
    {
        $result = $this->getAdapter()->fetchall('exec usp_ConsultarTiposCalle ');
        return $result;
    }
    public function getDatosClienteFac($idCliente)
    {
        $result = $this->getAdapter()->fetchall(
            'exec usp_ConsultarDatosFacturacionPorIdCliente ?', array($idCliente)
        );
        return ($result != NULL)?$result[0]:'';
    }
    public function registrarIpTrama($ipp, $trama)
    {
        $this->getAdapter()->fetchAll('exec usp_RegistrarIpTrama ?, ? ', array($ipp, $trama));
    }     

    public function getTransaccionKP($idAviso)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE usp_ConsultarTransaccionKP ?', array($idAviso)
        );
        return $result[0]->IdTransaccion;
    }
    
    public function registrarLogPagoEfectivoV2($operacion, $request, $response, $tipo)
    {
        $result = $this->getAdapter()->fetchall(
            'exec usp_RegistrarLogOperacionPagoEfectivo2 ?,?,?,?',
            array($operacion, $request, $response, $tipo)
        );
    }
   
    /**
     * 
     * Función que permite registrar el idResSolPago, esto para la versión 2 de
     * Pago Efectivo
     *
     * @param int $idOperacion
     * @param string $idResSolPago
     * @author Luis Mercado
     * @return type 
     */
    public function actUriPagoefectivoV2($idOperacion, $idResSolPago)
    {
        $response = $this->getAdapter()->fetchAll(
            "EXEC usp_ActualizarOperacionPagoEfectivoV2 ?,?",
            array($idOperacion, $idResSolPago)
        );
        return $response;
    }
    
    /**
     * Función que permite obtener las transacciones a conciliar con pagoEfectivo
     * y pasarela
     * 
     * @return array lista
     */
    public function getOrdenesaConciliar()
    {
        try {
            $result = $this->getAdapter()->fetchAll('EXECUTE usp_ConsultarTransaccionAConciliar');
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    
    }
    

    public function updEstadoValidacion($operacion, $cliente, $procesadoPor = 'cron')
    {
        try {
            $result = $this->getAdapter()->fetchAll(
                'EXECUTE usp_ActualizarValidacionOperacion ?,?,?',
                array($operacion, $cliente, $procesadoPor)
            );
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    
    }
    
    public function getDatosLiberarTransaccionPasarela($idTransaccion)
    {
        //OBTENER LOS DATOS DE LA TABLA KO_AVISO;
        $result = $this->getAdapter()->fetchAll(
            "SELECT idCliente,idOperacion FROM Operacion WHERE IdTrnPasarela = ?", $idTransaccion
        );
        return $result[0];
    }
    
    public function updOperacionValidada($idOperacion)
    {
        $this->_db->update('Operacion', array('Valido' => 1), array('idOperacion = ?' => $idOperacion));
    }

    /*
     * STORE PROCEDURES CONCILIACION
     */
    
    public function sp_validaConciliacion($idTransPasarela)
    {
        try {
            $result = $this->getAdapter()
                ->fetchAll('EXECUTE KP_SP_CONCILIACION_VALIDA_UPD ?', array($idTransPasarela));
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }
    
    /*
     * STORE PROCEDURES MEDIACION
     */
    
    public function getMediacion()
    {
        try {
            $result = $this->getAdapter()->fetchAll('EXECUTE usp_ConsultarTransaccionAConciliar');
            return $result;
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    
    }
    
    /**
     * Permite registrar destaques a un nuevo aviso
     * @author Ander
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function guardarDatosFacturacion($input)
    {
        $array[]    = $input['K_ID_USR'];
        $array[]    = $input['K_TIPO_DOCUMENTO']; 
        $array[]    = $input['K_NUM_DOCUMENTO'];
        $array[]    = $input['K_RAZON_SOCIAL'];
        $array[]    = $input['K_NOMBRE'];
        $array[]    = $input['K_APE_PATERNO'];
        $array[]    = $input['K_APE_MATERNO'];
        $array[]    = $input['K_DIRECCION'];
        
        try {
            $return = $this->getAdapter()->fetchAll(
                "EXECUTE KP_SP_DATOS_FACTURACION_INS ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            // Retorna 
            return $return[0]->error;
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    /**
     * 
     * @author Ander
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function generarOperacion($input)
    {	
        $array[]    = $input['pstrIdTransaccion'];
        $array[]    = $input['pintIdCliente'];
        $array[]    = $input['pstrUrlEnvio'];
        $array[]    = $input['pintMedioPago'];
        $array[]    = $input['vdecMonto'];
        
        try {
            $return = $this->getAdapter()->fetchAll(
                "EXEC KP_SP_OPERACION_INS ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            return $return[0]->IdOperacion;
//            return true;$return
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        } 
    }
    
    /**
     * 
     * @author Ander
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function guardarTipoDocumentoFacturando($input)
    {   
        $array[]    = $input['K_ID_OPERACION']; //->
        $array[]    = $input['K_ID_USR'];
        $array[]    = $input['K_ID_TIPO_FACTURA']; //->
        
        try {
            $return = $this->getAdapter()->fetchAll(
                "EXECUTE KP_SP_TIPO_DOCUMENTO_FACTURADO_INS ?".str_repeat(",?", (count($array)-1)), $array
            );
            return true;//$return
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function actualizarOperacionPE($input)
    {
        $array[]    = $input['K_ID_OPERACION'];//pintIdOperacion
        $array[]    = $input['K_URL_ENVIO'];//pstrUrlEnvio
        $array[]    = $input['K_CIP'];//pstrCIP
        
        try {
            $return = $this->getAdapter()->fetchAll(
                "EXECUTE KP_OPERACION_PAGOEFECTIVO_UPD ?".str_repeat(",?", (count($array)-1)), $array
            );
            return $return[0]->error;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }        
    }
    
    public function getCipOperacionByIdaviso($input)
    {
        $array[]    = $input['K_IDAVISO'];//pintIdOperacion
        $array[]    = $input['K_IDUSR'];//pstrUrlEnvio
        
        try {
            $return = $this->getAdapter()->fetchAll(
                "EXECUTE KP_SP_CONSULTAR_CIP_SEL ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            return $return[0];
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }        
    }
    
    public function getListaMediacion()
    {
        $response = $this->getAdapter()->fetchAll("EXEC KP_SP_AVISOS_MEDIACION_SEL");
        return $response;
    }
    
    /**
     * 
     * @author Ander
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function guardarAvisosMediacion($idTransP)
    {
        try {
            $return = $this->getAdapter()->fetchAll("EXECUTE KP_SP_AVISOS_MEDIACION_UPD ?", $idTransP);
            return $return[0]->error;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }        
    }
}
