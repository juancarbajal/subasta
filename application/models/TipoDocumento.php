<?php
require_once('Base/TipoDocumento.php');
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
class TipoDocumento extends Base_TipoDocumento
{
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getList()
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('tipoDocumentoLista')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_TIPO_DOC_QRY');
            $cache->save($result, 'tipoDocumentoLista');
        }
        return $result;
    } // end function
    
    /**
     *
     * @param type $idTipoDocumento
     * @return type String | DNI, PASAPORTE, RUC 
     */
    public function getDescripcionCorta($idTipoDocumento)
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('tipoDocumentoLista')) {
            $result = $this->getList();
        }
        
        foreach ($result as $tipoDocumento) {
            if ($tipoDocumento->ID_TIPO_DOC == $idTipoDocumento) {
                return $tipoDocumento->DES_CORTA;
            }
        }
    }
    
    /*
     * Ander
     * Para el generar combos
     */
    public static function getSTipoDocumento()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('cache_tabla_STipoDocumento')) {
            $obj = new Base_TipoDocumento();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array());
            $sql = $sql->columns(array('ID_TIPO_DOC','DES_CORTA'));
            $result = $dba->fetchPairs($sql);
            $cache->save($result, 'cache_tabla_STipoDocumento');
        }
        return $result;
    }
}
