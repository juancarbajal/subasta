<?php

class Admin_usuarioFiltro_2 extends Twitter_Form
{
    
//    private $elementDecorators = array(
//        'ViewHelper',
//        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),
//        'Label',
//        array(array('row' => 'HtmlTag'), array('tag' => 'li')),
//    );
//
//    private $elementGroupDecorators = array(
//            'FormElements',
//            array('HtmlTag', array('tag' => 'div', 'class' => 'grupo'))
//    );
//    
//    
//
    private $elementFormDecorators = array(
            'FormErrors',
            'FormElements',
            array('HtmlTag', array('tag' => 'ol', 'class'=>'olo')),
            'Form'
    );
    
    public function init()
    {
        $this->setDecorators(Twitter_Form::$deForm);
        
////        $name = new Zend_Form_Element_Text('name', array(
////            'decorators' => $this->elementDecorators,
////            'label' => 'Your name'
////        ));
////        $this->addElement($name);
////        
////        $email = new Zend_Form_Element_Text('email', array(
////            'decorators' => $this->elementDecorators,
////            'label' => 'Your email'
////        ));
////        $this->addElement($email);
        
        $this->addElement('text', 'firstname', array('label' => 'firstname',
            'class'=>'span2',
            'decorators' => Twitter_Form::$deGeneral));
        $this->addElement('text', 'lastname', array('label' => 'lastname',
            'class'=>'span2',
            'decorators' => Twitter_Form::$deGeneral));
        $this->addDisplayGroup(array('firstname', 'lastname'), 'Nombre',
                array('decorators' => Twitter_Form::$deGroup));
        
////        $this->addDisplayGroup(array('submit', 'cancel',), 'submitButtons', array(
////            'order'=>4,
////            'decorators' => array(
////                'FormElements',
////                array('HtmlTag', array('tag' => 'div', 'class' => 'element')),
////            ),
////        )); 
//        
////        $this->setDisplayGroupDecorators(array(
////            'FormElements',
////            array('HtmlTag', array('tag' => 'div', 'class' => 'grupo'))
////        ));
//        
    }
    
//    public function loadDefaultDecorators()
//    {
//        $this->setDecorators(array(
//            'FormErrors',
//            'FormElements',
//            array('HtmlTag', array('tag' => 'ol')),
//            'Form'
//        ));
//    }

}