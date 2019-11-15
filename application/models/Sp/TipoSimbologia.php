<?php
/**
 * Admin class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Model
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
 * @package  Model
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class Application_Model_Sp_TipoSimbologia
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_TIPO_SIMBOLOGIA';
    protected $_primary = 'ID_TIPO_SIMBOLOGIA';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSTipoSimbologia()
    {
        $cache = Zend_Registry::get('cache');
        $nameCache = 'cache_tabla_TipoSimbologia';
        if (!$result = $cache->load($nameCache)) {            
            $obj = new Application_Model_Sp_TipoSimbologia();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_TIPO_SIMBOLOGIA','DESCRIPCION'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, $nameCache);
        }
        return $result;
    }
}