<?php
/**
 * @author ander
 *
 */
require_once('Base/InTipoModulo.php');

class InTipoModulo 
    extends Base_InTipoModulo
{
    public static function getSInTipoModulo()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SInTipoModulo';
        if (!$result = $cache->load($cacheName)) {
            $obj = new InTipoModulo();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_TIPO_MODULO','DES'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, $cacheName);
        }
        return $result;
    }
} // end class
