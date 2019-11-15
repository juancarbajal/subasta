<?php
class Admin_AuthController 
    extends App_Controller_Action
{
    protected $_loginRequiredFor = array('logout');
    protected $_messageSuccess = 'Bienvenido';
    protected $_messageError = 'Error al iniciar sesión';

    public function loginAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        if ($this->isAuth) {
            $moduloLogin = $this->auth["usuario"]->rol;
            $modulo = $this->_getParam("tipo");
            if ($modulo != $moduloLogin) {
                Zend_Auth::getInstance()->clearIdentity();
            } else {
                $this->_redirect($this->_request->getPost('return') . '/');
            }
        }
        $rutaQueEnvio = $this->getRequest()->getServer('HTTP_REFERER');
        $next = $this->getRequest()->getParam('next', $rutaQueEnvio);
        
        if ($this->getRequest()->isPost()) {
            Zend_Auth::getInstance()->clearIdentity();
            $login = $this->getRequest()->getPost('user', '');
            $pswd = $this->getRequest()->getPost('pass', '');
            $type = $this->getRequest()->getPost('tipo', '');
            $isValid = Application_Model_Sp_InUsuario::auth($login, $pswd, $type);
            
            if ($this->getRequest()->getPost('save', '') == '1') {
                $config = $this->getConfig();
                Zend_Session::rememberMe($config->app->sessionRemember);
            }
            
            if ($isValid) {
                $this->getMessenger()->success($this->_messageSuccess);
            } else {
                $this->getMessenger()->error(
                    $this->_messageError . ': Datos inválidos'
                );
            }
        }
        
//        if ($next == $this->config->app->site . '/') {
//            $next = '/mi-cuenta';
//        }
        $this->_redirect($next);
    }

//    public function logoutAction()
//    {
//        $next = $this->getRequest()->getParam('next', $this->view->baseUrl('/'));
//        Zend_Auth::getInstance()->clearIdentity();
//        $this->_redirect($next);
//    }
//
//    public function validacionFacebookAction()
//    {
//        $code = $this->getRequest()->getParam('code', 0);
//        if (empty($code)) {
//            $this->_redirect('/');
//        }
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $config = $this->getConfig();
//        $appId = $config->apis->facebook->appid;
//        $appSecret = $config->apis->facebook->appsecret;
//        $url = $config->app->siteUrl
//            . '/auth/validacion-facebook';
//        $tokenUrl = "https://graph.facebook.com/oauth/access_token?"
//            . "client_id=" . $appId . "&redirect_uri=" . urlencode($url)
//            . "&client_secret=" . $appSecret . "&code=" . $_REQUEST["code"];
//
//        $response = file_get_contents($tokenUrl);
//        $params = null;
//        parse_str($response, $params);
//
//        $graphUrl = "https://graph.facebook.com/me?access_token="
//            . $params['access_token'];
//
//        $facebookUser = json_decode(file_get_contents($graphUrl));
//
//        $red = new Application_Model_CuentaRs();
//        $userId = $red->getUserIdByRedSocial($facebookUser->id, 'facebook');
//        if ($userId == null) {
//            if (isset($facebookUser->username)) {
//                $screenname = $facebookUser->username;
//            } else {
//                $screenname = $facebookUser->name;
//            }
//            $this->getMessenger()->error(
//                $this->_messageError .
//                ': No hay una cuenta asociada para el usuario ' . $screenname
//            );
//        } else {
//            $this->_guardarSesion($userId);
//            $this->getMessenger()->success($this->_messageSuccess);
//        }
//        $this->_redirect('/');
//    }
//
//    public function validacionGoogleAction()
//    {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $config = $this->getConfig();
//        $dataGoogle = $this->getRequest()->getParams();
//        if (!isset($dataGoogle)) {
//            $this->_redirect('/');
//        }
//        $rsId = str_replace(
//            $config->apis->google->urlResponse, "", $dataGoogle['openid_claimed_id']
//        );
//        $red = new Application_Model_CuentaRs();
//        $userId = $red->getUserIdByRedSocial(
//            $rsId, 'google'
//        );
//        if ($userId == null) {
//            $this->getMessenger()->error(
//                $this->_messageError .
//                ': No hay una cuenta asociada para el usuario ' .
//                $dataGoogle['openid_ext1_value_email']
//            );
//        } else {
//            $this->_guardarSesion($userId);
//            $this->getMessenger()->success($this->_messageSuccess);
//        }
//        $this->_redirect('/');
//    }
//
//    public function recuperarClaveAction()
//    {
//        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender();
//        $dataPost = $this->getRequest()->getParams();
//        if ($dataPost['email'] == null) {
//            $this->_redirect('/');
//        }
//        $listaClear=array(" ", "|");
//        $dataPost['email'] = str_replace($listaClear, "", $dataPost['email']);
//        //$dataPost['email'] = strtolower($dataPost['email']);
//        $mUsuario = new Application_Model_Usuario();
//        $usuario = $mUsuario->getIdByEmailRol($dataPost['email'], $dataPost['rol']);
//        $form = new Application_Form_RecuperarClave();
//        $form->setDefaults($dataPost);
//        $formValid = $form->isValid($dataPost);
//        if ($usuario != false && $formValid == true) {
//            $config = $this->getConfig();
//            $token = Application_Model_Usuario::generarToken(
//                $dataPost['email'], 
//                $dataPost['rol'], 
//                $config->app->tokenUser
//            );
//            $valid = true;
//            if ($usuario['rol'] == Application_Form_Login::ROL_SUSCRIPTOR) {
//                $mSuscriptor = new Application_Model_Suscriptor();
//                $suscriptor = $mSuscriptor->getSlugByUsuarioId($usuario['id']);
//                $this->_helper->Mail->recuperarContrasenaSuscriptor(
//                    array(
//                        'to' => $dataPost['email'],
//                        'nombre' => $suscriptor['nombres'],
//                        'slug' => $suscriptor['slug'],
//                        'urlToken' => $token
//                    )
//                );
//            } elseif ($usuario['rol'] == Application_Form_Login::ROL_ESTABLECIMIENTO) {
//                $mEstablecimiento = new Application_Model_Establecimiento();
//                $mAdministrador = new Application_Model_Administrador();
//                $estab = $mEstablecimiento->getEstablecimientoPorUsuario($usuario['id']);
//                $admin = $mAdministrador->getAdministradorxId($usuario['id']);
//                if(!empty($estab) && !empty($admin)):
//                    $this->_helper->Mail->recuperarContrasenaEstablecimiento(
//                        array(
//                            'to' => $dataPost['email'],
//                            'email' => $dataPost['email'],
//                            'nombre' => $admin['nombres'].' '.$admin['apellido_paterno'].' '.
//                                $admin['apellido_materno'],
//                            'establecimiento' => $estab['nombre'],
//                            'urlToken' => $token
//                        )
//                    );
//                else:
//                    $valid = false;
//                endif;
//            } else {
//                $mAdministrador = new Application_Model_Administrador();
//                $admin = $mAdministrador->getAdministradorxId($usuario['id']);
//                if(!empty($admin)):
//                    $this->_helper->Mail->recuperarContrasenaAdministrador(
//                        array(
//                            'to' => $dataPost['email'],
//                            'email' => $dataPost['email'],
//                            'nombre' => $admin['nombres'].' '.$admin['apellido_paterno'].' '.
//                                $admin['apellido_materno'],
//                            'urlToken' => $token
//                        )
//                    );
//                else:
//                    $valid = false;
//                endif;
//            }
//            if($valid):
//                $data = array(
//                    'status' => $token ? 'ok' : 'error',
//                    'msg' => $token ? 'Se envio el correo' : 'Hubo un Error'
//                );
//            else:
//                $data = array(
//                    'status' => 'error',
//                    'msg' => 'Formulario no valido'
//                );
//            endif;
//        } elseif (!$usuario) {
//            $data = array(
//                'status' => 'mailinvalid',
//                'msg' => 'Correo electrónico no existe o no es válido'
//            );
//        } else {
//            $data = array(
//                'status' => 'error',
//                'msg' => 'Formulario no valido.'
//            );
//        }
//        $this->_response->appendBody(Zend_Json::encode($data));
//    }
//
//    public function generarClaveAction()
//    {
//        Zend_Layout::getMvcInstance()->setLayout('simple');
//        $token = $this->getRequest()->getParam('key', 0);
//        $user = Application_Model_Usuario::isValidToken($token);
//        if ($user === false) {
//            $this->_redirect('/auth/token-invalido');
//        }
//        Zend_Layout::getMvcInstance()->assign(
//            'bodyAttr', array('id' => 'perfilReg', 'class' => 'noMenu')
//        );
//        $form = new Application_Form_EstablecerClave();
//        $form->token->setValue($token);
//        if ($this->getRequest()->isPost()) {
//            $postData = $this->_getAllParams();
//            if ($form->isValid($postData)) {
//                Application_Model_Usuario::setNewPassword(
//                    $user['id'], $this->getRequest()->getPost('password')
//                );
//                $this->_redirect('/auth/exito-cambio-clave');
//            }
//        }
//        $this->view->form = $form;
//    }
//
//    public function tokenInvalidoAction()
//    {
//        Zend_Layout::getMvcInstance()->setLayout('simple');
//        Zend_Layout::getMvcInstance()->assign(
//            'bodyAttr', array('id' => 'perfilReg', 'class' => 'noMenu')
//        );
//    }
//
//    public function exitoCambioClaveAction()
//    {
//        Zend_Layout::getMvcInstance()->setLayout('simple');
//        Zend_Layout::getMvcInstance()->assign(
//            'bodyAttr', array('id' => 'perfilReg', 'class' => 'noMenu')
//        );
//    }
//    
//    private function _guardarSesion($userId)
//    {
//        $mUsuario = new Application_Model_Usuario();
//        $usuario = $mUsuario->getUsuarioId($userId);
//        $auth = Zend_Auth::getInstance();
//        $auth->setStorage(new Zend_Auth_Storage_Session());
//        $suscriptor = new Application_Model_Suscriptor();
//        $related = $suscriptor
//                    ->fetchRow('usuario_id = '.$userId)
//                    ->toArray();
//        $authStorage = $auth->getStorage();
//        $authStorage->write(
//            array(
//                'usuario' => $usuario,
//                'suscriptor' => $related
//            )
//        );
//        return true;
//    }

}