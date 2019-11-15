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
class Application_Model_Sp_TipoDocumento 
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_TIPO_DOC';
    protected $_primary = 'ID_TIPO_DOC';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSTipoDocumento()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('cache_tabla_STipoDocumento')) {
            $obj = new Application_Model_Sp_TipoDocumento();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_TIPO_DOC','DES_CORTA'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, 'cache_tabla_STipoDocumento');
        }
        return $result;
    }
}