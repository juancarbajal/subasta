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
require_once 'UsuarioPortal.php';
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
class Usuario_AccesoController extends Devnet_Controller_Action
{
    
    function setAuthData ($data)
    {
        $this->session->auth = $data;
    }
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */

    function indexAction ()
    {
        $this->view->headTitle('Ingresar | Kotear.pe');
        
        $msj = "";
        
        if ($this->_request->isPost()) {
            $mUsuarioPortal = new UsuarioPortal();
            $res = $mUsuarioPortal->validarUsuario(
                $this->_request->getParam('user'), $this->_request->getParam('password')
            );
            
            if ($res->K_ERROR == 0) {
                $data = $mUsuarioPortal->findAuth($res->ID_USR);
                $this->view->email = $data->EMAIL;
                $this->session->accesoIdUsuario = $data->ID_USR;
                
                switch ($data->ID_ESTADO_USUARIO) {
                    case 1:
                        $msj = 'Usuario no válidado, revise su cuenta de correo para validar su registro';
                        //'href' => $this ->view->baseUrl() . '/usuario/registro/reenvio-confirmacion/id/' . 
                        //      $this->_request->getParam('user')  , 
                        $this->view->msgUrl = array(
                            'rel' => $this->view->baseUrl() . '/usuario/registro/envio-correo-confirmacion',
                            'label' => 'Reenviar correo de confirmación',
                            'id' => 'resend-email-link'
                        );
                        break;
                    case 2:
                        $this->setAuthData($data);
                        //$url = $_SERVER['REQUEST_URI'];
                        //echo $url; exit;
                        if (isset($this->session->requiereLoginUrl)) {
                            $url = $this->session->requiereLoginUrl;
                            unset($this->session->requiredLoginUrl);
                        } else {
                            $url = $this->view->baseUrl() . '/usuario/cuenta';
                        }
                        $this->_redirect($url);
                        break;
                    case 4:
                        $nivelSuspension = $mUsuarioPortal->nivelSuspension($res->ID_USR);
                        //print_r($nivelSuspension);
                        if ($nivelSuspension[0]->ID_MOTIVO_REGLA_NIVEL == 1) {
                            $this->session->usuarioAccesoNivel1 = $nivelSuspension[0]->ID_MOTIVO_REGLA_NIVEL;
                            $this->setAuthData($data);
                            if (isset($this->session->requiereLoginUrl)) {
                                $url = $this->session->requiereLoginUrl;
                                unset($this->session->requiredLoginUrl);
                            } else {
                                $url = $this->view->baseUrl() . '/usuario/cuenta';
                            }
                            $this->_redirect($url);
                        }
                        if ($nivelSuspension[0]->ID_MOTIVO_REGLA_NIVEL == 2) {
                            $this->session->usuarioAccesoNivel2 = $nivelSuspension[0]->ID_MOTIVO_REGLA_NIVEL;
                            $this->setAuthData($data);
                            if (isset($this->session->requiereLoginUrl)) {
                                $url = $this->session->requiereLoginUrl;
                                unset($this->session->requiredLoginUrl);
                            } else {
                                $url = $this->view->baseUrl() . '/usuario/cuenta';
                            }
                            $this->_redirect($url);
                        }
                        if ($nivelSuspension[0]->ID_MOTIVO_REGLA_NIVEL == 3) {
                            $msj = 'Usuario suspendido';
                            $this->view->usuarioSuspendido = $this->_request->getParam('user');
                            $this->view->msgUrl = array(
                                'href' => $this ->view->baseUrl() . '/usuario/suspension/index/usuario/' . 
                                          $this->_request->getParam('user') ,
                                'label' => 'Cancelar suspensión',
                                'rel' => '#frmSuspended'
                            );
                            Zend_Auth::getInstance()->clearIdentity();
                            //$this->clearIdentity();
                        }
                        break;
                    case 5:
                        $msj = 'Usuario dado de Baja';
                        $this->view->usuarioSuspendido = $this->_request->getParam('user');
                        // $this->view->msgUrl = array('href' => $this ->view->baseUrl()  , 
                        //      'label' => 'Ir a la página principal');
                        Zend_Auth::getInstance()->clearIdentity();
                        //$this->clearIdentity();
                        break;
                }
           /*     }
                else
                    { $this->view->msg = $res->K_MSG;}*/
            } else {
                $msj = $res->K_MSG;
//                $msj = 'Error en el nombre de usuario y/o contrase&ntilde;a';
            } //end else
            //print_r(Zend_Auth::getInstance()->getIdentity());
        } else {
           // $this->clearSession(false);
        }
        /*Generacion de Captcha*/
        $frontController = Zend_Controller_Front::getInstance();
        $captchaOp = $frontController->getParam('bootstrap')->getOption('captcha');
        $captchaOp['name'] = 'palabraSeguridad';
        $captchaOp['baseUrl'] = $this->view->baseUrl();
        $captcha = new Devnet_Captcha($captchaOp);
        $captcha->generate();
        $this->session->claveRecuperarCaptchaWord = $captcha->getWord();
        $this->view->captcha = $captcha;
        $this->view->msj = $msj;
        /*Generacion de Captcha - Fin*/
    }
    
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function ajaxAction()
    {
        //  /usuario/acceso/ajax
        if ($this->_request->isXMLHttpRequest()) {
            $mUsuarioPortal = new UsuarioPortal();
            $res = $mUsuarioPortal->validarUsuario(
                $this->_request->getParam('user'), $this->_request->getParam('password')
            );
            if ($res->K_ERROR == 0) {
                $data = $mUsuarioPortal->findAuth($res->K_MSG);
                $this->setAuthData($data);
                $this->session->accesoIdUsuario = $data->ID_USR;
            }
            $this->json(array('code'=>$res->K_ERROR , 'msg' =>$res->K_MSG));
        }
    } // end function
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function panelAction ()
    {
        
    }
    
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function logoutAction()
    {
        //If (Zend_Session::sessionExist()) {
        $this->clearSession();
        if ($this->_request->isXMLHttpRequest()) {
            $this->json(array('code' => 0, 'msg' => 'Se cerró sesión satisfactoriamente.'));
        } else {
            $this->_redirect("/");
        }
        //}
    }
    function clearSession($all = true)
    {
        $this->clearIdentity();
        //Zend_Session::destroy(true, false);
        if ($all) {
            Zend_Session::destroy(false);
            unset($this->session);
        }
    }
    function clearIdentity()
    {
        unset($this->identity);
        unset($this->view->identity);
        Zend_Auth::getInstance()->clearIdentity();
    }
    
    public function ingresarAction ()
    {
        $this->_helper->layout->disableLayout();
    }
    
    public function recuperarClaveAction()
    {
        $this->_helper->layout->disableLayout();
        
        $frontController = Zend_Controller_Front::getInstance();
        $captchaOp = $frontController->getParam('bootstrap')->getOption('captcha');
        $captchaOp['name'] = 'palabraSeguridad';
        $captchaOp['baseUrl'] = $this->view->baseUrl();
        $captcha = new Devnet_Captcha($captchaOp);
        $captcha->generate();
        
        $this->session->claveRecuperarCaptchaWord = $captcha->getWord();
        $this->view->captcha = $captcha;
        
    }
    
    public function ingresarRegistrarAction ()
    {
        $this->_helper->layout->disableLayout();
        
        require_once 'Ubigeo.php';
        require_once APPLICATION_PATH . '/modules/usuario/forms/formRegistro.php';
        
        $mUbigeo = new Ubigeo();
        
        $arrUbigeo['prov'] = Zend_Json::encode($mUbigeo->getListJoson(2));
        $arrUbigeo['dist'] = Zend_Json::encode($mUbigeo->getListJoson(3));
        
                
        $fRegistro = new Usuario_formRegistro();
        
        $this->view->arrUbigeo = $arrUbigeo;
        $this->view->form = $fRegistro;
        $this->view->isAuth = empty($this->session->auth)?0:1;
    }
}