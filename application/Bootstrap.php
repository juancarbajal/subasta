<?php
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
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Constructor de Bootstrap
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function __construct($app)
    {
        parent::__construct($app);
        //Autocargado de Clases
        /*
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setFallbackAutoloader(true);
		*/
        set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_PATH . '/../library') , realpath(APPLICATION_PATH . '/models') , get_include_path())));
        $app = $this->getOption('app');
        date_default_timezone_set($app['date_default_timezone']);
        //Inicializar Base de datos
        //$bootDb = $this->bootstrap('db');
        //$db = $bootDb->getResource('db');
        //$db = $this->bootstrap('multidb')->getDb('db');
        //$db = $this->bootstrap('multidb')->getResource('multidb')->getDb('db');
        //$db = $this->getPluginResource('db')->getDbAdapter();
        /*
        $this->bootstrap('multidb');
        $db = $this->getPluginResource('multidb')->getDb('db');
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Registry::set('db', $db);
        $kotearPagos = $this->getPluginResource('multidb')->getDb('pagos');
        $kotearPagos->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Registry::set('kotearPagos',$kotearPagos);
        */
        
        $this->bootstrap('multidb');
        $db = $this->getPluginResource('multidb')->getDb('db');
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db);

        $kotearPagos = $this->getPluginResource('multidb')->getDb('pagos');
        $kotearPagos->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Registry::set('kotearPagos', $kotearPagos);

        $KotearMigracion = $this->getPluginResource('multidb')->getDb('migra');
        $KotearMigracion->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Registry::set('KotearMigracion', $KotearMigracion);

        $bootSession = $this->bootstrap('session');

        Zend_Session::start();
//      $bootSession = $this->bootstrap('session');

        //Inicializar Cache
        $this->bootstrap('cachemanager');
        $cache = $this->getResource('cachemanager')->getCache('database');
        Zend_Registry::set('cache', $cache);
        //Inicializar Log
        $logConfig = $this->getOption('log');
        $logger = new Zend_Log();
        $logger->addWriter(new Zend_Log_Writer_Stream($logConfig['file']['error']));
        Zend_Registry::set('log', $logger);
        //Inicializar Correo Electronico
        $this->bootstrap('mail');
        Zend_Mail::setDefaultTransport($this->getResource('mail'));
        Zend_Registry::set('mail', new Zend_Mail('utf-8'));        
        
    }
    /**
     * Inicialización de Helper BaseUrl
     *
     * @return void
     */
    protected function _initBaseUrl()
    {
        //parent::_initBaseUrl();
        $app = $this->getOption('app');
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->getHelper('BaseUrl')->setBaseUrl($app['url']);
        
    }
    
    public function _initConfig()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->addHelperPath('App/View/Helper', 'App_View_Helper');
        
        $config = new Zend_Config($this->getOptions(), true);
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH.'/configs/pagoefectivo.ini', APPLICATION_ENV));
        //-->PRIVATE
        $inifile = APPLICATION_PATH."/configs/private.ini";
        if (is_readable($inifile)) {
            $config->merge(new Zend_Config_Ini($inifile));
        }
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/app.ini'));
        $config->merge(new Zend_Config_Ini(APPLICATION_PATH . '/configs/cs.ini'));
        $config->setReadOnly();
        Zend_Registry::set('config', $config);
        
        //$url_static = empty($config->app->url)? $this->view->baseUrl() . '/f/' : $config->app->staticurl;
        $url_static = $this->view->baseUrl() . '/f/';
        define('URL_SITE', $config->app->url);
        define('URL_STATIC', $url_static);
        define('URL_ELEMENTS', $config->fileshare->url);
        define('JOSON_MIN', $config->joson->min);
        
    }
    
    public function _initRoutes()
    {
        $this->bootstrap('frontController');
        $router = $this->getResource('frontController')->getRouter();
        $routeConfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/routes.ini');
        $router->addConfig($routeConfig);
    }
    
    protected function _initResponse()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->doctype(Zend_View_Helper_Doctype::XHTML1_RDFA);
        //$view->doctype(Zend_View_Helper_Doctype::XHTML1_TRANSITIONAL);
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
        $view->headMeta()->appendName("language", "es");
    }
    
    public function _initLogDb()
    {
        $logDb = new Zend_Log_Writer_Db($this->getPluginResource('multidb')->getDb('pagos'), 'logCron');
        $logDbFact = new Zend_Log_Writer_Db($this->getPluginResource('multidb')->getDb('pagos'), 'LogFacturacion');
        $logDbMed = new Zend_Log_Writer_Db($this->getPluginResource('multidb')->getDb('pagos'), 'logMediacion');
        Zend_Registry::set('logCron', $logDb);
        Zend_Registry::set('logFact', $logDbFact);
        Zend_Registry::set('logMed', $logDbMed);
    }

}
