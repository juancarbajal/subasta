<?php
/**
 * @author ander
 *
 */
class Devnet_FormAdmin extends Zend_Form {
    
    protected $standarDecorador = array(
        'ViewHelper',
//        array(array('data' => 'HtmlTag'), array('tag' => 'span', 'class' => 'element')),//-->addElement(decorators)
        array('HtmlTag', array('tag' => 'span', 'class' => 'element')),//->addValidator
//        'Label',
        array('Label', array('class'=>'awidth2'/*,'tag' => 'span'*/)),
//        array(array('row' => 'HtmlTag'), array('tag' => 'li')),
    );
    
    protected $standarDecoradorBtn = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-actions')),
//        array(array('row' => 'HtmlTag'), array('tag' => 'li'))
    );    

    protected $standarDecoradorGroup = array(
        'FormElements',
        array('HtmlTag', array('tag' => 'div', 'class' => 'form-inline aFormLine'))
    );
    
    protected $standarDecoradorForm = array(
        'FormErrors',
        'FormElements',
        array('HtmlTag', array('tag' => 'div', 'class'=>'ahero-unit')),
        'Form'
    );
    
    protected $_maxDesc = '60';
    protected $_maxFec = '10';
    
    protected function addStandarDecotadorV1()
    {
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
            array(array('data'=>'HtmlTag'),array('tag'=>'span', 'class'=>'element')),
//            'Label',
            array('Label', array('class'=>'awidth2')),
//            array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class'=>'element'))
        ));
        $arrCheck = $arrBtn = array();
        $elementObjs = $this->getElements();
        foreach ($elementObjs as $element) {
            if($element->getType() == 'Zend_Form_Element_Checkbox' && empty($element->esNormal)){                
                $arrCheck[$element->getName()]=$element->getName();
            } elseif($element->getType() == 'Zend_Form_Element_Button'){
                $arrBtn[$element->getName()]=$element->getName();
            }
        }
        if (!empty($arrCheck)) {
            $this->setElementDecorators(
                array('viewHelper', 
                    'Errors',
                    array(array('data'=>'HtmlTag'),array('tag'=>'span', 'class'=>'element')),
                    array('Label', array('class'=>'awidth3')),
    //                array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class'=>'element'))
                ), $arrCheck //,array('submit', 'reset')
            );
        }
        if (!empty($arrBtn)) {
            $this->setElementDecorators(
                array('viewHelper', 
                    array(array('data'=>'HtmlTag'),array('tag' => 'div', 'class' => 'form-actions'))
                ), $arrBtn
            );
        }
        
        $this->setDisplayGroupDecorators(array(
                'FormElements',
//                'Fieldset',
                array('HtmlTag', array('tag' => 'div', 'class' => 'form-inline aFormLine'))
        ));
        $this->setDecorators(array('FormElements',
                array(array('data'=>'HtmlTag'),array('tag'=>'div', 'class'=>'ahero-unit')),
                'Form'
        ));
//        $form->setSubFormDecorators(array(
//            'FormElements',
//            'Fieldset'
//        ));
    }
    
    /**
     *  Categoria
     */
    protected function addStandarDecotadorV1_1()
    {
        $this->setElementDecorators(array(
            'viewHelper',
            'Errors',
            array(array('data'=>'HtmlTag'),array('tag'=>'span', 'class'=>'element')),
//            'Label',
            array('Label', array('class'=>'awidth2')),
//            array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class'=>'element'))
        ));
        
        $elementObjs = $this->getElements();
        foreach ($elementObjs as $element) {
            if($element->getType() == 'Zend_Form_Element_Button'){
                $arrBtn[$element->getName()]=$element->getName();
            }
        }
        if (!empty($arrBtn)) {
            $this->setElementDecorators(
                array('viewHelper', 
                    array(array('data'=>'HtmlTag'),array('tag'=>'span', 'class'=>'span3'))
                ), $arrBtn
            );
        }
        
        $this->setDisplayGroupDecorators(array(
                'FormElements',
//                'Fieldset',
                array('HtmlTag', array('tag' => 'div', 'class' => 'form-inline aFormLine'))
        ));
        $this->setDecorators(array('FormElements',
                array(array('data'=>'HtmlTag'),array('tag'=>'div', 'class'=>'ahero-unit')),
                'Form'
        ));
    }
    
    /*
     * Para formularios de edicion
     */
    protected function addStandarDecotadorV2()
    {
        $this->setElementDecorators(array(
            'viewHelper',
//            'Errors',
            array('Description', array('tag'=>'span', 'escape'=>false)),
            array(array('data'=>'HtmlTag'),array('tag'=>'span', 'class'=>'element')),
            array('Label', array('class'=>'awidth3', 'escape'=>false)),
        ));
        $arrCheck = $arrBtn = array();
        $elementObjs = $this->getElements();
        foreach ($elementObjs as $element) {
            if($element->getType() == 'Zend_Form_Element_Checkbox' && empty($element->esNormal)){                
                $arrCheck[$element->getName()]=$element->getName();
            } elseif($element->getType() == 'Zend_Form_Element_Button'){
                $arrBtn[$element->getName()]=$element->getName();
            }
        }
        if (!empty($arrCheck)) {
            $this->setElementDecorators(
                array('viewHelper', 
//                    'Errors',
                    array(array('data'=>'HtmlTag'),array('tag'=>'span', 'class'=>'element')),
                    array('Label', array('class'=>'awidth3')),
    //                array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class'=>'element'))
                ), $arrCheck //,array('submit', 'reset')
            );
        }
        if (!empty($arrBtn)) {
            $this->setElementDecorators(
                array('viewHelper', 
                    array(array('data'=>'HtmlTag'),array('tag' => 'div', 'class' => 'form-actions'))
                ), $arrBtn
            );
        }
        
        $this->setDisplayGroupDecorators(array(
                'FormElements',
//                'Fieldset',
                array('HtmlTag', array('tag' => 'div', 'class' => 'form-inline aFormLine'))
        ));
//        $this->setDecorators(
//          array('FormElements',array(array('data'=>'HtmlTag'),array('tag'=>'div', 'class'=>'ahero-unit')),'Form'
//        ));
        $this->setDecorators(array(
            new Zend_Form_Decorator_FormErrors(array(
                'ignoreSubForms'=>true,
                'markupElementLabelEnd'=> '</b>',
                'markupElementLabelStart'=> '<b>',
                'markupListEnd' => '</div>',
                'markupListItemEnd'=>'</span>',
                'markupListItemStart'=>'<span>',
                'markupListStart'=>'<div>'
            )),
            'FormElements',
//            array(array('data'=>'HtmlTag'),array('class'=>'error')),
            array(array('row'=>'HtmlTag'),array('tag'=>'div', 'class'=>'ahero-unit')),
            'Form'
        ));
    }
    
}
?>