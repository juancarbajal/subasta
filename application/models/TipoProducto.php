<?php
require_once 'Base/TipoProducto.php';
/**
 * @author jcarbajal
 *
 */
class TipoProducto
    extends Base_TipoProducto
{
    /**
     * Lista de productos
     */
    function getList ()
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('TipoProductoLista')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_TIPO_PRODUCTO_LIST');
            $cache->save($result, 'TipoProductoLista');
        }
        return $result;
    }
}