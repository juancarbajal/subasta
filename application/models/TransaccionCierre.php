<?php
require_once('Base/TransaccionCierre.php');
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
class TransaccionCierre
    extends Base_TransaccionCierre
{
    /**
     * Retorna los valores de transaccion por ventas exitosas
     * @param int $idUsuario Codigo del usuario
     * @param int $flag Tipo de visualizaciòn (1: Ventas Exitosas)
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getTransacciones($idUsuario, $filtroOne = 0, $filtroTwo = 0)
    {
       // print_r(array($idUsuario, $filtro1, $filtro2));
        return $result = $this->getAdapter()->fetchAll(
            'exec KO_SY_CONSULTAR_ESTADO_CUENTA ?, ?, ? ',
            array($idUsuario, $filtroOne==''?0:$filtroOne, $filtroTwo==''?0:$filtroTwo)
        );
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getTransaccionesDetalle($idTransaccion)
    {
        return  $this->getAdapter()->fetchAll(
            'EXEC KO_SY_CONSULTAR_DETALLE_TRANSACCION ?', array($idTransaccion)
        );
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getpendienteDePago($idUsuario, $filtroOne = 0)
    {
        return $result = $this->getAdapter()->fetchAll(
            'EXEC KO_SY_CONSULTAR_SALDO_PAGAR ?, ? ', array($idUsuario, $filtroOne==''?0:$filtroOne)
        );
    }

}

