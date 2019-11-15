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
class Application_Model_Sp_EstadoUsuario
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_ESTADO_USUARIO';
    protected $_primary = 'ID_TIPO_USUARIO';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSEstadoUsuario()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SEstadoUsuario';
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_EstadoUsuario();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_ESTADO_USUARIO','NOM'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, $cacheName);
        }
        return $result;
    }
}