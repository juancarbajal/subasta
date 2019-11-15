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
class Factura 
    extends Base_Pagos
{
    
    public function getClientesNoMigrados()
    {
        $result = $this->getAdapter()->fetchall('exec KP_SP_FACTURACION_CLIENTE_SEL ');
        return $result;
    }    
    
    public function getCipOperacionByIdaviso($input)
    {
        $array[]    = $input['K_IDCLIENTE'];
        $array[]    = $input['K_COD_DIRECCION'];
        $array[]    = $input['K_COD_ENTE'];
        
        try {
            return $this->getAdapter()->fetchAll(
                "EXECUTE KP_SP_FACTURACION_CODDIR_CODENTE ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
//            return $return;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function getConsultarDatosFacturacionPorCodigoEnteNull()
    {
        $result = $this->getAdapter()->fetchAll('exec usp_ConsultarDatosFacturacionPorCodigoEnteNull ');
        return $result;
    }
    
    public function ActualizarLogEnte($input)
    {
        $array[]    = $input['K_IdCliente'];
        $array[]    = $input['K_XML'];
        
        try {
            return $this->getAdapter()->fetchAll(
                "EXECUTE KP_SP_LOG_ENTE_INS ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function getConsultarParametrosAdecsys()
    {
        $result = $this->getAdapter()->fetchPairs('exec KP_SP_CONSULTAR_PARAMETROS_ADECSYS');
        return $result;
    }
    
    
    public function getConsultaCargosPendientesAdecsys()
    {
        $result = $this->getAdapter()->fetchall('exec usp_ConsultarCargosPendientesAdecsys');
        return $result;
    }
    
    public function ActCargo_CodigoAdecsys($input)
    {
        $array[]    = $input['pstrCodigoAdecsys'];
        $array[]    = $input['pintIdCargo'];
        $array[]    = $input['pintEstado'];
        
        try {
            $this->getAdapter()->fetchAll(
                "EXECUTE usp_ActualizarCargoCodigoAdecsys ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function ActualizarLogFacturacion($input)
    {
        $array[]    = $input['pintIdCliente'];
        $array[]    = $input['pintIdCargo'];
        $array[]    = $input['pstrXML'];
        $array[]    = $input['pintCodigoAdecsys'];
        
        try {
            $this->getAdapter()->fetchAll(
                "EXECUTE usp_ActualizarLogFacturacion ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function CicloContable($input)
    {
        $array[]    = $input['FechaInicio'];
        $array[]    = $input['FechaFin'];
        $array[]    = $input['Encargado'];
        $array[]    = $input['CantCargosInicio'];
        $array[]    = $input['CantCargosFinal'];
        $array[]    = $input['Descripcion'];
        
        try {
            $this->getAdapter()->fetchAll(
                "EXECUTE usp_InsertaCicloContable ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function ConsultarCorrelativoAdecsys()
    {
        try {
            $result = $this->getAdapter()->fetchAll('exec usp_ConsultarCorrelativoAdecsys');
            return $result[0]->Serie;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function ActualizarCorrelativoAdecsys($input)
    {
        $array[]    = $input['pintSerie'];
        
        try {
            $this->getAdapter()->fetchAll(
                "EXECUTE usp_ActualizarCorrelativoAdecsys ?", 
                $array
            );
            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function ConsultarPlantillaxCodigo($codigo)
    {
        $array[]    = $codigo;
        
        try {
            $return = $this->getAdapter()->fetchAll(
                "EXECUTE usp_ConsultarPlantillaEmailxCodigo ?", 
                $array
            );
            return $return[0];
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function ConsultarParametrosxCodigoGrupo($codigo)
    {
        $array[]    = $codigo;
        
        try {
            $return = $this->getAdapter()->fetchAll(
                "EXECUTE usp_ConsultarParametrosxCodigoGrupo ?", 
                $array
            );
            return $return[0];
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    public function ActualizarCodigoEnte($input)
    {   
        $array[]    = $input['pidCliente'];
        $array[]    = $input['pCodigoEnte'];
        
        try {
            $this->getAdapter()->fetchAll(
                "EXECUTE usp_ActualizarDatosFacturacion ?".str_repeat(",?", (count($array)-1)), 
                $array
            );
            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }   
}