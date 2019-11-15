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
class Application_Model_Sp_InTipoModulo
    extends App_Db_Table_Abstract
{
    protected $_name = 'IN_TIPO_MODULO';
    protected $_primary = 'ID_TIPO_MODULO';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSInTipoModulo()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SInTipoModulo';
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_InTipoModulo();
            $dba = $obj->getAdapter();
            $result = $dba->fetchPairs(
                "EXEC IN_SP_CMB_TIPO_MODULO_SEL"
            );
            $cache->save($result, $cacheName);
        }
        return $result;
    }
}