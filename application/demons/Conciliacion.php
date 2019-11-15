<?php
error_reporting(E_ALL);
/**
 * Description of conciliacion
 * 
 * Clase que consulta y elimina todas las solicitudes de pago que a generado o 
 * no el cliente con el sistema de Pago Efectivo
 *
 * @author Luis Mercado
 */
require 'init.php';
require_once 'Pagos.php';

define('spAE', "usp_ActualizarEstadoOperacionExitosa");
define('spAF', "usp_ActualizarEstadoOperacionFallida");
define('spA', "ActualizarValido");
////->v2 pago efectivo
//define('CipPendiente', "591");
//define('CipGenerado', "592");
//define('CipPagado', "593");
//define('CipExpirada', "594");
////->v1 pago efectivo
//define('CipV1Expirado', "21");
//define('CipV1Generada', "22");
//define('CipV1Cancelada', "23");
//define('CipV1Eliminado', "25");
////->v1 Pasarela
//define('PasarelaPagado', "PA");

define('CipPagadoV1', "Cancelada");

class Conciliacion
{
    
    /**
     *
     * @var array
     */
    private $_configPE;
    
    /**
     *
     * @var Pagos
     */
    private $_pagos;
    
    /**
     *
     * @var String
     */
    private $_log = '', $_logError = '', $_logEnvio = '';
    
    //Condicion pasarela
    /**
     * Primer valor Valido
     * Segundo valor Estado
     * Tercer valor ME
     */
    private $pasarela = array(
        '0' => array(
                '0' => array(
                        //array(spA, spAE, spAF),
                        'PA' => array(true, spA, spAE, ''),
                        'PE' => array(true, spA, '', spAF),
                        'NP' => array(true, spA, '', spAF),
                        'NE' => array(true, spA, '', spAF)
                    ),
                '1' => array(
                        'PA' => array(true, spA, '', ''),
                        'PE' => array(true, spA, '', spAF),
                        'NP' => array(true, spA, '', spAF),
                        'NE' => array(true, spA, '', spAF)
                    ),
                '2' => array(
                        'PA' => array(true, spA, spAE, ''),
                        'PE' => array(true, spA, '', ''),
                        'NP' => array(true, spA, '', ''),
                        'NE' => array(true, '', '', '')
                    )
            ),
        '1' => array(
                '0' => array(
                        'PA' => array(true, '', '', ''),
                        'PE' => array(true, '', '', ''),
                        'NP' => array(true, '', '', ''),
                        'NE' => array(true, '', '', '')
                    ),
                '1' => array(
                        'PA' => array(true, '', '', ''),
                        'PE' => array(true, '', '', spAF),
                        'NP' => array(true, '', '', spAF),
                        'NE' => array(true, '', '', spAF)
                    ),
                '2' => array(
                        'PA' => array(true, spA, spAE, ''),
                        'PE' => array(true, '', '', ''),
                        'NP' => array(true, '', '', ''),
                        'NE' => array(true, '', '', '')
                    )
            )
    );
    //Condicion PagoEfectivo v2
    private $pav2 = array(
        '0' => array(
                '0' => array(
                        '591' => array(true, '', '', ''),
                        '592' => array(true, '', '', ''),
                        '593' => array(true, spA, spAE, ''),
                        '594' => array(true, spA, '', spAF)
                    ),
                '1' => array(
                        '591' => array(true, spA, '', spAF),
                        '592' => array(true, spA, '', spAF),
                        '593' => array(true, spA, '', ''),
                        '594' => array(true, spA, '', spAF)
                    ),
                '2' => array(
                        '591' => array(true, spA, '', ''),
                        '592' => array(true, spA, '', ''),
                        '593' => array(true, spA, spAE, ''),
                        '594' => array(true, spA, '', '')
                    ),
            ),
        '1' => array(
                '0' => array(
                        '591' => array(true, '', '', ''),
                        '592' => array(true, '', '', ''),
                        '593' => array(true, '', '', ''),
                        '594' => array(true, '', '', '')
                    ),
                '1' => array(
                        '591' => array(true, spA, '', spAF),
                        '592' => array(true, spA, '', spAF),
                        '593' => array(true, '', '', ''),
                        '594' => array(true, spA, '', spAF)
                    ),
                '2' => array(
                        '591' => array(true, '', '', ''),
                        '592' => array(true, '', '', ''),
                        '593' => array(true, spA, spAE, ''),
                        '594' => array(true, '', '', '')
                    )
            )
    );
    //Condicion PagoEfectivo v1
    private $pav1 = array(
        '0' => array(
                '0' => array(
                        '22' => array(true, '', '', ''),
                        '23' => array(true, spA, spAE, ''),
                        '21' => array(true, spA, '', spAF),
                        '25' => array(true, spA, '', spAF)
                    ), 
                '1' => array(
                        '22' => array(true, spA, '', spAF),
                        '23' => array(true, spA, '', ''),
                        '21' => array(true, spA, '', spAF),
                        '25' => array(true, spA, '', spAF)
                    ), 
                '2' => array(
                        '22' => array(true, spA, '', ''),
                        '23' => array(true, spA, spAE, ''),
                        '21' => array(true, spA, '', ''),
                        '25' => array(true, spA, '', '')
                    ) 
            ),
        '1' => array(
                '0' => array(
                        '22' => array(true, '', '', ''),
                        '23' => array(true, '', '', ''),
                        '21' => array(true, '', '', ''),
                        '25' => array(true, '', '', '')
                    ), 
                '1' => array(
                        '22' => array(true, '', '', spAF),
                        '23' => array(true, '', '', ''),
                        '21' => array(true, '', '', spAF),
                        '25' => array(true, '', '', spAF)
                    ), 
                '2' => array(
                        '22' => array(true, '', '', ''),
                        '23' => array(true, spA, spAE, ''),
                        '21' => array(true, '', '', ''),
                        '25' => array(true, '', '', '')
                    ) 
            )
    );
    
    public function __construct()
    {
        $this->_pagos = new Pagos();
        $this->_configPE = $this->_getConfig()->pagoefectivo->toArray();
        $this->conciliarOrdenes();
    }
    
    private function _getConfig()
    {
        return Zend_Registry::get('config');
    }
    
    private function apArr50($arrI)
    {
        $arrI[1]++;
        if ($arrI[1] == 51) {
            $arrI[0]++;
            $arrI[1]=-1;
        }
        return $arrI;
    }
    
    private function conciliarOrdenes()
    {
        //->1er para el arr
        $arrI = array('PEV2'=>array(0,-1), 'PEV1'=>array(0,-1), 'PA'=>array(0,-1));
        $arrMP = array();
        
        $ordenesConciliar = $this->_pagos->getOrdenesaConciliar();
        $countDatos = count($ordenesConciliar);
        if (!empty($countDatos) && $countDatos != 0) {
            foreach ($ordenesConciliar as $orden) {
                $this->_saveLogEnvio($orden);
                $NumeroOrden = trim($orden->NumeroOrden);
                if ($orden->ServicioPago == 1 && empty($NumeroOrden)) {
                    $msg = 'Cip Liberado por Cron - Operacion no generada';
                    $this->_eliminarOperacion($orden->IdOperacion, $orden->IdCliente, $msg);
                } else {
                    if ($orden->ServicioPago == 1 && !empty($NumeroOrden)) {
                        if (!empty($orden->idResSolPago)) {
                            $medioPago = 'PEV2';
                            $arrI[$medioPago] = $this->apArr50($arrI[$medioPago]);
                            $arrMP[$medioPago][$arrI[$medioPago][0]]['NumeroOrden'][$arrI[$medioPago][1]] = 
                                $NumeroOrden;
                            $arrMP[$medioPago][$arrI[$medioPago][0]]['orden'][(string)$NumeroOrden] = $orden;
                        } else {
                            $medioPago = 'PEV1';
                            $arrI[$medioPago] = $this->apArr50($arrI[$medioPago]);
                            $arrMP[$medioPago][$arrI[$medioPago][0]]['NumeroOrden'][$arrI[$medioPago][1]] = 
                                $NumeroOrden;
                            $arrMP[$medioPago][$arrI[$medioPago][0]]['orden'][$NumeroOrden] = $orden;
                        }
                    } elseif ($orden->ServicioPago == 2 || $orden->ServicioPago == 3) {
                        $medioPago = 'PA';
                        $arrI[$medioPago] = $this->apArr50($arrI[$medioPago]);
                        $arrMP[$medioPago][$arrI[$medioPago][0]][$orden->IdOperacion] = $orden;
                    }
                }
            }
            
            if (!empty($arrMP['PEV2'])) {
                $this->_consultarSolicitudPagoEfectivoV1($arrMP['PEV2']);
            }
            if (!empty($arrMP['PEV1'])) {
                $this->_consultarSolicitudPagoEfectivoV1($arrMP['PEV1']);
            }
            if (!empty($arrMP['PA'])) {
                $this->_consultarOrdenesPasarela($arrMP['PA']);
            }
            
            //-->Registrando Log Envio
            if (!empty($this->_logEnvio)) {
                Zend_Registry::get('logCron')->write(
                    array(
                        'mensaje' => "Envio ".$this->_logEnvio,
                        'prioridad' => Zend_Log::WARN,
                        'metodo' => 'Operaciones Conciliadas'
                    )
                );
            }
            
            //-->Registrando Log
            if (!empty($this->_log)) {
                Zend_Registry::get('logCron')->write(
                    array(
                        'mensaje' => "Log ".$this->_log,
                        'prioridad' => Zend_Log::WARN,
                        'metodo' => 'Operaciones Conciliadas'
                    )
                );
            }
            
            if (empty($this->_getConfig()->correo->disable)) {
                if (!empty($this->_logError)) {
                    try {
                        $correoVendedor = Zend_Registry::get('mail');
                        $correoVendedor = new Zend_Mail('utf-8');
                        $correoVendedor->addTo($this->_getConfig()->confpagos->mail->admin, 'Admin')
                                ->addCc($this->_getConfig()->confpagos->mail->copia)
                                ->clearSubject()
                                ->setSubject('Reporte Conciliacion')
                                ->setBodyHtml($this->_logError)
                                ->send();
                    } catch (Devnet_Errorcron $exc) {
                        $exc->save();
                    }
                }
            }
        }
    }
    
    /**
     * Función que te permite consultar el estado de la solicitud por el parámetro
     * idResSolPagos dependiendo del estado si esta generado y/o pendiente
     * se procede a eliminar la solicitud de Pago en PE
     * 
     * @param array $orden 
     */
    private function _consultarSolicitudPagoEfectivoV2($orden)
    {
        try {
            $servicePE = new App_Service_PagoEfectivo($this->_configPE);
            $response = $servicePE->consultarSolicitudPagoV2((int)$orden->idResSolPago);
            if (!empty($response)) {
                $data['CodigoOrden'] = $orden->IdOperacion;
                $data['idTransP'] = $orden->NumeroOrden;
                $data['idUsuario'] = $orden->IdCliente;
                $data['carId'] = "";
                $data['paramPas'] = $orden->ServicioPago;
                $data['msg'] = 'Conciliado por cron';
                $arr = $this->pav2[$orden->Valido][$orden->Estado][(int)$response->Estado];
                $resultado = $this->_ejecSP($arr, $data, 'pe');
            } else {
                $this->_saveLogError(
                    'Se produjo un error al consultar con Pago Efectivo IdOperacion' . $orden->IdOperacion
                );
            }
        } catch (Devnet_Errorcron $exc) {
            echo $exc->getMessage();
            $exc->save();
        }
    }
    
    /**
     * Función que te permite consultar el estado de la solicitud por el parámetro
     * idResSolPagos dependiendo del estado si esta generado y/o pendiente
     * se procede a eliminar la solicitud de Pago en PE
     * 
     * @param array $orden 
     */
    private function _consultarSolicitudPagoEfectivoV1($arrInput)
    {
//        try {
        foreach ($arrInput as $value) {
                
            $cips = implode(",", $value['NumeroOrden']);
            $servicePE = new App_Service_PagoEfectivo($this->_configPE);
            $response = $servicePE->consultarSolicitudPagoV1($cips);
            
            if (!empty($response)) {
                foreach ($response->BEWSConsCIP as $valResp) {
                    if (!empty($valResp->NumeroOrdenPago)) {
                        $orden = $value['orden'][(string)$valResp->NumeroOrdenPago];
                        $orden->msg = "";
                        $arr = empty($this->pav1[$orden->Valido][$orden->Estado][(int)$valResp->IdEstado])?'':
                                        $this->pav1[$orden->Valido][$orden->Estado][(int)$valResp->IdEstado];
                        $resultado = $this->_ejecSP($arr, $orden, 'pe');
                    }
                }
            } else {
                $this->_saveLogError(
                    'Se produjo un error al consultar con Pago Efectivo IdOperacion' . $orden->IdOperacion
                );
            }
        }
//        } catch (Devnet_Errorcron $exc) {
//            $exc->save();
//        }
    }
    
    /**
     * Función que te permite liberar la operación para generar una nueva orden
     * por el cliente.
     * 
     * @param int $idOperacion
     * @param int $idCliente 
//     */
    
    private function _eliminarOperacion($idOperacion, $idCliente, $mensaje, $idTransP = 0)
    {
        try {
            $this->_pagos->registroOperacionFallida($idOperacion, $idTransP, $idCliente, $mensaje);
            $this->_pagos->updOperacionValidada($idOperacion);
            $this->_saveLog(
                'Libera la operación ' . $idOperacion . ' cliente ' . $idCliente . ' idTransPasa ' . $idTransP
            );
        } catch (Devnet_Errorcron $exc) {
            echo $exc->getMessage();
            $exc->save();
        }
    }
    
    private function _consultarOrdenesPasarela($arrInput)
    {
        $client = @new Zend_Soap_Client($this->_getConfig()->confpagos->pasarela->consultar);
        
//        try {
        foreach ($arrInput as $value) {
            $array = array('request' => array(
                    'CodServicio' => $this->_getConfig()->confpagos->pasarela->pCodigoTienda,
                    'ListaOrdenes' => array_keys($value)
                ));
            $response = $client->ConsultarOrdenes($array); 
            $response = $response->ConsultarOrdenesResult;
            if ($response->CodigoRespuesta == 1) {
                $ordenes = $response->ListaOrdenes->BEConsOrdenDetalle;
                if (is_object($ordenes)) {
                    if (!empty($ordenes->CodigoOrden)) {
                        $CodigoOrden = (int)$ordenes->CodigoOrden ;
                        $orden = $value[$CodigoOrden];
                        $orden->msg = (string)$ordenes->Mensaje;
                        $arr = $this->pasarela[$orden->Valido][$orden->Estado][(string)$ordenes->Estado];
                        $resultado = $this->_ejecSP($arr, $orden, 'pa');
                    }
                } else {
                    foreach ($ordenes as $value2) {
                        if (!empty($value2->CodigoOrden)) {
                            $CodigoOrden = (int)$value2->CodigoOrden;
                            $orden = $value[$CodigoOrden];
                            $orden->msg = (string)$value2->Mensaje;
                            $arr = $this->pasarela[$orden->Valido][$orden->Estado][(string)$value2->Estado];
                            $resultado = $this->_ejecSP($arr, $orden, 'pa');
                        }
                    }
                }
////                Zend_Registry::get('logCron')->write(array(
////                    'metodo' => 'ConsultarOrdenesPasarela',
////                    'trace' => $client->getLocation(),
////                    'request' => $client->getLastRequest(),
////                    'response' => $client->getLastResponse(),
////                    'url' => $client->getUri()
////                ));                 
            } else {
//                    Zend_Registry::get('logCron')->write(array(
//                    'mensaje' => "pasarela error", 
//                        'prioridad' => Zend_Log::WARN,
//                        'metodo' => 'Operaciones Conciliadas'
//                    ));
                $this->_saveLogError(
                    'Se produjo un error al consultar con pasarela ' . $response->MensajeRespuesta
                );
            }
            
//            if(!empty($response)){
//                foreach ($response->BEWSConsCIP as $valResp) {
//                    $orden = $value['orden'][(string)$valResp->NumeroOrdenPago];
//                    $data['CodigoOrden'] = $orden->IdOperacion;
//                    $data['idTransP'] = $orden->NumeroOrden;
//                    $data['idUsuario'] = $orden->IdCliente;
//                    $data['carId'] = "";
//                    $data['paramPas'] = $orden->ServicioPago;
//                    $data['msg'] = 'Concialido por cron';
//                    $arr = empty($this->pav1[$orden->Valido][$orden->Estado][(int)$valResp->IdEstado])?'':
//                                    $this->pav1[$orden->Valido][$orden->Estado][(int)$valResp->IdEstado];
//                    $resultado = $this->_ejecSP($arr, $data, 'pe');
//                }
//            } else {
//                $this->_saveLogError(
//                  'Se produjo un error al consultar con Pago Efectivo IdOperacion' . $orden->IdOperacion
//                );
//            }
        }
//        } catch (Devnet_Errorcron $exc) {
//            $exc->save();
//        }
    }
    
    private function _saveLog($msge, $salto = TRUE)
    {
        $this->_log .= $msge;
        if ($salto) {
            $this->_log .= "<br/>";
        }
    }
    
    private function _saveLogError($msge, $salto = TRUE)
    {
        $this->_logError .= $msge;
        if ($salto) {
            $this->_logError .= "<br/>";
        }
    }
    
    private function _saveLogEnvio($obj)
    {
        $_xml = '';
        foreach ($obj as $key => $val) {
            $_xml .= '<' . $key . '>' . $val . '</' . $key . ">";
        }
        $this->_logEnvio .= "<operacion>" . $_xml . "</operacion>";
    }
    
    private function _ejecSP($arr, $data, $mp)
    {
        if ($mp=='pe') {
            $msjLog = "Pago Efectivo ";
            $NumeroOrden = "";
        } else {
            $msjLog = "Pasarela ";
            $NumeroOrden = empty($data->NumeroOrden)?'':$data->NumeroOrden;
            //$idTransP = empty($data['idTransP'])?'':$data['idTransP'];
        }
        $IdOperacion = empty($data->IdOperacion)?'':$data->IdOperacion;
        $IdCliente = empty($data->IdCliente)?'':$data->IdCliente;
//        $CodigoOrden = empty($data['CodigoOrden'])?'':$data['CodigoOrden'];
//        $idUsuario = empty($data['idUsuario'])?'':$data['idUsuario'];
        
        if (empty($arr[0])) {
            $this->_saveLogError(
                'Se produjo un error al consultar con ' . $msjLog . 'IdOperacion: ' . 
                $IdOperacion.' IdCliente: '.$IdCliente
            );
        } else {
            $paramPas = empty($data->ServicioPago)?'':$data->ServicioPago;
//            $paramPas = empty($data['paramPas'])?'':$data['paramPas'];
            $pstrProcesadoPor='KWS';
            $msg = empty($data->msg)?'':$data->msg;
//            $msg = empty($data['msg'])?'':$data['msg'];
            $msgLog = false;
            if (!empty($arr[2]) && !empty($IdOperacion)) {
                $msgLog = true;
                $this->_pagos->registroOperacionExitosa(
                    $IdOperacion, $NumeroOrden, $IdCliente, $carId = "", $paramPas, $pstrProcesadoPor
                );
            }
            if (!empty($arr[3]) && !empty($IdOperacion)) {
                $msgLog = true;
                $this->_pagos->registroOperacionFallida(
                    $IdOperacion, $NumeroOrden, $IdCliente, $msg, $pstrProcesadoPor
                );
            }
            if (!empty($arr[1]) && !empty($IdOperacion)) {
                $msgLog = true;
                $this->_pagos->sp_validaConciliacion($IdOperacion);
            }
            if ($msgLog) {
                $this->_saveLog(
                    'Se concilia la orden de  '.$msjLog.'IdOperacion: '.$IdOperacion.' IdCliente: '.
                    $IdCliente
                );
            }
        }
    }   
}

$conciliacion = new Conciliacion();