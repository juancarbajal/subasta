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
require_once 'Base/Ubigeo.php';
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
class Ubigeo
    extends Base_Ubigeo
{
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getUbigeos ()
    {
        $data=$this->fetchAll(
            $this->select()
                ->from($this->_name, array('ID_UBIGEO','NOM'))
                ->where('ID_UBIGEO>0')
                ->order('NOM ASC')
        );
        return $data;
    } //end function

    /**
     * @return array Lista de  TOP 10 Ubigeos ordenados por la provincia
     */
    function getList($term)
    {
        $cache = Zend_Registry::get('cache');
        $result = $this->getAdapter()->fetchAll('EXEC KO_SP_UBIGEO_LIST ?', $term);
        return $result;
    }
    
    /**
     * @return array Lista total de Ubigeos ordenados ascendentemente
     */
    function getListTot()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('listTot')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_UBIGEO_PROV');
            $cache->save($result, 'listTot');
        }
        return $result;
    }

    //Ander
    function getListJoson($nivel)
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_listJson_nivel'.$nivel;
        if (!$result = $cache->load($cacheName)) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_UBIGEO_LIST_JSON ?', $nivel);
            $cache->save($result, $cacheName);
        }
        return $result;
    }
    
    //Ander
    public static function getDistritoByIdDepaProv($idDepa, $idProv)
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_distritos_by_idDepaProv_'.$idDepa.'_'.$idProv;
        if (!$result = $cache->load($cacheName)) {
            $obj = new Ubigeo();
            $result = $obj->getAdapter()->fetchPairs(
                'EXEC KO_SP_UBIGEO_ID_DIST_LIST ?, ?', array($idDepa, $idProv)
            );
            $cache->save($result, $cacheName);
        }
        return $result;
    }
    
    //Ander
    public static function getProvinciaByIdDepa($idd)
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_provincias_by_idDepa_'.$idd;
        if (!$result = $cache->load($cacheName)) {
            $obj = new Ubigeo();
            $result = $obj->getAdapter()->fetchPairs('EXEC KO_SP_UBIGEO_ID_PROV_LIST ?', $idd);
            $cache->save($result, $cacheName);
        }
        return $result;
    }
    
    //Ander
    public static function getProvinciaJosonValidate()
    {
        $obj = new Ubigeo();
        $departamentos = $obj->getListJoson(2);        
        foreach ($departamentos as $value) {
            $result[$value->ID_PROV]=$value->NOM;
        }
        return $result;
    }
    
    //Ander
    public static function getDistritoJosonValidate()
    {
        $obj = new Ubigeo();
        $departamentos = $obj->getListJoson(3);        
        foreach ($departamentos as $value) {
            $result[$value->ID_UBIGEO]=$value->NOM;
        }
        return $result;
    }
    
    //Ander
    public static function getDepartamento()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SDepartamento';
        
        $obj = new Ubigeo();
        $departamentos = $obj->getListJoson(1);        
        foreach ($departamentos as $value) {
            $result[$value->ID_DPTO]=$value->NOM;
        }
//        if (!$result = $cache->load($cacheName)) {
//            $obj = new Ubigeo();
//            $db = $obj->getAdapter();
//            $sql = $db->select()
//                ->from($obj->_name, array('ID_UBIGEO','NOM'))
//                ->where('ID_DPTO =?', '0')
//                ->where('ID_UBIGEO !=?', '0');
//            $result = $db->fetchPairs($sql);
//            echo $sql->assemble(); exit;
//            $cache->save($result, $cacheName);
//        }
        return $result;
    }
    
    //Ander
    public static function getProvincia($idDepartamento)
    {
        $cache = Zend_Registry::get('cache');
//        $cacheName = 'cache_tabla_SProvincia';
//        if (!$result = $cache->load($cacheName)) {
            $obj = new Ubigeo();
            $dba = $obj->getAdapter();
            $sql = $dba->select()
                ->from($obj->_name, array('ID_UBIGEO','NOM'))
                ->where('ID_DPTO =?', $idDepartamento)
                ->where('ID_PROV !=?', '0')
                ->where('ID_DIST =?', '0');
            $result = $dba->fetchPairs($sql);
//            echo $sql->assemble(); exit;
//            $cache->save($result, $cacheName);
//        }
        return $result;
    }
    
    //Ander
    function getListCiudadesActivas()
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('listaCiudadesActivas')) {
            $result = $this->getAdapter()->fetchAll('EXEC KO_SP_UBIGEO_AVI_ACT');
            $cache->save($result, 'listaCiudadesActivas');
        }
        return $result;
    }
} //end class
