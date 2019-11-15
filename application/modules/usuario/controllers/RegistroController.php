<?php
/**
 * Descripción Corta
 * Descripción Larga
 * @category
 * @package
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 */
require_once 'Ubigeo.php';
require_once 'TipoDocumento.php';
require_once 'UsuarioPortal.php';


class Usuario_RegistroController
    extends Devnet_Controller_Action
{
    /**
     *  Paso 1: Ingreso de Datos a el formulario Registro
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function indexAction ()
    {
        
        $this->_helper->layout->disableLayout();
        
        require_once APPLICATION_PATH . '/modules/usuario/forms/formRegistro.php';
        
        $form           = new Usuario_formRegistro();
        $mUbigeo        = new Ubigeo();
        $mUsuarioPortal = new UsuarioPortal();
//        $mTipoDocumento = new TipoDocumento();
        //$arrUbigeo['depa'] = Zend_Json::encode($mUbigeo->getListJoson(1));
        $arrUbigeo['prov'] = Zend_Json::encode($mUbigeo->getListJoson(2));
        $arrUbigeo['dist'] = Zend_Json::encode($mUbigeo->getListJoson(3));
        $code = 1; 
        $page = '';
        $msg = '';
        $captcha = '';
        unset($this->session->frmRegistro);
        if (!$this->isAjax)
                $this->_redirect($this->view->baseUrl());
        if ($this->_request->isPost()) {
            try{
                $allParams  = $this->getRequest()->getPost();
////                $allParams  = $this->_request->getParams();
                if ($form->isValid($allParams)) {
                    $this->session->usuarioRegistroApodo = $allParams['apodo'];
                    $this->session->frmRegistro = $this->_request->getParams();
                    $codigoRegistro = $this->view->utils->generateCode();
                    $allParams['cod_conf'] = $codigoRegistro;
                    $allParams['ubigeo'] = $allParams['ubication_district'];
                    $result = $mUsuarioPortal->insert($allParams);
                    if (($result->K_ERROR != 0)) {
                        //$this->json(array('code' => 1, 'msg' => $result->K_MSG));
                        //echo $result->K_ERROR;
                        throw new Exception('Error error bd');
                    } else { //Si no hay errores enviamos el correo
                        if (empty($this->getConfig()->correo->disable)) {
                            $this->enviarCorreoRegistro(
                                $allParams['apodo'],
                                $allParams['clave'],
                                $allParams['email'],
                                $allParams['nombre'] . ' ' . $allParams['apellido'],
                                $codigoRegistro
                            );
                        }
                    }
                    $code = 0;
                    $page = $this->view->baseUrl() . '/usuario/registro/confirmar-correo';
                } else {
                    $captcha = $form->getElement('captcha')->render();
                    foreach ($form->getElements() as $key => $e) {
                        foreach ($e->getErrorMessages() as $message) {
                            $msgArr[] = $message;
                        }
                        $msg = implode(";", $msgArr);
                    };
                    throw new Exception($msg);
                }
            } catch (Exception $exc) {
                //echo $exc->getMessage();exit;
                $msg = $exc->getMessage();
    //            $this->_redirect($this->view->baseUrl());
            }
            $this->json(array('code' => $code, 'page' => $page, 'msg' => $msg, 'captcha' => $captcha));
        } else {
            $this->view->arrUbigeo = $arrUbigeo;
            $this->view->form = $form;
        }
    }

    /**
     * Confirmar el envio de correo electronico
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function confirmarCorreoAction ()
    {
        if (!isset($this->session->usuarioRegistroApodo)) {
            $this->_redirect($this->view->baseUrl() . '/usuario/registro');
        }
        $this->view->headTitle('Registro | Kotear.pe');
        $this->view->usuario = $this->session->frmRegistro['apodo'];
        $this->view->correo = $this->session->frmRegistro['email'];
       // print_r($this->session->frmRegistro);
    } //end function

    /**
     * Enviar el correo electronico y Guardar Registro
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function enviarCorreoAction ()
    {
        if ($this->_request->isXMLHttpRequest()) {
            require_once 'UsuarioPortal.php';
            $mUsuarioPortal = new UsuarioPortal();
            $arr = $mUsuarioPortal->findByApodo($this->session->usuarioRegistroApodo, 1);
            try{
                $this->enviarCorreoRegistro(
                    $arr->APODO,
                    $arr->PASSWORD,
                    $arr->EMAIL,
                    $arr->NOM . ' ' . $arr->APEL,
                    $arr->COD_CONF
                );
                $this->json(
                    array(
                        'code' => 0,
                        'msg' => 'Se ha enviado un correo de confirmación de registro.'
                    )
                );
            } catch (Exception $e){
                $this->json(array('code' => 1, 'msg' => $e->getMessage()));
            }
        }
    }
    /**
     *
     */
    function enviarCorreoRegistro($apodo, $clave, $correo, $nombres, $codigoRegistro)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $app = $frontController->getParam('bootstrap')->getOption('app');
        $enlaceValidar = $app['url'] . '/usuario/registro/validar';
        $enlaceConfirmar = $enlaceValidar
            . sprintf(
                '/apodo/%s/clave/%s/confirma/%s/correo/%s',
                $apodo,
                $clave,
                $codigoRegistro,
                $correo
            );
        $template = new Devnet_TemplateLoad('confirm_mail');
        $template->replace(
            array(
                '[nombre]' => $this->view->escape($nombres),
                '[correo]' => $this->view->escape($correo) ,
                '[apodo]' => $this->view->escape($apodo) ,
                '[clave]' => $clave ,
                '[enlaceconfirmar]' => $enlaceConfirmar ,
                '[enlacevalidar]' => $enlaceValidar ,
                '[codigoregistro]' => $codigoRegistro
            )
        );
        if (empty($this->getConfig()->correo->disable)) {
            $email = Zend_Registry::get('mail');
            $email->addTo($correo, $apodo)
                ->setSubject('Confirma tu registro!')
                ->setBodyHtml($template->getTemplate());
            $email->send();
        }            
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function cambioCorreoAction ()
    {
        //$this->_helper->layout->setLayout('clear');
        //$this->view->frm = $this->session->frmRegistro;
        if ($this->_request->isXMLHttpRequest()) {
            $validator = new Devnet_Validator();
            $emailValidator = new Zend_Validate();
            $emailValidator->addValidator(new Zend_Validate_EmailAddress());
            $mUsuarioPortal = new UsuarioPortal();
            $validator->add('newmail', $emailValidator);
            $validator->addError(
                'newmail',
                ($mUsuarioPortal->existeEmail($this->_request->getParam('newmail')))
                ?'El correo ya está registrado en Kotear.pe': null
            );
            $validator->addError(
                'newmail2',
                ($this->_request->getParam('newmail2') != $this->_request->getParam('newmail'))
                ?'La confirmación de email debe ser igual al email': null
            );
            if (! $validator->isValid($this->_request->getParams())) {
                $this->json(array('code' => 1, 'msg' => $validator->getErrors()));
            } else {
                $this->session->frmRegistro['email'] = $this->_request->getParam('newmail');
                $this->session->frmRegistro['emailRep'] = $this->_request->getParam('newmail2');
                $arr = $mUsuarioPortal->findByApodo($this->session->usuarioRegistroApodo, 1);
                $mUsuarioPortal->setEmail($arr->ID_USR, $this->session->frmRegistro['email']);
            //Enviar correo
                try{
                    $this->enviarCorreoRegistro(
                        $arr->APODO,
                        $arr->PASSWORD,
                        $this->session->frmRegistro['email'],
                        $arr->NOM . ' ' . $arr->APEL,
                        $arr->COD_CONF
                    );
                    $this->json(
                        array(
                            'code' => 0,
                            'msg' => 'El correo ha sido modificado, revise su bandeja de correo para 
                                activar la cuenta.'
                        )
                    );
                } catch (Exception $e){
                    $this->json(array('code' => 1, 'msg' => $e->getMessage()));
                }
                //$this->json(array('code' => 0, 'msg' => 'Se cambio con satisfacción el correo'));
            } //end if
        }
    } //end function

    /**
     * Verificar Registro desde el correo de confirmacion - cambiar el estado a registrado
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function validarAction ()
    {
        if (isset($this->identity)) {
            Zend_Auth::getInstance()->clearIdentity();
            unset($this->identity);
            unset($this->view->identity);            
        }
        if ($result = $this->_request->getParam('apodo')) {
            $this->view->apodo = $this->_request->getParam('apodo');
            $mUsuarioPortal = new UsuarioPortal();
            $idUsuario = $mUsuarioPortal->getIdUsuarioPorApodo($this->view->apodo);
            $existeEmail = $mUsuarioPortal->perteneceEmail(
                $this->view->apodo, $this->_request->getParam('correo')
            );
            $nuevoCorreo = $this->_request->getParam('correo');
            if (isset($nuevoCorreo) && ($existeEmail==1)) {
                $this->view->msg = 
                    'La cuenta no esta esta asociada al correo del cual quiere activar la cuenta.';
            } else {
                if ($mUsuarioPortal->getCodConf($idUsuario) == $this->_request->getParam('confirma')) {
                    $result = $mUsuarioPortal->cambioEstadoRegistro($this->_request->getParam('apodo'));
                    //print_r($result);exit;
                    if ($result[0]->K_ERROR == 0) {
                            //Envio de Correo
                        $mUsuarioPortal = new UsuarioPortal(); //Usuario del Portal
                        $data = $mUsuarioPortal->findByApodo($this->_request->getParam('apodo'));
                                $template = new Devnet_TemplateLoad('bienvenida');
                                $template->replace(array('[apodo]' => $data->APODO));
                                $correo = Zend_Registry::get('mail');
                                $correo->addTo($data->EMAIL, $data->APODO)
                                       ->setSubject('Bienvenido a Kotear.pe!')
                                       ->setBodyHtml($template->getTemplate());
                                $correo->send();
                        $this->_redirect(
                            $this->view->baseUrl() . '/usuario/registro/bienvenido/apodo/' . 
                            $this->view->apodo
                        );
                    } else {
                            $this->view->msg = $result[0]->K_MSG;
                    } //end else
                } else {
                    $this->view->msg = 'C&oacute;digo de confirmaci&oacute;n invalido';
                }
            }
        }
    } //end function

    /**
     * Registra los datos mediante Soap
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function registroSoap ($idCliente, $apodo, $apellido, $nombre, $email, $fono, $idDocumento, 
        $nroDocumento, $fecCreacion)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $wsdl = $frontController->getParam('bootstrap')->getOption('wsdl');
        try {
            //$streamContext = stream_context_create();
            $soap = new Zend_Soap_Client($wsdl['kotearPagos']['usuario']);
            $args = array(
                'IdCliente' => $idCliente,
                'Apellidos' => $apellido,
                'Nombres' => $nombre,
                'Apodo' => $apodo,
                'Email' => $email,
                'Telefono' => $fono,
                'IdDocumento' => $idDocumento,
                'NroDocumento' => $nroDocumento,
                'FechaCreacion' => $fecCreacion
            );
            $result = $soap->RegistrarCliente($args);
            return true;
        } catch (Exception $e) {
            $this->log->warn($e->getMessage());
            return false;
        }
    }

    /**
     * Acción de bienvenida al usuario;
     * @return void
     */
    function bienvenidoAction()
    {
        $this->view->headTitle('Bienvenido | Kotear.pe');
        $this->view->apodo = $this->_request->getParam('apodo');
    }

    /**
     * Acción de cancelar registro
     * @return void
     */
    function cancelarAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            $this->vaciarFormSession();
            $this->json(array('code' => 0, 'msg' => ''));
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function vaciarFormSession ()
    {
        unset($this->session->frmRegistro);
    } //end function

    function envioCorreoConfirmacionAction()
    {
        if ($this->_request->isXMLHttpRequest() && isset($this->session->accesoIdUsuario)) {
            try{
                require_once 'UsuarioPortal.php';
                $mUsuarioPortal = new UsuarioPortal();
                $data = $mUsuarioPortal->getCorreoConfirmacion(
                    $this->session->accesoIdUsuario
                ); //extraemos datos de usuario
                $frontController = Zend_Controller_Front::getInstance();
                $app = $frontController->getParam('bootstrap')->getOption('app');
                $enlaceValidar = $app['url'] . '/usuario/registro/validar';
                $enlaceConfirmar = $enlaceValidar
                     . sprintf(
                         '/apodo/%s/clave/%s/confirma/%s',
                         $data->APODO,
                         $data->CLAVE,
                         $data->COD_CONF
                     );
                $template = new Devnet_TemplateLoad('confirm_mail');
                $template->replace(
                    array(
                        '[nombre]' => $data->NOM,
                        '[correo]' => $data->EMAIL,
                        '[apodo]' => $data->APODO,
                         '[clave]' => $data->CLAVE,
                         '[enlaceconfirmar]' => $enlaceConfirmar,
                         '[enlacevalidar]' => $enlaceValidar,
                         '[codigoregistro]' => $data->COD_CONF
                    )
                );
                $correo = Zend_Registry::get('mail');
                $correo->addTo($data->EMAIL, $data->APODO)
                       ->setSubject('Confirma tu registro!')
                       ->setBodyHtml($template->getTemplate());
                $correo->send();
                $this->json(
                    array(
                        'code' => 0,
                        'msg' => 'Se reenvio correctamente el correo de confirmación.'
                    )
                );
            } catch(Exception $e){
            $this->json(array('code' => 1, 'msg' => $e->getMessage()));
            }
        }
    }
   
}
