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
class Application_Model_Sp_IkModulo
    extends App_Db_Table_Abstract
{
    protected $_name = 'IK_MODULO';
    protected $_primary = 'K_ID_MODULO';
    
    /**
     * Descripcion
     * 
     * @param array $tipo Variables
     * 
     * @return void
     */
    public static function getSIkModulo($tipo = null)
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'cache_tabla_SIkModulo';
        if (!$result = $cache->load($cacheName)) {
            $obj = new Application_Model_Sp_IkModulo();
            $dba = $obj->getAdapter();
            $result = $dba->fetchAll(
                "EXEC IN_SP_CMB_MODULO_ADM_ITEM_SEL"
            );
            $cache->save($result, $cacheName);
        }
        if (!empty($tipo)) {
            foreach ($result as $key) {
                $arrTipo[$key->K_ID_TIPO_MODULO][$key->K_ID_MODULO] = $key->K_TITULO;
            }
            $result = $arrTipo[$tipo];
        }
        return $result;
    }
}