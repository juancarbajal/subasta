<?php
require_once 'Base/Reputacion.php';
class Reputacion 
    extends Base_Reputacion
{
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function verReputacion($idUsr)
    {
        return $this->getAdapter()->fetchAll('exec KO_SP_USUARIO_REPUTACION_SEL ? ', array($idUsr));
    }
    
    /**
     * Registra notificacion (reclamo, denuncia, etc.) de acuerdo al motivo
     * @param lista Asunto
     * @return boolean Se logro activar todos los avisos
     */
    public function registrarNotificacion($data)
    {
        try {
            /*$data['idUsr'] = 94;
            $data['mensaje'] =  'Prueba de cambio de store';
            $data['apodo'] = 'mipcperu';
            $data['idMotivo'] = 31;
            $data['idTipoNotificacion'] = 4;
            $data['idTransaccion'] = 82727;*/
            $retorno = $this->getAdapter()->fetchAll(
                'EXEC KO_SP_NOTIFICACION_INSERTAR ?, ?, ?, ?, ?, ?',
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
}