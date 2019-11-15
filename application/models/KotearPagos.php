<?php
/**
 * @author jcarbajal
 *
 */
class KotearPagos
{
    /**
     * @param string $cadena Cadena e encriptar
     * @return string|string
     */
    function encriptar ($cadena)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $wsdl = $frontController->getParam('bootstrap')->getOption('wsdl');
        try {
            $soap = new Zend_Soap_Client($wsdl['kotearPagos']['encriptar']);            
            $args = array('Cad' => $cadena);
            $result = $soap->BlackBox($args);
            return $result->BlackBoxResult;
        } catch (Exception $e) {
            return false;
        }
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function  getpendienteDePago($idUsuario)
    {
        $dba = Zend_Registry::get('db');
        return $dba->fetchAll('EXEC KotearPagos_2:usp_ConsultarDetalleAPagar', array($idUsuario));
    }
}