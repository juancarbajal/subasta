<?php
/**
 * @author ander
 *
 */
class Admin_IndexController extends Devnet_Controller_Action
{
    public function indexAction()
    {
        if (!$this->session->auth->admin == 1) {
            $this->_forward('login');
        }
    }
    
    public function loginAction()
    {
        require_once APPLICATION_PATH . '/modules/admin/forms/formsUsuario.php';
        
        $form = new Admin_formsUsuario();
        $form = $form->getLogin();
        if ($this->_request->isPost()) {
            if (!$form->isValid($this->_request->getParams())) {
                $form->populate($this->_request->getParams());
            } else {
                require 'Usuario.php';
                require 'UsuarioIntranet.php';
                
                $usuario = new Usuario();
                $usuarioAdm = new UsuarioIntranet();
                $result = $usuario->validarUsuarioIntranet($this->_request->getParam('user',0)
                        ,$this->_request->getParam('pass',''));
                if ($result) {
                    $datoUsuarios = $usuario->getDatosUsuarioIntranet();
                    foreach ($datoUsuarios as $val) {
                        if ($val->USERNAME == $this->_request->getParam('user')
                                && $val->EST == '1') {
                            $this->session->auth->APODO = $val->USERNAME;
                            $this->session->auth->admin = $val->ID_USUARIO_INTRANET;
                            $this->session->auth->rol_id = $val->ROL_ID;
                            $this->session->auth->acl = $usuarioAdm->getPermisos($val->ROL_ID);
                            $this->_redirect('/admin/index/index');                            
                        }
                    }
                }
            }
        }
        $this->view->form = $form;         
    }
    
    public function menuAction()
    {
        $this->render('index');
    }
    
    public function logoutAction()
    {
        unset($this->identity);
        unset($this->view->identity);
        Zend_Session::destroy(false);
        unset($this->session);
        Zend_Auth::getInstance()->clearIdentity();        
    }
}