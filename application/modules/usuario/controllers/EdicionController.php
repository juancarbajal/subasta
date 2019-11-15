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
 /**
 * Descripción Corta
 * Descripción Larga
 * @category
 * @package
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 */
class Usuario_EdicionController extends Devnet_Controller_Action
{
    /**
     *  Paso 1: Ingreso de Datos a el formulario Edicion
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function indexAction ()
    {
        if (! isset($this->identity)) {
            $this->session->requiredLoginUrl = $_SERVER['REQUEST_URI'];
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
        }
        
        require_once 'Ubigeo.php';
        require_once 'UsuarioPortal.php';
        require_once APPLICATION_PATH . '/modules/usuario/forms/formUsuario.php';
        
        $mUbigeo = new Ubigeo();
        $mUsuarioPortal = new UsuarioPortal();
        $form   = new Usuario_formUsuario();
        $arrRender = new stdClass();
        
        $arrRender->tab = 'perfil';
        $arrRender->page = 'edicion/_index';
        $arrRender->active = $this->getRequest()->getActionName();
        //Devnet_Controller_Plugin_AuthSimple::$requiredAuth = true;
        $this->view->headTitle('Datos de contacto | Kotear.pe');
        $this->view->tieneDeuda = 0;
        //$this->view->tieneDeuda = $up->tieneDeudaKotearPagos($this->identity->ID_USR);
        
        if ($this->_request->isPost()) {
            
            $allParams  = $this->getRequest()->getPost();
            
            if ($form->isValid($allParams)) {
                $allParams['ubigeo'] = $allParams['ubication_district'];
                $allParams['ciudad'] = '';
                $allParams['suscripcionNews'] = !empty($allParams['suscripcionNews'])?1:0;
                
                $result = $mUsuarioPortal->update($allParams, $this->identity->ID_USR);
                
                $msg = "Se guardar la informacion correctamente.";
                
                // autentificacion
                $usuario = $mUsuarioPortal->findAuth($this->identity->ID_USR);
                $this->session->auth = $usuario;
                //var_dump($this->session->accesoApel='Pepito');
                //var_dump($this->session->accesoNom);
                //var_dump($this->session->accesoFono1);
                //var_dump($usuario); exit;
                
                
//                $this->_redirect($this->view->baseUrl() . '/usuario/cuenta');
            } else {
                //$validator->getErrors();
                $msg = "Error: No se pudo guardar la informacion.";
                $this->view->datosCategoria = $this->_request->getParams();
            }
        }
        
        $data = $mUsuarioPortal->find($this->identity->ID_USR);
        //var_dump($data);exit;
        if (!empty($data)) {
            $dataEdit['nombre'] = $data->NOM;
            $dataEdit['apellido'] = $data->APEL;
            $dataEdit['telefono'] = $data->FONO1;
            $dataEdit['telefono2'] = $data->FONO2;
            $dataEdit['suscripcionNews'] = $data->ACEPTA_PUB;
            $dataEdit['ubication_departement'] = $data->ID_UBIGEO_DEPARTAMENTO;
            $dataEdit['ubication_province'] = $data->ID_UBIGEO_PROVINCIA;
            $dataEdit['ubication_district'] = $data->ID_UBIGEO_DISTRITO;
            $form->setProvinciaJosonValidate(
                array('K_ID_DEPARTAMENTO'=>$data->ID_UBIGEO_DEPARTAMENTO)
            );
            $form->setDistritoJosonValidate(
                array(
                    'K_ID_DEPARTAMENTO'=>$data->ID_UBIGEO_DEPARTAMENTO,
                    'K_ID_PROVINCIA'=>$data->ID_UBIGEO_PROVINCIA
                )
            );
//          $form->setProvinciaJosonValidate(array($data->ID_UBIGEO_PROVINCIA=>$data->NOM_UBIGEO_PROVINCIA));
//          $form->setDistritoJosonValidate(array($data->ID_UBIGEO_DISTRITO=>$data->NOM_UBIGEO_DISTRITO));
            $form->setDefaults($dataEdit);
        }
        
        $this->view->data = $mUsuarioPortal->find($this->identity->ID_USR);
        $this->view->datosCategoria = array(
            'ubigeo' => $this->view->data->ID_UBIGEO, 
            'ubicacion' => $this->view->data->UBIGEO
        );
        
        $arrUbigeo['depa'] = Zend_Json::encode($mUbigeo->getListJoson(1));
        $arrUbigeo['prov'] = Zend_Json::encode($mUbigeo->getListJoson(2));
        $arrUbigeo['dist'] = Zend_Json::encode($mUbigeo->getListJoson(3));
        
        $this->view->arrUbigeo = $arrUbigeo;
        $this->view->form = $form;
        $this->view->arrRender = $arrRender;
        $this->view->msg = $msg;
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function claveAction ()
    {
        require_once 'UsuarioPortal.php';
        $this->_helper->layout->setLayout('clear');
        $this->view->termino = $this->_request->getParam('termino');
        if ($this->_request->isXMLHttpRequest()) {
            $validator = new Devnet_Validator();
            $claveValidator = new Zend_Validate();
            $claveValidator->addValidator(new Zend_Validate_StringLength(4, 12))
               ->addvalidator(new Zend_Validate_Alnum());
            $validator->add('newpassw', $claveValidator);
            $validator->addError(
                'newpassw2',
                ($this->_request->getParam('newpassw') != $this->_request->getParam('newpassw2'))
                ?'La confirmación de la contraseña debe ser igual que la contraseña.': null
            );
            if (! $validator->isValid($this->_request->getParams())) {
                $this->json(array('code' => 1, 'msg' => $validator->getErrors()));
            } else {
                $mUsuarioPortal = new UsuarioPortal();
                $result = $mUsuarioPortal->setPassword(
                    $this->identity->ID_USR, $this->_request->getParam('newpassw')
                );
                if ($result->K_ERROR == 0) {
                    $this->json(array('code' => 0, 'msg' => 'Cambio de clave satisfactorio.'));
                } else {
                    $this->json(array('code' => 1, 'msg' => 'Error en Conexión con la Base de Datos.'));
                } // end if else
            } //end if else
        }//end if
    } //end function

    /**
     * Edicion de Correo
     * @return void
     */
    function correoAction() 
    {
        require_once 'UsuarioPortal.php';
        //$this->_helper->layout->setLayout('clear');
        $this->view->headTitle('Datos de contacto | Kotear.pe');
        $this->view->termino = $this->_request->getParam('termino');
        if ($this->_request->isXMLHttpRequest()) {
            $mUsuarioPortal = new UsuarioPortal();
            $validator = new Devnet_Validator();
            $emailValidator = new Zend_Validate();
            $emailValidator->addValidator(new Zend_Validate_EmailAddress());
            $validator->add('newmail', $emailValidator);
            $validator->addError(
                'newmail2', 
                ($this->_request->getParam('newmail') != $this->_request->getParam('newmail2'))
                ? 'La confirmación del e-mail debe ser igual que el e-mail.': null,
                'E-mail de confirmación'
            );
            $validator->addError(
                'newmail',
                ($mUsuarioPortal->existeEmail($this->_request->getParam('newmail')))
                ? 'El email actualmente esta siendo usado': null,
                'E-mail'
            );
            if (! $validator->isValid($this->_request->getParams())) {
                $this->json(array('code' => 1, 'msg' => $validator->getErrors()));
            } else {

                $codigoGenerado = $this->view->utils->generateCode();
                $frontController = Zend_Controller_Front::getInstance();
                $app = $frontController->getParam('bootstrap')->getOption('app');
                $enlaceValidar = $app['url'] . '/usuario/edicion/validar-correo';
                $enlaceConfirmar = $enlaceValidar . 
                    sprintf('/apodo/%s/confirma/%s', $this->identity->APODO, $codigoGenerado);
                $mUsuarioPortal->setCodConfEmailNuevo($this->identity->ID_USR, $codigoGenerado);
                $mUsuarioPortal->setEmailNuevo($this->identity->ID_USR, $this->_request->getParam('newmail'));
                //Envio de Correo electronico
                
                if (empty($this->getConfig()->correo->disable)) {
                    $template = new Devnet_TemplateLoad('confirm_email_nuevo');
                    $template->replace(
                        array(
                            '[apodo]' => $this->identity->APODO,
                            '[enlaceconfirmar]' => $enlaceConfirmar,
                            '[enlacevalidar]' => $enlaceValidar,
                            '[codigoconfirmacion]' => $codigoGenerado
                        )
                    );
                    $correo = Zend_Registry::get('mail');
                    $correo->addTo($this->_request->getParam('newmail'), $this->identity->APODO)
                        ->setSubject('Cambio de e-mail!')
                        ->setBodyHtml($template->getTemplate());
                    $correo->send();
                }

                $this->json(
                    array(
                        'code' => 0,
                        'msg' => 'Se ha enviado un correo de confirmación de cambio de correo a tu 
                            nuevo correo'
                    )
                );
            } //end if else
        }//end if
    }

    function validarCorreoAction()
    {
        require_once 'UsuarioPortal.php';
        $this->view->headTitle('Datos de contacto | Kotear.pe');
        if ($result = $this->_request->getParam('apodo')) {
            $this->view->apodo = $this->_request->getParam('apodo');
            $mUsuarioPortal = new UsuarioPortal();
            $idUsuario = $mUsuarioPortal->getIdUsuarioPorApodo($this->view->apodo);
            if ($mUsuarioPortal->getCodConfEmailNuevo($idUsuario) == $this->_request->getParam('confirma')) {
                $result = $mUsuarioPortal->confirmarEmailNuevo($idUsuario);
                if ($result->K_ERROR == 0) {
                    if (isset($this->identity->EMAIL)) {
                        $this->view->identity->EMAIL = $this->identity->EMAIL = 
                            $mUsuarioPortal->getEmail($idUsuario);
                    }
                    $this->_redirect($this->view->baseUrl() . '/usuario/edicion/validar-correo-termino');
                } else {
                    $this->view->msg = $result->K_MSG;
                } //end else
            } else {
                $this->view->msg = 'El c&oacute;digo de confirmaci&oacute;n no correspende al asignado.';
            }
        }
    }

    /**
     * Registra los datos mediante Soap
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function edicionSoap ($idCliente, $apodo, $apellido, $nombre, $email, $fono, $idDocumento, 
        $nroDocumento, $fecCreacion)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $wsdl = $frontController->getParam('bootstrap')->getOption('wsdl');
        try {
            //$streamContext = stream_context_create();
            $soap = new Zend_Soap_Client(
                $wsdl['kotearPagos']
            );
            /*,array('encoding' => 'UTF-8',
                   'cache_wsdl' => false,
                   'soap_version' => SOAP_1_1,
                   'stream_context' => $streamContext)*/
            $args = array(
                'IdCliente' => $idCliente,
                'Apellidos' => $apellido,
                'Nombres' => $nombre,
                'Email' => $email,
                'Telefono' => $fono,
                'IdDocumento' => $idDocumento,
                'NroDocumento' => $nroDocumento,
                'FechaActualizacion' => $fecCreacion
            );
            $result = $soap->ActualizarCliente($args);
            return true;
        } catch (Exception $e) {
            $this->log->warn($e->getMessage());
            return false;
        }
    } //end function

    function validarCorreoTerminoAction()
    {
        $this->view->headTitle('Datos de contacto | Kotear.pe');
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function cancelarCuentaAction ()
    {
        $this->view->headTitle('Datos de contacto | Kotear.pe');
        require_once 'UsuarioPortal.php';
        if ($this->_request->isXMLHttpRequest()) {
           /* $validator = new Devnet_Validator();
            $textoValidator = new Zend_Validate();
            $textoValidator->addValidator(
                new Zend_Validate_NotEmpty())->addvalidator(new Zend_Validate_Alpha(true)
            );
            $validator->add('apodo', $textoValidator);
            $validator->add('password', $textoValidator);
            $emailValidator = new Zend_Validate(); //ESPACIO DE LINEAS
            $emailValidator->addValidator(new Zend_Validate_EmailAddress());
            $validator->add('email', $emailValidator);
            if (! $validator->isValid($this->_request->getParams())) {
                $this->view->errors = $validator->getErrors();
            } else {*/
                $mUsuarioPortal = new UsuarioPortal();

                    $dato = $mUsuarioPortal->find($this->identity->ID_USR);
                    $retornoDeuda = $mUsuarioPortal->verificarDeuda($this->identity->ID_USR);
                    if ($retornoDeuda[0]->RETORNO == 0) {
                        //$baja = $up->estadoBaja($dato->ID_USR);
                        $retornoBaja = $mUsuarioPortal->darBaja($this->identity->ID_USR, '5', '10');
                        try {
                            $this->_helper->layout->setLayout('clear');
                            $template = new Devnet_TemplateLoad('confirm_baja');
                            $template->replace(
                                array(
                                    '[NOMBRE]' => $dato->APODO,
                                    '[EMAIL]' => $dato->EMAIL
                                )
                            );
                            $correo = Zend_Registry::get('mail');
                            $correo->addTo($dato->EMAIL, $dato->APODO)
                                ->setSubject('Cancelacion de Cuenta')
                                ->setBodyHtml($template->getTemplate());
                            $correo->send();
                            Zend_Auth::getInstance()->clearIdentity();
                            $this->json(
                                array(
                                    'code'=>0,
                                    'msg'=>'Ud. cancelo su cuenta en Kotear, Revise su e-mail',
                                    'url'=> $this->view->baseUrl()
                                )
                            );
                        } catch (Exception $e) {
                            $this->log->err($e->getMessage());
                        }
                        $this->_redirect($this->view->baseUrl() . '/');
                    } else {
                        $this->json(
                            array(
                                'code'=>1, 
                                'msg'=>'Ud. tiene deudas con Kotear; No puede cancelar su Cuenta'
                            )
                        );
                    }

            //} //ELSE DE LA VALIDACION
        } // POST
    } // FUNCTION


} // end class
