<?php
/**
 * Plugin para Caching
 * 
 * @uses Zend_Controller_Plugin_Abstract
 */
class Devnet_Plugin_Caching extends Zend_Controller_Plugin_Abstract
{
    /**
     *  @var bool desactivar cache
     */
    //public static $doNotCache = false;
    public static $doCache = false;
    /**
     * @var Zend_Cache_Frontend
     */
    public $cache;
    
    /**
     * @var string Llave de cache
     */

    public $key;
    
    /**
     * Constructor: Inicializa el cache
     * 
     * @param  array|Zend_Config $options 
     * @return void
     * @throws Exception
     */
    public function __construct ($options)
    {
    	//echo get_class($options);exit;
    	if (get_class($options) == 'Zend_Cache_Core'){
    		$this->cache = $options;
    	} else {
	        if ($options instanceof Zend_Config) {
	            $options = $options->toArray();
	        }
	        if (! is_array($options)) {
	            throw new Exception('Invalid cache options; must be array or Zend_Config object');
	        }
	        if (array('frontend' , 'backend' , 'frontendOptions' , 'backendOptions') != array_keys($options)) {
	            throw new Exception('Invalid cache options provided');
	        }
	        $options['frontendOptions']['automatic_serialization'] = true;
	        $this->cache = Zend_Cache::factory($options['frontend'], $options['backend'], $options['frontendOptions'], $options['backendOptions']);
    	}
    }
    
    /**
     * Inicia Cache
     *
     * Determina si existe cache, Si existe lo retorna en caso contrario lo genera
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */   
	public function dispatchLoopStartup (Zend_Controller_Request_Abstract $request)
    {
        if (! $request->isGet()) {
            //self::$doNotCache = true;
            self::$doCache = false;
            return;
        }
        $path = $request->getPathInfo();
        $this->key = md5($path);
        // diferente
        if (false !== ($response = $this->getCache())) {
            $response->sendResponse();
            exit();
        }
    }
    
    /**
     * Almacenamiento de cache
     * 
     * @return void
     */
    public function dispatchLoopShutdown ()
    {
        //if (self::$doNotCache || $this->getResponse()->isRedirect() || (null === $this->key)) {
    	if ((!self::$doCache) || $this->getResponse()->isRedirect() || (null === $this->key)) {
            return;
        }
        $this->cache->save($this->getResponse(), $this->key);
    }
    
	/**
     * @return the $cache
     */
    function getCache ()
    {
        return $this->cache;
    }

	/**
     * @param $cache the $cache to set
     */
    function setCache ($cache)
    {
        $this->cache = $cache;
    }
}
