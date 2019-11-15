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
class Devnet_Db_Table 
    extends Zend_Db_Table 
{
    public $log;
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function __construct ( $config = array() , $definition = null) 
    { 
        parent::__construct($config, $definition);
        $this->log=Zend_Registry::get('log');
    } //end function
    
	/**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getNextId ($increment=1) 
    { 
        if (isset($this->_name) && isset($this->_primary)){
            return $this->getAdapter()->fetchOne("SELECT COALESCE(MAX({$this->_primary}),0)+{$increment} FROM {$this->_name}");
        }
        return 0;
    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function now() 
    { 
        return date('Y-m-d H:i:s');
    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function nowShort () 
    { 
        return date('Y-m-d');
    } //end function
    
}