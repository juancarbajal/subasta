<?php
/**
 * Descripción Corta
 *
 * Descripción Larga
 *
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer el archivo LICENSE
 * @version    1.0
 * @since      Archivo disponible desde su version 1.0
 */
require_once 'Base/Compras.php';
/**
 * Descripción Corta
 * Descripción Larga
 * @category
 * @package
 * @subpackage
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 * @version    Release: @package_version@
 * @link
 */

class Compras 
    extends Base_Compras
{

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function delComprasSeguimiento($idSeguimiento,$idUsuario)
    {
        $this->getAdapter()->fetchAll(
            'EXEC KO_SP_COMPRAS_SEG_DEL ?, ?', array ($idSeguimiento,$idUsuario)
        );
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function historialCompras($idUsuario, $categoria=0, $estadoCompra=0, $filtro=0)
    {
        $retorno = $this->getAdapter()->fetchAll(
            'EXEC KO_SP_HISTORIAL_COMPRAS_QRY ?, ?, ?, ?',
            array($idUsuario,
                $categoria==''?0:$categoria,
                $estadoCompra==''?0:$estadoCompra,
                $filtro==''?0:$filtro
            )
        );
        return $retorno;

    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function enviarEmail($idAviso)
    {
        $retorno = $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_DATOS_EMAIL ?', array($idAviso));
        return $retorno;
    }
    
    /** Ander
     * Lista las Ventas no Activas del Usuario Logeado
     * @param integer $nroResultados Numero de registros que deseamos obtener
     * @uses Clase::metodo()
     * @return type desc
     */
    public function listarComprasSeguimiento($input)
    {
        $array[]    = $input['K_ID_USR'];
        $array[]    = empty($input['K_FILTRO'])?0:$input['K_FILTRO'];
        $array[]    = empty($input['K_TIT'])?'':$input['K_TIT'];
        $array[]    = empty($input['K_FILTRO_FECHA'])?'0':$input['K_FILTRO_FECHA'];
        $array[]    = empty($input['K_NUM_PAGINA'])?'1':$input['K_NUM_PAGINA'];
        $array[]    = empty($input['K_NUM_REGISTROS'])?'30':$input['K_NUM_REGISTROS'];
        
        return $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_COMPRAS_SEG_SEL ?".str_repeat(",?", (count($array)-1)), $array
        );
    }
}