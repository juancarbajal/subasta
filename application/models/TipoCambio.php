<?php
require_once('Base/TipoCambio.php');
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
class TipoCambio extends Base_TipoCambio
{
    /**
     * Retorna el valor del cambio de dolar
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getTipoCambio()
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('TipoCambioLista')) {
            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_TIPO_CAMBIO_SEL');
            $cache->save($result, 'TipoCambioLista');
        }
        return $result[0];
    } // end function
}