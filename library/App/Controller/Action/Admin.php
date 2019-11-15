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
class App_Controller_Action_Admin 
    extends App_Controller_Action
{
    public static $m_mant_usuario = array(
        'controller'=>'usuario', 'title'=>'Administración Usuarios Portal', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_categoria = array(
        'controller'=>'categoria', 'title'=>'Administración Categorías', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_item = array(
        'controller'=>'item', 'title'=>'Administración de Item', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_item_especial = array(
        'controller'=>'item', 'title'=>'Administración de Item', 'seccion'=>'mantenimiento',
        'nivel2' => array('action'=>'especial', 'title'=>'Agregar registro - Especial')
    );
    public static $m_mant_modulo = array(
        'controller'=>'modulo', 'title'=>'Administración de Modulos', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_apercibi = array(
        'controller'=>'apercibimiento', 'title'=>'Motivos Apercibimiento', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_simbologia = array(
        'controller'=>'simbologia', 'title'=>'Simbología', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_destaque = array(
        'controller'=>'tipo-destaque', 'title'=>'Tipo de Destaque', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_moderable = array(
        'controller'=>'texto-moderable', 'title'=>'Texto Moderable', 'seccion'=>'mantenimiento'
    );
    public static $m_mant_dolar = array(
        'controller'=>'dolar', 'title'=>'Mantenimiento Dolar', 'seccion'=>'mantenimiento'
        );
    public static $m_mant_diccionario = array(
        'controller'=>'diccionario-palabra', 'title'=>'Diccionario de Palabras', 'seccion'=>'mantenimiento'
    );
    
    public static $seccion = array('mantenimiento'=>array('title'=>'Mantenimiento'));
    
    /*
     * Para el generar nro paginas por default para PAGINACION
     */
    protected $apgNroPagMin = 10;

    /*
     * Para el generar nro paginas por default para PAGINACION
     */
    protected $apgNroRanMin = 10;
    
    public $urlAdmin;
    
    protected $isAjax;
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
        
        $this->isAjax = $this->_request->isXmlHttpRequest();
        $this->urlAdmin = $this->view->baseUrl().'/admin';
        
        define('URL_SITE_ADMIN', $this->urlAdmin);
        
        $dataAdmin = $this->auth['admin'];
        if (empty($dataAdmin)) {
            if ($this->isAjax) {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender();
                $this->logout();
                $this->_response->clearBody();
                $this->_response->clearHeaders();
                $this->_response->setHttpResponseCode(401);
            } else {
                $this->_helper->layout->setLayout('admin/default');
                $this->logout();
                $controllerName = $this->getRequest()->getControllerName();
                if ($controllerName != 'auth') {
                    $this->_redirect('/admin/auth');
                }
            }
        } else {
            $this->_helper->layout->setLayout('admin/main');
            $controllerName = $this->getRequest()->getControllerName();
            $moduleName = $this->getRequest()->getModuleName();
            $actionName = $this->getRequest()->getActionName();
            if ($controllerName == 'auth' && $actionName=='index') {
                $this->_redirect('/admin/index');
            }
            if (!empty($this->auth['opciones'])) {
                $options = $this->auth['opciones']; $validopt = false;
                foreach ($options as $opt) {
                    $menu[$opt->ID_MODULO][$opt->ID_OPCION] = array(
                        'MODULO'=>$opt->MODULO, 'CONTROLADOR'=>$opt->CONTROLADOR, 'TIT'=>$opt->TIT
                    );
                    if ($opt->MODULO==$moduleName && $opt->CONTROLADOR==$controllerName) {
                        $validopt = true;
                    }
                }
                Zend_Layout::getMvcInstance()->assign('authMenu', $menu);
                
                //var_dump($this->getRequest()->getParams(),$validopt); exit;
                if (!$validopt && $actionName<>'logout') {
                    $this->_redirect($options[0]->MODULO.'/'.$options[0]->CONTROLADOR);
                }
            }
        }
        
        $this->initStyles();
        
    }
    
    /**
     * Permite adjuntar los estilos al sitio de acuerdo a la pagina
     * 
     * @return void
     */
    public function initStyles()
    {
        $this->view->headLink()->appendStylesheet(
            $this->view->S(URL_STATIC . 'css/bootstrap/bootstrap.css'), 'all'
        );
        $this->view->headLink()->appendStylesheet($this->view->S(URL_STATIC . 'css/admin.css'), 'all');
    }
    
    /**
     * Descripcion
     * 
     * @param int $total        Variables
     * @param int $paginaActual Variables
     * @param int $perPage      Variables
     * @param int $pageRange    Variables
     * 
     * @return void
     */
    public function apPaginador($total = 100, $paginaActual = 1, $perPage = 10, $pageRange = 10)
    {
        $total = (integer) $total;
        $paginator = Zend_Paginator::factory($total);
        $paginaActual = empty($paginaActual)?1:$paginaActual;
        $perPage = empty($perPage)?$this->apgNroPagMin:$perPage;
        $pageRange = empty($pageRange)?$this->apgNroRanMin:$pageRange;
        return $paginator->setCurrentPageNumber($paginaActual)
            ->setItemCountPerPage($perPage)
            ->setPageRange($pageRange);
    }
    
}
