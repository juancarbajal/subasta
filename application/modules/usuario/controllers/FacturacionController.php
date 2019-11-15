<?php
/**
 * Descripción Corta
 *
 * Descripción Larga
 *
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer el archivo LICENSE
 * @version    2.0
 * @since      Archivo disponible desde su version 1.0
 */
 /**
 * Descripción Corta
 * Descripción Larga
 * @category
 * @package
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 */
class Usuario_FacturacionController extends Devnet_Controller_Action
{
    
    function init()
    {
        parent::init();        
        if (!isset($this->identity->ID_USR)) {
            $this->session->requiereLoginUrl = $_SERVER['REQUEST_URI'];
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
        }
    }
    
    /**
     *  
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function indexAction ()
    {
        require_once 'Pagos.php';
        require_once APPLICATION_PATH . '/modules/usuario/forms/formUsuarioFacturacion.php';
        
        $mPagos = new Pagos();
        $form = new Usuario_formUsuarioFacturacion();
        $arrRender = new stdClass();
        
        $arrRender->tab = 'perfil';
        $arrRender->page = 'facturacion/_index';
        $arrRender->active = $this->getRequest()->getControllerName();
        
        $msg = '';
        
        $datosUsuraioFac = $mPagos->getDatosClienteFac($this->identity->ID_USR);
        $validador = (empty($datosUsuraioFac));
        //var_dump($datosUsuraioFac);exit;
        
        if ($this->_request->isPost() && $validador) {
            $allParams  = $this->getRequest()->getPost();
            if ($form->isValid($allParams)) {
                $datos = $form->getValues();
                
                $zfFilterAlNum = new Zend_Filter_Alnum();
                $zfFilterAlNum->setAllowWhiteSpace(TRUE);
                
                $input['K_ID_USR'] = $this->identity->ID_USR;
                $input['K_TIPO_DOCUMENTO'] = 2;//;$datos['document_type'];
                $input['K_NUM_DOCUMENTO'] = $datos['document_number'];
                $input['K_RAZON_SOCIAL'] = trim($datos['customer_name']);
                $input['K_NOMBRE'] = $zfFilterAlNum->filter(trim($this->identity->NOM));
                $input['K_APE_PATERNO'] = $zfFilterAlNum->filter(trim($this->identity->APEL));
                $input['K_APE_MATERNO'] = $zfFilterAlNum->filter(trim($this->identity->APEL));
                $input['K_DIRECCION'] = $datos['address'];
                
                $flagDatos = $mPagos->guardarDatosFacturacion($input);
                
                $form->disabledAll();
                
                $msg = ($flagDatos == '1')
                    ?'Mensaje: Se Guardo Satisfactoriamente.':'Error: No se pudo Guardar.';
                
            } else {
                $msg = "Error: No se pudo Guardar.";
            }
            
        } else {
            if ($validador) {
                if ($this->identity->ID_TIPO_DOC == '05') {
                    $datosUsuario['document_type'] = 2;
                } elseif ($this->identity->ID_TIPO_DOC == '07') {
                    $datosUsuario['document_type'] = 1;
                }
                $datosUsuario['document_number'] = $this->identity->NRO_DOC;
                $form->addButton();
            } else {
//                var_dump($datosUsuraioFac);exit;
                $datosUsuario['customer_name'] = $datosUsuraioFac->RazonSocial;
                if ($datosUsuraioFac->RUC != "") {
                    $datosUsuario['document_number'] = $datosUsuraioFac->RUC;
                    $datosUsuario['document_type'] = 1;
                } elseif ($datosUsuraioFac->DNI != "") {
                    $datosUsuario['document_number'] = $datosUsuraioFac->DNI;
                    $datosUsuario['document_type'] = 2;
                }
    //                $datosUsuario['NRO_DIRECCION'] = $datosUsuraioFac->Numero;
    //                $datosUsuario['COD_DPTA'] = $datosUsuraioFac->IdDepartamento;
    //                $datosUsuario['NOM_DPTA'] = $datosUsuraioFac->NombreDepartamento;
    //                $datosUsuario['COD_DIST'] = $datosUsuraioFac->IdDistrito;
    //                $datosUsuario['NOM_DIST'] = $datosUsuraioFac->NombreDistrito;

                $datosUsuario['address'] = $datosUsuraioFac->Direccion;
                $form->setDefaults($datosUsuario);
                $form->disabledAll();
            }
            
        }

        $this->view->msg = $msg;
        $this->view->form = $form;
        $this->view->arrRender = $arrRender;
    }
    
} // end class
