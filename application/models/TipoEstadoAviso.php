<?php
/**
 * @author ander
 *
 */
require_once('Base/TipoEstadoAviso.php');

class TipoEstadoAviso extends Base_TipoEstadoAviso
{
    public static function getSTipoEstadoAviso()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('cache_tabla_STipoEstadoAviso')) {
            $obj = new TipoEstadoAviso();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_TIPO_ESTADO','DESCRIPCION'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, 'cache_tabla_STipoEstadoAviso');
        }
        return $result;
    }
}