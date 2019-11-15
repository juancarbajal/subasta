<?php
require_once 'Base/Moneda.php';
/**
 * @author jcarbajal
 *
 */
class Moneda
    extends Base_Moneda
{
    
    public function getList ()
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('MonedasLista')) {
            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_MONEDA_LIST');
            $cache->save($result, 'MonedasLista');
        }
        return $result;
    }

    public function getBusquedaList ()
    {
        return $this->getList();
    }

}