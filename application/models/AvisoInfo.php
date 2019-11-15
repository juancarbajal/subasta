<?php
require_once 'Aviso.php';
require_once 'Foto.php';
/**
 * @author jcarbajal
 *
 */
class AvisoInfo
    extends Aviso
{
    /**
     * @param varchar(10) $idAviso
     * @return unknown
     */
    function getBaseByAviso ($idAviso)
    {
        $result = $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_BASE_QRY ?', array($idAviso));
        return $result[0];
    }

    
    /**
     * retorna la informacion del aviso
     * @param unknown_type $idAviso
     * @return unknown
     */
    function getInfo ($idAviso)
    {           
        $result = $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_DETALLE_QRY ?', array($idAviso));
        return $result[0];
    }
    
    
    /**
     * Proceso de visitar un aviso, el contador de visitas se incrementa
     * @param unknown_type $idAviso
     * @return unknown
     */
    function visitar($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll("EXEC KO_SP_VISITAR_AVISO_RET ? ", array($idUsuario));
        return $result[0];
    }

    
    /**
     * Proceso de visitar un aviso, el contador de visitas se incrementa
     * @param unknown_type $idAviso
     * @return unknown
     */
    function actualizarContactos($idAviso)
    {
        $result = $this->getAdapter()->fetchAll("EXEC KO_SP_CONTACTAR_AVISO_RET ? ", array($idAviso));
        return $result[0];
    }

    
    /**
     * Retorna la lista de categorias a al que pertenece determinado aviso
     * @param unknown_type $idAviso
     * @return unknown
     */
    function getTreeCategorias($idAviso)
    {
        require_once 'Agrupador.php';
        $agrupador = new Agrupador();
        return $agrupador->getLevelsByAviso($idAviso);
    }


    /**
     * Retorna la lista de categorias a al que pertenece determinado aviso
     * @param unknown_type $idAviso
     * @return unknown
     */
    function obtenerDatos($idAviso)
    {
        $result = $this->getAdapter()->fetchAll("EXEC KO_SP_AVISO_ID_QRY ? ", $idAviso);
        return $result;
    }
    
    
    /**
     * Retorna la lista de categorias a al que pertenece determinado aviso
     * @param unknown_type $idAviso
     * @return unknown
     */
    function getVendedor($idAviso)
    {
            return $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_VENDEDOR_QRY ?', array($idAviso));
    }

    
    /**
     * Retorna la lista de categorias a al que pertenece determinado aviso
     * @param unknown_type $idAviso
     * @return unknown
     */
    function getValidacion($idAviso, $idUsuario, $cantidad = 1 , $montoMaximo = null, $precioBase = null,
        $tipo = 1)
    {
            switch ($tipo){
                case 1: 
                    $result = $this->getAdapter()->fetchAll(
                        'EXEC KO_SP_VALIDAR_COMPRA ?, ?, ?, NULL, NULL, 1',
                        array($idAviso, $idUsuario, $cantidad)
                    );
                    break;
                case 2:
                case 1:
                    $result = $this->getAdapter()->fetchAll(
                        'EXEC KO_SP_VALIDAR_COMPRA ?, ?, ?, ?, ?, 2',
                        array($idAviso, $idUsuario, $cantidad, $montoMaximo, $precioBase)
                    );
                    break;
            }
       //$result = $this->getAdapter()->fetchAll('EXEC KO_SP_VALIDAR_COMPRA ?', array($idAviso));
       return $result[0];
    }

    public function verSuspension($idUsuario)
    {
        $result = $this->getAdapter()->fetchAll(
            "select s.*, r.des, r.id_motivo_regla_nivel
            from ko_suspension s inner join ko_regla r on
            r.id_regla=s.id_regla
            where flag=0 and id_usr = ? ", $idUsuario
        );
        return $result;
    }
    /**
     * @param integer $idAviso
     * @return integer Tipo de Aviso de 1: Compra Directa 2: Subasta
     */
    function getTipoAviso($idAviso)
    {
        return $this->getAdapter()->fetchOne(
            'SELECT ID_TIPO_AVISO FROM KO_AVISO WHERE ID_AVISO = ?', array($idAviso)
        );

    }
//    function getAnteriorGanador($idAviso) {
//    	$result = $this->getAdapter()->fetchAll('EXEC KO_SP_ANTERIOR_GANADOR_QRY ?', array($idAviso));
//    	return $result[0];
//    }


    function validaUrl($idAviso)
    {
        $result = $this->getAdapter()->fetchAll(
            "select URL, ID_AVISO
            from KO_AVISO
            WHERE ID_AVISO = ? ", $idAviso
        );
      return $result;
    }
    function getIdOracle($idAviso)
    {
        $result = $this->getAdapter()->fetchAll(
            "select ID_AVISO
            from AVISO_SEO
            WHERE AV_ID_ORACLE = ? ", $idAviso
        );
      return $result;
    }
}
