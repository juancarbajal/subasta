<?php
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
require_once 'UsuarioPortal.php';

class Usuario_ClaveController extends Devnet_Controller_Action
{
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function recuperarAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            //Validamos el captcha
            if ($this->_request->getParam('captcha') != $this->session->claveRecuperarCaptchaWord) {
                    $this->json(array('code' => 1, 'msg' => 'El código de verificación no corresponde'));
            } else {
                try {
                    $mUsuarioPortal = new UsuarioPortal();
                    if ($mUsuarioPortal->existeApodo($this->_request->getParam('usercontact')) || 
                            $mUsuarioPortal->existeEmail($this->_request->getParam('usercontact'))) {
                        //Verificamos existencia de Usuario
                        //Envio de
                        $usuario = $mUsuarioPortal->findByApodo($this->_request->getParam('usercontact'));
                        if ($usuario->ID_ESTADO_USUARIO == 4) {
                        $this->json(array('code' => '1', 'msg' => 'El usuario esta actualmente suspendido.'));
                        } elseif ($usuario->ID_ESTADO_USUARIO == 5) {
                        $this->json(array('code' => '1', 'msg' => 'El usuario ha sido dado de baja.'));
                        } elseif ($usuario->ID_ESTADO_USUARIO == 1) {
                            $this->json(
                                array(
                                    'code' => '1', 
                                    'msg' => 'El usuario no ha confirmado su registro.'
                                )
                            );
                        } else {
                            $this->session->usuarioClaveRecuperar = $usuario;  //Datos del Usuario
                            $codigoGenerado = $this->view->utils->generateCode();
                            $frontController = Zend_Controller_Front::getInstance();
                            $app = $frontController->getParam('bootstrap')->getOption('app');
                            $linkIngresoConfirm = $app['url'] . '/usuario/clave/recuperar-confirmar';
                            $enlaceConfirmar = $linkIngresoConfirm
                                                         . '/apodo/'
                                             . $usuario->APODO
                                             . '/codigo/'
                                             . $codigoGenerado;
                            $mUsuarioPortal->setCodConfPassword($usuario->ID_USR, $codigoGenerado);
                            //$template = new Devnet_TemplateLoad('recupera_clave_confirma');
                            $template = new Devnet_TemplateLoad('recuperar_clave');
                            $template->replace(
                                array('[apodo]' => $usuario->APODO,
                                    //'[kotear_url]' => $enlace,
                                    '[enlaceconfirmar]' => $enlaceConfirmar,
                                    '[enlaceingresoconfirmacion]' => $linkIngresoConfirm,
                                    '[codigocambio]' => $codigoGenerado
                                )
                            );
                            $correo = Zend_Registry::get('mail');
                            $correo->addTo($usuario->EMAIL, $usuario->APODO)
                                   ->setSubject('Cambio de clave Kotear')
                                   ->setBodyHtml($template->getTemplate());
                            $correo->send();
                            $this->json(
                                array('code' => '0', 
                                    'msg' => 'Se ha enviado un e-mail, indicandote los pasos a seguir para 
                                        el cambio de correo.')
                            );
                        }
                    } else {
                        $this->json(array('code' => '1', 'msg' => 'El apodo/email no existe'));
                    } //end if
                } catch (Exception $e) {
                    $this->json(array('code' => '2', 'msg' => $e->getMessage()));
                }
            } //end else
        } else {
            $this->view->headTitle('Cambiar clave | Kotear.pe');
            $this->_helper->layout->setLayout('simple');
        } //end if
    } // end function
    
    public function recuperarCaptchaAction() 
    {
        if ($this->_request->isXMLHttpRequest()) {
            try{
                $frontController = Zend_Controller_Front::getInstance();
                $captchaOp = $frontController->getParam('bootstrap')->getOption('captcha');
                $captchaOp['name'] = 'palabraSeguridad';
                $captchaOp['baseUrl'] = $this->view->baseUrl();
                $captcha = new Devnet_Captcha($captchaOp);
                $captcha->generate();
                $this->session->claveRecuperarCaptchaWord = $captcha->getWord();
                $img = explode('"', $captcha->render($this->view));
                //$this->view->captcha = $captcha;
                $this->json(array('code' => '0', 'msg' => '', 'data' => $img[7]));
            } catch(Exception $e){
                $this->json(array('code' => '1', 'msg' => $e->getMessage()));
            }
        }
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function recuperarConfirmarAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            //Revisar la fonrimacion de codigo de confirmación
            $mUsuarioPortal = new UsuarioPortal();
            if ($this->_request->getParam('vcod') == 
                    $mUsuarioPortal->getCodConfPassword($this->session->usuarioClaveRecuperar->ID_USR)) {
                $this->json(
                    array('code' => 0,
                        'msg' => 'El código de confirmación es valido, a continuación accedera al 
                            formulario de cambio de contraseña.',
                        'url' => $this->view->baseUrl() . '/usuario/clave/recuperar-nueva')
                );
            } else {
                $this->json(
                    array('code' => 1,
                        'msg' => 'El código de confirmación no es el correcto.')
                );
            }
            /*
    		$this->json(array('code' => 0,
                      'msg' => 'El código de confirmación es valido, a continuación accedera al formulario de 
                               cambio de contraseña.',
                      'url' => $this->view->baseUrl() . '/usuario/clave/recuperar-nueva' ));*/
        } else {
            $this->view->headTitle('Recuperación de clave | Kotear.pe');
            if ($this->_request->isGet() && ($result = $this->_request->getParam('apodo'))) {
                    $mUsuarioPortal = new UsuarioPortal();
                    $usuario = $mUsuarioPortal->findByApodo($this->_request->getParam('apodo'));
                    $this->session->usuarioClaveRecuperar = $usuario;
                    $idUsuario = $usuario->ID_USR;
                    if ($this->_request->getParam('codigo') == 
                        $mUsuarioPortal->getCodConfPassword($idUsuario)) {
                            $this->_redirect($this->view->baseUrl() . '/usuario/clave/recuperar-nueva');
                    } else {
                            $this->view->msg = 'El código de confirmación no es el correcto.';
                    }
            }
        }
    } // end function
    /**
     * Recuperar Nueva contraseña
     */
    function recuperarNuevaAction()
    {
        $this->view->headTitle('Recuperación de clave | Kotear.pe');
        //print_r($this->session->usuarioClaveRecuperar);
        if ($this->_request->isPost()) {
            $validator = new Devnet_Validator();
            $claveValidator = new Zend_Validate();
            $claveValidator->addValidator(new Zend_Validate_StringLength(4, 12))
                           ->addvalidator(new Zend_Validate_Alnum());
            $validator->add('clave', $claveValidator);
            $validator->addError(
                'clave2', ($this->_request->getParam('clave') != $this->_request->getParam('clave2'))
                ? 'La confirmación de la contraseña debe ser igual que la contraseña.': null
            );
            if (! $validator->isValid($this->_request->getParams())) {
                $this->view->errors = $validator->getErrors();
            } else {
                $mUsuarioPortal = new UsuarioPortal();
                if (isset($this->session->usuarioClaveRecuperar)) {
                    $result = $mUsuarioPortal->setPassword(
                        $this->session->usuarioClaveRecuperar->ID_USR, $this->_request->getParam('clave')
                    );
                    unset($this->session->usuarioClaveRecuperar);
                }
                if ($result->K_ERROR == 0) {
                    $this->view->msg = 'La contraseña ha sido cambiada satisfactoriamente.';
                } else {
                    $this->view->errors = 'Error en Conexión con la Base de Datos.';
                } // end if else
            }
        }
    }
    
    
    function recuperarCodigoAction()
    {
        $this->_helper->layout->setLayout('clear');
    }// end function
    
    
} // end class