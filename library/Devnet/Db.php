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
class Devnet_Db extends Zend_Db_Adapter_Pdo_Mssql{
	protected $_cacheId;
	/**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
	function __construct()
	{
		parent::__construct();
		$this->_cacheId=array();
	}
	/**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
	public function fetchAllCache($sql, $bind = array(), $fetchMode = null)
	{
		$newKey=time();
		$cache=Zend_Registry::get('cache');
		$sqlUpper=strtoupper($sql);
		$key=array_search($sqlUpper,$this->_cacheId);
		if ($key===false){ //Si no esta en cache
			$data=$this->db->fetchAll($sql,$bind,$fetchMode);
			$cache->save($data,$newKey);
			$this->_cacheId[$newKey]=$sqlUpper;
		} else { //Si esta en cache
			if (!$data=$cache->load($key)){
				$data=$this->db->fetchAll($sql,$bind,$fetchMode);
				$cache->save($data,$newKey);
			} 
		}
		return $data;
	}
}
?>