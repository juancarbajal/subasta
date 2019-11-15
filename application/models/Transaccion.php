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
require_once('Base/Transaccion.php');
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
class Transaccion
    extends Base_Transaccion
{
    function getTransaccion($array)
    {


    }

    function setTransaccion()
    {

    }

    function setTipoCambio()
    {
            return $this->getAdapter()->fetchOne("SELECT * FROM KO_TIPO_CAMBIO ORDER BY FEC_FIN");
    }
}