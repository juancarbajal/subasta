<?php
/**
 * @author ander
 *
 */
class Admin_IndexController extends App_Controller_Action_Admin
{
    public function init() {
        parent::init();
    }
    
    public function indexAction()
    {
        $this->view->form = 'INDEX';
    }
    
}