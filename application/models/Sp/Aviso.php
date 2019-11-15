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
class Application_Model_Sp_Aviso
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_AVISO';
    protected $_primary = 'ID_AVISO';
    
    /**
     * Se utiliza para retornar valores de paginacion y datos de un usuario
     * 
     * @param array $input Variables
     * 
     * @return void
     */
    public function getPaginacionModerar($input)
    {
        try {
            $array[] = empty($input['K_NUM_PAGINA'])?1:$input['K_NUM_PAGINA'];
            $array[] = empty($input['K_NUM_REGISTROS'])?10:$input['K_NUM_REGISTROS'];
            
            $array[] = empty($input['K_ID_AVISO'])?'-1':$input['K_ID_AVISO'];
            $array[] = empty($input['K_APODO'])?'':$input['K_APODO'];
            $array[] = empty($input['K_ID_DESTAQUE'])?'-1':$input['K_ID_DESTAQUE'];
            $array[] = empty($input['K_FECHA_INI'])?'2012-09-20 00:00:00':$input['K_FECHA_INI'];
            $array[] = empty($input['K_FECHA_FIN'])?'2013-03-20 00:00:00':$input['K_FECHA_FIN'];
            $array[] = empty($input['K_TIPO_ESTADO'])?'1':$input['K_TIPO_ESTADO'];
            $array[] = empty($input['K_ID_CATEGORIA'])?'-1':$input['K_ID_CATEGORIA'];

            $result = $this->getAdapter()->fetchAll(
                "EXEC IN_SP_AVISOSMODERAR_SEL ?" . str_repeat(",?", (count($array)-1)), $array
            );
            return $result;
        } catch (Exception $exc) {
            return null;
            //echo $exc->getMessage();exit;
        }
    }
    
    /**
     * Se utiliza para retornar valores de paginacion y datos de un usuario
     * 
     * @param int $idCategoria Variables
     * 
     * @return void
     */
    public function getSitemapByIdCategoria($idCategoria)
    {
        try {
            $result = $this->getAdapter()->fetchAll("EXEC KO_SP_GENERA_SITEMAP ?", $idCategoria);
            return $result;
        } catch (Exception $exc) {
            return null;
            //echo $exc->getMessage();exit;
        }
    }
    
}