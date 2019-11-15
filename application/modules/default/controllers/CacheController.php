<?php
class CacheController 
    extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'text/javascript');
    }
    
    public function jsAction() 
    {
        $this->_helper->layout->disableLayout();
        $filename = $this->_request->getParam('file');
        $this->getResponse()->setHeader('Content-Type', 'text/javascript; charset: UTF-8');
        echo $this->cargarArchivo(
            realpath(APPLICATION_PATH . '/../public/f/js'), str_replace('_', '.', $filename) . '.js'
        );
    }
    
    public function cssAction() 
    {

    }
    
    public function cargarArchivo($path, $filename)
    {
        $cache = Zend_Registry::get('cache');
        $cacheName = 'kotear_cache_'.str_replace('.', '_', $filename);
        if (! $result = $cache->load($cacheName)) {
            $result =  file_get_contents($path . '/' . $filename);
            $cache->save($result, $cacheName);
        }
        return $result;
    }
}