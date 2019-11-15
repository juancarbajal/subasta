<?php
require_once 'Base/Seguimiento.php';
/**
 * @author jcarbajal
 *
 */
class Seguimiento 
    extends Base_Seguimiento
{
    /**
     * Iniciamos seguimiento de un usuario sobre determinado aviso
     * @param integer $idUsuario
     * @param intege $idAviso
     * @return array Resultado de proceso
     */
    function iniciarSeguimiento ($idUsuario, $idAviso)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXEC KO_SP_SEGUIMIENTO_INI_INS ?, ?', array($idUsuario, $idAviso)
        );
        return $result[0];
    }

    /**
     * Consulta si usuario da seguimiento a un aviso.
     * @param integer $idUsuario
     * @param integer $idAviso
     */
    function tieneSeguimiento($idUsuario, $idAviso)
    {
        $result = $this->getAdapter()->fetchOne(
            'EXEC KO_SP_TIENE_SEGUIMIENTO_QRY ?, ?', array($idUsuario, $idAviso)
        );
        return ($result>0)?$result:FALSE;
    }
}