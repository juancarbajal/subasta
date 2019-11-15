<?php

class Admin_usuarioFiltro_1 extends Twitter_Form
{
//    private $_form;
//    public $checkboxDecorator = array(
//                                    'ViewHelper',
//                                    'Errors',
//                                    'Description',
//                                    array('HtmlTag',array('tag' => 'td')),
//                                    array('Label',array('tag' => 'td','class' =>'element')),
//                                    array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
    public $elementDecorators = array(
                                    'ViewHelper',
                                    'Errors',
                                    'Description',
                                    array('HtmlTag',array('tag' => 'span')),
                                    array('Label',array('tag' => 'span')),
                                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'openOnly' => true))
                            );
    public $elementDecorators_f = array(
                                    'ViewHelper',
                                    'Errors',
                                    'Description',
                                    array('HtmlTag',array('tag' => 'span')),
                                    array('Label',array('tag' => 'span')),
                                    array(array('row' => 'HtmlTag'), array('tag' => 'div', 'closeOnly' => false))
                            );
//    public $buttonDecorators = array(
//                                    'ViewHelper',
//                                    array('HtmlTag',array('tag' => 'td')),
//                                    //array('Label',array('tag' => 'td')), NO LABELS FOR BUTTONS
//                                    array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
    
    public function init()
    {
        
        
        //add two elements
//$this->addElement('text', 'one');
//$this->addElement('text', 'two');
// 
////Prepend an opening div tag before "one" element:
//$this->one->addDecorator('HtmlTag', array(
//    'tag' => 'div',
//    'openOnly' => true,
//    'placement' => Zend_Form_Decorator_Abstract::PREPEND
//));
// 
////Append a closing div tag after "two" element:
//$this->two->addDecorator('HtmlTag', array(
//    'tag' => 'div',
//    'closeOnly' => true,
//    'placement' => Zend_Form_Decorator_Abstract::APPEND
//));
 
        
                        
//////        
////        //We don't want the default decorators
//        $this->setDisableLoadDefaultDecorators(true);
//
//        $this->addDecorator('FormElements')
//        ->addDecorator('HtmlTag', array('tag' => 'ul')) //this adds a <ul> inside the <form>
//        ->addDecorator('Form');
//
        //Create the name elements
        $this->addElement('text', 'firstname', array(
            'label' => 'FIRST',
            'required' => true
        ));

        $this->addElement('text', 'lastname', array(
            'label' => 'LAST',
            'required' => true
        ));
        
        
        $this->addDisplayGroup(array('firstname', 'lastname'), 'name', array(
            'legend' => 'Your name'
        ));
//
//        //Put the elements inside a displaygroup to get the fieldset and all
//        $this->addDisplayGroup(array('firstname', 'lastname'), 'name', array(
//            'legend' => 'Your name'
//        ));
//
////        $this->addElement('text', 'phone', array(
////            'label' => 'Phone'
////        ));
////
////        $this->addElement('submit', 'ok', array(
////            'label' => 'OK'
////        ));
//
//        //Set the decorators we need:
//        $this->setElementDecorators(array(
//            'ViewHelper',
//            'Label',
//            'Errors',
//            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li')) //wrap elements in <li>'s
//        ));
//
//        //Set decorators for the name fields so they appear side by side
//        $this->setElementDecorators(array(
//            'ViewHelper',
//            'Label',
//            new Zend_Form_Decorator_HtmlTag(array('tag' => 'div')) //wrap names in <div> for the float
//                ), array('firstname', 'lastname'));
//
//        //Set decorators for the displaygroup:
//        $this->setDisplayGroupDecorators(array(
//            'FormElements',
//            'Fieldset',
////            new CU_Form_Decorator_AllErrors(),
//            new Zend_Form_Decorator_HtmlTag(array('tag' => 'li')) //wrap groups in <li>'s too
//        ));
//
////        //Remove label from submit button:
////        $this->ok->removeDecorator('Label');
//        
//        
        
        
        
        
        
        
        
        
        
//        $this->setName('contact_us');        
//
//        $title = new Zend_Form_Element_Select('title');
//
//        $title->setLabel('Title')
//
//              ->setMultiOptions(array('mr'=>'Mr', 'mrs'=>'Mrs'))
//
//              ->setRequired(true)->addValidator('NotEmpty', true);
//
//        
//
//        $firstName = new Zend_Form_Element_Text('firstName');
//
//        $firstName->setLabel('First name')
//
//                  ->setRequired(true)
//
//                  ->addValidator('NotEmpty');
//
//
//
//        $lastName = new Zend_Form_Element_Text('lastName');
//
//        $lastName->setLabel('Last name')
//
//                 ->setRequired(true)
//
//                 ->addValidator('NotEmpty');
//
//             
//
//        $email = new Zend_Form_Element_Text('email');
//
//        $email->setLabel('Email address')
//
//              ->addFilter('StringToLower')
//
//              ->setRequired(true)
//
//              ->addValidator('NotEmpty', true)
//
//              ->addValidator('EmailAddress'); 
//
//              
//
//        
//
//        $submit = new Zend_Form_Element_Submit('submit');
//
//        $submit->setLabel('Contact us');
//
//        
//
//        $this->addElements(array($title, $firstName, $lastName, $email, $submit));
//        
//        
//        $this->clearDecorators();
//
//        $this->addDecorator('FormElements')
//             ->addDecorator('HtmlTag', array('tag' => '<ul>'))
//             ->addDecorator('Form');       
//
//        $this->setElementDecorators(array(
//            array('ViewHelper'),
//            array('Errors'),
//            array('Description'),
//            array('Label', array('separator'=>' ')),
//            array('HtmlTag', array('tag' => 'li', 'class'=>'element-group')),
//        ));
//
//        // buttons do not need labels
//        $submit->setDecorators(array(
//            array('ViewHelper'),
//            array('Description'),
//            array('HtmlTag', array('tag' => 'li', 'class'=>'submit-group')),
//
//        ));
        
        
//        $this->addElement('text', 'email', array(
//            'decorators' => $this->elementDecorators,
//            'label'       => 'Email:',
//        ));
//        
//        $this->addElement('text', 'email2', array(
//            'decorators' => $this->elementDecorators_f,
//            'label'       => 'prue:'
//        ));
        
        
        
//////        parent::init();
        
//        $this->addElement('text', 'email', array('label' => 'E-mail'));
//
//        $this->addElement('text', 'password', array('label' => 'Password'));
//
//        $this->addDisplayGroup(
//            array('email', 'password'),
//            'login'
//            array(
//                'legend' => 'Opciones para realizar el Filtro'
//            )
//        );
//        
//        $this->addElement('submit', 'submit', array(
//            'buttonType' => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
//            'disabled'   => true,
//            'label'      => 'Send e-mail!'
//        ));
        
        
//         $this->addElement('button', 'submit', array(
//            'label'         => 'Submit!',
//            'type'          => 'submit'
//        ));
//
//        $this->addElement('button', 'reset', array(
//            'label'         => 'Reset',
//            'type'          => 'reset'
//        ));
//
//        $this->addDisplayGroup(
//            array('submit', 'reset'),
//            'actions',
//            array(
//                'disableLoadDefaultDecorators' => true,
//                'decorators' => array('Actions')
//            )
//        );
        
//        // Some text
//        $this->addElement('text', 'input1', array(
//            'label'     => 'E-mail',
//            'dimension' => 6
//        ));
//        $this->addElement('text', 'input2', array(
//            'label'     => 'E-mail',
//            'dimension' => 6
//        ));
        
//        $this->addElement('text', 'uno', array(
//            'decorators' => Twitter_Form::$_inLine_i,
//            'label' => 'Uno'
//        ));
//        $this->addElement('text', 'dos', array('label' => 'Dos'));
//        $this->addElement('text', 'tres', array(
//            'decorators' => Twitter_Form::$_inLine_f,
//            'label' => 'Tres'
//        ));
//
//        $this->setElementDecorators(array('Label', 'ViewHelper', 'HtmlTag'));
//
////        // nos falta el div que los envuelve...
////        $this->getElement('uno')->addDecorator('HtmlTag', array('class' => 'row', 'openOnly' => true));
////        $this->getElement('tres')->addDecorator('HtmlTag', array('closeOnly' => false));
        
        
    }
//    public function loadDefaultDecorators()
//    {
//        $this->setDecorators(array(
//            'FormElements',
//            array('HtmlTag', array('tag' => 'table')),
//            'Form',
//            'Errors'
//        ));
//    }

}