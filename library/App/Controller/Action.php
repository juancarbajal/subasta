<?php
/**
 * Action class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */

/**
 * Action class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class App_Controller_Action
    extends Zend_Controller_Action
{
    private $_flashMessenger = null;
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
        //Inicializacion
        $storage = new Zend_Auth_Storage_Session();
        $authStorage = $storage->read();
        if ($authStorage) {
            $isAuth = true;
        } else {
            $authStorage = null;
            $isAuth = false;
        }
        $this->isAuth = $isAuth;
        $this->auth = $authStorage;
        Zend_Layout::getMvcInstance()->assign('auth', $authStorage);
        
        $this->initTranslate();
    }
    
    /**
     * Acciones realizadas antes de la carga de la pagina en view
     * 
     * @return void
     */
    public function preDispatch()
    {
        parent::preDispatch();
        
        $helper = $this->_helper->getHelper('FlashMessengerCustom');
        $this->_flashMessenger = $helper;
    }
    
    /**
     * Acciones realizadas despues de la carga total de la pagina en view
     * 
     * @return void
     */
    public function postDispatch()
    {
        //parent::postDispatch();
        $messages = $this->_flashMessenger->getMessages();
        if ($this->_flashMessenger->hasCurrentMessages()) {
            $messages = $this->_flashMessenger->getCurrentMessages();
            $this->_flashMessenger->clearCurrentMessages();
        }
        $this->view->assign('flashMessages', $messages);
        Zend_Layout::getMvcInstance()->assign('flashMessages', $messages);
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    protected function initTranslate()
    {
        $translate = new Zend_Translate('array', APPLICATION_PATH . '/configs/lang/es.php', 'es');
        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Validate_Abstract::setDefaultTranslator($translate);
        Zend_Form::setDefaultTranslator($translate);
    }
    
    /**
     * Retorna un objeto Zend_Config con los parámetros de la aplicación
     * 
     * @return Zend_Config
     */
    public function getConfig()
    {
        return Zend_Registry::get('config');
    }
    
    /**
     * Retorna la instancia personalizada de FlashMessenger
     * Forma de uso:
     * $this->getMessenger()->info('Mensaje de información');
     * $this->getMessenger()->success('Mensaje de información');
     * $this->getMessenger()->error('Mensaje de información');
     * 
     * @return App_Controller_Action_Helper_FlashMessengerCustom
     */
    public function getMessenger()
    {
        return $this->_flashMessenger;
    }
    
    /**
     * Elimina la session
     * 
     * @return void
     */
    protected function logout()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
    }
}