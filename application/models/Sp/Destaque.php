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
class Application_Model_Sp_Destaque
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_DESTAQUE';
    protected $_primary = 'ID_DESTAQUE';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSDestaque()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SDestaque';
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_Destaque();
            $dba = $obj->getAdapter();
            $result = $dba->fetchPairs(
                "EXEC KO_SP_DESTAQUE_ACTIVO_QRY"
            );
            $cache->save($result, $cacheName);
        }
        return $result;
    }
}