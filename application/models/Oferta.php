<?php
/**
<<<<<<< .mine
  * Descripción Corta
   *
    * Descripción Larga
     *
      * @copyright  Leer archivo COPYRIGHT
       * @license    Leer el archivo LICENSE
        * @version    1.0
         * @since      Archivo disponible desde su version 1.0
         */
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

require_once 'Base/Oferta.php';
class Oferta
    extends Base_Oferta
{
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getOferta($idoferta)
    {
        return $this->_db->fetchAll("SELECT * FROM KO_OFERTA WHERE ID_OFERTA='$idoferta'");
    }


    function comprar ($idAviso, $usr, $cantidad, $monto, $automatica = 0)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXEC KO_SP_OFERTA_INS ?, ?, ?, ?, ?, ?, ?, ?, ?',
            array(
                $cantidad,
                $monto,
                1,
                $usr,
                1,
                1,
                $automatica,
                $monto,
                $idAviso
            )
        );
        return $result[0];
    }
    /**
     * @param unknown_type $idAviso
     * @return unknown
     */
    function getVendedor($idAviso)
    {
        $result = $this->getAdapter()->fetchAll('EXEC KO_SP_AVISO_VENDEDOR_QRY ?', array($idAviso));
        return $result[0];
    }
    
    function getStock($idAviso)
    {
        return $this->getAdapter()->fetchOne(
            'SELECT STOCK FROM KO_AVISO WHERE ID_AVISO = ?', array($idAviso)
        );
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getHistorialVenta(
        $idUsr, $apodo = '', $filtroCategoria, $filtroCalificacion, $filtroDias, $filtroEstadoCalificacion
    )
    {
        return $this->getAdapter()->fetchAll(
            'EXEC KO_SP_HISTORIAL_VENTA_QRY ?,?,?,?,?,?',
            array($idUsr, $apodo, $filtroCategoria , $filtroCalificacion, $filtroDias,
                $filtroEstadoCalificacion)
        );
    } // end function
}

