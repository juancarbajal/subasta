<?php
/**
 * @author ander
 *
 */
require_once 'Base/FotoAviso.php';

class FotoAviso 
    extends Base_FotoAviso
{
    
    function generarArrayFoto($fotos, $antFotoDescrip, $nroFotos, $fotoGuardado=array())
    {
        $nuevasFotosName = array();
        $nuevasFotos = array();
        if (is_array($fotos)) {
            $increment = 0;
            foreach ($fotos as $index => $valor):
                if ($valor === 0) {
                    unset($fotos[$index]);
                }
                if ($increment < $nroFotos) {
                    $antDescrip = empty($fotoGuardado[$index])?$antFotoDescrip:'';
                    array_push($nuevasFotosName, $antDescrip.$valor);
                    $nuevasFotos[$index] = $valor;
                    $increment++;
                }
            endforeach;
            return array('name'=>$nuevasFotosName, 'key'=>$nuevasFotos);
        }
    }
    
    function guardarFotoAviso($input)
    {
        $array[]    = $input['K_FOTOS'];
        $array[]    = $input['K_ID_USR'];
        $array[]    = $input['K_ID_AVISO'];
        
        try {
            $return = $this->getAdapter()->fetchAll('EXECUTE KO_SP_FOTO_AVISO_INS ?, ?, ?', $array);
            // Retorna el codigo del aviso
            return $return[0];
//            return true;
        } catch (Exception $e) {
//            echo $e->getMessage();exit;
            return false;
        }
        
    }
    
}
