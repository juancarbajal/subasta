<?php
require_once('Base/UsuarioRango.php');
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
class UsuarioRango extends Base_UsuarioRango
{
    /**
     * Retorna los valores de rangos de usuario
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getUsuarioRangos()
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('UsuarioRangosLista')) {
            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_USUARIO_RANGO_SEL');
            $cache->save($result, 'UsuarioRangosLista');
        }
        return $result;
    } // end function
}