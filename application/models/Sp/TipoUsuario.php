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
class Application_Model_Sp_TipoUsuario 
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_TIPO_USUARIO';
    protected $_primary = 'ID_TIPO_USUARIO';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSTipoUsuario()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_STipoUsuario';
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_TipoUsuario();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_TIPO_USUARIO','DES'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, $cacheName);
        }
        return $result;
    }
}