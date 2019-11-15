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
class Application_Model_Sp_Categoria
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_CATEGORIA';
    protected $_primary = 'ID_CATEGORIA';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSCategoriaN1()
    {
        //$cache = Zend_Registry::get('cache');
        //$cacheName = 'cache_tabla_SCategoriaN1';
        //if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_Categoria();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array('ID_CATEGORIA','TIT'))
                ->where('NIVEL = ?', '1');
            $result = $dba->fetchPairs($sql);
            //$result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_NIVEL_QRY ?', array('1'));
            //$cache->save($result, $cacheName);
        //}
        return $result;
    }
}