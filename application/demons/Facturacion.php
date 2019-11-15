<?php
error_reporting(E_ALL);
/**
 * Description of conciliacion
 * 
 * Clase que consulta y elimina todas las solicitudes de pago que a generado o 
 * no el cliente con el sistema de Pago Efectivo
 *
 * @author Anderson Poccorpachi
 */
require 'init.php';
require_once 'Factura.php';

class Facturacion
{
    /**
     *
     * @var String
     */
    private $_logError = '';
    
    public function __construct()
    {
        $this->_factura = new Factura();
        //$this->_log = new LogCronFacturacion();
        $this->_serviceName = $this->_getConfig()->Adecsys->empresa->wsEmpresa;
        
        $this->ValidarClientes();
        $this->RegistrarClientes();
        $this->RegistrarAvisoAdecsys();
        
    }
    
    private function _getConfig()
    {
        return Zend_Registry::get('config');
    }
    
    private function RegistrarAvisoAdecsys()
    {
        $parametros = $this->_factura->getConsultarParametrosAdecsys();
        //$pstrSerie = $this->_factura->ConsultarCorrelativoAdecsys();
        
        $registros = $this->_factura->getConsultaCargosPendientesAdecsys();
        $countDatos = count($registros);
        if (!empty($countDatos) && $countDatos != 0) {
            $client = new Zend_Soap_Client($this->_serviceName);
            $fCargosProc = 0;
            $CodigoAdecsys = 0;
            foreach ($registros as $data) {
                try {
                    $idCliente = trim($data->idCliente);
                    $idCargo = trim($data->idCargo);
                    $input['oRegistroAvisoPref']['Importe'] = $data->monto;
                    $input['oRegistroAvisoPref']['Modulaje'] = $parametros['MODADE'];
                    $input['oRegistroAvisoPref']['Cod_Agencia'] = $parametros['CODAGE'];
                    $input['oRegistroAvisoPref']['Cod_Sede'] = $parametros['CODSED'];
                    $input['oRegistroAvisoPref']['Cod_Empresa'] = $parametros['CODEMP'];
                    $input['oRegistroAvisoPref']['Cod_Contrato'] = $parametros['CODCON'];
                    $input['oRegistroAvisoPref']['CanalVta'] = $parametros['CANVEN'];
                    $input['oRegistroAvisoPref']['Cod_Vendedor'] = $parametros['CODVEN'];
                    $input['oRegistroAvisoPref']['Id_Paquete'] = $parametros['CODPAQ'];
                    $input['oRegistroAvisoPref']['Id_num_solicitud'] = $parametros['NUMDOC'];
                    $input['oRegistroAvisoPref']['Id_Item'] = $parametros['CODITM'];
                    $input['oRegistroAvisoPref']['Cod_Aviso'] = $idCargo;
                    $input['oRegistroAvisoPref']['Correlativo'] = 1;
                    $input['oRegistroAvisoPref']['Cod_Cliente'] = $idCliente;
                    $input['oRegistroAvisoPref']['Tip_Doc'] = $data->tipoDocumento;
                    $input['oRegistroAvisoPref']['Num_Doc'] = $data->nroDocumento;
                    $input['oRegistroAvisoPref']['RznSoc_Nombre'] = $data->razonSocial;
                    $input['oRegistroAvisoPref']['Tipo_Aviso'] = $parametros['TIPAVI'];
                    $input['oRegistroAvisoPref']['Med_Pub_Id'] = $data->med_pub_id;
                    $input['oRegistroAvisoPref']['Cod_Med_Pub'] = $data->cod_med_pub;
                    $input['oRegistroAvisoPref']['Des_Med_Pub'] = $data->des_med_pub;
                    $input['oRegistroAvisoPref']['Pub_Id'] = $data->pub_id;
                    $input['oRegistroAvisoPref']['Cod_Pub'] = $data->cod_publicacion;
                    $input['oRegistroAvisoPref']['Des_Pub'] = $data->dsc_publicacion;
                    $input['oRegistroAvisoPref']['Edi_Id'] = $data->edi_id;
                    $input['oRegistroAvisoPref']['Cod_Edi'] = $data->cod_edicion;
                    $input['oRegistroAvisoPref']['Des_Edi'] = $data->dsc_edicion;
                    $input['oRegistroAvisoPref']['Sec_Id'] = $data->sec_id;
                    $input['oRegistroAvisoPref']['Cod_Sec'] = $data->cod_seccion;
                    $input['oRegistroAvisoPref']['Des_Sec'] = $data->dsc_seccion;
                    $input['oRegistroAvisoPref']['Sub_Sec_Id'] = $data->sub_sec_id;
                    $input['oRegistroAvisoPref']['Cod_Sub_Sec'] = $data->cod_subseccion;
                    $input['oRegistroAvisoPref']['Des_Sub_Sec'] = $data->dsc_subseccion;
                    $input['oRegistroAvisoPref']['Ubi_Id'] = $data->ubi_id;
                    $input['oRegistroAvisoPref']['Cod_Ubi'] = $data->cod_ubi;
                    $input['oRegistroAvisoPref']['Des_Ubi'] = $data->dsc_ubi;
                    $input['oRegistroAvisoPref']['Tar_Id'] = $data->tar_id;
                    $input['oRegistroAvisoPref']['Cod_Tar'] = $data->cod_tarifa;
                    $input['oRegistroAvisoPref']['Des_Tar'] = $data->dsc_tarifa;
                    $input['oRegistroAvisoPref']['Prim_Fec_Pub'] = $data->prim_fec_pub;
                    $input['oRegistroAvisoPref']['Fechas_Pub'] = $data->fechas_pub;
                    $input['oRegistroAvisoPref']['Cant_Fechas_Pub'] = $parametros['CANFEC'];
                    
                    $input['oRegistroAvisoPref']['Fechas_Pub_Aviso'] = array($data->fechas_pub_aviso);
                    $input['oRegistroAvisoPref']['Form_Pago'] = $parametros['FOMPAG'];
                    $input['oRegistroAvisoPref']['Cod_Moneda'] = $parametros['CODMON'];
                    $input['oRegistroAvisoPref']['Fec_Registro'] = $data->fec_registro;
                    $input['oRegistroAvisoPref']['Email_Contacto'] = $data->email;
                    $input['oRegistroAvisoPref']['Modulo'] = $parametros['MODULO'];
                    
                    $input['oRegistroAvisoPref']['Tit_Aviso'] = $data->tituloAviso;
                    $input['oRegistroAvisoPref']['Med_Id'] = trim($data->med_id);
                    $input['oRegistroAvisoPref']['Des_Med'] = $data->dsc_medida;
                    $input['oRegistroAvisoPref']['Med_Horizontal'] = trim($data->nroMod);
                    $input['oRegistroAvisoPref']['Med_Vertical'] = trim($data->nroCol);
                    $input['oRegistroAvisoPref']['Nom_Contacto'] = $data->nombres;
                    $input['oRegistroAvisoPref']['Ape_Pat_Contacto'] = $data->apePaterno;
                    $input['oRegistroAvisoPref']['Ape_Mat_Contacto'] = $data->apeMaterno;
                    $input['oRegistroAvisoPref']['Telf_Contacto'] = $data->telefono;
                    $input['oRegistroAvisoPref']['Des_Adicional'] = $parametros['DESCRP'];
//                    $input['oRegistroAvisoPref']['Puestos_Aviso'] = array();
                    $input['oRegistroAvisoPref']['Val_Moneda'] = $parametros['VALMON'];
                    $input['oRegistroAvisoPref']['Cod_ExtraCargos'] = '';
                    $input['oRegistroAvisoPref']['Hra_Despacho'] = $data->hra_despacho;
                    //$pstrSerie++;
                    $input['oRegistroAvisoPref']['Cod_DireccdDspacho'] = $data->serie;
                    $input['oRegistroAvisoPref']['Hra_Despacho'] = $parametros['HRDESP'];
//                    $input['oRegistroAvisoPref']['Cod_DireccdDspacho'] = $pstrSerie;

                    $_xml = $this->_toXml($input['oRegistroAvisoPref']);
                    
                    $resultado = $client->Registrar_Aviso_Pref($input);
                    
//                    $ander = serialize($resultado);
//                    Zend_Registry::get('logFact')->write(array(
//                        'Observacion' => $ander,
//                        'IdCliente' => 0,
//                        'IdCargo' => 0,
//                        'CodigoAdecsys' => 0
//                    ));
                    
                    $CodigoAdecsys = empty($resultado->Registrar_Aviso_PrefResult)?0:
                                        $resultado->Registrar_Aviso_PrefResult;
                    
                    if (!empty($CodigoAdecsys)) {
                        $input['pstrCodigoAdecsys'] = $CodigoAdecsys;
                        $input['pintIdCargo'] = $idCargo;
                        $input['pintEstado'] = 1;
                        $this->_factura->ActCargo_CodigoAdecsys($input);
                    }
                    
                    //$data->pstrSerie = $data->serie;
                    $input['pintIdCliente'] = $data->idCliente;
                    $input['pintIdCargo'] = 0;
                    $input['pstrXML'] = $_xml;
                    $input['pintCodigoAdecsys'] = $CodigoAdecsys;
                    $this->_factura->ActualizarLogFacturacion($input);
                    $fCargosProc++;
                    
                    if (empty($CodigoAdecsys)) {
                        Throw New Exception("No se puede registrar aviso en Adecsys");
                    }
                            
                } catch (Exception $exc) {
//                    $pstrSerie--;
                    $this->_saveLogError(
                        'ERROR RegistrarAvisoAdecsys - idCargo: ' . $data->idCargo . ' MSG: ' . 
                        $exc->getMessage()
                    );
//                    Zend_Registry::get('logFact')->write(array(
//                        'Observacion' => 'ERROR RegistrarAvisoAdecsys - idCargo: '.$data->idCargo . 
//                          ' MSG: '.$exc->getMessage(),
//                        'IdCliente' => 0,
//                        'IdCargo' => 0,
//                        'CodigoAdecsys' => 0
//                    ));
                }
                
//                Zend_Registry::get('logFact')->write(array(
//                    'IdCliente' => $idCliente,
//                    'IdCargo' => $idCargo,
//                    'Observacion' => $_xml,
//                    'CodigoAdecsys' => $CodigoAdecsys
//                ));
                
//                $this->EnviarEmailNoProcesar();
            }
            
            $dateNow = date("Y-m-d H:i:s");
            $input['FechaInicio'] = $dateNow;
            $input['FechaFin'] = $dateNow;
            $input['Encargado'] = 'KWS';
            $input['CantCargosInicio'] = $countDatos;
            $input['CantCargosFinal'] = $fCargosProc;
            $input['Descripcion'] =  "Cierre Contable - Intento 1";
            $this->_factura->CicloContable($input);
//            
//            $input['pintSerie'] = $pstrSerie;//($pstrSerie+1);
//            $this->_factura->ActualizarCorrelativoAdecsys($input);
            
            if (empty($this->_getConfig()->correo->disable)) {
                if (!empty($this->_logError)) {
                    try {
                        $correoVendedor = Zend_Registry::get('mail');
                        $correoVendedor = new Zend_Mail('utf-8');
                        $correoVendedor->addTo($this->_getConfig()->confpagos->mail->admin, 'Admin')
                                ->addCc($this->_getConfig()->confpagos->mail->copia)
                                ->clearSubject()
                                ->setSubject('Error Registrar Aviso Adecsys')
                                ->setBodyHtml($this->_logError)
                                ->send();
                    } catch (Devnet_Errorcron $exc) {
                        $exc->save();
                    }
                }
            }
            
        }
    }
    
    
    private function _saveLogError($msge, $salto = TRUE)
    {
        $this->_logError .= $msge;
        if ($salto) {
            $this->_logError .= "<br/>";
        }
    }
    
    private function RegistrarClientes()
    {
        $registros = $this->_factura->getConsultarDatosFacturacionPorCodigoEnteNull();
        $countDatos = count($registros);
        
        if (!empty($countDatos) && $countDatos != 0) {
            
            //$client = new Zend_Soap_Client($this->_serviceName, array('encoding' => 'ISO-8859-1'));
            $client = new Zend_Soap_Client($this->_serviceName);
            foreach ($registros as $data) {
                try {
                    $input['Tipo_Documento'] = trim($data->TipoDocumento);
                    $input['Numero_Documento'] = "";
                    $input['Ape_Paterno'] = $data->TipoPersona=='NATURAL'? $data->ApePaterno:"";
                    $input['Ape_Materno'] = $data->TipoPersona=='NATURAL'? $data->ApeMaterno:"";
                    $input['Nombres_RznSocial'] = "";
                    $input['Email'] = $data->Email;
                    $input['Telefono'] = trim($data->Telefono);
                    $input['Tipo_Cen_Poblado'] = $data->IdCentroPoblado;
                    $input['Nombre_Cen_Poblado'] = $data->NombreCentroPoblado;
                    $input['Tipo_Calle'] = $data->IdTipoCalle;
                    $input['Nombre_Calle'] = $data->Direccion;
                    $input['Numero_Puerta'] = $data->Numero;
                    $input['CodCiudad'] = $data->IdCiudad;
                    $input['Nombre_RznComc'] = "";
                    $RUC = trim($data->RUC);
                    $DNI = trim($data->DNI);
                    
                    if (!empty($RUC) && $input['Tipo_Documento'] == 'RUC') {
                        $input['Numero_Documento'] = $RUC;                        
                        $input['Nombres_RznSocial'] = $data->TipoPersona=='NATURAL'? $data->Nombres: $data->RazonSocial;
                        $input['Nombre_RznComc'] = $data->TipoPersona=='NATURAL'? '': $data->RazonSocial;
                        
                    } elseif (!empty($DNI) && $input['Tipo_Documento'] == 'DNI') {
                        $input['Numero_Documento'] = $DNI;                        
                        $input['Nombres_RznSocial'] = $data->Nombres;
                    } else {
                        Throw New Exception("El registro en DatosFacturacion no contiene RUC ni DNI");
                    }
                    
                    $input['K_IdCliente'] = $data->IdCliente;
                    $input['K_XML'] = $this->_toXml($data);
                    $this->_factura->ActualizarLogEnte($input);
                    
                    $result = $client->Registrar_Cliente($input);
                    
                    $input['pidCliente'] = $data->IdCliente;
                    $input['pCodigoEnte'] = $result->Registrar_ClienteResult;
                    if(!$this->_factura->ActualizarCodigoEnte($input))
                        Throw New Exception("El registro del Cliente falló");
                    
                    $array = array('Tipo_Documento' => $input['Tipo_Documento'],
                                   'Numero_Documento' => $input['Numero_Documento']
                             );
                    
                    $result = $client->Validar_Cliente($array);
                    
                    $EnteWs = $result->Validar_ClienteResult;
                    if (!empty($EnteWs)) {
                        $input['K_IDCLIENTE'] = $data->IdCliente;
                        $input['K_COD_DIRECCION'] = $EnteWs->Cod_Direccion;
                        $input['K_COD_ENTE'] = $EnteWs->Id;
                        $this->_factura->getCipOperacionByIdaviso($input);
                    }
                    
                        
                } catch (Exception $exc) {
                    $this->_saveLogError(
                        'ERROR RegistrarClientes IdCliente: '.$data->IdCliente.'- MSG: '.$exc->getMessage()
                    );
//                    Zend_Registry::get('logFact')->write(array(
//                        'Observacion' => 'ERROR RegistrarClientes - MSG: '.$exc->getMessage(),
//                        'IdCliente' => $data->IdCliente,
//                        'IdCargo' => 0,'CodigoAdecsys' => 0
//                    ));
                }
            }
            if (empty($this->_getConfig()->correo->disable)) {
                if (!empty($this->_logError)) {
                    try {
                        $correoVendedor = Zend_Registry::get('mail');
                        $correoVendedor = new Zend_Mail('utf-8');
                        $correoVendedor->addTo($this->_getConfig()->confpagos->mail->admin, 'Admin')
                                ->addCc($this->_getConfig()->confpagos->mail->copia)
                                ->clearSubject()
                                ->setSubject('Error Registrar Clientes')
                                ->setBodyHtml($this->_logError)
                                ->send();
                    } catch (Devnet_Errorcron $exc) {
                        $exc->save();
                    }
                }
                $this->_logError = "";
            }
        }
    }
    
    private function ValidarClientes()
    {
        $ordenesConciliar = $this->_factura->getClientesNoMigrados();
        $countDatos = count($ordenesConciliar);
        
        if (!empty($countDatos) && $countDatos != 0) {
            $client = new Zend_Soap_Client($this->_serviceName);
            
            foreach ($ordenesConciliar as $orden) {
                try {
                    $array = array('Tipo_Documento' => $orden->TipoDocumento,
                                   'Numero_Documento' => $orden->NumeroDocumento
                             );
                    $result = $client->Validar_Cliente($array);
                    $EnteWs = $result->Validar_ClienteResult;
                    if (!empty($EnteWs)) {
                        $input['K_IDCLIENTE'] = $orden->IdCliente;
                        $input['K_COD_DIRECCION'] = $EnteWs->Cod_Direccion;
                        $input['K_COD_ENTE'] = $EnteWs->Id;
                        $this->_factura->getCipOperacionByIdaviso($input);
                    }
                } catch (Exception $exc) {
                    $this->_saveLogError(
                        'ERROR RegistrarClientes IdCliente: '.$orden->IdCliente.'- MSG: '.$exc->getMessage()
                    );
//                    Zend_Registry::get('logFact')->write(array(
//                        'Observacion' => 'ERROR ValidarClientes - MSG: '.$exc->getMessage(),
//                        'IdCliente' => $orden->IdCliente,
//                        'IdCargo' => 0,'CodigoAdecsys' => 0
//                    ));
                }
            }
            if (empty($this->_getConfig()->correo->disable)) {
                if (!empty($this->_logError)) {
                    try {
                        $correoVendedor = Zend_Registry::get('mail');
                        $correoVendedor = new Zend_Mail('utf-8');
                        $correoVendedor->addTo($this->_getConfig()->confpagos->mail->admin, 'Admin')
                                ->addCc($this->_getConfig()->confpagos->mail->copia)
                                ->clearSubject()
                                ->setSubject('Error Validar Clientes')
                                ->setBodyHtml($this->_logError)
                                ->send();
                    } catch (Devnet_Errorcron $exc) {
                        $exc->save();
                    }
                }
                $this->_logError = "";
            }
        }
    }
    
    private function _toXml($obj)
    {
        $_xml = '';
        foreach ($obj as $key => $val) {
            $_xml .= '<' . $key . '>' . $val . '</' . $key . ">";
        }
        return "<cargos>" . $_xml . "</cargos>";
    }
    
}

$objeto = new Facturacion();
