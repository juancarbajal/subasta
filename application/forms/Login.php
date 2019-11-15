<?php
/**
 * Admin class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Form
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */

/**
 * Admin class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Form
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class Application_Form_Login extends App_Form_Default
{
    const ROL_ADMIN = 'admin';
    
    private $_form;
    
    private $_decoradorInput = array(
        'ViewHelper',
        array('Description', array('escape' => false, 'tag' => false, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
        array('Label', array('class'=>'control-label')),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group')),        
        array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class'=>'control-group'))
    );
    
    private $_decoradorForm = array(
        'FormErrors',
        'FormElements',
        'Form'
    );
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->_form = $this;
        $this->setAttrib("horizontal", true); 
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function getLogin()
    {
        $this->setMethod('POST')
            ->setAttrib('id', 'loginAdmin')
            ->setAttrib('class', 'form-horizontal')
            ->addDecorators($this->_decoradorForm)
            //->setAction("/admin/auth/login")
            //->removeDecorator('HtmlTag')
            ;
        
        $user = $this->createElement('text', 'user');//, array('decorators' => array($decorator)));
        $user->setLabel('Usuario')
            ->setDescription('<div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span></div>')
            ->setAttribs(array('size'=>50,'class'=>'span2'))
            ->addValidator('StringLength', false, array(1, 50))
            ->setRequired(true)
            ->addDecorators($this->_decoradorInput)
            ;
        
        $pass = $this->createElement('password', 'pass');
        $pass->setLabel('Clave')
            ->setDescription('<div class="input-prepend"><span class="add-on"><i class="icon-chevron-right"></i></span></div>')
            ->setAttribs(array('size'=>50,'class'=>'span2'))
            ->addValidator('StringLength', false, array(1, 50))
            ->setValue("")
            ->setRequired(true)
            ->addDecorators($this->_decoradorInput);
        
        $tipo = $this->createElement('hidden', 'tipo');
        $tipo->setValue("1")
            ->setRequired(true)
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Label');
        
        $submit = $this->createElement('submit', 'send');
        $submit->setLabel('Enviar')
            ->setAttribs(array('class'=>'btn btn-danger'))
            ->removeDecorator('Label')
            ->addDecorators($this->_decoradorInput)
            ->removeDecorator('Label');
        
        $token = new Zend_Form_Element_Hash('token');
        $token->setSalt(md5(uniqid(rand(), true)))
            ->setTimeout(300)
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Label');

        $this->addElements(array($user, $pass, $tipo, $token, $submit));
        
        return $this->_form;
    }
}