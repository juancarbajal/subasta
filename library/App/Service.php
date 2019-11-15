<?php
abstract class App_Service
{
    protected $_options = array();
    protected static $_instance;
    /*
     * Ejecutar el servicio
     */
    public function _loadService($service, $data)
    {
        $soap = new SoapClient($this->_options['url']);
        try {
            $info = $soap->$service($data);
                Zend_Registry::get('logCron')->write(array(
                    'metodo' => $service,
                    'request' => json_encode($data),
                    'response' => json_encode((array)$info),
                    'url' => $this->_options['url']
                ));
        } catch (Exception $exc) {
            Zend_Registry::get('logCron')->write(array(
                'metodo' => $service,
                'mensaje' => $exc->getMessage(),
                'trace' => $exc->getTraceAsString(),
                'url' => $this->_options['url']
            ));
        }
        return $info;
    }
    /*
     * Extraer una instancia de la aplicación
     * @param string $securityPath Carpeta donde se almacenan public.key y private.key
     * @return PagoEfectivo retorna la instancia de la clase
     */
    final public static function getInstance($options = null)
    {
        $class = static::getClass();
        if (!isset(static::$_instance[$class])){	    
            static::$_instance[$class] = new $class($options);
        }
        return static::$_instance[$class];
    }
    /*
     * Captura el nombre de la clase
     */
    final public static function getClass(){
        return get_called_class();
    }
    public function getOptions()
    {
        return $this->_options;
    }
    
    private function getLog()
    {
        
    }
}