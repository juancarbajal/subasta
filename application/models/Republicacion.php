<?php
require_once 'Base/Republicacion.php';
/**
 * @author jcarbajal
 *
 */
class Republicacion extends Base_Republicacion
{
    /**
     * Lista de Republicaciones 
     */
    function getList()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('republicacionLista')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_REPUBLICACION_LIST');
            $cache->save($result, 'republicacionLista');
        }
        return $result;
    }
    function masiva ($avisos, $idUsuario)
    { 
       return $this->getAdapter()->fetchRow(
           'EXEC KO_SP_AVISO_REPUBLICACION_MASIVA_RET ?, ?', array ($avisos, $idUsuario)
       );
    } //end function masiva
    
}