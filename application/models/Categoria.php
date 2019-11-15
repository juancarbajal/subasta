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
require_once 'Base/Categoria.php';
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
class Categoria
    extends Base_Categoria
{
    
    public function getCategorias($nivel)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('categoriaLista' . $nivel)) {
            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_NIVEL_QRY ?', array($nivel));
            $cache->save($result, 'categoriaLista' . $nivel);
        }
        return $result;
    }    
    
    public function getCategoriasActivas($nivel)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('categoriaArbolLista' . $nivel)) {
            $result = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_CATEGORIA_ARBOL_NIVEL_QRY ?', array($nivel)
            );
            $cache->save($result, 'categoriaArbolLista' . $nivel);
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
    function getCategoriasL1($parametro)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('categoriaL1Home')) {
            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_SEL ?', array($parametro));
            $cache->save($result, 'categoriaL1Home');
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
    function getCategoriasBuscadorL1($parametro)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('categoriaL1Buscador')) {
            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_SEL ?', array($parametro));
            $cache->save($result, 'categoriaL1Buscador');
        }
        return $result;
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getCategoriaId($idCat)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('categoriaIdSel'.$idCat)) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_CATEGORIAID_SEL ?", $idCat);
            $cache->save($result, 'categoriaIdSel'.$idCat);
        }
        return $result;
    }

    /**
     * Permite visualizar el arbol d categoria en función al parámetro enviado
     * @param int $parametro -1 Se muestran todas las categorias desde L1,
     * si se enviar un valor diferente debera de ser el codigo de la categoria L1.
     * @uses Clase::methodo()
     * @return type desc
     */
    function getCategoriaArbol($parametro)
    {
        $cache = Zend_Registry::get('cache');
        $arbol = ($parametro ==-1) ? 0 : $parametro;
        if (!$result = $cache->load('arbolCategorias' . $arbol)) {
            $result = $this->getAdapter()->fetchAll("EXECUTE KO_SP_CATEGORIA_ARBOL ?", $parametro);
            $cache->save($result, 'arbolCategorias' . $arbol);
        }
        return $result;
    }
    
    /**
     * Ander
     * @return type 
     */
    public static function getSCategoriaN1()
    {
//        $cache = Zend_Registry::get('cache');
//        $cacheName = 'cache_tabla_SCategoriaN1';
//        if (!$result = $cache->load($cacheName)) {
            $obj = new Categoria();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array('ID_CATEGORIA','TIT'))
                ->where('NIVEL = ?', '1');
            $result = $dba->fetchPairs($sql);
////            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_NIVEL_QRY ?', array('1'));
//            $cache->save($result, $cacheName);
//        }
        return $result;
    }
    
    /**
     * Ander
     * @return type 
     */
    public static function getSCategoriaByPadreId($idCat)
    {
//        $cache = Zend_Registry::get('cache');
//        $cacheName = 'cache_tabla_SCategoriaN1';
//        if (!$result = $cache->load($cacheName)) {
            $obj = new Categoria();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array('ID_CATEGORIA','TIT'))
                ->where('ID_PADRE = ?', $idCat);
            $result = $dba->fetchPairs($sql);
////            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_NIVEL_QRY ?', array('1'));
//            $cache->save($result, $cacheName);
//        }
        return $result;
    }
    
    /**
     * Ander
     * @return type 
     */
    public function getCategoriaById($idCat)
    {
//        $cache = Zend_Registry::get('cache');
//        $cacheName = 'cache_tabla_SCategoriaN1';
//        if (!$result = $cache->load($cacheName)) {
        $obj = new Categoria();
        $dba = $obj->getAdapter();
        $sql = $dba->select()
            ->from($obj->_name)
            ->where('ID_CATEGORIA = ?', $idCat);
        $result = $dba->fetchRow($sql);
////            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_NIVEL_QRY ?', array('1'));
//            $cache->save($result, $cacheName);
//        }
        return $result;
    }
    
    /**
     * Ander
     * @return type 
     */
    public function guardar(
        $k_TIT,             $k_ID_PADRE,    $k_VISUALIZAHOME,   $k_ADULTO,
        $k_APTA_DESTAQUE,   $k_NIVEL,       $k_DES,             $k_EST
    )
    {
        return $this->getAdapter()->fetchAll(
            'EXEC IN_SP_CATEGORIA_INS ?, ?, ?, ?, ?, ?, ?, ?',
            array($k_TIT, $k_ID_PADRE, $k_VISUALIZAHOME, $k_ADULTO,
                  $k_APTA_DESTAQUE, $k_NIVEL, $k_DES, $k_EST
            )
        );        
    }
    
    /**
     * Ander
     * @return type 
     */
    public function actualizar(
        $K_ID_CATEGORIA, $K_TIT, $K_VISUALIZAHOME, $K_ADULTO, $K_APTA_DESTAQUE, $K_DES, $K_EST
    )
    {
        return $this->getAdapter()->fetchAll(
            'EXEC IN_SP_CATEGORIA_UPD ?, ?, ?, ?, ?, ?, ?',
            array($K_ID_CATEGORIA, $K_TIT, $K_VISUALIZAHOME, $K_ADULTO,
                  $K_APTA_DESTAQUE, $K_DES, $K_EST
            )
        );        
    }
    
    /**
     * Permite enviar las categorias que pertenecen a los combos de busqueda     
     * @uses Clase::methodo()
     * @return type desc
     */
    function getCategoriaCombo()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('combCategoria')) {
            $result = $this->getAdapter()->fetchAll('EXECUTE KO_SP_CATEGORIA_COMBO');
            $cache->save($result, 'comCategoria');
        }
        return $result;
    }
    
    public function getCategoriaNivel($nivel, $idPadre)
    {
        $dba = $this->getAdapter();
        
        $sql = $dba->select()->from(array('ca' => 'KO_CATEGORIA_ARBOL'));
        $sql->where('ca.NIVEL = ?', $nivel);
        if (!empty($idPadre))
            $sql->where('ca.ORIGEN = ?', $idPadre);
        
        return $dba->fetchAll($sql, '', Zend_Db::FETCH_ASSOC);
    }
    
    public function getSugerenciaCategoria($idAviso)
    {          
        $result = $this->getAdapter()->fetchAll(
            'EXEC KO_SP_AVISO_CATEGORIA_SUGERENCIA_SEL ?', array($idAviso)
        );
        return $result;
    }
}