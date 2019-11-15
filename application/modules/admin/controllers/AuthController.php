<?php
/**
 * Admin class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/admin/auth
 */

/**
 * Admin class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/admin/auth
 */
class Admin_AuthController extends App_Controller_Action_Admin
{
    /**
     * Descripcion
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function indexAction()
    {
        $form = new Application_Form_Login();
        $form = $form->getLogin();
        if ($this->_request->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                
                $user = $form->getValue('user');
                $pass = $form->getValue('pass');
                $tipo = $form->getValue('tipo');
                
                $mInUsuario = new Application_Model_Sp_InUsuario();
                $mInAdministrador = new Application_Model_Sp_InAdministrador();
                $mInOpcion = new Application_Model_Sp_InOpcion();
                
                $resp = $mInUsuario->validarUsuario($user, $pass, $tipo);
                
                if ($resp->K_ERROR == 0) {                    
                    $data['admin'] = $mInAdministrador->getAdministradorForAuth($resp->K_ID_USR);
                    $data['opciones'] = $mInOpcion->getOpcionForAuth($resp->K_ID_USR);
                    
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write($data);
                    
                    $url = $this->view->baseUrl() . '/admin/index';
                    /*$this->setAuthData($data);
                    if (isset($this->session->requiereLoginUrl)) {
                        $url = $this->session->requiereLoginUrl;
                        unset($this->session->requiredLoginUrl);
                    } else {
                        $url = $this->view->baseUrl() . '/admin/index';
                    }*/
                    $this->_redirect($url);
                } else {
                    $form->addError($resp->K_MSG);
                }
            }
        }
        $this->view->form = $form;         
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    function logoutAction()
    {
        $this->logout();
        if ($this->isAjax) {
            $this->json(array('code' => 0, 'msg' => 'Se cerró sesión satisfactoriamente.'));
        } else {
            $this->_redirect("/admin/auth");
        }
    }
}