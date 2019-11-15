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
class Devnet_Time{ 
	protected $startTime;
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
	public function getTime () 
	{ 
		list($usec, $sec) = explode(" ",microtime());   
		return ((float)$usec + (float)$sec);   
	} //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
	public function startTime () 
	{ 
	  	$this->startTime=$this->getTime();
	} //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
	public function endTime () 
	{ 
	    return round($this->getTime()-$this->startTime,6);
	} //end function
	  
} //end class

?>