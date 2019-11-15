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
class Admin_CategoriaController 
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
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::$m_mant_categoria;
        
        $formFiltroBusqueda = new Application_Form_Categoria();
        $formFiltroBusqueda->busqueda();
        $this->view->form = $formFiltroBusqueda;
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function jsonCargarNivelesAction()
    {
        $f_id = $this->_getParam('f_id', '');
        
        if (!$this->_ajax || empty($f_id)) {
            $this->_redirect($this->urlAdmin.'/categoria');
        }
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $messages = Categoria::getSCategoriaByPadreId($f_id);
        $this->_response->appendBody(Zend_Json::encode($messages));
        
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function formAction()
    {
        if (!$this->_ajax) {
            $this->_redirect($this->urlAdmin.'/categoria');
        }
        
        $this->_helper->layout->disableLayout();
        
        $f_id = $this->_getParam('f_id', '');
        $f_padre_id = $this->_getParam('f_padre_id', '');
        $f_nivel = $this->_getParam('f_nivel', '');
        
        $mCategoria = new Categoria();
        $isData = -1;
        $msg = '';
        
        $form = new Admin_formCategoria();
        $form->formulario();
        $form->addNivel($f_nivel);
        
        if (!empty($f_id)) {
            $form->addId($f_id);
            $mDatos =  $mCategoria->getCategoriaById($f_id);
            $arrayData['f_id'] =  $mDatos->ID_CATEGORIA;
            $arrayData['f_titulo'] =  $mDatos->TIT;
            $arrayData['f_descripcion'] =  $mDatos->DES;
            $arrayData['f_adulto'] =  $mDatos->ADULTO;
            $arrayData['f_destaque'] =  $mDatos->APTA_DESTAQUE;
            $arrayData['f_visualiza'] =  $mDatos->VISUALIZAHOME;
            $arrayData['f_nivel'] =  $mDatos->NIVEL;
            $form->setDefaults($arrayData);
        } else {
            if ($f_nivel != 1) {
                $form->addPadreId($f_padre_id);                
            }
        }
        $form->addDecorador();
        if ($this->_request->isPost()) {
            $params = $this->getRequest()->getPost();
            if ($form->isValid($params)) {
                $valForm = $form->getValues();
                $k_TIT = $valForm['f_titulo'];
                $k_VISUALIZAHOME = empty($valForm['f_visualiza'])?0:1;
                $k_ADULTO = empty($valForm['f_adulto'])?0:1;
                $k_APTA_DESTAQUE = empty($valForm['f_destaque'])?0:1;
                $k_DES = $valForm['f_descripcion'];
                $k_EST = 1;
                
                if (empty($valForm['f_id'])) {
                    $k_ID_PADRE = $valForm['f_padre_id'];
                    $k_NIVEL = $valForm['f_nivel'];
                    $data = $mCategoria->guardar(
                        $k_TIT, $k_ID_PADRE, $k_VISUALIZAHOME, $k_ADULTO,
                        $k_APTA_DESTAQUE, $k_NIVEL, $k_DES, $k_EST
                    );
                } else {
                    $K_ID_CATEGORIA = $valForm['f_id'];
                    $data = $mCategoria->actualizar(
                        $K_ID_CATEGORIA, $k_TIT, $k_VISUALIZAHOME, $k_ADULTO,
                        $k_APTA_DESTAQUE, $k_DES, $k_EST
                    );
                }
                $isData = $data[0]->K_ERROR;
                $msg = $data[0]->K_MSG;
            } else {
                $isData = 1;
            }
        }
        $this->view->isData = $isData;
        $this->view->msg = $msg;
        $this->view->form = $form;
    }
    
}