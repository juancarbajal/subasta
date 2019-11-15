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
class Admin_ItemController extends App_Controller_Action_Admin
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
        $jsonModulo = Zend_Json::encode(Application_Model_Sp_IkModulo::getSIkModulo());
        $apJs = sprintf("var _dataModulo = %s", $jsonModulo);
        $this->view->headScript()->appendScript($apJs);
        
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::$m_mant_item;
        
        $formBusqueda = new Application_Form_InItem();
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
        $this->_helper->layout->disableLayout();
        
        if (!$this->isAjax) {
            $this->_redirect($this->urlAdmin.'/item');
        }
        
        $params = $this->getRequest()->getParams();
        
        $tipoModulo = $this->_getParam('a_tipoModulo', '');
        
        $dataSearch['K_ID_MODULO'] = $params['a_modulo'];
        $dataSearch['K_NAME_ITEM'] = $params['a_nombreItem'];
        $dataSearch['K_LINK'] = $params['a_link'];
        $dataSearch['K_ID_AVISO'] = $this->_getParam('a_codigoAviso', '');
        $dataSearch['K_ESTADO'] = $params['a_activo'];
        $dataSearch['K_NUM_PAGINA'] = $page = $this->_getParam('page', 1);
        $dataSearch['K_NUM_REGISTROS'] = $this->_nroPagMin;
        
        if ($tipoModulo == 1) { //Enlace
            $mInModuloAviso = new Application_Model_Sp_IkItem();
            $data = $mInModuloAviso->getPaginacion($dataSearch);
            $paginador = $this->apPaginador($data[0]->TOTAL, $page);
        } elseif ($tipoModulo == 2) { //Especial
            $mInModuloAviso = new Application_Model_Sp_InModuloAviso();
            $data = $mInModuloAviso->getPaginacion($dataSearch);
            $paginador = $this->apPaginador($data[0]->TOTAL, $page);
        }
        $this->view->tipoModulo = $tipoModulo;
        $this->view->paginador = $paginador;
        $this->view->data = $data;
        
    }
    
    /**
     * El formulario solo es para actualizar y agregar
     * 
     * @return void
     */
    public function formAction()
    {
        $this->_helper->layout->disableLayout();
        
        if (!$this->isAjax) {
            $this->_redirect($this->urlAdmin.'/usuario');
        }
        
        $idPk = $this->_getParam('f_id', '');
        $tipoModulo = $this->_getParam('tipo', '');
        
        $form = new Application_Form_InItem();
        
        $isData = -1;
        $msg = '';
        
        if ($tipoModulo == 1) { //Enlace
            $form->formularioEnlaces($tipoModulo);

        } elseif ($tipoModulo == '2' && !empty($idPk)) { //Especial
            $form->formularioEspecialActualizar($tipoModulo);
        }
        
        if ($this->_request->isPost()) {
            
            $params = $this->getRequest()->getPost();
            
            if ($form->isValid($params)) {
                try {
                    $valForm = $form->getValues();
                    
                    $dataSave['K_ESTADO'] = $valForm['f_estado'];
                    $dataSave['K_ID_MODULO'] = $valForm['f_modulo'];
                    if ($tipoModulo == 1) { //Enlace
                        $mModel = new Application_Model_Sp_IkItem();
                        $dataSave['K_ID_ITEM'] = $valForm['f_id'];
                        
                        $dataSave['K_NOMBRE'] = $valForm['f_nombre'];
                        $dataSave['K_ORDEN'] = $valForm['f_orden'];
                        $dataSave['K_LINK'] = $valForm['f_link'];
                    } elseif ($tipoModulo == '2') { //Especial
                        $mModel = new Application_Model_Sp_InModuloAviso();
                        $dataSave['K_ID_MODULO_AVISO'] = $valForm['f_id'];
                    } else {
                        throw new Exception("No tiene tipo");
                    }
                    
                    if (!empty($idPk)) {
                        $data = $mModel->actualizar($dataSave);
                    } else {
                        $data = $mModel->guardar($dataSave);
                    }
                    
                    $isData = $data->K_ERROR;
                    $msg = $data->K_MSG;
                } catch (Exception $exc) {
                    //echo $exc->getMessage();exit;
                }
            } else {
                $msg = $form->auth_hash->getErrors();
                $msg = !empty($msg) ? ('Error: '.App_Msg::$tiempoAgotado) : '';
            }
        } else {
            if ($tipoModulo == 1 && !empty($idPk)) { //Enlace
                $mModel = new Application_Model_Sp_IkItem();
                $data = $mModel->getPaginacion(array('K_ID_ITEM' => $idPk));
                $data = $data[0];
                $arrayData['f_id'] =  $data->K_ID_ITEM;
                $arrayData['f_nombre'] =  $data->K_DESC;
                $arrayData['f_orden'] =  $data->K_ORDEN;        
                $arrayData['f_modulo'] =  $data->K_ID_MODULO;
                $arrayData['f_link'] =  $data->K_LINK;
                $arrayData['f_estado'] =  $data->K_ID_ESTADO;
                $form->setDefaults($arrayData);
            } elseif ($tipoModulo == '2' && !empty($idPk)) { //Especial
                $mModel = new Application_Model_Sp_InModuloAviso();
                
                $data = $mModel->getPaginacion(array('K_ID_MODULO_AVISO' => $idPk));
                $data = $data[0];
                $arrayData['f_id'] =  $data->K_ID_MODULO_AVISO;
                $arrayData['f_modulo'] =  $data->K_ID_MODULO;
                $arrayData['f_estado'] =  $data->K_COLOCADO;
                $arrayData['f_hdesc'] =  $data->K_ID_AVISO;
                $arrayData['f_hname'] =  $data->K_ID_MODULO_AVISO;
                $form->setDefaults($arrayData);
            }
            
        }
        
        $this->view->titulo = empty($idPk)?'Agregar':'Editar';
        $this->view->isData = $isData;
        $this->view->msg = $msg;
        $this->view->form = $form;
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function especialAction()
    {
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::$m_mant_item_especial;
        $formBusqueda = new Application_Form_InItem();
        $formBusqueda->busquedaEspecial();
        $this->view->form = $formBusqueda;
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function especialBuscarAction()
    {
        $this->_helper->layout->disableLayout();
        
        if (!$this->isAjax) {
            $this->_redirect($this->urlAdmin.'/item');
        }
        
        $params = $this->getRequest()->getParams();
        
        $form = new Application_Form_InItem();
        
        $dataSearch['K_ID_AVISO'] = $params['a_codigoAviso'];
        $dataSearch['K_APODO'] = $params['a_apodo'];
        //$dataSearch['K_NAME_ITEM'] = $params['a_tipoUsuario'];
        $dataSearch['K_ID_DESTAQUE'] = $params['a_tipoDestaque'];
        
        $utilfile = $this->_helper->getHelper('Fecha');
        $dataSearch['K_FECHA_INI'] = $utilfile->formatDb($this->_getParam('a_fecIni', ''), true, 180);
        $dataSearch['K_FECHA_FIN'] = $utilfile->formatDb($this->_getParam('a_fecFin', date("d/m/y")), false);
        
        $dataSearch['K_NUM_PAGINA'] = $page = $this->_getParam('page', 1);
        $dataSearch['K_NUM_REGISTROS'] = $this->_nroPagMin;
        
        $mAviso = new Application_Model_Sp_Aviso();
        $data = $mAviso->getPaginacionModerar($dataSearch);
        $paginador = $this->apPaginador($data[0]->TOTAL, $page);
        
        $fileshare = $this->getConfig()->fileshare->toArray();
        
        $form->formularioEspecialNuevo();
        $this->view->form = $form;
        $this->view->paginador = $paginador;
        $this->view->data = $data;
        $this->view->ruta = $fileshare['url'].'/'.$fileshare['thumbnails'].'/';
        
    }
    
    /**
     * El formulario parar guardar especial
     * 
     * @return void
     */
    public function especialFormAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        if (!$this->isAjax) {
            $this->_redirect($this->urlAdmin.'/usuario');
        }
        
        $form = new Application_Form_InItem();
        $mModel = new Application_Model_Sp_InModuloAviso();
        $isError = 1;
        $msg = "";
        if ($this->_request->isPost()) {
            $params = $this->getRequest()->getPost();
            
            if ($form->isValid($params)) {
                try {
                    //$valForm = $form->getValues();
                    $valForm = $params;
                    
                    $dataSave['K_ESTADO'] = 1;
                    $dataSave['K_ID_MODULO'] = $valForm['f_modulo'];
                    
                    $dataSave['K_IDS_AVISO'] = $valForm['f_idAviso'];
                    $data = $mModel->guardar($dataSave);
                                        
                    $isError = $data->K_ERROR;
                    $msg = $data->K_MSG;
                } catch (Exception $exc) {
                    //echo $exc->getMessage();exit;
                }
            }
            
            $envio['isError'] = $isError;
            $envio['msg'] = $msg;
            $this->_helper->json($envio);
        }
    }
    
}