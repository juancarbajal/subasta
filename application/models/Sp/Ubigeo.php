<?php
/**
 * Admin class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Model
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */

/**
 * Admin class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Model
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class Application_Model_Sp_Ubigeo 
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_UBIGEO';
    protected $_primary = 'ID_UBIGEO';
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public static function getSDepartamento()
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SDepartamento';
        
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_Ubigeo();
            $departamentos = $obj->getListJoson(1);
            foreach ($departamentos as $value) {
                $result[$value->ID_DPTO]=$value->NOM;
            }
        }
        return $result;
    }
    
    /**
     * Descripcion
     * 
     * @param int $nivel Variable
     * 
     * @return void
     */
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
    
    /**
     * Descripcion
     * 
     * @param int $idDepartament Variable
     * 
     * @return void
     */
    public static function getProvinciaByIdDepa($idDepartament)
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_provincias_by_idDepa_'.$idDepartament;
        
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_Ubigeo();
            $result = $obj->getAdapter()->fetchPairs('EXEC KO_SP_UBIGEO_ID_PROV_LIST ?', $idDepartament);
            $cache->save($result, $cacheName);
        }
        return $result;
    }
    
    /**
     * Descripcion
     * 
     * @param int $idDepa Variable
     * @param int $idProv Variable
     * 
     * @return void
     */
    public static function getDistritoByIdDepaProv($idDepa, $idProv)
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_distritos_by_idDepaProv_'.$idDepa.'_'.$idProv;
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_Ubigeo();
            $result = $obj->getAdapter()->fetchPairs(
                'EXEC KO_SP_UBIGEO_ID_DIST_LIST ?, ?', array($idDepa, $idProv)
            );
            $cache->save($result, $cacheName);
        }
        return $result;
    }
}