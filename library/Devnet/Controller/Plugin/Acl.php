<?php
/**
 * @author jcarbajal
 *
 */
class Devnet_Controller_Plugin extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var unknown_type
     */
    protected $_acl = null;
    /**
     * @var unknown_type
     */
    protected $_auth = null;
    /**
     * @var unknown_type
     */
    protected $_defaultPage = '';
    /**
     * @var unknown_type
     */
    protected $_guestRole = 'guest';
    /**
     * @param Zend_Auth $auth
     * @param Zend_Acl $acl
     */
    function __construct (Zend_Auth $auth, Zend_Acl $acl)
    {
        $this->_acl = $acl;
        $this->_auth = $auth;
    }
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return string
     */
    function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $role = $this->getGuestRole();
        if ($this->_auth->hasIdentity()) {
            $userSession = $this->_auth->getStorage()->read();
            $role = $userSession->rol;
        }
        $resource = $request->getModuleName();
        if ($this->_acl->isAllowed($role, $resource) && $role != $this->getGuestRole()) {
            return true;
            //es permitido el usuario a ver el modulo requerido.
        } else {
            if ($resource != 'default') {
                //Mediante este parametro veo si mostrare un mensaje de error
                $request->setParam('error', true);
            }
            //$request->setModuleName('default')->setControllerName('index')->setActionName('index');
            $request->_redirect($this->getDefaultPage());
        }
    }
    /**
     * @return the $_defaultPage
     */
    function getDefaultPage ()
    {
        return $this->_defaultPage;
    }
    /**
     * @param $_defaultPage the $_defaultPage to set
     */
    function setDefaultPage ($_defaultPage)
    {
        $this->_defaultPage = $_defaultPage;
        return $this;
    }
    /**
     * @return the $_guestRole
     */
    function getGuestRole ()
    {
        return $this->_guestRole;        
    }    
    /**
     * @param $_guestRole the $_guestRole to set
     */
    function setGuestRole ($_guestRole)
    {
        $this->_guestRole = $_guestRole;
        return $this;
    }
}