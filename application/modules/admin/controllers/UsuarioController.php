<?php
/**
 * Admin class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */

/**
 * Admin class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class Admin_UsuarioController extends App_Controller_Action_Admin
{
    /**
     * Descripcion
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function indexAction()
    {
        $mUbigeo = new Application_Model_Sp_Ubigeo();
        $jsonProv = Zend_Json::encode($mUbigeo->getListJoson(2));
        $jsonDist = Zend_Json::encode($mUbigeo->getListJoson(3));
        $apJs = sprintf("var _ubiProv = %s, _ubiDist = %s", $jsonProv, $jsonDist);
        $this->view->headScript()->appendScript($apJs);
        
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::$m_mant_usuario;
        
        $formBusqueda = new Application_Form_Usuario();
        $formBusqueda->busqueda();
        $this->view->form = $formBusqueda;
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function buscarAction()
    {
        
        if (!$this->isAjax) {
            $this->_redirect($this->urlAdmin.'/usuario');
        }
        
        $this->_helper->layout->disableLayout();
        
        $mUsuario = new Application_Model_Sp_Usuario();
        
        $dataSearch['K_APODO'] = $this->_getParam('a_apodo', '');
        $dataSearch['K_ID_TIPO_DOC'] = $this->_getParam('a_tipDoc', '');
        $dataSearch['K_EMAIL'] = $this->_getParam('a_email', '');
        $dataSearch['K_NUM_DOC'] = $this->_getParam('a_nDoc', '');
        $dataSearch['K_ID_TIPO_USUARIO'] = $this->_getParam('a_tipoUsuario', '');
        $dataSearch['K_ID_EST_USUARIO'] = $this->_getParam('a_estadoUsuario', '');
        $col = $this->_getParam('col', '');
        $ord = $this->_getParam('ord', 'DESC');
        $utilfile = $this->_helper->getHelper('Fecha');
        $dataSearch['K_FECHA_INI'] = $utilfile->formatDb($this->_getParam('a_fecIni', ''));
        $dataSearch['K_FECHA_FIN'] = $utilfile->formatDb($this->_getParam('a_fecFin', ''), false);
        
        $dataSearch['K_NUM_PAGINA'] = $page = $this->_getParam('page', 1);
        $dataSearch['K_NUM_REGISTROS'] = $this->_nroPagMin;
        
        $data = $mUsuario->getPaginacion($dataSearch);
        
        $paginador = $this->apPaginador($data[0]->TOTAL, $page);
        
        $this->view->col = $col;
        $this->view->ord = $ord;
        $this->view->paginador = $paginador;
        $this->view->data = $data;
    }
    
    /**
     * El formulario solo es para actualizar
     * 
     * @return void
     */
    public function formAction()
    {
        if (!$this->isAjax) {
            $this->_redirect($this->urlAdmin.'/usuario');
        }
        
        $this->_helper->layout->disableLayout();
        
        $idUsuario = $this->_getParam('f_id', '');
        
        $form = new Application_Form_Usuario();
        
        $mUsuario = new Application_Model_Sp_Usuario();
        
        $isData = -1;
        $msg = '';
        
        if ($this->_request->isPost()) {
            //$params = $this->_getAllParams();
            //$params = $this->getRequest()->getParams();
            $params = $this->getRequest()->getPost();
            
            $options['K_ID_DEPARTAMENTO'] = $params['f_departamento'];
            $options['K_ID_PROVINCIA'] = $params['f_provincia'];

            $form->formulario($options);
            
            if ($form->isValid($params)) {
                try {
                    $valForm = $form->getValues();
                    $dataSave['K_ID_USUARIO'] = $valForm['f_id'];
                    $dataSave['K_NOM'] = $valForm['f_nombre'];
                    $dataSave['K_APEL'] = $valForm['f_apellidos'];
                    $dataSave['K_APODO'] = $valForm['f_apodo'];
                    $dataSave['K_EMAIL'] = $valForm['f_email'];
                    $dataSave['K_ID_TIPO_DOCUMENTO'] = $valForm['f_tipDoc'];
                    $dataSave['K_NUM_DOC'] = $valForm['f_nDoc'];
                    $dataSave['K_ID_TIPO_USUARIO'] = $valForm['f_tipoUsuario'];
                    $dataSave['K_ID_EST_USUARIO'] = $valForm['f_estadoUsuario'];
                    $dataSave['K_CLAVE'] = $valForm['f_clave'];
                    $dataSave['K_ID_UBIGEO'] = $valForm['f_distrito'];
                    $dataSave['K_NUM_TELEF1'] = $valForm['f_tel1'];
                    $dataSave['K_NUM_TELEF2'] = $valForm['f_tel2'];
                    $data = $mUsuario->actualizar($dataSave);
                    $isData = $data[0]->K_ERROR;
                    $msg = $data[0]->K_MSG;
                } catch (Exception $exc) {
                    //echo $exc->getMessage();exit;
                }
            } else {
                $msg = $form->auth_hash->getErrors();
                $msg = !empty($msg) ? ('Error: '.App_Msg::$tiempoAgotado) : '';
            }
        } else {
            
            $data = $mUsuario->getPaginacion(array('K_ID_USR' => $idUsuario));
            
            $options['K_ID_DEPARTAMENTO'] = $data[0]->ID_DEPARTAMENTO;
            $options['K_ID_PROVINCIA'] = $data[0]->ID_PROVINCIA;

            $form->formulario($options);

            $arrayData['f_apodo'] =  $data[0]->APODO;
            $arrayData['f_id'] =  $data[0]->ID_USR;        
            $arrayData['f_email'] =  $data[0]->EMAIL;
            $arrayData['f_clave'] =  $data[0]->CLAVE;
            $arrayData['f_nombre'] =  $data[0]->NOMBRE;
            $arrayData['f_apellidos'] =  $data[0]->APELLIDOS;
            $arrayData['f_tipDoc'] =  $data[0]->TIPO_DOCUMENTO;
            $arrayData['f_nDoc'] =  $data[0]->NUMERO_DOCUMENTO;
            $arrayData['f_tel1'] =  $data[0]->TELEFONO1;
            $arrayData['f_tel2'] =  $data[0]->TELEFONO2;
            $arrayData['f_tipoUsuario'] =  $data[0]->ID_TIPO_USUARIO;
            $arrayData['f_estadoUsuario'] =  $data[0]->ID_ESTADO_USUARIO;
            $arrayData['f_departamento'] =  $data[0]->ID_DEPARTAMENTO;
            $arrayData['f_provincia'] =  $data[0]->ID_PROVINCIA;
            $arrayData['f_distrito'] =  $data[0]->ID_DISTRITO;
            $form->setDefaults($arrayData);
        }
        $this->view->isData = $isData;
        $this->view->msg = $msg;
        $this->view->form = $form;
    }
    
}