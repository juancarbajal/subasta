<?php
/**
 * Description of BlockIp
 *
 * @author luis
 */

class Devnet_Controller_Plugin_BlockIp extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        parent::preDispatch($request);
        if ($request->getControllerName() == 'aviso' 
                && $request->getActionName() == 'ver') {
                    $this->_validarIp();
        }
    }
    
    protected function _validarIp()
    {
        require 'BlockIp.php';
        
        $blackList = new BlockIp();
        $idSession = trim($this->_getSessionPage());
        if (!empty($idSession)) {
            $datos = array('ID_SESSION' => $idSession,
                'ID_AVISO' => (int) $this->_getIdProducto()
            );
            if ($blackList->validar($datos) < 1) {
                $blackList->insert($datos);
                require_once 'AvisoInfo.php';

                $aviso = new AvisoInfo();
                $aviso->visitar($this->_getIdProducto());
            }
        }
        
        if (!empty(Zend_Registry::get('config')->prueba->blackip)) {
            require 'Base/BlockIpLog.php';        
            $blackListLog = new Base_BlockIpLog();
            $ip = trim($this->_getIp());
            $datos = array(
                'IP' => $ip, 'ID_SESSION' => $idSession, 'ID_AVISO' => (int) $this->_getIdProducto()
            );
            $blackListLog->insert($datos);
        }
        
    }

    private function _getSessionPage()
    {
        Zend_Session::start();
        $mysession = new Zend_Session_Namespace('kFichaAviso');
        $mysession->setExpirationSeconds(86400); 
        $mysession->initialized = true;
        return Zend_Session::getId();
    }
    
    protected function _getIp()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) 
                AND $_SERVER['HTTP_X_FORWARDED_FOR'] 
                AND (!isset($_SERVER['REMOTE_ADDR']) 
                OR preg_match('/^127\..*/i', trim($_SERVER['REMOTE_ADDR'])) 
                OR preg_match('/^172\.16.*/i', trim($_SERVER['REMOTE_ADDR'])) 
                OR preg_match('/^192\.168\.*/i', trim($_SERVER['REMOTE_ADDR'])) 
                OR preg_match('/^10\..*/i', trim($_SERVER['REMOTE_ADDR'])))) {
            
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                return $ips[0];
            
        }
        return str_replace('.', '', $_SERVER['REMOTE_ADDR']);
    }
    
    protected function _getBrowser()
    {
        return $this->getRequest()->getServer('HTTP_USER_AGENT');
    }
    
    protected function _getIdProducto()
    {
        $idTotal = explode('-', $this->getRequest()->getParam('id', 0));
        return $idTotal[0];
    }    
}