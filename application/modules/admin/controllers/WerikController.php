<?php
/**
 * @author ander
 *
 */
require_once 'Usuario.php';

class Admin_WerikController extends App_Controller_Action
{
    public function init() {
        parent::init();
    }
    
    public function indexAction()
    {
        //Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::$m_mant_usuario;
        
//        $this->view->headScript()->appendFile(
//            $this->libSV->s($this->urlStatic.'/js/admin/afiltroBusqueda.js')
//        );
        
        /*$formBusqueda = new Application_Form_Admin_Usuario();
        $formBusqueda->busqueda();
        $this->view->form = $formBusqueda;*/
        $this->_helper->layout()->disableLayout();
    }
    
    public function layoutAction()
    {
        $this->_helper->layout()->disableLayout();
    }
    
    public function buscarAction()
    {
        if (!$this->_ajax) $this->_redirect($this->urlAdmin.'/usuario');
        
        $this->_helper->layout->disableLayout();
        $a_apodo = $this->_getParam('a_apodo', '');
        $a_tipDoc = $this->_getParam('a_tipDoc', '');
        $a_email = $this->_getParam('a_email', '');
        $a_nDoc = $this->_getParam('a_nDoc', '');
        $a_tipoUsuario = $this->_getParam('a_tipoUsuario', '');
        $a_estadoUsuario = $this->_getParam('a_estadoUsuario', '');
        $a_fecIni = $this->_fechaDb->convert($this->_getParam('a_fecIni', ''));
        $a_fecFin = $this->_fechaDb->convert($this->_getParam('a_fecFin', ''), false);
        
        $this->view->col = $this->_getParam('col', '');
        $this->view->ord = $this->_getParam('ord', 'DESC');
        
        $page = $this->_getParam('page', 1);
        $mUsuario = new Usuario();
//        echo $a_fecIni.PHP_EOL.$a_fecFin;exit;
        $data = $mUsuario->getPaginacion(
            $page, $this->_nroPagMin, '', $a_apodo, $a_tipDoc,
            $a_email, $a_nDoc, $a_tipoUsuario, $a_estadoUsuario, '',
            $a_fecIni, $a_fecFin
        );
        $paginador = $this->_paginador($data[0]->TOTAL, $page);
        $this->view->paginador = $paginador;
        $this->view->data = $data;
    }
    
    /**
     * El formulario solo es para actualizar
     */
    public function formAction()
    {
        if (!$this->_ajax) { $this->_redirect($this->urlAdmin.'/usuario');}
        $this->_helper->layout->disableLayout();
        
        $id = $this->_getParam('f_id', '');
        
        $form = new Application_Form_Admin_Usuario();
        $form->formulario();
        
        $mUsuario = new Usuario();
        $data = $mUsuario->getPaginacion('', '', $id);
        $isData=-1;
        $msg='';
        
        if ($this->_request->isPost()) {
//            $params = $this->_getAllParams();
//            $params = $this->getRequest()->getParams();
            $params = $this->getRequest()->getPost();
            if ($form->isValid($params)) {
                $isData=0;
            } else {
                $valForm = $form->getValues();
                $K_ID_USUARIO = $valForm['f_id'];
                $K_NOM = $valForm['f_nombre'];
                $K_APEL = $valForm['f_apellidos'];
                $K_APODO = $valForm['f_apodo'];
                $K_EMAIL = $valForm['f_email'];
                $K_ID_TIPO_DOCUMENTO = $valForm['a_tipDoc'];
                $K_NUM_DOC = $valForm['a_nDoc'];
                $K_ID_TIPO_USUARIO = $valForm['f_tipoUsuario'];
                $K_ID_EST_USUARIO = $valForm['f_estadoUsuario'];
                $K_CLAVE = $valForm['f_clave'];
                $K_ID_UBIGEO = $valForm['f_provincia'];
                $K_NUM_TELEF1 = $valForm['f_tel1'];
                $K_NUM_TELEF2 = $valForm['f_tel2'];
                        
                $data = $mUsuario->guardar(
                    $K_ID_USUARIO, $K_NOM, $K_APEL, $K_APODO, $K_EMAIL, $K_ID_TIPO_DOCUMENTO, 
                    $K_NUM_DOC, $K_ID_TIPO_USUARIO, $K_ID_EST_USUARIO, $K_CLAVE, $K_ID_UBIGEO,
                    $K_NUM_TELEF1, $K_NUM_TELEF2
                );
//                $isData=10;
                $isData = $data[0]->K_ERROR;
                $msg = $data[0]->K_MSG;
//                var_dump($data);exit;
                
            }
        } else {
            $form->setProvincia($data[0]->DEPARTAMENTO);
            $arrayData['f_apodo'] =  $data[0]->APODO;
            $arrayData['f_id'] =  $data[0]->ID_USR;        
            $arrayData['f_email'] =  $data[0]->EMAIL;
            $arrayData['f_clave'] =  $data[0]->CLAVE;
            $arrayData['f_nombre'] =  $data[0]->NOMBRE;
            $arrayData['f_apellidos'] =  $data[0]->APELLIDOS;
            $arrayData['a_tipDoc'] =  $data[0]->TIPO_DOCUMENTO;
            $arrayData['a_nDoc'] =  $data[0]->NUMERO_DOCUMENTO;
            $arrayData['f_tel1'] =  $data[0]->TELEFONO1;
            $arrayData['f_tel2'] =  $data[0]->TELEFONO2;
            $arrayData['f_tipoUsuario'] =  $data[0]->ID_TIPO_USUARIO;
            $arrayData['f_departamento'] =  $data[0]->DEPARTAMENTO;
            $arrayData['f_estadoUsuario'] =  $data[0]->ID_ESTADO_USUARIO;
            $arrayData['f_provincia'] =  $data[0]->ID_PROV;
            $form->setDefaults($arrayData);
        }
        $this->view->isData = $isData;
        $this->view->msg = $msg;
        $this->view->form = $form;
    }
    
}