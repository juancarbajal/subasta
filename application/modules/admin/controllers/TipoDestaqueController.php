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
class Admin_TipoDestaqueController 
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
        Zend_Layout::getMvcInstance()->active = App_Controller_Action_Admin::$m_mant_destaque;
        
        $formFiltroBusqueda = new Application_Form_TipoDestaque();
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
}