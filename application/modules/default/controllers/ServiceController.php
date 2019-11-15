<?php
class ServiceClass
{
    function limpiarCache ()
    {
        $cache = Zend_Registry::get('cache');
        try {
            $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
            return array('code' => 0 , 'msg' => 'Cache borrado satisfactoriamente');
        } catch (Exception $e) {
            return array('code' => 1 , 'msg' => $e->getMessage());
        }
    }
}
class ServiceController extends Zend_Rest_Controller
{    
    function init ()
    {
        parent::init();
        $this->_helper->viewRenderer->setNoRender(true);
    }
    public function indexAction ()
    {
        $server = new Zend_Rest_Server();
        $server->setClass(ServiceClass);
        $server->handle();
        exit();
    }
    public function getAction ()
    {
        $this->_forward('index');
    }
    public function postAction ()
    {
        $this->_forward('index');
    }
    public function putAction ()
    {
        $this->_forward('index');
    }
    public function deleteAction ()
    {
        $this->_forward('index');
    }    
    public function headAction()
    {
        $this->_forward('index');
    }
}