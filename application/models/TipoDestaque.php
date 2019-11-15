<?php
require_once 'Base/TipoDestaque.php';
/**
 * @author njara
 *
 */
class TipoDestaque
    extends Base_TipoDestaque
{
    public function getTipoDestaque ($idTipoDestaque)
    {
        return $this->getAdapter()->fetchAll(
            'EXEC KO_SP_TIPODESTAQUE_DESTAQUE_QRY ?', array($idTipoDestaque)
        );
    }
}