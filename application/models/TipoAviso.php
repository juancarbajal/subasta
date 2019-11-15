<?php
require_once 'Base/TipoAviso.php';
/**
 * @author jcarbajal
 *
 */
class TipoAviso
    extends Base_TipoAviso
{
    public function getTipoAvisoDuracion ($idTipoAviso)
    {
        //$cache = Zend_Registry::get('cache'); 
        //if (! $result = $cache->load('tipoAvisoDuracionLista'.$idTipoAviso)){
        $result = $this->getAdapter()->fetchAll(
            'EXEC KO_SP_DURACION_TIPO_AVISO_QRY ?', array($idTipoAviso)
        );
        //  $cache->save($result, 'tipoAvisoDuracionLista'.$idTipoAviso);
        //}
        return $result;
    }
}