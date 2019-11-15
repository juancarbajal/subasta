<?php
/**
 * @author ander
 *
 */
require_once('Base/IkItem.php');

class IkItem extends Base_IkItem
{
    
    /**
     * ander
     * Se utiliza para retornar valores de paginacion y datos de un usuario
     * @return type array
     */
    public function getPaginacion(
        $K_ID_MODULO, $K_NOMBRE, $K_LINK, $K_ESTADO
        )
    {
        $result = $this->getAdapter()->fetchAll(
            'EXEC IN_SP_ITEM_SEL ?, ?, ?, ?', array($K_ID_MODULO, $K_NOMBRE, $K_LINK, $K_ESTADO)
        );
        return $result;
    }
        
    /**
     * ander
     */
    public static function getSIkItem()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SIkItem';
        if (!$result = $cache->load($$cacheName)) {
            $obj = new IkItem();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_ITEM','DES'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, $$cacheName);
        }
        return $result;
    }
}