<?php
class Devnet_Controller_Plugin_AuthSimple extends Zend_Controller_Plugin_Abstract
{
    public static $requiredAuth = false;
    public static $baseUrl = null;
    private $_session = null;
        
    /**
     * 
     */
    function __construct ()
    {
        //parent::__construct();
        if ($this->_session == null)
            $this->_session = new Zend_Session_Namespace('AuthSimple');
    }
    
    /**
     * @param array $authUrl Array de ubicación de URL de autentificación array('module'=>'','controller'=>'','action'=>'')
     */
    function setAuthUrl ($authUrl)
    {
        $this->session->authUrl = $authUrl;
        return $this;
    }
    
    /**
     * @param array $baseUrl Array de ubicación de URL que necesita autentificación array('module'=>'','controller'=>'','action'=>'')
     */
    function setBaseUrl ($baseUrl)
    {
        $this->session->baseUrl = $baseUrl;
        return $this;
    }
    /**
     * @param Zend_Controller_Request_Abstract $request
     */
    //function dispatchLoopStartup (Zend_Controller_Request_Abstract $request)
    function postDispatch(Zend_Controller_Request_Abstract $request)
    {    	
    	if (($request->getModuleName() == $this->_session->authUrl['module']) 
        	&& ($request->getControllerName() == $this->_session->authUrl['controller']) 
        	&& ($request->getActionName() == $this->_session->authUrl['action'])) {
            self::$baseUrl = $this->session->baseUrl['module']
            			   . '/'
            			   . $this->session->baseUrl['controller']
            			   . '/'
            			   . $this->session->baseUrl['action'];
        } else {        		
	        if (self::$requiredAuth) {	        	
	            $this->_session->baseUrl['controller'] = $request->getControllerName();
	            $this->_session->baseUrl['module'] = $request->getModuleName();
	            $this->_session->baseUrl['action'] = $request->getActionName();
	            //$auth = Zend_Auth::getInstance();
	           	$auth = new Zend_Session_Namespace('auth');	            
	           	if (($auth == null) || (!$auth->authenticated)){
	           		//echo 'ENTRO';
	            	$request->setModulename($this->_session->authUrl['module']);
	            	$request->setControllerName($this->_session->authUrl['controller']);
	            	$request->setActionName($this->_session->authUrl['action']);	            	
	            	return ;           		
	           	}
	        }
        }        
    }
}