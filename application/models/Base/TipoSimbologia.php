<?php
/**
 * @author ander
 *
 */
class Base_TipoSimbologia extends Devnet_Db_Table
{
    /**
     * @var unknown_type
     */
    protected $_name = 'KO_TIPO_SIMBOLOGIA';
    /**
     * @var unknown_type
     */
    protected $_primary = 'ID_TIPO_SIMBOLOGIA';
    
    public static function getSTipoSimbologia()
    {
        $cache = Zend_Registry::get('cache');
        $nameCache = 'cache_tabla_TipoSimbologia';
        if (!$result = $cache->load($nameCache)) {            
            $obj = new Base_TipoSimbologia();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_TIPO_SIMBOLOGIA','DESCRIPCION'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, $nameCache);
        }
        return $result;
    }
}