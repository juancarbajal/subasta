<?php
error_reporting(E_ALL) ;

require 'init.php';
require_once 'Pagos.php';

/**
 * Description of mediacion
 *
 * @author ander
 */
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

define("EXCHANGENAME", 'exchange_mediacion');
define("QUEUENAME", 'queue_mediacion');

class Mediacion {

    /**
     *
     * @var AMQPConnection 
     */
    protected $_amqConnection;

    /**
     *
     * @var 
     */
    protected $_channel;

    /**
     *
     * @var AMQPMessage 
     */
    protected $_mensaje;
    
    /**
     *
     * @var String
     */
    private $_logError = '';
    
    public function __construct()
    {
        try {
            $this->_amqConnection = new AMQPConnection(
                        $this->_getMediacion()->host,
                        $this->_getMediacion()->port,
                        $this->_getMediacion()->user,
                        $this->_getMediacion()->pass,
                        $this->_getMediacion()->vhost
            );
            $this->_channel = $this->_amqConnection->channel();
            $this->_channel->exchange_declare(EXCHANGENAME, 'direct', false, true, false);
            $this->_channel->queue_declare(QUEUENAME, false, true, false, false);
            $this->_channel->queue_bind(QUEUENAME, EXCHANGENAME, $this->_getMediacion()->routingkey);
            $this->getDataMediacion();
        } catch (Exception $exc) {
            Zend_Registry::get('logMed')->write(array(
                'observacion' =>  $exc->getMessage()
            ));
        }
    }

    private function _getMediacion() {
        return Zend_Registry::get('config')->mediacion;
    }

    public function send($mensaje) {

        $msg = new AMQPMessage($mensaje);
        $this->_channel->basic_publish($msg, '', QUEUENAME);
//        echo date("Ymd-Hms") . " Enviado: " . $mensaje  . PHP_EOL;
    }

    public function getDataMediacion() {
        $pagos = new Pagos();
//        $date = new Zend_Date();
        $transacciones = $pagos->getListaMediacion();
//        $codPortal = Zend_Registry::get('config')->mediacion->codigoportal;
                
        foreach ($transacciones as $transaccion) {
            try {
                $this->send(Zend_Json::encode(
                        array(
                            'codigo_transaccion' => $transaccion->codigo_transaccion,
                            'codigo_mediopago' => $transaccion->codigo_mediopago,
                            'codigo_avisoweb' => $transaccion->codigo_avisoweb,
                            'codigo_portal' => $transaccion->codigo_portal,
                            'monto_pago' => $transaccion->monto_pago,
    //                       'fecha_adecsys''fecha_registro' => $date->set($transaccion->FechaFacturacion)->toString('Y-M-dTH:m:s')
                            'est_mediacion' => $transaccion->est_mediacion,
                            'cod_ente' => $transaccion->cod_ente,
                            'cod_agrupador' => $transaccion->cod_agrupador,
                            'codigo_adecsys' => $transaccion->codigo_adecsys
                        )
                    )
                );
                
                $obs = $this->_toXml($transaccion);

                Zend_Registry::get('logMed')->write(array(
                    'observacion' => $obs
                ));
                
                $pagos->guardarAvisosMediacion($transaccion->codigo_transaccion);
                
            } catch (Exception $exc) {
//                Zend_Registry::get('logMed')->write(array(
//                    'observacion' =>  $exc->getMessage()
//                ));
                $this->_saveLogError('codigo_transaccion: '.$transaccion->codigo_transaccion.
                        ' codigo_avisoweb: '.$transaccion->codigo_avisoweb);
            }
        
        }
        
        if(empty($this->_getConfig()->correo->disable)){
            if(!empty($this->_logError)){
                try {
                    $correoVendedor = Zend_Registry::get('mail');
                    $correoVendedor = new Zend_Mail('utf-8');
                    $correoVendedor->addTo($this->_getConfig()->confpagos->mail->admin, 'Admin')
                            ->addCc($this->_getConfig()->confpagos->mail->copia)
                            ->clearSubject()
                            ->setSubject('Reporte Mediacion')
                            ->setBodyHtml($this->_logError)
                            ->send();
                } catch (Devnet_Errorcron $exc) {
                    $exc->save();
                }
            }
        }
        
    }
    
    private function _toXml($obj)
    {
        $_xml = '';
        foreach($obj as $key => $val){
            $_xml .= '<' . $key . '>' . $val . '</' . $key . ">";
        }
        return "<datos>" . $_xml . "</datos>";
    }
    
    private function _saveLogError($msge, $salto = TRUE)
    {
        $this->_logError .= $msge;
        if ($salto) {
            $this->_logError .= "<br/>";
        }
    }
}

$mediacion = new Mediacion();