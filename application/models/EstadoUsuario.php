<?php
require_once('Base/EstadoUsuario.php');
/**
 * @author ander
 *
 */
class EstadoUsuario 
    extends Base_EstadoUsuario
{
    public static function getSEstadoUsuario()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SEstadoUsuario';
        if (!$result = $cache->load($cacheName)) {
            $obj = new Base_EstadoUsuario();
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