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
class Application_Model_Sp_Palabra
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_PALABRA';
    protected $_primary = 'ID_PALABRA';
    
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
            if (empty($input['K_ID_PALABRA'])) {
                $array[] = -1;
            } else {
                $array[] = $input['K_ID_PALABRA'];
                $array[0] = -1;
                $array[1] = -1;
            }
            $array[] = empty($input['K_TAG'])?'':$input['K_TAG'];
            $array[] = empty($input['K_PRIORIDAD'])?'-1':$input['K_PRIORIDAD'];
            $array[] = empty($input['K_ORDEN'])?'-1':$input['K_ORDEN'];
            $array[] = empty($input['K_ESTADO'])?($input['K_ESTADO']==0?'0':'-1'):$input['K_ESTADO'];
            
            $result = $this->getAdapter()->fetchAll(
                "EXEC IN_SP_DICCIONARIO_TAG_SEL ?" . str_repeat(",?", (count($array)-1)), $array
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
        $array[] = $input['K_ID_PALABRA'];
        $array[] = $input['K_TAG'];
        $array[] = $input['K_URL'];
        $array[] = $input['K_PRIORIDAD'];
        $array[] = $input['K_ORDEN'];
        $array[] = $input['K_ESTADO'];
        
        return $this->getAdapter()->fetchRow(
            "EXEC IN_SP_DICCIONARIO_TAG_UPD ?".str_repeat(",?", (count($array)-1)), $array
        );
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
            $array[] = $input['K_TAG'];
            $array[] = $input['K_URL'];
            $array[] = $input['K_PRIORIDAD'];
            $array[] = $input['K_ORDEN'];
            $array[] = $input['K_ESTADO'];

            $result = $this->getAdapter()->fetchRow(
                "EXEC IN_SP_DICCIONARIO_TAG_INS ?".str_repeat(",?", (count($array)-1)), $array
            );
            return $result;
        } catch (Exception $exc) {
            return null;
            //echo $exc->getMessage();exit;
        }
    }
}