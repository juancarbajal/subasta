<?php
class Kotear_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
        $acl = Zend_Registry::get('acl');
        //$auth = new Zend_Session_Namespace('auth');
        if (Zend_Auth::getInstance()->hasIdentity()) {
        	$rol = 'usuario';
        } else {
        	$rol = 'visitante';
        }
        $privilageName = $request->getActionName();
        $nav = new Zend_Session_Namespace('Navigation');
        if (!$acl->isAllowed($rol, null, $privilageName)){
        	$nav->requiredAuthUrl = $request->getModuleName() 
        					   	  . '/'
        					   	  . $request->getControllerName()
        					   	  . '/'
        					   	  . $request->getActionName();
        	$request->setModuleName('usuario');
        	$request->setControllerName('acceso');
        	$request->setActionName('index');        	
        }
        
        /*$usersNs = new Zend_Session_NameSpace(members);
        if ($usersNs->userType == '') {
            $roleName = guest;
        } else {
            $roleName = $userType;
        }
        $privilageName = $request->getActionName();
        if (! $acl->isAllowed($roleName, null, $privilageName)) {
            $request->setControllerName(Error);
            $request->setActionName(index);
        }*/
        
    }
}
