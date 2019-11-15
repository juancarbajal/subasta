<?php
/**
 * @author ander
 *
 */
require_once('Base/Aviso.php');

class Publicacion extends Base_Aviso
{
    public function getPublicacion($idAviso, $idUsuario)
    {
        return $this->getAdapter()->fetchRow(
            'EXECUTE KO_SP_AVISO_PUBLICACION_QRY ?, ?', array($idAviso, $idUsuario)
        );
    }
    
    /** Ander
     * Registra los datos de un aviso nuevo
     * 
     */
    public function guardarPublicacion($input)
    {   
        $array[]    = $input['K_ID_TIPO_PRODUCTO'];
        $array[]    = $input['K_TIT'];
        $array[]    = $input['K_TAG'];
        $array[]    = $input['K_PRECIO'];
        $array[]    = $input['K_HTML'];
        //$array[]    = $input['K_IMG_DEF'];
        
        $array[]    = $input['K_EST'];
        $array[]    = $input['K_URL'];
        $array[]    = $input['K_ID_MONEDA'];
        $array[]    = $input['K_ID_USR'];
        $array[]    = $input['K_ID_UBIGEO'];
        $array[]    = $input['K_ID_CATEGORIA'];
        $array[]    = $input['K_ID_MEDIO_PAGO'];
        $array[]    = $input['P_DESTAQUE_POST_PAGO'];
        
        $array[]    = $input['K_ID_DESTAQUE'];
        $array[]    = $input['K_TEXT_IMPRESO'];
        $array[]    = $input['K_TIT_IMPRESO'];
        
        $array[]    = $input['K_FOTOS'];
        
        $array[]    = $input['K_MEDIO_PAGO'];
        $array[]    = $input['K_MONTO'];
        $array[]    = $input['K_ID_TIPO_FACTURA'];
                
        try {
            $return = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_AVISO_PUBLICACION_INS ?, ?, ?, ?, ?, 
                                                     ?, ?, ?, ?, ?, 
                                                     ?, ?, ?, ?, ?,
                                                     ?, ?, ?, ?, ?',
                $array
            );
            // Retorna el codigo del aviso
            return $return[0];
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
    public function actualizaPublicacion($input)
    {   
        $array[]    = $input['K_ID_AVISO'];
        $array[]    = $input['K_ID_TIPO_PRODUCTO'];
        $array[]    = $input['K_TIT'];
        $array[]    = $input['K_TAG'];
        $array[]    = $input['K_PRECIO'];
        $array[]    = $input['K_HTML'];
        $array[]    = $input['K_EST'];
        $array[]    = $input['K_URL'];
        $array[]    = $input['K_ID_DURACION'];
        $array[]    = $input['K_ID_MONEDA'];
        $array[]    = $input['K_ID_USR'];
        $array[]    = $input['K_ID_UBIGEO'];
        $array[]    = $input['K_ID_CATEGORIA'];
        $array[]    = $input['K_ID_MEDIO_PAGO'];
        $array[]    = 1;//$input['P_DESTAQUE_POST_PAGO'];
        $array[]    = $input['K_ID_DESTAQUE'];
        $array[]    = $input['K_TEXT_IMPRESO'];
        $array[]    = $input['K_TIT_IMPRESO'];
        $array[]    = $input['K_FOTOS'];
        $array[]    = $input['K_MEDIO_PAGO'];
        $array[]    = $input['K_MONTO'];
        $array[]    = $input['K_ID_TIPO_FACTURA'];
        
        try {
            $return = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_AVISO_PUBLICACION_UPD ?, ?, ?, ?, ?, 
                                         ?, ?, ?, ?, ?, 
                                         ?, ?, ?, ?, ?,
                                         ?, ?, ?, ?, ?,
                                         ?, ?',
                $array
            );
            // Retorna el codigo del aviso
            return $return[0];
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
    }
}