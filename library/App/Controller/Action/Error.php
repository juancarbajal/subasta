<?php

class App_Controller_Action_Error extends App_Controller_Action
{
    
    public function init()
    {
        parent::init();
        
        $config = $this->getConfig();
        
        $this->_helper->layout->setLayout('main-error');
        
        $this->view->headTitle()->set(
            'Ha ocurrido un error - ' . $config->app->title
        );
        
        $this->view->headLink()->appendStylesheet(
            $config->app->mediaUrl . '/css/main.admin.css', 'all'
        );
    }
}