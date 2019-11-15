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
class Devnet_Config_Ini
extends Zend_Config_Ini
{ 
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function __construct (string $filename,  $section = null,  $options = false) 
    { 
        //Revisar si el Cache puede guardar un Objeto con sus metodos
        $cache=Zend_Registry::get('cache');
        if (isset($cache)){
            if (!$result=$this->cache->load($filename)){
                return $result;
            } else {
                return parent::__construct($filename,$section,$options);
            }
        }
    } //end function
    
} //end class

?>