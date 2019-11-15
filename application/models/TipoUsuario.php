<?php
/**
 * @author ander
 *
 */
require_once('Base/TipoUsuario.php');

class TipoUsuario extends Base_TipoUsuario
{
    public static function getSTipoUsuario()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_STipoUsuario';
        if (!$result = $cache->load($cacheName)) {
            $obj = new TipoUsuario();
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