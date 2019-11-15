<?php
require_once ('Base/MedioPago.php');
/**
 * @author jcarbajal
 *
 */
class MedioPago 
    extends Base_MedioPago
{
    public function getList ()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('medioPagoLista')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_MEDIO_PAGO_QRY');
            $cache->save($result, 'medioPagoLista');
        }
        return $result;
    }
    function getMedioDePagoAviso ($idAviso)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('medioPagoAviso'.$idAviso)) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_MEDIO_PAGO_IMG_QRY ?', array($idAviso));
            $cache->save($result, 'medioPagoAviso'.$idAviso);
        }
        return $result;
        //return $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_MEDIO_PAGO_IMG_QRY ?', array($idAviso));
    }
}
