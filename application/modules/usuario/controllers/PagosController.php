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
require_once 'TransaccionCierre.php';
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

class Usuario_PagosController
    extends Devnet_Controller_Action
{

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function CargosPagadosAction()
    {
        $this->view->HeadTitle = ('Cargos Pagados | Kotear');
        $this->view->transacciones = $this->getTransacciones(
            $this->identity->ID_USR, $this->_request->getParam('filtro1'), 
            $this->_request->getParam('filtro2')
        );
        $this->view->filtro1=$this->_request->getParam('filtro1');
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');


    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function detalleestadoAction()
    {
        $this->_helper->layout->setLayout('clear');
        $this->view->transacciones = $this->getTransacciones($this->identity->ID_USR, 1);
        //echo $this->identity->ID_USR;
        /*$this->identity->ID_USR;
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        require_once 'KotearPagos.php';
        $kp = new KotearPagos();
        $this->view->detalle = $wkp['kotearPagos']['web'] . '/' . $kp->encriptar(6);*/
    }

    /**
     *  Paso 1: Ingreso de Datos a el formulario Registro
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getTransacciones ($idUsuario, $flag)
    {
        $transacciones = new TransaccionCierre();
        return $transacciones->getTransacciones($idUsuario, $flag);
    }

    function detalleTransaccionAction()
    {
        $this->_helper->layout->setLayout('clear');
        $transacciones = new TransaccionCierre();
        $result=$transacciones->getTransaccionesDetalle($this->_request->getParam('cod'));
        $this->view->result=$result;
    }

    function pendienteDePagoAction()
    {
        $transacciones = new TransaccionCierre();
        $this->view->HeadTitle = ('Pendiente de Pago | Kotear.pe');
        $this->view->transacciones = $transacciones->getpendienteDePago(
            $this->identity->ID_USR, $this->_request->getParam('filtro1')
        );
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        require_once 'KotearPagos.php';
        $mKotearPagos = new KotearPagos();
        $this->view->pagar = $wkp['kotearPagos']['web'] . '/' . 
            $mKotearPagos->encriptar($this->identity->ID_USR);
    }
    
    function cargosfacturarAction()
    {

    }
    
    function cargoshistoricosAction()
    {
        
    }
    
    function datosfacturarAction()
    {
        
    }
    
    function eligemediopagoAction()
    {

    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function kotearPagosEstadoCuentaAction()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        $this->view->rutaKotearPagos=$wkp['kotearPagosEstadoCuenta']['web'];
        require_once 'KotearPagos.php';
        $mKotearPagos = new KotearPagos();
        $this->view->idenc = $mKotearPagos->encriptar($this->identity->ID_USR);
    }

    public function kotearPagosPagoDestaquesAction()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        $this->view->rutaKotearPagos=$wkp['kotearPagosPagoDestaque']['web'];
        require_once 'KotearPagos.php';
        $this->view->idenc = $this->_request->getParam('idenc');
    }
}