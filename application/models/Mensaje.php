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
require_once ('Base/Mensaje.php');
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
class Mensaje 
    extends Base_Mensaje
{
    /**
     * Obtiene informacion de la tabla mensaje y detalle mensaje
     * @param integer $idAviso Identificador de aviso
     * @param string $pregunta Texto de la pregunta realizada
     * @param integer $idUsuario Identificador del usuario que hace la pregunta
     */
    function insertPreguntaAviso1 ($idAviso, $pregunta, $idUsuario)
    {
       /* $this->getAdapter()->beginTransacction();
        try {*/
            $result = $this->getAdapter()->fetchAll(
                "EXEC KO_SP_MENSAJE_INS ?, ?, ?, ?, ?, ? ",
                array($idAviso, '1', '1', $pregunta, '1', $idUsuario)
            );
            //$this->getAdapter()->commit();
            return $result[0];
        /*} catch (Exception $e) {
            $log = Zend_Registry::get('log');
            $log->err($e->getMessage());
            $this->getAdapter()->rollBack();
        }*/
    } //end function
    
    /**
     * Insersion de preguntas a un determinado aviso
     * @param integer $idAviso Identificador de aviso
     * @param string $pregunta Texto de la pregunta realizada
     * @param integer $idUsuario Identificador del usuario que hace la pregunta
     */
    function insertPreguntaAviso ($idAviso, $pregunta, $idUsuario,$flag)
    {
       /* $this->getAdapter()->beginTransacction();
        try {*/
            $result = $this->getAdapter()->fetchAll(
                "EXEC KO_SP_MENSAJE_INS ?, ?, ?, ?, ?, ? ",
                array($idAviso , '1' , '1', $pregunta, $flag, $idUsuario)
            );
            //$this->getAdapter()->commit();
            return $result[0];
        /*} catch (Exception $e) {
            $log = Zend_Registry::get('log');
            $log->err($e->getMessage());
            $this->getAdapter()->rollBack();
        }*/
    } //end function
    
    /**
     * Lista de preguntas y respuestas de determinado aviso
     * @param integer $idAviso Identificador de Aviso
     * @return array Lista de preguntas correspondientes a determinado aviso
     */
    function getPreguntasRespuestasAviso ($idAviso)
    {
        //try {            
        $result = $this->getAdapter()->fetchAll(
            "EXEC KO_SP_AVISO_PREGUNTA_RESPUESTA_QRY ?", array($idAviso)
        );
            return  $result;
        //} catch (Exception $exc) {          
         //   exit;
        //}
    } //end function
    
    /** 
     * Denunciar pregunta de un aviso
     * @param integer $idPregunta Identificador de pregunta a denunciar
     * @param string denuncia Texto de la denuncia  
     */
    function denunciarPreguntaAviso($idPregunta, $denuncia)
    {
        return true;
    }
    
    /**
     * Registra mensajes realizados
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function registrarRespuesta ($data)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $this->getAdapter()->fetchAll(
                "EXEC KO_SP_DICCIONARIO_TAG_INS ?, ?",
                array($data['palabra_buscada'], $data['nro_resultados'])
            );
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();
            //            echo $e->getMessage();die();
        }
    } //end function

    /**
     * KOTEANDO - listar preguntas realizadas. (preguntas, preguntas y respuestas)
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function listarPreguntasRealizadas($idUsuario, $categoria, $filtro)
    {
        return $this->getAdapter()->fetchAll(
            "EXECUTE KO_SP_PREGUNTAS_CATEGORIA_PROCESO_QRY ? , ? , ? ",
            array($idUsuario, $categoria, $filtro)
        );
    } //end function

    /**
     * KOTEANDO - verifico si el usuario no puede preguntar por su propio aviso.
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function validarPropioAviso($idAviso)
    {
        return $this->getAdapter()->fetchAll(
            "select ID_AVISO,TIT, ID_USR FROM KO_AVISO WHERE ID_AVISO = ? ", $idAviso
        );
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
    function eliminarMensaje ($idMensaje)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $this->getAdapter()->fetchAll("DELETE FROM KO_MENSAJE WHERE ID_MENSAJE = ?", $idMensaje);
            $this->getAdapter()->commit();
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();            
        }
    } //end function

    /**
     * Preguntas sin contestar por parte de un usuario
     * @param integer $idAviso Identificador de Aviso
     * @return array Lista de preguntas correspondientes a determinado aviso
     */
    function getPreguntasSinContestar ($idAviso)
    {
        return $this->getAdapter()->fetchAll("EXEC KO_SP_AVISO_PREGUNTA_RESPUESTA_QRY ?", array($idAviso));
    }

    /**
     * Consulta a la tabla mensaje para obtener sus datos generales
     * @param integer $idAviso Identificador de Aviso
     * @return array Lista de preguntas correspondientes a determinado aviso
     */
    function getMensajeDatos($idMensaje)
    {
        return $this->getAdapter()->fetchAll(
            "SELECT * FROM KO_MENSAJE WHERE ID_MENSAJE=?", array($idMensaje)
        );
    }
} 