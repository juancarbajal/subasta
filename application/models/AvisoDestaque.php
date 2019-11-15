<?php
/**
 * @author ander
 *
 */
require_once 'Base/AvisoDestaque.php';

class AvisoDestaque extends Base_AvisoDestaque
{

    /**
     * Permite registrar destaques a un nuevo aviso
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function guardarAvisoDestaque($input)
    {
        $array[]    = $input['K_ID_AVISO'];
        $array[]    = $input['K_ID_DESTAQUE'];
        $array[]    = '1';//$input['K_VAR_BUS'];
        $array[]    = $input['K_TEXT_IMPRESO'];
        $array[]    = $input['K_TIT_IMPRESO'];        
        try {
            $return = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_AVISO_DESTAQUE_INS ?, ?, ?, ?, ?', $array
            );
            // Retorna 
            return $return[0]->computed;
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
    /** Ander
     * actualiza
     * 
     */
    public function actualizaAvisoDestaque($input)
    {
        $array[]    = $input['K_ID_AVISO'];
        $array[]    = $input['K_ID_DESTAQUE'];
        $array[]    = 0;//$input['K_VAR_BUS'];
        $array[]    = $input['K_TEXT_IMPRESO'];
        $array[]    = $input['K_TIT_IMPRESO'];
        $array[]    = $input['K_URL_IMG'];
        $array[]    = 1;//$input['K_USE_TEMPORAL'];
        $array[]    = 1;//$input['K_VAR_REGLA6'];
        $array[]    = 1;//$input['O_RESPUESTA'];
        
        try {
            $return = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_AVISO_DESTAQUE_UPD ?, ?, ?, ?, ?, 
                                                  ?, ?, ?, ?',
                $array
            );
            // 
            return $response[0]->computed;
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
    
}
