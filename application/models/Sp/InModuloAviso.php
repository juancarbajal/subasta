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
class Application_Model_Sp_InModuloAviso
    extends App_Db_Table_Abstract
{
    protected $_name = 'IN_MODULO_AVISO';
    protected $_primary = 'ID_MODULO_AVISO';
    
    /**
     * Se utiliza para retornar valores de paginacion y datos
     * 
     * @param array $input Variables
     * 
     * @return void
     */
    public function getPaginacion($input)
    {
        try {
            $array[] = empty($input['K_NUM_PAGINA'])?1:$input['K_NUM_PAGINA'];
            $array[] = empty($input['K_NUM_REGISTROS'])?10:$input['K_NUM_REGISTROS'];
            if (empty($input['K_ID_MODULO_AVISO'])) {
                $array[] = -1;
            } else {
                $array[] = $input['K_ID_MODULO_AVISO'];
                $array[0] = -1;
                $array[1] = -1;
            }
            $array[] = empty($input['K_ID_MODULO'])?'-1':$input['K_ID_MODULO'];
            $array[] = empty($input['K_ID_AVISO'])?'-1':$input['K_ID_AVISO'];
            $array[] = empty($input['K_ESTADO'])?0:$input['K_ESTADO'];
            
            $result = $this->getAdapter()->fetchAll(
                "EXEC IN_SP_MODULOAVISO_SEL ?" . str_repeat(",?", (count($array)-1)), $array
            );
            return $result;
        } catch (Exception $exc) {
            return null;
            //echo $exc->getMessage();exit;
        }
    }
    
    /**
     * Descripcion
     * 
     * @param array $input Variables
     * 
     * @return void
     */
    public function actualizar($input)
    {
        try {
            $array[] = $input['K_ID_MODULO_AVISO'];
            $array[] = $input['K_ESTADO'];
            $array[] = $input['K_ID_MODULO'];
            $result = $this->getAdapter()->fetchRow(
                "EXEC IN_SP_MODULOAVISO_UPD ?".str_repeat(",?", (count($array)-1)), $array
            );
            return $result;
        } catch (Exception $exc) {
            return null;
            //echo $exc->getMessage();exit;
        }
    }
    
    /**
     * Descripcion
     * 
     * @param array $input Variables
     * 
     * @return void
     */
    public function guardar($input)
    {
        try {
            $array[] = $input['K_ESTADO'];
            $array[] = $input['K_ID_MODULO'];
            $array[] = $input['K_IDS_AVISO'];
            $result = $this->getAdapter()->fetchRow(
                "EXEC IN_SP_MODULOAVISO_INS ?".str_repeat(",?", (count($array)-1)), $array
            );
            return $result;
        } catch (Exception $exc) {
            return null;
            //echo $exc->getMessage();exit;
        }
    }
}