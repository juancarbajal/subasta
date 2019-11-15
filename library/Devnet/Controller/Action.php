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
class Devnet_Controller_Action
    extends Zend_Controller_Action {

    public $db;
    public $identity;
    public $cache;
    public $session;
    public $utils;
    public $urlStatic;
    public $isAjax;

    /* Ander
     * Para el generar nro paginas por default para PAGINACION
     */
    protected $_nroPagMin = 30;

    /* Ander
     * Para el generar nro paginas por default para PAGINACION
     */
    protected $_nroRanMin = 5;
    
    /**
     * Acciones generales para los diversos controladores del sitio
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function init() {
        parent::init();
        //Inicializacion
        $this->db = (Zend_Registry::isRegistered('db'))?Zend_Registry::get('db'):null;
        $this->cache = (Zend_Registry::isRegistered('cache'))?Zend_Registry::get('cache'):null;
        $this->log = (Zend_Registry::isRegistered('log'))?Zend_Registry::get('log'):null;
        $this->mail = (Zend_Registry::isRegistered('mail'))?Zend_Registry::get('mail'):null;
        $this->session = (!isset($this->session))?new Zend_Session_Namespace('kotear'):null;
        $this->isAjax = $this->_request->isXmlHttpRequest();
        //print_r($this->session->auth);
        //exit;
        //new Zend_Session_Namespace('auth');
        //$this->identity= & Zend_Auth::getInstance()->getIdentity();
        //print_r($this->identity);die();
        $this->identity= $this->session->auth;
        $this->view->identity = $this->identity;
        $this->view->utils = new Devnet_Utils();
        $frontController = Zend_Controller_Front::getInstance();
        $wiki = $frontController->getParam('bootstrap')->getOption('wiki');
        $this->view->wikiUrl = $wiki['url'];
        $this->initTranslate();
        
    }

    /**
     * Acciones realizadas antes de la carga de la pagina en view
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function preDispatch() {
        parent::preDispatch();
        $this->initStyles();
        $adultos = $this->getRequest()->getModuleName() . '/'
                 . $this->getRequest()->getControllerName() . '/'
                 . $this->getRequest()->getActionName();
        if (($adultos != 'default/adultos/index') && ($adultos != 'default/busqueda/buscador')) {
                $this->session->toPage = $_SERVER['REQUEST_URI'];
        }
    }

    /**
     * Acciones realizadas despues de la carga total de la pagina en view
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function postDispatch() {
        parent::postDispatch();
        $adultos = $this->getRequest()->getModuleName() . '/'
                 . $this->getRequest()->getControllerName() . '/'
                 . $this->getRequest()->getActionName();
        if (($adultos != 'default/adultos/index') && ($adultos != 'default/busqueda/buscador')) {
            $this->session->fromPage = $_SERVER['REQUEST_URI'];
        }
    }

    /**
     * Permite adjuntar los estilos al sitio de acuerdo a la pagina
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function initStyles() {
        //$this->view->headLink()->appendStylesheet($this->view->baseUrl().'/f/css/global.css', 'screen');
        $request = $this->getRequest();
        $index = $this->getRequest()->getModuleName() . '/'
                . $this->getRequest()->getControllerName() . '/'
                . $this->getRequest()->getActionName();
        
        $this->view->headLink()->appendStylesheet($this->view->S(URL_STATIC . 'css/style.css'), 'all');
        
        if ($index != 'default/index/index') {
            //$this->view->headLink()->appendStylesheet($this->view->baseUrl().'/f/css/inside.css', 'screen');
        } //end if
        
    } // end function
    /**
     * Convierte una cadena en una url SEO
     * @param object $objeto Array de objetos
     * @param varchar $campo Campo del array que se desea cambiar
     * @param varchar $url Nuevo campo que se desea obtener con la url SEO
     * @uses Clase::methodo()
     * @return object Devuelve el mismo objeto adicionando el elemento $url
     */
    public function getUrlSeo($objeto, $campo, $url) {

        $filter = new Devnet_Filter_Alnum();

        foreach($objeto as $key => $obj):
        	$objeto[$key]->$url = $this->view->utils->convertSEO($obj->$campo);
        endforeach;

        return $objeto;
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function json($json) {
        $this->_helper->json($json);
    }

    /**
     * Captura el IP de la PC del cliente
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function getIp() {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function getFormsPath() {
        return APPLICATION_PATH.'/modules/' . $this->_request->getModuleName() . '/forms';
    }

    /**
     *
     * @param type name descç
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function  initTranslate() {
        $translate = new Zend_Translate('array', APPLICATION_PATH . '/configs/lang/es.php' ,'es');
        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Validate_Abstract::setDefaultTranslator($translate);
        Zend_Form::setDefaultTranslator($translate);
    } //end function
    
    /**
     * Ander
     * Retorna un objeto Zend_Config con los parámetros de la aplicación
     * 
     * @return Zend_Config
     */
    public function getConfig()
    {
        return Zend_Registry::get('config');
    }
    
    protected function _paginador($total = 100, $paginaActual = 1, $perPage = '', $pageRange = '') {
        $total = (integer) $total;
        $paginator = Zend_Paginator::factory($total);
        $paginaActual = empty($paginaActual)?1:$paginaActual;
        $perPage = empty($perPage)?$this->_nroPagMin:$perPage;
        $pageRange = empty($pageRange)?$this->_nroRanMin:$pageRange;
        return $paginator->setCurrentPageNumber($paginaActual)
                         ->setItemCountPerPage($perPage)
                         ->setPageRange($pageRange);
    }
}
