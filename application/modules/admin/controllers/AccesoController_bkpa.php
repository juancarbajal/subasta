<?php
/**
 * Admin class file
 *
 * PHP Version 5.3
 *
 * @category Busqueda
 * @package  Busqueda
 * @author   @author Ander <anderson.poccorpachi@ec.pe>
 * @link     http://kotear.pe/busqueda
 */

/**
 * Admin class
 *
 * The class holding the root Recipe class definition
 *
 * @category Busqueda
 * @package  Busqueda
 * @author   @author Ander <anderson.poccorpachi@ec.pe>
 * @link     http://kotear.pe/busqueda
 */
class Admin_AccesoController extends App_Controller_Action_Admin
{
    public function init()
    {
        parent::init();
    }
    
    public function indexAction()
    {
//        require_once APPLICATION_PATH . '/modules/admin/forms/login.php';
        
        $form = new Application_Form_Login();
        $form = $form->getLogin();
        if ($this->_request->isPost()) {
            if (!$form->isValid($this->_request->getParams())) {
                $form->populate($this->_request->getParams());
            } else {
//                include 'UsuarioPortal.php';
//                $mUsuarioPortal = new UsuarioPortal();
                
                $mUsuarioPortal = new Application_Model_Sp_InUsuario();
                
                $res = $mUsuarioPortal->validarUsuario(
                    $this->_request->getParam('user'), $this->_request->getParam('pass')
                );
                
//                var_dump($res);exit();
                
                if ($res->K_ERROR == 0) {
                    $data = $mUsuarioPortal->findAuth($res->ID_USR);
                    $this->setAuthData($data);
                    if (isset($this->session->requiereLoginUrl)) {
                        $url = $this->session->requiereLoginUrl;
                        unset($this->session->requiredLoginUrl);
                    } else {
                        $url = $this->view->baseUrl() . '/admin/index';
                    }
                    $this->_redirect($url);
                }
            }
        }
        $this->view->form = $form;         
    }
    
    function setAuthData($data)
    {
        $this->session->auth = $data;
    }
        
    function logoutAction()
    {
        //If (Zend_Session::sessionExist()) {
        $this->clearSession();
        if ($this->_request->isXMLHttpRequest()) {
            $this->json(array('code' => 0, 'msg' => 'Se cerró sesión satisfactoriamente.'));
        } else {
            $this->_redirect("/admin/acceso");
        }
        //}
    }
    
    function clearSession($all = true)
    {
        $this->clearIdentity();
        //Zend_Session::destroy(true, false);
        if ($all) {
            Zend_Session::destroy(false);unset($this->session);
        }
    }
    
    function clearIdentity()
    {
        unset($this->identity);
        unset($this->view->identity);
        Zend_Auth::getInstance()->clearIdentity();
    }
    
}