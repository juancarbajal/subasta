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
require_once 'Base/Notificacion.php';
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
class Notificacion
    extends Base_Notificacion
{
    /**
     * Registra la solicitud de cancelacion de suspension realizada por un usuario
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function addSolicitudCancelacion ($data)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $this->getAdapter()->fetchAll(
                "EXEC KO_SP_NOTIFICACION_INS ?, ?", 
                array($data['usuariosuspendido'], $data['msg'])
            );
            $this->getAdapter()->commit();
            return $result[0];
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();
            return $result[0];
        }
    } //end function

    function insert ($data)
    {
        $this->getAdapter()->beginTransaction();
        try {
            $data['suscripcionNews'] = ($data['suscripcionNews'] == 1) ? 1 : 0;
            $result = $this->getAdapter()->fetchAll(
                'EXECUTE KO_SP_USUARIO_INS ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                array(
                    $data['apodo'] ,
                    $data['clave'] ,
                    $data['nombre'] ,
                    $data['apellido'] ,
                    $data['tipodocumento'] ,
                    $data['numerodocumento'] ,
                    $data['email'] ,
                    $data['departamento'] ,
                    $data['codigoArea'] . '-' . $data['telefono'] ,
                    '' ,
                    $data['codigoArea2'] . '-' . $data['telefono2'] ,
                    $data['ciudad'] ,
                    $data['suscripcionNews'] ,
                    $data['cod_conf']
                )
            );
            $this->getAdapter()->commit();
            return $result[0];
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            $this->getAdapter()->rollBack();
            return $result[0];
        }
    } //end function
}