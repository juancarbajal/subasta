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
require_once 'Base/Calificacion.php';
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

class Calificacion
    extends Base_Calificacion
{
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function setCalificacion($datos)
    {
        try {
            $this->getAdapter()->query(
                "set ANSI_NULLS ON;set QUOTED_IDENTIFIER ON;set CONCAT_NULL_YIELDS_NULL on;
                set ANSI_WARNINGS on; set ANSI_PADDING on;SET TEXTSIZE 30000;"
            );
            $cal=$this->getAdapter()->fetchAll(
                "EXECUTE KO_SP_CALIFICACION_INS ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? ", $datos
            );
            if ($cal->K_ERROR=='0')
                return true;
            else
                return false;
        } catch (Exception $exc) {
            //echo $exc;
            die();
            return false;
        }
    }

    /**
     * Visualiza los motivos de denuncia de una calificacion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function getMotivosDenuncia($tipo)
    {
        $cache = Zend_Registry::get('cache');
        if (!$result = $cache->load('motivoDenunciaCalificacion' . $tipo)) {
            $result = $this->getAdapter()->fetchAll(
                'SELECT * FROM KO_MOTIVO WHERE EST=1 AND ID_TIPO_MOTIVO = ?', array($tipo)
            );
            $cache->save($result, 'motivoDenunciaCalificacion' . $tipo);
        }
        return $result;
    }

    /**
     * Registra notificacion (reclamo, denuncia, etc.) de acuerdo al motivo
     * @param lista Asunto
     * @return boolean Se logro activar todos los avisos
     */
    public function registrarNotificacion($data)
    {
        try {
            $retorno = $this->getAdapter()->fetchAll(
                "EXEC KO_SP_NOTIFICACION_COMPLETO_INS ?, ?, ?, ?, ?, ?",
                array($data['idUsr'],
                    $data['mensaje'],
                    $data['apodo'],
                    $data['idMotivo'],
                    $data['idTipoNotificacion'],
                    $data['idTransaccion']
                )
            );
            return $retorno;
        }
        catch (Zend_Exception $e) {
            echo "Db error : " . $e->getMessage() . "\n";
        }  
    }
    
    function obtenerPuntaje($idUsr)
    {
        $retorno = $this->getAdapter()->fetchAll("SELECT [dbo].[KO_FN_PUNTAJE_USR] (?)", $idUsr);
        return $retorno[0]->computed;
    }

}