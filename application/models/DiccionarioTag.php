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
require_once 'Base/DiccionarioTag.php';
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
class DiccionarioTag 
    extends Base_DiccionarioTag
{
    
    /**
     * Registra las palabras buscadas en el diccionario de tags actualizando la informacion
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function insertPalabraBuscada($data)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $result = $this->getAdapter()->fetchAll(
                "EXEC KO_SP_DICCIONARIO_TAG_INS ?, ?", array($data['q'], $data['r'])
            );
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $log=Zend_Registry::get('log');
            $log->err($e->getMessage());
            $this->getAdapter()->rollBack();
        }
    } //end function

    /**
     * Realiza la busqueda del autocomplete por caracter ingresado
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function getAutocomplete($caracter)
    {
        $cache = Zend_Registry::get('cache');
        if (! $result = $cache->load('Autocompletado' . $caracter)) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_BUSQUEDA_AUTOCOMPLETADO ?", $caracter);
            $cache->save($result, 'Autocompletado' . $caracter);
        }
        return $result;
    }

    /**
     * Visualiza las categorias L1 segun criterio indicado para la visualizacion
     * 1 Visualiza Home
     * 0 No visualiza Home
     * -1 Todos
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function getLoMasBuscado()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('LoMasBuscado')) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_DICCIONARIO_TAG_SEL");
            $cache->save($result, 'LoMasBuscado');
        }
        return $result;
    }
    
    /**
     * Obtiene el registro de una palabra buscada
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function getPalabraBuscada($cadena)
    {
        $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_DICCIONARIO_PALABRA_BUSCADA_SEL ?", $cadena);
        return $result[0];
    }
}