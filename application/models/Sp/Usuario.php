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
class Application_Model_Sp_Usuario 
    extends App_Db_Table_Abstract
{
    protected $_name = 'KO_USUARIO';
    protected $_primary = 'ID_USR';
    
    /**
     * Se utiliza para retornar valores de paginacion y datos de un usuario
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
            //$array[] = empty($input['K_ID_USR'])?-1:$input['K_ID_USR'];
            if (empty($input['K_ID_USR'])) {
                $array[] = -1;
            } else {
                $array[] = $input['K_ID_USR'];
                $array[0] = -1;
                $array[1] = -1;
            }
            $array[] = empty($input['K_APODO'])?'':$input['K_APODO'];
            $array[] = empty($input['K_ID_TIPO_DOC'])?'':$input['K_ID_TIPO_DOC'];
            $array[] = empty($input['K_EMAIL'])?'':$input['K_EMAIL'];
            $array[] = empty($input['K_NUM_DOC'])?'':$input['K_NUM_DOC'];
            $array[] = empty($input['K_ID_TIPO_USUARIO'])?0:$input['K_ID_TIPO_USUARIO'];
            $array[] = empty($input['K_ID_EST_USUARIO'])?0:$input['K_ID_EST_USUARIO'];
            $array[] = empty($input['K_CLAVE'])?'':$input['K_CLAVE'];
            $array[] = empty($input['K_FECHA_INI'])?'1900-01-01 00:00:00':$input['K_FECHA_INI'];
            $array[] = empty($input['K_FECHA_FIN'])?'2080-01-01 00:00:00':$input['K_FECHA_FIN'];

            //var_dump($array);exit;
            $result = $this->getAdapter()->fetchAll(
                "EXEC IN_SP_USUARIO_PORTAL_SEL ?" . str_repeat(",?", (count($array)-1)), $array
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
        $array[] = $input['K_ID_USUARIO'];
        $array[] = $input['K_NOM'];
        $array[] = $input['K_APEL'];
        $array[] = $input['K_APODO'];
        $array[] = $input['K_EMAIL'];
        $array[] = $input['K_ID_TIPO_DOCUMENTO'];
        $array[] = $input['K_NUM_DOC'];
        $array[] = $input['K_ID_TIPO_USUARIO'];
        $array[] = $input['K_ID_EST_USUARIO'];
        $array[] = $input['K_CLAVE'];
        $array[] = $input['K_ID_UBIGEO'];
        $array[] = $input['K_NUM_TELEF1'];
        $array[] = $input['K_NUM_TELEF2'];
        
        return $this->getAdapter()->fetchAll(
            "EXEC IN_SP_USUARIO_PORTAL_UPD ?".str_repeat(",?", (count($array)-1)), $array
        );
    }
}