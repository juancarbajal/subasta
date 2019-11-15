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
class Admin_SimbologiaController 
    extends App_Controller_Action_Admin
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
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::$m_mant_simbologia;
        
        $formFiltroBusqueda = new Application_Form_Simbologia();
        $formFiltroBusqueda->busqueda();
        
        $this->view->form = $formFiltroBusqueda;
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function buscarAction()
    {
        
        if (!$this->isAjax) {
            $this->_redirect($this->urlAdmin.'/diccionario-palabra');
        }
        
        $this->_helper->layout->disableLayout();
        
        $mPalabra = new Application_Model_Sp_Palabra();
        
        $dataSearch['K_TAG'] = $this->_getParam('a_tag', '');
        $dataSearch['K_PRIORIDAD'] = $this->_getParam('a_prioridad', '');
        $dataSearch['K_ORDEN'] = $this->_getParam('a_orden', '');
        $dataSearch['K_ESTADO'] = $this->_getParam('a_estado', '');
        
        $col = $this->_getParam('col', '');
        $ord = $this->_getParam('ord', 'DESC');
        
        $dataSearch['K_NUM_PAGINA'] = $page = $this->_getParam('page', 1);
        $dataSearch['K_NUM_REGISTROS'] = $this->_nroPagMin;
        
        $data = $mPalabra->getPaginacion($dataSearch);
        
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
        
        $idPalabra = $this->_getParam('f_id', '');
        
        $form = new Application_Form_DiccionarioPalabras();
        
        $mPalabra = new Application_Model_Sp_Palabra();
        
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
                    $dataSave['K_TAG'] = $valForm['f_tag'];
                    $dataSave['K_URL'] = $valForm['f_url'];
                    $dataSave['K_PRIORIDAD'] = $valForm['f_prioridad'];
                    $dataSave['K_ORDEN'] = $valForm['f_orden'];
                    $dataSave['K_ESTADO'] = $valForm['f_estado'];
                    
                    if (!empty($idPalabra)) {
                        $data = $mPalabra->actualizar($dataSave);
                    } else {
                        $data = $mPalabra->guardar($dataSave);
                    }
                    //var_dump($data);exit;
                    $isData = $data->K_ERROR;
                    $msg = $data->K_MSG;
                } catch (Exception $exc) {
                    //echo $exc->getMessage();exit;
                }
            }
        } else {
            
            $form->formulario();
            
            if (!empty($idPalabra)) {
                $data = $mPalabra->getPaginacion(array('K_ID_PALABRA' => $idPalabra));
                $arrayData['f_tag'] =  $data[0]->K_TAG;
                $arrayData['f_id'] =  $data[0]->K_ID_PALABRA;        
                $arrayData['f_url'] =  $data[0]->K_URL;
                $arrayData['f_prioridad'] =  $data[0]->K_PRIORIDAD;
                $arrayData['f_orden'] =  $data[0]->K_ORDEN;
                $arrayData['f_estado'] =  $data[0]->K_ESTADO;
                $form->setDefaults($arrayData);
            }
                
        }
        
        $this->view->titulo = empty($idPalabra)?'Agregar':'Editar';
        $this->view->isData = $isData;
        $this->view->msg = $msg;
        $this->view->form = $form;
    }
}