<?php
  /**
   * Descripción Corta
   * 
   * Descripción Larga
   * 
   * @copyright  Leer archivo COPYRIGHT
   * @license    Leer el archivo LICENSE
   * @version    1.0
   * @since      Archivo disponible desde su version 1.0
   */
require_once('Base/MensajeDetalle.php');
  /**
    * Descripción Corta
     * Descripción Larga
      * @category   
      * @package    
      * @subpackage    
      * @copyright  Leer archivo COPYRIGHT 
      * @license    Leer archivo LICENSE
      * @version    Release: @package_version@
      * @link
      */
class MensajeDetalle
    extends Base_MensajeDetalle
{
    /**
     * Registra las palabras buscadas en el diccionario de tags actualizando la informacion
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function addRespuesta($data) 
    { 
        $this->getAdapter()->beginTransaction();
        try{
            //Insertamos en Detalle Mensaje la respuesta asociandolo a la pregunta realizada
            $idDetalleMensaje = $this->getNextId();
            $array['ID_DETALLE_MENSAJE'] = $idDetalleMensaje;
            $array['ID_MENSAJE'] = $data['ID_MENSAJE'];
            $array['COMENT'] = $data['coment'];
            $array['ID_TIPO_MENSAJE'] = $data['idtipomensaje'];
            $array['FLAG'] = 'NULL';
            $array['ID_MENSAJE'] = $data['idmensaje'];
            $array['ID_USR'] = $this->identity->ID_USR;
            var_dump($array);
            $this->insert($array);
            $this->db->commit();
        } catch (Exception $e){
             echo $e->getMessage(); die();
             $this->db->rollBack();
        }
    } //end function	


    public function insertRespuesta($comentario, $tipoMensaje, $idMensaje, $idUsuario , $flag)
    {
        $retorno = $this->getAdapter()->fetchAll(
            "EXEC KO_SP_DETALLE_MENSAJE_INS ?, ?, ?, ?, ? ",
            array($comentario, $tipoMensaje, $idMensaje, $idUsuario, $flag)
        );
        return $retorno;
        
    }

    /**
     * Registra las replicas realizadas a los comentarios por calificaciones recibidas
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function registrarReplica($data)
    {
        $result = $this->getAdapter()->fetchAll(
            "EXEC KO_SP_DETALLE_MENSAJE_INS ?, ?, ?, ? ",
            array(
                $data['comentario'],
                $data['tipoMensaje'],
                $data['idmensajereplica'],
                $data['idUsuario']
            )
        );
        return $result;

    }

    public function getNextId()//PARA SACAR EL NUMERO SIGUIENTE AL ULTIMO REGISTRO
    {
        return $this->_db->fetchOne("SELECT COALESCE(max(ID_DETALLE_MENSAJE),0)+1 FROM KO_DETALLE_MENSAJE");
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function find ($key)
    {
        $data = $this->getAdapter()->fetchAll(
            "SELECT KDM.*,'S' COMMENT, KM.*, KUP.APODO, KUP.ID_USR AS IDCOMPRADOR 
            FROM KO_DETALLE_MENSAJE KDM
            INNER JOIN KO_USUARIO_PORTAL KUP ON KDM.ID_USR = KUP.ID_USR
            INNER JOIN KO_MENSAJE KM ON KM.ID_MENSAJE=KDM.ID_MENSAJE
            WHERE ID_DETALLE_MENSAJE  = ?", $key
        );
        return $data[0];
    } //end function
    
    /**
     * KOTEANDO - Permita conocer si una pregunta o comentario tiene una respuesta o replica
     * y que pertenezca al usuario que hace la consulta.
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     * @author BL
     */
    function getNroRespuestasMensaje($data)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $result = $this->getAdapter()->fetchAll(
                "SELECT KDM.ID_DETALLE_MENSAJE, KM.ID_MENSAJE, KA.ID_AVISO, KUP.ID_USR
                FROM KO_DETALLE_MENSAJE KDM
                INNER JOIN KO_MENSAJE KM ON KDM.ID_MENSAJE = KM.ID_MENSAJE
                INNER JOIN KO_AVISO KA ON KM.ID_REGISTRO = KA.ID_AVISO
                INNER JOIN KO_USUARIO_PORTAL KUP ON KA.ID_USR = KUP.ID_USR
                WHERE KM.ID_MENSAJE=? AND KDM.ID_TIPO_MENSAJE=? AND KUP.ID_USR=?",
                array(
                    $data['idMensaje'],
                    $data['idTipoMensaje'],
                    $data['idUsr']
                )
            );
            $this->getAdapter()->commit();
            return $result[0];
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();
        }
    } //end function

    /**
     * KOTEANDO - Eliminacion de mensaje para cualquier tipo de detalle mensaje
     * Primero deberá de verificar la eliminación a nivel de aplicación como ser
     * si el detalle mensaje pertenece al usuario u otras reglas.
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     * @author BL
     */
    function eliminarDetalleMensaje ($idDetalleMensaje)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $this->getAdapter()->fetchAll(
                "DELETE FROM KO_DETALLE_MENSAJE WHERE ID_DETALLE_MENSAJE= ?", $idDetalleMensaje
            );
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();
        }
    } //end function


    function verDatosDetalle ($idDetalle)
    {
        $data = $this->getAdapter()->fetchAll(
            "SELECT ID_DETALLE_MENSAJE, COMENT, ID_MENSAJE, ID_USR
            FROM KO_DETALLE_MENSAJE
            WHERE ID_DETALLE_MENSAJE = ?", $idDetalle
        );
        return $data[0];
    }

}