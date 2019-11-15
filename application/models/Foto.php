<?php
/** 
 * @author jcarbajal
 * 
 * 
 */
require_once 'Base/Foto.php';
class Foto 
    extends Base_Foto
{
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function findByAviso ($idAviso)
    {   
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_FOTO_AVISO_SEL ?', array($idAviso)
        );
        return $result;
    }
    
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function findByAvisoPrioridad ($idAviso)
    {
        $result = $this->getAdapter()->fetchAll(
            'EXECUTE KO_SP_FOTO_AVISO_PRIO_SEL ?', array($idAviso)
        );
        return $result[0];
    }
    function insertFotoMigra($nombre,$prio,$usr,$idaviso)
    {
        return $this->getAdapter()->fetchAll(
            'exec KO_INSERT_FOTO_MIGRA ?,?,?,?', array($nombre, $prio, $usr, $idaviso)
        );
    }
    function listarfotos()
    {
        return $this->getAdapter()->fetchAll('select * from ko_foto');
    }
}