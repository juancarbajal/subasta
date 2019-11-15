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
require_once 'TransaccionCierre.php';
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

class Pagos_IndexController
extends Devnet_Controller_Action {

 function init() {
        parent::init();
        if($this->getRequest()->getActionName() != 'pago-efectivo-termino' 
            && $this->getRequest()->getActionName() != 'pago-efectivo-urlok' 
            && $this->getRequest()->getActionName() != 'pago-efectivo-error'
            && $this->getRequest()->getActionName() != 'url-confirmacion-pago-efectivo-v') {            
            if(!isset($this->identity->ID_USR)) {
                $this->session->requiereLoginUrl = $_SERVER['REQUEST_URI'];
                $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
            }
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function cargosPagadosAction() {

        require_once 'Pagos.php';
        $pagos = new Pagos();
        $script =                "function imprimir(){"
				. sprintf("var v = window.open('%s')", $this->view->baseUrl() . "'/pagos/index/imprimir-cargos-pagados/tipo/'+getElementById(tipoCuenta)+'/cod/'+getElementById(consolidaddoMes)")
				. "}\n";
        $this->view->headScript()->appendScript($script);
        
        $this->view->HeadTitle = ('Estado de Cuenta / Kotear');
        $this->view->transacciones = $pagos->getComisionesPagadas($this->identity->ID_USR);        $this->view->saldos = array();
        $this->view->total = 0;
        $this->view->mesConsolidado=array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
        for($i = count($this->view->transacciones)-1; $i >= 0; $i--){
            $this->view->saldos[$this->view->transacciones[$i]->ID] = $this->view->transacciones[$i]->Monto + $this->view->total;
            $this->view->total = $this->view->saldos[$this->view->transacciones[$i]->ID];
        }
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        $anio=$this->anioConsolidado();
       $this->view->anioConsolidado=$anio;
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function detalleestadoAction() {
        $this->_helper->layout->setLayout('clear');
        $this->view->transacciones = $this->getTransacciones($this->identity->ID_USR,1);
    }

    /**
     *  Paso 1: Ingreso de Datos a el formulario Registro
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getTransacciones ($idUsuario, $flag) {
        $transacciones = new TransaccionCierre();
        return $transacciones->getTransacciones($idUsuario, $flag);
    }

    function detalleTransaccionAction()
    {
        //echo $this->_request->getParam('cod') . '<br/>' . $this->identity->ID_USR. '<br/>'. $this->_request->getParam('tipo');
        
        //echo $this->_request->getParam('cod') . '<br/>' . $this->identity->ID_USR. '<br/>'. $this->_request->getParam('tipo'). '<br/>'. $this->_request->getParam('anio');
        //exit;

        $this->_helper->layout->setLayout('clear');

        require_once 'Pagos.php';
        $pagos = new Pagos();
        $resultCabezera = $pagos->getComisionesCabezera($this->identity->ID_USR,
                                                       $this->_request->getParam('tipo'),
                                                       $this->_request->getParam('cod'));

        $resultDetalle  = $pagos->getDetalleComisionesPagadas($this->identity->ID_USR,
                                                              $this->_request->getParam('tipo'),
                                                              $this->_request->getParam('cod'),
                                                              $this->_request->getParam('anio'));
        

        
        $this->view->resultDetalle = $resultDetalle;
        $this->view->resultCabezera = $resultCabezera[0];
        $this->view->tipo=$this->_request->getParam('tipo');
        $this->view->cod=$this->_request->getParam('cod');
        $this->view->anio = date('Y');
        $meses = $this->getMeses();
        $dias = $this->getDias($this->view->anio);
        $this->view->mes= $meses[$this->view->cod];
        $this->view->ultimoDia = $dias[$this->view->cod];
    }
    function getMeses(){
       return array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
    }
    function getDias($anio){
       $dias = array(1=>31,2=>28,3=>31,4=>30,5=>31,6=>30,7=>31,8=>31,9=>30,10=>31,11=>30, 12=>31);
       if (($anio % 4 == 0) && ($anio %10 != 0)) $dias[2]=29;
       return $dias;
    }
    function detallePendientePagoAction()
    {
        $this->_helper->layout->setLayout('clear');
         require_once 'Pagos.php';
         $pagos = new Pagos();
         $resultDetalle  = $pagos->getDetalleTransaccion($this->_request->getParam('cod'),$this->identity->ID_USR);
       // print_r($resultDetalle);
        $this->view->resultDetalle = $resultDetalle;
    }

    function pendienteDePagoAction() {
        /*Borramos Cache por el bug*/
        $this->getResponse()->setHeader('Expires', '0', true)
             ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
             ->setHeader('Pragma', 'no-cache', true);

        $this->view->headTitle('Pendiente de Pago | Kotear.pe');
        $transacciones = new TransaccionCierre();
        $this->view->filtro1 = $this->_request->getParam('filtro1');
        //$this->view->transacciones = $transacciones->getpendienteDePago($this->identity->ID_USR, $this->_request->getParam('filtro1'));
        $trans = $transacciones->getpendienteDePago($this->identity->ID_USR, $this->_request->getParam('filtro1'));
        $this->view->saldos = array();
        $this->view->total = 0;
        $this->view->comisiones = array('Monto' => 0);
        $existeComisionSinAgrupar = null;
       //Generamos los saldos pendientes
        for ( $i=0; $i<count($trans); $i++ ){ 
            if ($trans[$i]->TipoTransaccion == 0) {
                if (!isset($existeComisionSinAgrupar) ) 
                    $existeComisionSinAgrupar = $i;
                else {
                    $trans[$existeComisionSinAgrupar]->Monto+=$trans[$i]->Monto;
                    unset($trans[$i]);
                } //end if
            } 
        } // end for
        $this->view->transacciones = array();
        foreach($trans as $row)  $this->view->transacciones[]=$row;
        //print_r( $this->view->transacciones);
        for($i = count($this->view->transacciones)-1; $i >= 0; $i--) {
            $id = $this->view->transacciones[$i]->ID;
            $this->view->saldos[$id] = $this->view->transacciones[$i]->Monto + $this->view->total;
            $this->view->total = $this->view->saldos[$id];
         } //end for
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        require_once 'KotearPagos.php';
        $kp = new KotearPagos();
        $this->view->pagar = $wkp['kotearPagos']['web'] . '/' . $kp->encriptar($this->identity->ID_USR);
    }
    function cargosfacturarAction() {

    }
    function cargoshistoricosAction() {

    }
    function datosfacturarAction() {

    }
    function datosFacturacionAction()
    {

      $this->getResponse()->setHeader('Expires', '0', true)
        ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
        ->setHeader('Pragma', 'no-cache', true);
      $this->view->headScript()->appendScript('$(document).ready(function(){ $.Kotear.validarProcesoPago(); });');
      require_once 'Pagos.php';
      $pagos = new Pagos();
      $this->paramsPost = 0;

      if($this->_request->isPost())
        {
    
          $validator = new Devnet_Validator();
          //valida el tipo de documento
          $tipoDocumento = new Zend_Validate();
          $tipoDocumento->addValidator(new Zend_Validate_Digits());
          $validator->add('tipoDocumento',$tipoDocumento,'Tipo de Documento');


            
          //valida el numero de documento dependiendo si es dni o ruc
          $numDoc = new Zend_Validate();
          if($this->_request->getParam('tipoDocumento')==1)
            $numDoc->addValidator(new Zend_Validate_Digits())->addValidator(new Zend_Validate_StringLength(11, 11));
          if($this->_request->getParam('tipoDocumento')==2)
            $numDoc->addValidator(new Zend_Validate_Digits())->addValidator(new Zend_Validate_StringLength(8, 8));
          $validator->add('numDoc',$numDoc,'Número de Documento');
            
          //valida el correo electronico
          $email = new Zend_Validate();
          $email->addValidator(new Zend_Validate_EmailAddress());
          $validator->add('email', $email,'Email');

          //valida la razon social
          if($this->_request->getParam('tipoDocumento')==1){

            $razonSocial = new Zend_Validate();
            $razonSocial->addValidator(new Zend_validate_Alpha(true));
            $validator->add('razonSocial', $razonSocial,'Razón Social');
          }

          //valida el apellido paterno
          if($this->_request->getParam('tipoDocumento')==2){
            $ApePatValidator = new Zend_Validate();
            $ApePatValidator->addvalidator(new Zend_Validate_NotEmpty());
            $validator->add('apePat', $ApePatValidator,'Apellido Paterno');
            
            //valida el apellido materno
            $ApeMatValidator = new Zend_Validate();
            $ApeMatValidator->addvalidator(new Zend_Validate_NotEmpty());
            $validator->add('apeMat', $ApeMatValidator,'Apellido Materno');

            //valida el tipo de nombre
            $nombre = new Zend_Validate();
            $nombre->addValidator(new Zend_Validate_StringLength(3, 200));
            $validator->add('nombre',$nombre,'Nombres');
          }

          //valida el telefono
          $telefonoValidator = new Zend_Validate();
          $telefonoValidator->addValidator(new Zend_Validate_Digits())->addValidator(new Zend_Validate_StringLength(9, 9));
          $validator->add('telefono', $telefonoValidator,'Teléfono');

          //valida el departamento
          $departamentoValidator = new Zend_Validate();
          $departamentoValidator->addValidator(new Zend_Validate_NotEmpty);
          $validator->add('departamento', $departamentoValidator,'Departamento');

          //valida el distrito
          $distritoValidator = new Zend_Validate();
          $distritoValidator->addValidator(new Zend_Validate_NotEmpty);
          $validator->add('distrito', $distritoValidator,'Distrito');

          //valida la urbanizacion
          $urbanizacionValidator = new Zend_Validate();
          $urbanizacionValidator->addValidator(new Zend_Validate_NotEmpty);
          $validator->add('urbanizacion', $urbanizacionValidator,'Urbanización');

          //valida El nombre de la urbanizacion
          $nomUrbanizacionValidator = new Zend_Validate();
          $nomUrbanizacionValidator->addValidator(new Zend_Validate_NotEmpty);
          $validator->add('nomUrbanizacion', $nomUrbanizacionValidator,'Nombre Urbanización');

          //valida el tipo de calle
          $tipoCalleValidator = new Zend_Validate();
          $tipoCalleValidator->addValidator(new Zend_Validate_NotEmpty);
          $validator->add('tipoCalle', $tipoCalleValidator,'Tipo de calle');

          //valida el Nombre de la calle
          $nomCalleValidator = new Zend_Validate();
          $nomCalleValidator->addValidator(new Zend_Validate_NotEmpty);
          $validator->add('nomCalle', $nomCalleValidator,'Nombre Calle');

          //valida el numero o direccion
          $numeroValidator = new Zend_Validate();
          $numeroValidator->addValidator(new Zend_Validate_NotEmpty);
          $validator->add('numero', $numeroValidator,'Número');
 
          if (!$validator->isValid($this->_request->getParams())) {
            $this->view->errors = $validator->getErrors();
            $this->paramsPost = $this->_request->getParams();
          } else {

            $this->session->paramsPost = $this->_request->getParams();
            $this->_redirect($this->view->baseUrl() . '/pagos/index/registro-datos-fac');
          }
       
        }
        $frontController = Zend_Controller_Front::getInstance();
        $param = $frontController->getParam('bootstrap')->getOption('app');
        $this->view->departamento = $pagos->getDepatamentoDatosFac($param['pais']);
        $this->view->centrosPoblados = $pagos->getCentrosPobladosFac();
        $this->view->tipoCalle = $pagos->getTipoCalleFac();        
        $datosUsuraioFac = $pagos->getDatosClienteFac($this->identity->ID_USR);
        if (count($this->paramsPost)) { //Mostrar datos por defecto        
            if (count($datosUsuraioFac) == 0) {                
                $datosUsuario['ID_USR'] = $this->identity->ID_USR;
                $datosUsuario['APEPAT'] = $this->identity->APEL;
                $datosUsuario['APEMAT'] = $this->identity->APEL;
                $datosUsuario['NOM'] = $this->identity->NOM;
                $datosUsuario['EMAIL'] = $this->identity->EMAIL;
                $arrayfono = explode('-', $this->identity->FONO1);
                if ($arrayfono[1] == '')
                    $datosUsuario['FONO'] = $arrayfono[0];
                else
                    $datosUsuario['FONO'] = $arrayfono[1];

                if ($this->identity->ID_TIPO_DOC == '05') {
                    $datosUsuario['ID_TIPO_DOC'] = 2;
                } elseif ($this->identity->ID_TIPO_DOC == '07') {
                    $datosUsuario['ID_TIPO_DOC'] = 1;
                }
                //echo $datosUsuario[ID_TIPO_DOC];

                $datosUsuario['NRO_DOC'] = $this->identity->NRO_DOC;
                /*var_dump($datosUsuario);
                var_dump(count($this->paramsPost));*/

                $this->view->update = true;
            } else {
                $datosUsuario['RAZON_SOCIAL'] = $datosUsuraioFac->RazonSocial;
                $datosUsuario['APEPAT'] = $datosUsuraioFac->ApePaterno;
                $datosUsuario['APEMAT'] = $datosUsuraioFac->ApeMaterno;
                $datosUsuario['NOM'] = $datosUsuraioFac->Nombre;
                $datosUsuario['EMAIL'] = $datosUsuraioFac->Email;
                $datosUsuario['FONO'] = $datosUsuraioFac->Telefono;
                if ($datosUsuraioFac->RUC != "") {
                    $datosUsuario['NRO_DOC'] = $datosUsuraioFac->RUC;
                    $datosUsuario['ID_TIPO_DOC'] = 1;
                }elseif ($datosUsuraioFac->DNI != "") {
                    $datosUsuario['NRO_DOC'] = $datosUsuraioFac->DNI;
                    $datosUsuario['ID_TIPO_DOC'] = 2;
                }
                $datosUsuario['NRO_DIRECCION'] = $datosUsuraioFac->Numero;
                $datosUsuario['COD_DPTA'] = $datosUsuraioFac->IdDepartamento;
                $datosUsuario['NOM_DPTA'] = $datosUsuraioFac->NombreDepartamento;
                $datosUsuario['COD_DIST'] = $datosUsuraioFac->IdDistrito;
                $datosUsuario['NOM_DIST'] = $datosUsuraioFac->NombreDistrito;
                $datosUsuario['COD_TIPO_CALLE'] = $datosUsuraioFac->IdTipoCalle;
                $datosUsuario['NOM_TIPO_CALLE'] = $datosUsuraioFac->NombreTipoCalle;
                $datosUsuario['NOM_COD_URB'] = $datosUsuraioFac->DescCentroPoblado;
                $datosUsuario['COD_URB'] = $datosUsuraioFac->IdCentroPoblado;
                $datosUsuario['NOM_URB'] = $datosUsuraioFac->NombreCentroPoblado;
                $datosUsuario['NOM_CALLE'] = $datosUsuraioFac->Direccion;
                $datosUsuario['NUMERO'] = $datosUsuraioFac->Numero;
                $this->view->update = false;
                /*var_dump($datosUsuraioFac);*/

            }
        } else { 
            //echo $this->paramsPost['email'];
            $datosUsuario['APEPAT'] = $this->paramsPost['apePat'];
            $datosUsuario['APEMAT'] = $this->paramsPost['apeMat'];
            $datosUsuario['RAZON_SOCIAL'] = $this->paramsPost['razonSocial'];
            $datosUsuario['NOM'] = $this->paramsPost['nombre'];
            $datosUsuario['EMAIL'] = $this->paramsPost['email'];
            $datosUsuario['FONO'] = $this->paramsPost['telefono'];
            $datosUsuario['ID_TIPO_DOC'] = $this->paramsPost['tipoDocumento'];
            $datosUsuario['NRO_DOC'] = $this->paramsPost['numDoc'];
            $datosUsuario['NRO_DIRECCION'] = $this->paramsPost['numero'];
            $datosUsuario['COD_DPTA'] = $this->paramsPost['departamento'];
            $datosUsuario['COD_DIST'] = $this->paramsPost['distrito'];
            $datosUsuario['COD_TIPO_CALLE'] = $this->paramsPost['tipoCalle'];
            $datosUsuario['NOM_CALLE'] = $this->paramsPost['nomCalle'];
            $datosUsuario['NUMERO'] = $this->paramsPost['numero'];
            $datosUsuario['COD_URB'] = $this->paramsPost['urbanizacion'];
            $datosUsuario['NOM_URB'] = $this->paramsPost['nomUrbanizacion'];
            $this->view->update = true;
        }
        $this->view->datosUsuario = $datosUsuario;
        $this->view->headScript()->appendScript("f_getDatosDistrito('{$datosUsuario['COD_DIST']}');");
   

    }
    function registroDatosFacAction()
    {
        require_once 'Pagos.php';
        $pagos = new Pagos();
        //print_r($this->session->paramsPost);
        $flagDatos=$pagos->setDatosClienteFac($this->identity->ID_USR,
                                  ($this->session->paramsPost['tipoDocumento']==1)?$this->session->paramsPost['numDoc']:'',
                                  ($this->session->paramsPost['tipoDocumento']==1)?$this->session->paramsPost['razonSocial']:'',
                                  $this->session->paramsPost['tipoCalle'],
                                  $this->session->paramsPost['nomCalle'],
                                  $this->session->paramsPost['numero'],
                                  $this->session->paramsPost['urbanizacion'],
                                  $this->session->paramsPost['nomUrbanizacion'],
                                  $this->session->paramsPost['departamento'],
                                  $this->session->paramsPost['distrito'],
                                  '',
                                  ($this->session->paramsPost['tipoDocumento']==2)?$this->session->paramsPost['nombre']:'',
                                  ($this->session->paramsPost['tipoDocumento']==2)?$this->session->paramsPost['apePat']:'',
                                  ($this->session->paramsPost['tipoDocumento']==2)?$this->session->paramsPost['apeMat']:'',
                                  ($this->session->paramsPost['tipoDocumento']==2)?$this->session->paramsPost['numDoc']:'',
                                  $this->session->paramsPost['email'],
                                  $this->session->paramsPost['telefono']);
        $this->view->error=$flagDatos->error;
        unset($this->session->paramsPost);
        
    }
    function ajaxGetDistritoAction()
    {   require_once 'Pagos.php';
        $pagos = new Pagos();
        $frontController = Zend_Controller_Front::getInstance();
        $param = $frontController->getParam('bootstrap')->getOption('app');

        if ($this->_request->isXMLHttpRequest()) {
            $this->json($pagos->getDistritoDatosFac($param['pais'],$this->_request->getParam('departamento')));
        }
    }
    

    function seleccionMedioPagoAction() {
        $this->getResponse()->setHeader('Expires', '0', true)
             ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
             ->setHeader('Pragma', 'no-cache', true);
        $this->_helper->layout->setLayout('clear');
        $id =$this->_request->getParam('id','');
        if (!empty($id)) {
            $this->identity->ID_USR;
            require_once 'Pagos.php';
            $pagos = new Pagos();
            $result = $pagos->getDetalleTransaccion($id, $this->identity->ID_USR);
            $total = 0;
            foreach($result as $row):
                $total += $row->Monto;
            endforeach;
       
            $this->view->result=$result;
            $this->view->ids=$id;
            $this->view->monto=$total;
         
        }
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function kotearPagosEstadoCuentaAction()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        $this->view->rutaKotearPagos = $wkp['kotearPagosEstadoCuenta']['web'];
        require_once 'KotearPagos.php';
        $kp = new KotearPagos();
        $this->view->idenc = $kp->encriptar($this->identity->ID_USR);
    }

    public function kotearPagosPagoDestaquesAction()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $wkp = $frontController->getParam('bootstrap')->getOption('wsdl');
        $this->view->rutaKotearPagos = $wkp['kotearPagosPagoDestaque']['web'];
        require_once 'KotearPagos.php';
        $this->view->idenc = $this->_request->getParam('idenc');
    }
    public function medioPagoAction()
    {
        $this->_helper->layout->setLayout('clear');
    }

    public function enviarMedioPagoAction()
    {        
        require_once 'UsuarioPortal.php';
        require_once 'Pagos.php';
        $this->getResponse()->setHeader('Expires', '0', true)
             ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
             ->setHeader('Pragma', 'no-cache', true);
        $this->_helper->layout->setLayout('clear');
        $frontController = Zend_Controller_Front::getInstance();
        $aPagos=$frontController->getParam('bootstrap')->getOption('confpagos');
        $pagos = new Pagos();
        $monto = 0;

        try{
            //print_r($this->_request->getParams());
            //exit;
            $r = $this->_request->getParams();
            $datosUsuarioFac = $pagos->getDatosClienteFac($this->identity->ID_USR);

            //var_dump($r);
            //exit;

            if (count($datosUsuarioFac) == 0 ) {
                $flagDatos = $pagos->setDatosClienteFac($this->identity->ID_USR,
                                                        ($r['tipoDocumentoHidden']==2)?$r['numDoc']:'',
                                                        ($r['tipoDocumentoHidden']==2)?$r['razonSocial']:'',
                                                        $r['tipoCalle'],
                                                        $this->_limpiarTexto($r['nomCalle']),
                                                        $r['numero'],
                                                        $r['urbanizacion'],
                                                        $this->_limpiarTexto($r['nomUrbanizacion']),
                                                        $r['departamento'],
                                                        $r['distrito'],
                                                        '',
                                                        ($r['tipoDocumentoHidden']==1)?$this->_limpiarTexto($r['nombre']):'',
                                                        ($r['tipoDocumentoHidden']==1)?$this->_limpiarTexto($r['apePat']):'',
                                                        ($r['tipoDocumentoHidden']==1)?$this->_limpiarTexto($r['apeMat']):'',
                                                        ($r['tipoDocumentoHidden']==1)?$r['numDoc']:'',
                                                        $r['email'],
                                                        $r['telefono']);

            }
        }catch(Exception $error) {

        }

        if(is_array($_REQUEST['t']))
        {
            foreach ($_REQUEST['t'] as $index => $valor):
                $monto=$monto+$valor;
            endforeach;
        }

        $idTransaccion=implode(',',array_keys($_REQUEST['t']));
        $idTransaccion=$this->_request->getParam('ids');
        $monto=$this->_request->getParam('montoids');
        
        $input['pstrIdTransaccion'] = $idTransaccion;
        $input['pintIdCliente'] = $this->identity->ID_USR;
        $input['pstrUrlEnvio'] = '';
        $input['pintMedioPago'] = $this->_request->getParam('medioPago');
        $input['vdecMonto'] = $monto;
        
        $idOperacion = $pagos->generarOperacion($input);
        
        if($this->_request->getParam('medioPago') == 1) { //PE
            if ($idOperacion != '' || $idOperacion != 0) {
                if ($aPagos['pagoEfectivo']['version'] == 2) {
                    $this->_redirect($this->view->baseUrl().'/pagos/index/confirmacion-pago-efectivo-V/operacion/'.$idOperacion.'/monto/'.$monto);
                }else {
                    $this->_redirect($this->view->baseUrl().'/pagos/index/confirmacion-pago-efectivo/operacion/'.$idOperacion.'/monto/'.$monto);
                } 
            }else {
                $this->view->error = 'No se genero la operacion';
                $log = Zend_Registry::get('log');
                $log->err($this->view->error);                
            }
        }else if($this->_request->getParam('medioPago') >= 2 and $this->_request->getParam('medioPago') <= 3) {
                if($idOperacion != ''){
                    try {
                        $uri["MerchantID"]  = $aPagos['pasarela']['pCodigoTienda'];
                        $uri["OrderId"]     = $idOperacion;
                        $uri["Amount"]      = $monto;
                        $uri["UserId"]      = $this->identity->ID_USR;
                        $uri["UrlOk"]       = $this->view->baseUrl().'/pagos/index/'.$aPagos['pasarela']['pURLOk'];
                        $uri["UrlError"]    = $this->view->baseUrl().'/pagos/index/'.$aPagos['pasarela']['pURLError'];
                        
                        if($this->_request->getParam('medioPago') == 2){
                            $mp = 'v';
                        }else {
                            $mp = 'm';
                        }
                        $uri["mp"] = $mp;
                        
                        foreach($uri as $index => $valor):
                            $strUri.=$index.'='.$valor.'|';
                        endforeach;
                        $strUri=substr($strUri, 0,strlen($strUri)-1);
                        $strUri=$aPagos['pasarela']['PasarelaPag'].'?datosEnc='.$this->encripta($strUri);
                        $pagos->actUriOperacion($idOperacion,utf8_decode($strUri));
                        $this->_redirect($strUri);}
                    catch (Exception $e)
                    {
                        $this->view->error = 'Hubo un error en la operacion';
                        $log = Zend_Registry::get('log');
                        $log->err($this->view->error);
                        //echo  $e->getMessage();
                    }
                } else {
                    $this->view->error = 'No se genero la operacion';
                    $log = Zend_Registry::get('log');
                    $log->err($this->view->error);
                     //echo  $e.getMessage();
                }            
        }else {
                $this->_redirect($this->view->baseUrl().'/pagos/index/pendiente-de-pago');
        }
    }
    
    /**
     * Ander
     */
    public function enviarMedioEncriptadoAction()
    {
        $r = $this->_request->getParams();
        
        $medioPago = $r['medioPago'];
        $idOperacion = $r['idOperacion'];
        $monto = $r['monto'];
        
        $this->getResponse()->setHeader('Expires', '0', true)
             ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
             ->setHeader('Pragma', 'no-cache', true);
        $this->_helper->layout->setLayout('clear');
        
        require_once 'Pagos.php';
        
        $pagos = new Pagos();
        
        $aPagos = $this->getConfig()->confpagos->toArray();
        
        if($medioPago == 1) { //PE
            if ($idOperacion != '' || $idOperacion != 0) {
                if ($aPagos['pagoEfectivo']['version'] == 2) {
                    $this->_redirect($this->view->baseUrl().'/pagos/index/confirmacion-pago-efectivo-V/operacion/'.$idOperacion.'/monto/'.$monto);
                }else {
                    $this->_redirect($this->view->baseUrl().'/pagos/index/confirmacion-pago-efectivo/operacion/'.$idOperacion.'/monto/'.$monto);
                } 
            }else {
                $this->view->error = 'No se genero la operacion';
                $log = Zend_Registry::get('log');
                $log->err($this->view->error);                
            }
        }else if($medioPago >= 2 and $medioPago <= 3) {
                if($idOperacion != ''){
                    try {
                        $uri["MerchantID"]  = $aPagos['pasarela']['pCodigoTienda'];
                        $uri["OrderId"]     = $idOperacion;
                        $uri["Amount"]      = $monto;
                        $uri["UserId"]      = $this->identity->ID_USR;
                        $uri["UrlOk"]       = $this->view->baseUrl().'/pagos/index/'.$aPagos['pasarela']['pURLOk'];
                        $uri["UrlError"]    = $this->view->baseUrl().'/pagos/index/'.$aPagos['pasarela']['pURLError'];
                        
                        if($medioPago == 2){
                            $mp = 'v';
                        }else {
                            $mp = 'm';
                        }
                        $uri["mp"] = $mp;
                        
                        foreach($uri as $index => $valor):
                            $strUri.=$index.'='.$valor.'|';
                        endforeach;
                        $strUri=substr($strUri, 0,strlen($strUri)-1);
                        $strUri=$aPagos['pasarela']['PasarelaPag'].'?datosEnc='.$this->encripta($strUri);
                        $pagos->actUriOperacion($idOperacion,utf8_decode($strUri));
                        $this->_redirect($strUri);}
                    catch (Exception $e)
                    {
                        $this->view->error = 'Hubo un error en la operacion';
                        $log = Zend_Registry::get('log');
                        $log->err($this->view->error);
                        //echo  $e->getMessage();
                    }
                } else {
                    $this->view->error = 'No se genero la operacion';
                    $log = Zend_Registry::get('log');
                    $log->err($this->view->error);
                     //echo  $e.getMessage();
                }
        }else {
                $this->_redirect($this->view->baseUrl().'/pagos/index/pendiente-de-pago');
        }
    }
    
    public function encripta($datos,$tipoEncr=0)
    {   
        $frontController = Zend_Controller_Front::getInstance();
        $uriEnc=$frontController->getParam('bootstrap')->getOption('confpagos');
        $client = new Zend_Soap_Client($uriEnc['EncryptKeyPagoEfectivo']);
        if($tipoEncr == 0) {            
            $result=$client->BlackBox(array('Cad' => $datos));            
            
            return $result->BlackBoxResult;
        } elseif ($tipoEncr == 1) {
            $result=$client->BlackBoxDecrypta(array('Cad' => $datos));
            return $result->BlackBoxDecryptaResult;
        } else return null;
    }
    public function urlImg($codCid,$url,$cclave,$capi)
    {
        $img = $url.'?codigo='.$this->encripta('cip='.$codCid.'|capi='.$capi.'|cclave='.$cclave);
        return $img;
    }
    public function confirmacionPagoEfectivoAction()
    {
        require_once 'Pagos.php';
        $this->getResponse()->setHeader('Expires', '0', true)
             ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
             ->setHeader('Pragma', 'no-cache', true);

        $frontController = Zend_Controller_Front::getInstance();
        $aPagos = $frontController->getParam('bootstrap')->getOption('confpagos');
        $pagos = new Pagos();
        $detalleOperacion=$pagos->detalleOperacion($this->_request->getParam('operacion'),$this->identity->ID_USR);
        
        $detalles.='<Detalle>
          <Cod_Origen> </Cod_Origen>
          <TipoOrigen> </TipoOrigen>
          <ConceptoPago>Operación '.$this->_request->getParam('operacion').'</ConceptoPago>
          <Importe>'.$this->_request->getParam('monto').'</Importe>
          <Campo1></Campo1>
          <Campo2></Campo2>
          <Campo3></Campo3>
        </Detalle>';
        
        $xml = '<?xml version="1.0" encoding="utf-8" ?>
    <OrdenPago>
          <IdOrdenPago>'.$this->_request->getParam('operacion').'</IdOrdenPago>
          <IdEstado>0</IdEstado>
          <IdServicio></IdServicio>
          <IdMoneda>'.$aPagos['pasarela']['pMoneda'].'</IdMoneda>
          <NumeroOrdenPago></NumeroOrdenPago>
          <Total>'.$this->_request->getParam('monto').'</Total>
          <MerchantId>'.$aPagos['pagoEfectivo']['MerchantID'].'</MerchantId>
          <OrdenIdComercio>'.$this->_request->getParam('operacion').'</OrdenIdComercio>
          <UrlOk>'.$this->view->baseUrl().'/pagos/index/'.$aPagos['pagoEfectivo']['pURLOk'].'</UrlOk>
          <UrlError>'.$this->view->baseUrl().'/pagos/index/'.$aPagos['pagoEfectivo']['pURLError'].'</UrlError>
          <MailComercio></MailComercio>
          <UsuarioId>'.$this->identity->ID_USR.'</UsuarioId>
          <DataAdicional></DataAdicional>
          <UsuarioNombre>'.$this->identity->NOM.'</UsuarioNombre>
          <UsuarioApellidos>'.$this->identity->APEL.'</UsuarioApellidos>
          <UsuarioLocalidad></UsuarioLocalidad>
          <UsuarioProvincia>'.$this->identity->ID_UBIGEO.'</UsuarioProvincia>
          <UsuarioPais></UsuarioPais>
          <UsuarioAlias>'.$this->identity->APODO.'</UsuarioAlias>
          <UsuarioEmail>'.$this->identity->EMAIL.'</UsuarioEmail>
          <Detalles>'.$detalles.'</Detalles>
    </OrdenPago>';
        /*
        var_dump($aPagos['pagoEfectivo']['capi']);
        echo('*************************************');
        var_dump($aPagos['pagoEfectivo']['cclave']);       
        exit;
         */
        $responseCip = $this->obtenercip($aPagos['pagoEfectivo']['capi'],$aPagos['pagoEfectivo']['cclave'],$this->identity->EMAIL,$aPagos['pagoEfectivo']['password'],$this->encripta($xml));
        // print_r($responseCip);
        $numCip = $responseCip->CIP;
        $mensajeCip = $responseCip->Mensaje;
        $obj = simplexml_load_string($this->encripta($responseCip->InformacionCIP,1));
        $TiempoExpiracionCip = ($obj->TiempoExpiracion)/24;
        $FechaEmisionCip = $obj->FechaEmision;
        if ($numCip != 0) {
            $this->pagoEfectivoMail('generacion_cip',
                                    $cargosOperacion[0]->Email,
                                    $this->identity->APODO,
                                    'Generación de Código Interno de Pago',
                                    array('[apodo]' => $this->identity->APODO,
                                    	  '[codigo]' => $numCip,
                                          '[monto]' => $this->_request->getParam('monto'),
                                          '[fecha]' => date('d/m/Y')));
            $this->view->codBarra = $this->urlImg($numCip,$aPagos['pagoEfectivo']['codBarra'],$aPagos['pagoEfectivo']['cclave'],$aPagos['pagoEfectivo']['capi']);
            $this->view->codBarrape = $aPagos['pagoEfectivo']['codBarra'];
            $this->view->cclavepe = $aPagos['pagoEfectivo']['cclave'];
            $this->view->capipe = $aPagos['pagoEfectivo']['capi'];
            $this->view->numCip = $numCip;
            $this->view->nombre = $this->identity->NOM;
            $this->view->apellido = $this->identity->APEL;
            $this->view->TiempoExpiracionCip = (round($TiempoExpiracionCip)) . ' Dias';
            $this->view->FechaEmisionCip = $FechaEmisionCip;
            $this->view->monto = $this->_request->getParam('monto');
            
        } else {
            $this->view->error = 'Hubo un error en la operacion';
            $log = Zend_Registry::get('log');
            $log->err('Hubo un error en la operacion');
        }
        $strUri = 'CAPI='.$aPagos['pagoEfectivo']['capi'].'|Cclave='.$aPagos['pagoEfectivo']['cclave'].'|Email='.$this->identity->EMAIL.'|Password='.$aPagos['pagoEfectivo']['password'].'|Xml='.$this->encripta($xml);
        
        $input['K_ID_OPERACION'] = $this->_request->getParam('operacion');
        $input['K_URL_ENVIO'] = $strUri;
        $input['K_CIP'] = $numCip;
        $pagos->actualizarOperacionPE($input);
        //var_dump($aPagos['pagoEfectivo']['capi']);
        //echo '--------';
        //var_dump($aPagos['pagoEfectivo']['cclave']);
        //exit;
    }
    public function obtenercip($capi,$clave,$email,$password,$xml)
    {
        try{        
             $frontController = Zend_Controller_Front::getInstance();
             $uriEnc = $frontController->getParam('bootstrap')->getOption('confpagos');
             $client = new Zend_Soap_Client($uriEnc['pagoEfectivo']['swpagoEfectivo']);
             $array=array('request' => array('CAPI' => $capi,
										 	 'CClave' => $clave,
											 'Email' => $email,
											 'Password' => $password,
											 'Xml' => $xml));
           	 $response=$client->GenerarCIP($array);
                 //var_dump($array);
                 //exit;
             return $response->GenerarCIPResult;
        	} catch (Exception $e) {
                    //echo($ex->getMessage);
                    //exit;
		   return '';
        	}

    }
    public function pasarelaUrlokAction()
    {
        $this->view->headTitle('Pasarela Confirmacion de Pago');
        require_once 'Pagos.php';
        $pagos = new Pagos();
        if (!$this->validarIpSegura(2)) {
            $pagos->registrarIpTrama($_SERVER['REMOTE_ADDR'], implode('&',$this->_request->getParams()));
            throw new Exception("Error en aplicación");
            $this->_redirect($this->view->baseUrl().'/error/error');
        }

        $response=explode('|',$this->encripta($this->_request->getParam('egp_data'),1));
        foreach($response as $index):
            $index2=explode('=',$index);$response2[$index2[0]]=$index2[1];
        endforeach;        


        if($this->_request->getParam('egp_est') == 1  or $this->_request->getParam('egp_est') == 'A') {
            if($response2['egp_CardId'] == 'VIS' or $response2['egp_CardId'] == 'MAS'){
                if($response2['egp_UserID'] == $this->identity->ID_USR){
                        $error = 0;
                } else {
                    $this->view->msgerror = 'El usuario no es el correcto';
                    $error = 1;
                    $log = Zend_Registry::get('log');
                    $log->err($this->view->msgerror);
                }
            } else {
                $this->view->msgerror = 'El tipo de tarjeta es incorrecto';
                $error = 1;
                $log = Zend_Registry::get('log');
                $log->err($this->view->msgerror);
            }
        } else {
            $this->view->msgerror = $response2['egp_MsgHost'];
            $error = 1;
            $log = Zend_Registry::get('log');
                $log->err($this->view->msgerror);
        }
        if ($error == 0) {
            $carId['VIS'] = 2;
            $carId['MAS'] = 3;
            $frontController = Zend_Controller_Front::getInstance();
            $parmPagos = $frontController->getParam('bootstrap')->getOption('confpagos');

            if ($pagos->registroOperacionExitosa($response2['egp_TrnID'], $response2['egp_OrderID'],$this->identity->ID_USR,$carId[$response2['egp_CardId']],$carId[$response2['egp_CardId']]) == 1) {
                $cargosOperacion=$pagos->getCargosOperacionPa($response2['egp_TrnID']);
                $detalleCargo.='<ul>';
                $montoCargo=0;
                $monto =0;
                foreach($cargosOperacion as $index):
                $monto=$monto+$index->Monto;
                if ($cargosOperacion->IdTipoTransaccion == 1)
                    {
                    $montoCargo = $montoCargo+$index->Monto;
                    $cantCargo = $cantCargo+$index->CantCargo;
                    } else{
                    if($montoCargo > 0)
                        {
                            $detalleCargo .= $cantCargo.'<li> Cargos gererados por comisi&oacute;n por un monto de S/. '.$montoCargo.' nuevos soles </li>' ;
                        } else {
                            $detalleCargo .= '<li> Activacion del destaque de tu producto " '.$index->TituloAviso.' " por S/. '.$index->Monto.' nuevos soles </li>' ;
                        }
                    }
                endforeach;
                $detalleCargo.='<ul>';
                $arrayTarjetas['MAS'] = 'MasterCard';
                $arrayTarjetas['VIS'] = 'Visa';
                $this->pagoEfectivoMail('pasarela_ok',
                                        $cargosOperacion[0]->Email,
                                        $cargosOperacion[0]->Apodo,
                                        'Confirmación de pagos pasarela',
                                        array('[apodo]'=>$cargosOperacion[0]->Apodo,
                                              '[medioPago]'=>$arrayTarjetas[$response2['egp_CardId']],
                                              '[numTransaccion]'=>$response2[egp_TrnID],
                                              '[monto]'=> $monto,                                              
                                              '[fechaPago]'=>$response2['egp_Fecha'],
                                              '[detalleCargo]'=>$detalleCargo));
                $this->view->monto=number_format($monto,2,'.','');
                $this->view->medioPago=$arrayTarjetas[$response2['egp_CardId']];
                $this->view->idTransaccion=$response2['egp_TrnID'];
                $this->view->errorPasarela=$response2['egp_MsgHost'];
                //$this->view->fechaPasarela=$this->formFecha($response2['egp_Fecha']);
                $this->view->fechaPasarela=$response2['egp_Fecha'];
                $this->view->mail=$this->identity->EMAIL;
                $error = 0;
                $pagos->registrarRespServPago($response2['egp_TrnID'],$_SERVER['REQUEST_URI'],2);
            } else {
                $error = 1;
                $log = Zend_Registry::get('log');
                $log->err($response2['egp_MsgHost']);
            }
        }

    }
    public function formFecha($fecha)
    {
        return strftime('%d/%m/%Y %H:%I hrs',strtotime($fecha));
    }

    public function pasarelaUrlerrorAction()
    {
     $this->view->headTitle('Pasarela Error de Pago');

    require_once 'Pagos.php';
    $pagos = new Pagos();
    if (!$this->validarIpSegura(2)) {
        $pagos->registrarIpTrama($_SERVER['REMOTE_ADDR'], implode('&',$this->_request->getParams()));
        throw new Exception("Error en aplicación");
        $this->_redirect($this->view->baseUrl().'/error/error');
    }
    $response = explode('|',$this->encripta($this->_request->getParam('egp_data'),1));

    //print_r($response);
    
    foreach($response as $index):
        $index2=explode('=',$index);
		$response2[$index2[0]]=$index2[1];
    endforeach;
            $carId['VIS'] = 2;
            $carId['MAS'] = 3;
              $frontController = Zend_Controller_Front::getInstance();
        $parmPagos = $frontController->getParam('bootstrap')->getOption('confpagos');
       if($pagos->registroOperacionFallida($response2['egp_TrnID'], $response2['egp_OrderID'],$this->identity->ID_USR,$response2['egp_MsgHost'])==1) {
           $cargosOperacion=$pagos->getCargosOperacionPa($response2['egp_TrnID']);
           $monto=0;
           foreach($cargosOperacion as $index):
                $monto=$monto+$index->Monto;
            endforeach;
            ($response2['egp_MsgHost']=='')?$response2['egp_MsgHost']='No se pudo registrar la operación':'';
            $arrayTarjetas['MAS'] = 'MasterCard';
            $arrayTarjetas['VIS'] = 'Visa';
            $this->pagoEfectivoMail('pasarela_error',
                                        $cargosOperacion[0]->Email,
                                        $cargosOperacion[0]->Apodo,
                                        'No se ha realizado el pago',
                                        array('[apodo]'=>$cargosOperacion[0]->Apodo,
                                              '[medioPago]'=>$arrayTarjetas[$response2[egp_CardId]],
                                              '[numTransaccion]'=>$response2[egp_TrnID],
                                              '[monto]'=>$monto,
                                              '[fechaPago]'=>$response2['egp_Fecha'],
                                              '[motivoError]'=>$response2['egp_MsgHost']));
            $this->view->monto = number_format($monto,2,'.','');
            $this->view->medioPago = $arrayTarjetas[$response2[egp_CardId]];
            $this->view->idTransaccion = $response2['egp_TrnID'];
            $this->view->errorPasarela = $response2['egp_MsgHost'];
            $this->view->fechaPasarela = $response2['egp_Fecha'];
            $this->view->mail = $this->identity->EMAIL;
            $pagos->registrarRespServPago($response2['egp_TrnID'],$_SERVER['REQUEST_URI'],2);
        }
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function encrypt2array($dataEncrypt)
    {
        $data = array();
        foreach(explode('|', $dataEncrypt) as $row):
            $param = explode('=', $row);
            $data[$param[0]] = $param[1];
        endforeach;
        return $data;
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function pagoEfectivoTerminoAction()
    {
        $data = $this->encrypt2array($this->encripta($this->_request->getParam('datosEnc'),1)); //Capturamos la respuesta
        require_once 'Pagos.php';
        if (!$this->validarIpSegura(1)) {
          $pagos->registrarIpTrama($_SERVER['REMOTE_ADDR'], implode('&',$this->_request->getParams()));
          throw new Exception("Error en aplicación");
          $this->_redirect($this->view->baseUrl().'/error/error');
        }

        $pagos = new Pagos();

        $frontController = Zend_Controller_Front::getInstance();
        $parmPagos = $frontController->getParam('bootstrap')->getOption('confpagos');
        try{

            if ($pagos->registroOperacionExitosa($data['OrdenIdComercio'],0,$data['UsuarioId'],0,$parmPagos['pagoEfectivo']['medioPago']) == 1){

                //Registro de operacion
                $oper = $pagos->getDatosOperacion($data['OrdenIdComercio']);
                //Generar detalle del pago con una <li>

                $total = 0;
                $detalle = '<ul>'."\n";
                foreach($oper as $row){
                    $total += $row->Monto;
                    if ($row->IdTipoTransaccion ==1) { //Destaque
                        $detalle .= sprintf('<li>Activación del destaque de tu aviso "%s" por S/. %s nuevos soles </li>', $row->TituloAviso, $row->Monto)."\n";
                    } elseif ($row->IdTipoTransaccion == 2) {    //Comisiones
                        $detalle .= sprintf('%s cargos generados por comisi&oacute;n por un monto de S/. %s nuevos soles </li>', $row->CantCargo, $row->Monto )."\n";
                    } //end if
                }
                $detalle .='</ul>'."\n";
                //Envio de correo electronico
                //print_r($oper);

                $this->pagoEfectivoMail('pago_efectivo_termino',
                                        $oper[0]->Email,
                                        //'jcarbajalp@hotmail.com',
                                        $oper[0]->Apodo,
                                        'Kotear.pe | Tú operación ha si sido pagada',
                                        array('[apodo]' => $oper[0]->Apodo,
                                              '[cip]' => $oper[0]->CIP,
                                              '[monto]' => number_format($total, 2, '.', ''),
                                              '[fecha]' => substr($oper[0]->FechaCancelacion,0,16) .' hrs',
                                              '[detalle]' => $detalle,
                                              '[total]' => $total
                                        ));


                $pagos->registrarRespServPago($data['OrdenIdComercio'],$_SERVER['REQUEST_URI'],1);

          }
        } catch(Exception $e){
           $log = Zend_Registry::get('log');
           $log->err($e->getMessage());
        }
        //exit;
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function pagoEfectivoErrorAction()
    {
        $this->view->headTitle('Pagoefectivo Error de Generacion');
        $data = $this->encrypt2array($this->encripta($this->_request->getParam('DatosEnc'),1)); //Capturamos la respuesta
        require_once 'Pagos.php';
        $pagos = new Pagos();
        if (!$this->validarIpSegura(1)) {
            $pagos->registrarIpTrama($_SERVER['REMOTE_ADDR'], implode('&',$this->_request->getParams()));
            throw new Exception("Error en aplicación");
            $this->_redirect($this->view->baseUrl().'/error/error');
        }
        try{
            if ($pagos->registroOperacionFallida($data['OrdenIdComercio']) == 1){ //Registro de operacion
                $oper = $pagos->getDatosOperacion($data['OrdenIdComercio']);
                $total = 0 ;
                foreach($oper as $o){
                  $total += $o->Monto;
                }
                //Envio de correo
                $this->pagoEfectivoMail('pago_efectivo_error',
                                        $oper[0]->Email,
                                        //'jcarbajalp@hotmail.com',
                                        $oper[0]->Apodo,
                                        'Kotear.pe | No se ha realizado tu pago',
                                        array('[apodo]' => $oper[0]->Apodo,
                                              '[cip]' => $oper[0]->CIP,
                                              '[monto]' => number_format($total, 2, '.', ''),
                                              '[fecha]' => substr($oper[0]->FechaCancelacion,0,16) .' hrs',
                                              '[motivo]' => $oper[0]->MensajeError
                                         ));
                $pagos->registrarRespServPago($data['OrdenIdComercio'],$_SERVER['REQUEST_URI'],1);
             }
        } catch(Exception $e){
           $log = Zend_Registry::get('log');
           $log->err($e->getMessage());
        }
        //exit;
    } // end function

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function pagoEfectivoMail($template, $to, $apodo,  $subject, $data)
    {
        try {
        $template = new Devnet_TemplateLoad($template);
        $template->replace($data);
        $email = Zend_Registry::get('mail');
        $email->addTo($to, $apodo)
              ->setSubject($subject)
              ->setBodyHtml($template->getTemplate());
        //print_r($template->getTemplate());
        $email->send();
        return true;
        }
        catch (Exception $e)
        {

        $log = Zend_Registry::get('log');
        $log->err($e->getMessage());
        return false;
        }
    } // end function

    /*public function testIpSeguraAction(){
        echo $_SERVER['REMOTE_ADDR'];
        echo $this->validarIpSegura(2)?'ok':'error';
        die();
    }*/

    /**
     * Descripcion Valida si la ip es una ip segura, 1 pago efectivo 2 pasarella
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    private function validarIpSegura($tipo){
        $ip =  $_SERVER['REMOTE_ADDR'];
        if ($tipo!=1 && $tipo!=2) return false;
        $frontController = Zend_Controller_Front::getInstance();
        $p = $frontController->getParam('bootstrap')->getOption('confpagos');
        $ips = ($tipo==1)?explode(',',$p['pagoEfectivo']['ipSegura']):explode(',',$p['pasarela']['ipSegura']);
        if (count($ips)==1 && (trim($ips[0])=='')) return true;
        return in_array($ip, array_map('trim', $ips), false);
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function pruebaEncriptarAction()
    {
          $url = $this->view->baseUrl().'/pagos/index/pago-efectivo-termino/DatosEnc/'.$this->encripta('OrderId=225',0);
          //echo $url;
          //exit;
    } // end function
    function enviarMail($mail,$plantilla,$arrayDatos,$subject,$apodo)
    {

    }
//Pago de destaque:
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function pagoDestaqueAction()
    {
        $this->view->data = $this->_request->getParams();
    }

// end function

    function plantillasemailAction()
    {
        $this->_helper->layout->setLayout('clear');
    }
    function correoenviadoAction()
    {
        $this->_helper->layout->setLayout('clear');
        $this->view->Email = $this->identity->EMAIL;
        if ($this->_request->getParam('numCip') != '') {
            $this->pagoEfectivoMail('generacion_cip',
                    $cargosOperacion[0]->Email,
                    $this->identity->APODO,
                    'Generación de Código Interno de Pago',
                    array('[apodo]' => $this->identity->APODO,
                        '[codigo]' => $this->_request->getParam('numCip'),
                        '[monto]' => $this->_request->getParam('monto'),
                        '[fecha]' => date('d/m/Y')));
        }
    }
    function imprimirCipAction()
    {
        $this->_helper->layout->setLayout('printcip');
        $frontController = Zend_Controller_Front::getInstance();
        $aPagos = $frontController->getParam('bootstrap')->getOption('confpagos');
        $this->view->codBarra = $this->urlImg($this->_request->getParam('numcip'), $aPagos['pagoEfectivo']['codBarra'], $aPagos['pagoEfectivo']['cclave'], $aPagos['pagoEfectivo']['capi']);
        $this->view->numCip = $this->_request->getParam('numcip');
        $this->view->monto = $this->_request->getParam('monto');
    }
    function imprimirCargosPagadosAction()
    {
        $this->_helper->layout->setLayout('printpagos');
        require_once 'Pagos.php';
        $pagos = new Pagos();
        $resultCabezera = $pagos->getComisionesCabezera($this->identity->ID_USR,
                        $this->_request->getParam('tipo'),
                        $this->_request->getParam('cod'));

        $resultDetalle = $pagos->getDetalleComisionesPagadas($this->identity->ID_USR,
                        $this->_request->getParam('tipo'),
                        $this->_request->getParam('cod'),
                        $this->_request->getParam('anio'));

        // print_r($resultDetalle);
        $this->view->resultDetalle = $resultDetalle;
        $this->view->resultCabezera = $resultCabezera[0];
        $this->view->cod = $this->_request->getParam('cod');
        $this->view->tipo = $this->_request->getParam('tipo');
        $this->view->anio = date('Y');
        $meses = $this->getMeses();
        $dias = $this->getDias($this->view->anio);
        $this->view->mes = $meses[$this->view->cod];
        $this->view->ultimoDia = $dias[$this->view->cod];
    }
    function pdfCargosPagadosAction()
    {
        //$this->_helper->layout->setLayout('simple');
        $this->_helper->layout->setLayout('clear');
        require_once("Devnet/dompdf/dompdf_config.inc.php");
        error_reporting('~E_ALL');
        spl_autoload_register('DOMPDF_autoload');
        $dompdf = new DOMPDF();

                require_once 'Pagos.php';

        $pagos = new Pagos();
        $resultCabezera = $pagos->getComisionesCabezera($this->identity->ID_USR,
                                                       $this->_request->getParam('tipo'),
                                                       $this->_request->getParam('cod'));
        $resultDetalle  = $pagos->getDetalleComisionesPagadas($this->identity->ID_USR,
                                                              $this->_request->getParam('tipo'),
                                                              $this->_request->getParam('cod'),
                                                              $this->_request->getParam('anio'));

        $this->resultDetalle = $resultDetalle;
        $this->resultCabezera = $resultCabezera[0];
        $anio = date('Y');
        $meses = $this->getMeses();
        $dias = $this->getDias($anio);
        $mes= $meses[$this->_request->getParam('cod')];
        $ultimoDia = $dias[$this->_request->getParam('cod')];
        $html = '';
         if (!isset($this->resultDetalle) || (count($this->resultDetalle)==0)) { 
        $html='<div class="error">No existen cargos asignados.</div>';
        } else {
        $html.='
            <div align="center" style="margin: 20px">
        <div align="center" style="width: 500px; border: 1px solid #000; padding: 20px">

            <div align="left">
        <img src="'.$this->view->baseUrl().'/img/global/pic_r1_c1.gif">
            </div>
            <p>
        <table align="left" border="0"  >
            <tr>
                <td align="left"><strong>'.((strlen($this->resultCabezera->NroDocumento)>=11)?'Razón':'Nombre').'</strong></td > <td align="left">&nbsp;&nbsp;'. $this->resultCabezera->NomCompleto.'&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td align="left"><strong>'.((strlen($this->resultCabezera->NroDocumento)>=11)?'RUC':'DNI').'</strong></td> <td align="left">&nbsp;&nbsp;'.$this->resultCabezera->NroDocumento.'</td>
            </tr>
            <tr>
                <td align="left"><strong>Dirección:</strong></td> <td align="left">&nbsp;&nbsp;'.$this->resultCabezera->Direccion.'</td>
            </tr>
            <tr>
                <td align="left"><strong>Teléfono:</strong></td > <td align="left">&nbsp;&nbsp;'.$this->resultCabezera->Telefono.'</td>
            </tr>

        </table>
        <p>
        <table align="left" border="0" width="80%" >
        <tr>
            <td align="left"><strong> Fecha</strong> </td>
            <td align="left"><strong> Descripci&oacute;n </strong></td>
            <td align="right"><strong> Monto </strong></td>
        </tr>';
        $monto = 0;
        foreach($this->resultDetalle as $index): 
        $html.='<tr>
            <td align="left">'.$index->Fecha.'</td>
            <td align="left">'.$index->Transaccion.'</td>
            <td align="right">S/. '.number_format($index->Monto,2,'.','').'</td>
        </tr>';
        $monto = $monto + $index->Monto;
        endforeach; 
        $html.='<tr class="rowtotal">
            <td align="right" colspan="2" class="desc"><strong>Total</strong></td>
            <td align="right"><strong>S/. '.number_format($monto,2,'.','') .'</strong></td>
        </tr>
        </table>
            <br/>
            <div align="left" style="font-size:0.8em">'.
            (($this->_request->getParam('tipo')==1)?'* Cargos generados del 01 de '.$mes. ' al ' . $ultimoDia.' de '. $mes .' del '.$anio. '<br/>':'').
            '** El presente documento no posee ning&uacute;n valor legal
            </div>
        </div></div>';
        } 


        $dompdf = new DOMPDF();
        $dompdf->set_paper("a4","portrait");
        $dompdf->load_html(utf8_decode($html) );
        $dompdf->render();
        $pol = $dompdf->output();
        header('Content-disposition: attachment; filename=document.pdf');
			header('Content-Type: application/octet-stream; charset=utf-8');
			header('Content-Length: '.strlen($pol));
			header('Pragma: no-cache');
			header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
			header('Expires: 0');
                        die($pol);


        }
        
        public function procesoPagoAction()
        {
            require_once 'Aviso.php';
            require_once 'Pagos.php';
            
            $aviso = new Aviso();
            $pagos = new Pagos();
            
            $this->view->headScript()->appendScript('$(document).ready(function(){ $.Kotear.validarProcesoPago(); });');
            
            if ($this->getRequest()->isPost()){
               //Registro de Pago
              $this->paramsPost = 0;
              $validator = new Devnet_Validator();
              //valida el tipo de documento
              $tipoDocumento = new Zend_Validate();
              $tipoDocumento->addValidator(new Zend_Validate_Digits());
              $validator->add('tipoDocumento',$tipoDocumento,'Tipo de Documento');

              //valida el numero de documento dependiendo si es dni o ruc
              $numDoc = new Zend_Validate();
              if($this->_request->getParam('tipoDocumento')==1)
                $numDoc->addValidator(new Zend_Validate_Digits())->addValidator(new Zend_Validate_StringLength(11, 11));
              if($this->_request->getParam('tipoDocumento')==2)
                $numDoc->addValidator(new Zend_Validate_Digits())->addValidator(new Zend_Validate_StringLength(8, 8));
              $validator->add('numDoc',$numDoc,'Numero de Documento');
            
              //valida el correo electronico
              $email = new Zend_Validate();
              $email->addValidator(new Zend_Validate_EmailAddress());
              $validator->add('email', $email,'Email');

              //valida la razon social
              if($this->_request->getParam('tipoDocumento')==1){

                $razonSocial = new Zend_Validate();
                $razonSocial->addValidator(new Zend_validate_Alpha(true));
                $validator->add('razonSocial', $razonSocial,'Razon Social');
              }

              //valida el apellido paterno
              if($this->_request->getParam('tipoDocumento')==2){
                $ApePatValidator = new Zend_Validate();
                $ApePatValidator->addvalidator(new Zend_Validate_NotEmpty());
                $validator->add('apePat', $ApePatValidator,'Apellido Paterno');
            
                //valida el pellido materno
                $ApeMatValidator = new Zend_Validate();
                $ApeMatValidator->addvalidator(new Zend_Validate_NotEmpty());
                $validator->add('apeMat', $ApeMatValidator,'Apellido Materno');

                //valida el tipo de nombre
                $nombre = new Zend_Validate();
                $nombre->addValidator(new Zend_Validate_StringLength(3, 200));
                $validator->add('nombre',$nombre,'Nombres');
              }

              //valida el telefono
              $telefonoValidator = new Zend_Validate();
              $telefonoValidator->addValidator(new Zend_Validate_Digits())->addValidator(new Zend_Validate_StringLength(9, 9));
              $validator->add('telefono', $telefonoValidator,'Teléfono');

              //valida el departamento
              $departamentoValidator = new Zend_Validate();
              $departamentoValidator->addValidator(new Zend_Validate_NotEmpty);
              $validator->add('departamento', $departamentoValidator,'Departamento');

              //valida el distrito
              $distritoValidator = new Zend_Validate();
              $distritoValidator->addValidator(new Zend_Validate_NotEmpty);
              $validator->add('distrito', $distritoValidator,'Distrito');

              //valida la urbanizacion
              $urbanizacionValidator = new Zend_Validate();
              $urbanizacionValidator->addValidator(new Zend_Validate_NotEmpty);
              $validator->add('urbanizacion', $urbanizacionValidator,'Urbanización');

              //valida El nombre de la urbanizacion
              $nomUrbanizacionValidator = new Zend_Validate();
              $nomUrbanizacionValidator->addValidator(new Zend_Validate_NotEmpty);
              $validator->add('nomUrbanizacion', $nomUrbanizacionValidator,'Nombre Urbanización');

              //valida el tipo de calle
              $tipoCalleValidator = new Zend_Validate();
              $tipoCalleValidator->addValidator(new Zend_Validate_NotEmpty);
              $validator->add('tipoCalle', $tipoCalleValidator,'Tipo de calle');

              //valida el Nombre de la calle
              $nomCalleValidator = new Zend_Validate();
              $nomCalleValidator->addValidator(new Zend_Validate_NotEmpty);
              $validator->add('nomCalle', $nomCalleValidator,'Nombre Calle');

              //valida el numoer o direccion
              $numeroValidator = new Zend_Validate();
              $numeroValidator->addValidator(new Zend_Validate_NotEmpty);
              $validator->add('numero', $numeroValidator,'Número/Dirección');
              $r = $this->_request->getParams();
              if (!$validator->isValid($params)) {
                $this->view->errors = $validator->getErrors();
              } else {
                /*$flagDatos = $pagos->setDatosClienteFac($this->identity->ID_USR,
                                                        ($r['tipoDocumento']==1)?$r['numDoc']:'',
                                                        ($r['tipoDocumento']==1)?$r['razonSocial']:'',
                                                        $r['tipoCalle'],
                                                        $r['nomCalle'],
                                                        $r['numero'],
                                                        $r['urbanizacion'],
                                                        $r['nomUrbanizacion'],
                                                        $r['departamento'],
                                                        $r['distrito'],
                                                        '',
                                                        ($r['tipoDocumento']==2)?$r['nombre']:'',
                                                        ($r['tipoDocumento']==2)?$r['apePat']:'',
                                                        ($r['tipoDocumento']==2)?$r['apeMat']:'',
                                                        ($r['tipoDocumento']==2)?$r['numDoc']:'',
                                                        $r['email'],
                                                        $r['telefono']);
                  $this->view->error = $flagDatos->error;
                  $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/fin-publicacion');
                 */
              }

            }
// Fin de Registro de Pago

        //if ($this->session->publicacionEstado == 1 || $this->session->publicacionEstado == 2){
            // Se obtiene el listado de los destaques del aviso            
            $lid_destaq=$aviso->getDestaquesAviso($this->session->fnp['id_aviso']);
            $this->view->IDS=$pagos->getTransaccionKP($this->session->fnp['id_aviso']);
            //$lid_transaviso=$aviso
            // Se obtiene el mayor de los destaques que sera el que se pague
            foreach ($lid_destaq as $index):
            if ($index->ID_DESTAQUE >= $destaque) {
                $destaque=$index->ID_DESTAQUE;
                $this->view->MONTO=$index->MONTO;
                $this->view->TIT=$index->TIT;
                $this->view->FOTO=$index->FOTO;
                $this->view->CANT_FOTO=$index->CANT_FOTO;
                $this->view->TIPO_IMPRESO=$index->TIPO_IMPRESO;
                $this->view->DIAS_PUB=$index->DIAS_PUB;
                $this->view->ID_TIPO_DESTAQUE=$index->ID_TIPO_DESTAQUE;                
            }
            endforeach;
        //}else if($this->session->publicacionEstado == 3){
            $destaque = $this->_validarDestaque(base64_decode($this->_request->getParam('destaque')));
            //print_r('llego');
            //print_r($destaque);
            $this->view->objDestaque = $destaque;
        //}
        $util = new Devnet_Utils();

            //$transaccion=$aviso->g($this->session->fnp['id_aviso']);

            $fec_pub = $this->generarFechaPublicacion();            
            $this->view->FECHA_PUBWEB = $util->convertDateProcesoPago();
            $this->view->FECHA_PUBLICACION=$fec_pub;
            $this->view->headTitle('Pagar Destaque de Aviso: Paso 5, Pago de Destaque de Aviso | Kotear.pe');

            /*Datos de facturación*/
			require_once 'Pagos.php';
            $pagos = new Pagos();
            //$param = $frontController->getParam('bootstrap')->getOption('app');
            $this->view->departamento = $pagos->getDepatamentoDatosFac(1);
			$this->view->tipoCalle = $pagos->getTipoCalleFac();
            $this->view->centrosPoblados = $pagos->getCentrosPobladosFac();
            $datosUsuarioFac = $pagos->getDatosClienteFac($this->identity->ID_USR);
            if (count($datosUsuarioFac) == 0 ) { //Datos de
                                                 //facturación para su
                                                 //edición 
              $datosUsuario['ID_USR'] = $this->identity->ID_USR;
              $datosUsuario['APEPAT'] = $this->identity->APEL;
              $datosUsuario['APEMAT'] = $this->identity->APEL;
              $datosUsuario['NOM'] = $this->identity->NOM;
              $datosUsuario['EMAIL'] = $this->identity->EMAIL;
              $arrayfono = explode('-', $this->identity->FONO1);
              if ($arrayfono[1] == '')
                $datosUsuario['FONO'] = $arrayfono[0];
              else
                $datosUsuario['FONO'] = $arrayfono[1];

              if ($this->identity->ID_TIPO_DOC == '05') {
                $datosUsuario['ID_TIPO_DOC'] = 2;
              } elseif ($this->identity->ID_TIPO_DOC == '07') {
                $datosUsuario['ID_TIPO_DOC'] = 1;
              }
              //echo $datosUsuario[ID_TIPO_DOC];

              $datosUsuario['NRO_DOC'] = $this->identity->NRO_DOC;
              /*var_dump($datosUsuario);
               var_dump(count($this->paramsPost));*/

              $this->view->update = true;

            } else {
                $datosUsuario['RAZON_SOCIAL'] = $datosUsuarioFac->RazonSocial;
                $datosUsuario['APEPAT'] = $datosUsuarioFac->ApePaterno;
                $datosUsuario['APEMAT'] = $datosUsuarioFac->ApeMaterno;
                $datosUsuario['NOM'] = $datosUsuarioFac->Nombre;
                $datosUsuario['EMAIL'] = $datosUsuarioFac->Email;
                $datosUsuario['FONO'] = $datosUsuarioFac->Telefono;
                if ($datosUsuarioFac->RUC != "") {
                    $datosUsuario['NRO_DOC'] = $datosUsuarioFac->RUC;
                    $datosUsuario['ID_TIPO_DOC'] = 1;
                }elseif ($datosUsuarioFac->DNI != "") {
                    $datosUsuario['NRO_DOC'] = $datosUsuarioFac->DNI;
                    $datosUsuario['ID_TIPO_DOC'] = 2;
                }
                $datosUsuario['NRO_DIRECCION'] = $datosUsuarioFac->Numero;
                $datosUsuario['COD_DPTA'] = $datosUsuarioFac->IdDepartamento;
                $datosUsuario['NOM_DPTA'] = $datosUsuarioFac->NombreDepartamento;
                $datosUsuario['COD_DIST'] = $datosUsuarioFac->IdDistrito;
                $datosUsuario['NOM_DIST'] = $datosUsuarioFac->NombreDistrito;
                $datosUsuario['COD_TIPO_CALLE'] = $datosUsuarioFac->IdTipoCalle;
                $datosUsuario['NOM_TIPO_CALLE'] = $datosUsuarioFac->NombreTipoCalle;
                $datosUsuario['NOM_COD_URB'] = $datosUsuarioFac->DescCentroPoblado;
                $datosUsuario['COD_URB'] = $datosUsuarioFac->IdCentroPoblado;
                $datosUsuario['NOM_URB'] = $datosUsuarioFac->NombreCentroPoblado;
                $datosUsuario['NOM_CALLE'] = $datosUsuarioFac->Direccion;
                $datosUsuario['NUMERO'] = $datosUsuarioFac->Numero;
                $this->view->update = false;
            }
			$this->view->datosUsuario = $datosUsuario;
            $this->view->headScript()->appendScript("f_getDatosDistrito('{$datosUsuario['COD_DIST']}');");

/*Fin de datos de facturación */
		
        }

    function generarFechaPublicacion()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $impreso = $frontController->getParam('bootstrap')->getOption('impresion');
        $diferencia = 0;
        //$hoy    = Zend_Date::now();
        $hoy=date('d-m-Y');
        $dia    = (int) date('N');
        $hora   = (int) date('H');
        $min    = (int) date('i');

        if (!isset ($impreso['diaCierre'])){
            $impreso['diaCierre']=4;
        }

        if (!isset ($impreso['horaCierre'])){
            $impreso['horaCierre']=12;
        }

        if (!isset ($impreso['cantidadDias'])){
            $impreso['cantidadDias']=14;
        }

        if ($dia > (int) $impreso['diaCierre']
            || ($dia == (int)$impreso['diaCierre']
                && ($hora > (int) $impreso['horaCierre']
                    || ($hora == (int) $impreso['horaCierre']
                        && $min >= (int) $impreso['minutoCierre'])))
        ) {
            $diferencia = (int) $impreso['cantidadDias'] - $dia;
        } else {
            $diferencia = (((int) $impreso['cantidadDias'])/2) - $dia;
        }
       
        if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$hoy))
            list($dia, $mes, $anio)=split("/", $hoy);

        if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$hoy))
              list($dia,$mes,$anio)=split("-",$hoy);

        $nueva = mktime(0,0,0, $mes,$dia,$anio) + abs($diferencia) * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y/N",$nueva);

        
        
        
        
        /*
        print_r((int) $impreso['diaCierre']);
        print_r((int) date('N'));
        */

        //$fechaPublicacion = clone $hoy;
        //$fec_fin = $fechaPublicacion->addDay(abs($diferencia));

        $u = new Devnet_Utils();

        //$date1 = $fec_fin->toString("d/M/Y/e");
        return $u->convertDateProcesoPago($nuevafecha);
    }

    private function _validarDestaque($destaque)
    {
        require_once 'Destaque.php';

        $objDestaque = new Destaque();
        return $objDestaque->getDestaque($destaque);
    }

   
    function anioConsolidado()
    {
        $year_act= date('Y');  $year_ini= 2010;                                
        while($year_ini<=$year_act){
        $year[$year_ini]=$year_ini;
        $year_ini++;}
        return $year;
    }

   public function _limpiarTexto($string)
   {
       $specialCharacters = array(
           '#' => "", '$' => "", '%' => "", '&' => "", '@' => "", '.' => "",
           '€' => "", '+' => "", '=' => "", '§' => "", '\\' => "", '/' => "",
           '|' => "", '{' => "",'}' => ""
       );

       while (list($character, $replacement) = each($specialCharacters)) {
       $string = str_replace($character, '-' . $replacement . '-', $string);
       }

       $string = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $string);
       $string = preg_replace('/^[\-]+/', ' ', $string);
       $string = preg_replace('/[\-]+$/', ' ', $string);
       $string = preg_replace('/[\-]{2,}/', ' ', $string);

       return $string;
   }
   
    public function confirmacionPagoEfectivoVAction()
    {
        require_once 'Pagos.php';
        require_once 'TipoDocumento.php';

        $this->getResponse()->setHeader('Expires', '0', true)
                ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
                ->setHeader('Pragma', 'no-cache', true);

        $pagos = new Pagos();
        $detalleOperacion = $pagos->detalleOperacion($this->_request->getParam('operacion'), $this->identity->ID_USR);

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/pagoefectivo.ini', APPLICATION_ENV);
        $config = $config->pagoefectivo->toArray();
        $pagoEfectivo = new App_Service_PagoEfectivo($config);

        $now   = new DateTime();
        $now->format('d/m/Y H:i:s');
        $objDateAdd = date_add($now, new DateInterval("PT360H"));

        $tipoDocumento = new TipoDocumento();

        $xml = new App_Service_PagoEfectivo_Solicitud();
        $xml->addContenido(array(
                                'IdMoneda' => 1,                                                    #
                                'Total' => $this->_request->getParam('monto'),                      #
                                'MetodosPago' => '1,2',                                             #
                                'CodServicio' => $config['apiKey'],
                                'Codtransaccion' => $this->_request->getParam('operacion'), 
                                'EmailComercio' => $config['mailAdmin'],
                                'FechaAExpirar' => $objDateAdd->format('d/m/Y H:i:s'),
                                'UsuarioId' => $this->identity->ID_USR,
                                #'DataAdicional' => '',
                                'UsuarioNombre' => $this->_limpiarTexto($this->identity->NOM),
                                'UsuarioApellidos' => $this->_limpiarTexto($this->identity->APEL),
                                #'UsuarioLocalidad' => '',                                          #
                                #'UsuarioProvincia' => $this->identity->ID_UBIGEO,                  #
                                #'UsuarioPais' => '',                                               #
                                'UsuarioAlias' => $this->_limpiarTexto($this->identity->APODO),     
                                'UsuarioTipoDoc' => $tipoDocumento->getDescripcionCorta($this->identity->ID_TIPO_DOC),
                                'UsuarioNumeroDoc' => $this->identity->NRO_DOC,
                                'UsuarioEmail' => $this->identity->EMAIL,
                                'ConceptoPago' => 'Pago por Destaque'
                                ));

        $detalle = array();
        $transacciones = '';
        foreach($detalleOperacion as $key => $operacion) {
            $detalle[$key] = array(
                'Cod_Origen' => 'CT',
                'TipoOrigen' => 'TO',
                'ConceptoPago' => $operacion->Descripcion,
                'Importe' => $operacion->Monto
                );
            $transacciones .= ',' . $operacion->IdTransaccion;
        }
        $xml->addParametroUrl(array('usuarioId' => $this->identity->ID_USR, 'transaccionesId' => $transacciones))
                ->addDetalle($detalle);

        $pagos->registrarLogPagoEfectivoV2($this->_request->getParam('operacion'), (string)$xml->toXml(), '', 'SolicitarPago');
        $paymentRequest = $pagoEfectivo->solicitarPago($xml);
        
        $pagos->actUriPagoefectivoV2($this->_request->getParam('operacion'), (string)$paymentRequest->iDResSolPago);
        $pagos->registrarLogPagoEfectivoV2($this->_request->getParam('operacion'), '', 
                'iDResSolPago=' . $paymentRequest->iDResSolPago . '&' . 
                'CodTrans=' . $paymentRequest->CodTrans . '&' . 
                'Token=' . $paymentRequest->Token . '&' . 
                'Fecha=' . $paymentRequest->Fecha, 
                'SolicitarPago');
        $this->_redirect($config['gen']['url'] . '?Token=' . $paymentRequest->Token);
    }

    public function urlConfirmacionPagoEfectivoVAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        require_once 'Pagos.php';
        $pagos = new Pagos();

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/pagoefectivo.ini', APPLICATION_ENV);
        $config = $config->pagoefectivo->toArray();
        $pagoEfectivo = new App_Service_PagoEfectivo($config);
        $pagos->registrarLogPagoEfectivoV2(12000, '', $_POST['data'], 'ConfirmacionPago');
        $postData = $_POST['data'];
        try {
            $data = $pagoEfectivo->desencriptarData($postData);
        } catch (Exception $exc) {
            $pagos->registrarLogPagoEfectivoV2(12000, '', $exc->getMessage(), 'ConfirmacionPagoError');
        }

        $xml = simplexml_load_string($data);
        $pagos->registrarLogPagoEfectivoV2($xml->CodTrans, '', $data, 'ConfirmacionPago');

        $paramsUrl = $xml->ParamsURL;
        $parametrosAdicionales = array();

        if (!empty($paramsUrl)) {
            foreach ($paramsUrl as $paramUrl) {
                foreach ($paramUrl as $param) {
                    $parametrosAdicionales[(string) $param->Nombre] = (string) $param->Valor;
                }
            }
        }
        switch ($xml->Estado) {
            case 592:#Generado
                $input['K_ID_OPERACION'] = $xml->CodTrans;
                $input['K_URL_ENVIO'] = $postData;
                $input['K_CIP'] = $xml->CIP->NumeroOrdenPago;//$xml->CIP->IdOrdenPago;
                $pagos->actualizarOperacionPE($input);
                break;
            case 593:#Pagado
                if ($pagos->registroOperacionExitosa($xml->CodTrans, 0,
                                    $parametrosAdicionales['usuarioId'], 0, $config['medioPago']) == 1) {
                    $pagos->registrarRespServPago($xml->CodTrans,$data,1);
                }
                break;
            case 594:#Cip Vencido Pendiente
            case 595:#Cip Vencido Pendiente
                $pagos->registroOperacionFallida($xml->CodTrans, 0, $parametrosAdicionales['usuarioId']);
                break;
            
        }
    }
    
    public function eliminarCipAction()
    {         
        $this->getResponse()->setHeader('Expires', '0', true)
                ->setHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true)
                ->setHeader('Pragma', 'no-cache', true);
        
        require_once 'Pagos.php';
        
        $mPagos = new Pagos();
        
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $form = new Application_Form_ApToken();
        
        $allParams = $this->_request->getParams();
        $msg = "";
        $code = 0;
        
        try{
            $input = array("K_IDAVISO"=>$allParams["id"], 'K_IDUSR'=>$this->identity->ID_USR);
            $data = $mPagos->getCipOperacionByIdaviso($input);
            
            $cip = $data->CIP;
            $idOperacion = $data->ID_OPERACION;
            
            if(empty($cip)) throw new Exception('Error:: no se pudo guardar');
            elseif(empty($idOperacion)) throw new Exception('Error:: no se pudo guardar');
            elseif (!$form->valid($allParams)) throw new Exception('Error:: no se pudo guardar');
            
            $frontController = Zend_Controller_Front::getInstance();
            $aPagos = $frontController->getParam('bootstrap')->getOption('confpagos');
            $client = new Zend_Soap_Client($aPagos['pagoEfectivo']['swpagoEfectivo']);
             
            $array = array('request' => array('CAPI' => $aPagos['pagoEfectivo']['capi'],
                                              'CClave' => $aPagos['pagoEfectivo']['cclave'],
                                              'CIP' => $cip,
                							  'InfoResquest' => ''
                                              ));
           	$response = $client->EliminarCIP($array);
            
            $msg = $response->EliminarCIPResult->Mensaje;
            
            Zend_Registry::get('logCron')->write(array(
                'mensaje' => "Log ".$response->EliminarCIPResult->Mensaje,
                'prioridad' => Zend_Log::WARN,
                'metodo' => 'eliminar cip'
            ));
            
            if($response->EliminarCIPResult->Estado == '1'){
                $mPagos->registroOperacionFallida($idOperacion, "", $this->identity->ID_USR, 'se elimino CIP');
            } else {
                throw new Exception($msg);
            }
        } catch (Exception $e) {
            //echo $e->getMessage();exit;
            //$return = "/error/1/msg/".$ex->getMessage;
            $code = 1;
            $msg = "Error: ".$e->getMessage();
        }
//        var_dump($response);exit;
        
        $this->json(array('code' => $code, 'msg' => $msg));
        //$this->_redirect($this->view->baseUrl() . '/usuario/venta/pendiente-pago'.$return);

    }
    
}
