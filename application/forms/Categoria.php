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
class Application_Form_Categoria
    extends App_Form_Admin
{
    /**
     * Descripcion
     * 
     * @return void
     */
    public function init()
    {
        
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function formulario()
    {
        $this->setMethod('post')
            ->setAttribs(array('id'=>'afbForm'));
        
            $element = new Zend_Form_Element_Text('f_titulo');
            $element->setLabel('Titulo:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setRequired(true)
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_titulo'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Text('f_descripcion');
            $element->setLabel('Descripcion:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setRequired(true)
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_descripcion'), 
            'grup2'
        );
        
        $element = new Zend_Form_Element_Checkbox('f_adulto');
            $element->setLabel('Adulto:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'));
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_adulto'), 
            'grup3'
        );
        
        $element = new Zend_Form_Element_Checkbox('f_destaque');
            $element->setLabel('Apta Destaque:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'));
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_destaque'), 
            'grup4'
        );
        
        $element = new Zend_Form_Element_Checkbox('f_visualiza');
            $element->setLabel('Visualiza:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'));
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_visualiza'), 
            'grup5'
        );
        
        $hash = new Zend_Form_Element_Hash('auth_hash');
        $this->addElement($hash);
        
        //$this->addStandarDecotadorV2();
        
    }
    
    /**
     * Descripcion
     * 
     * @param String $value Variables
     * 
     * @return void
     */
    public function addId($value)
    {
        $element = new Zend_Form_Element_Hidden('f_id');
        $element->setValue($value)
            ->setRequired(true);
        $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_id'), 
            'agrup1'
        );
    }
    
    /**
     * Descripcion
     * 
     * @param String $value Variables
     * 
     * @return void
     */
    public function addPadreId($value)
    {
        $element = new Zend_Form_Element_Hidden('f_padre_id');
        $element->setValue($value)
            ->setRequired(true);
        $this->addElement($element);
    }
    
    /**
     * Descripcion
     * 
     * @param String $value Variables
     * 
     * @return void
     */
    public function addNivel($value)
    {
        $element = new Zend_Form_Element_Hidden('f_nivel');
        $element->setValue($value)
            ->setRequired(true);
        $this->addElement($element);
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function addDecorador()
    {
        $this->addStandarDecotadorV2();
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function busqueda()
    {
        $this->setMethod('post')
            ->setAttribs(array('id'=>'aFormCategoria'))
            ->setAction('/categoria/form');
            
            $element = new Zend_Form_Element_Select('a_catn1');
            $element->setAttribs(
                array(
                    'class'=>'span3 aSelectCat',
                    'size'=>15,
                    'dependencia'=>'#a_catn2',
                    'nivel'=>'1',
                    'padre_id'=>'0'
                )
            )
                ->addMultiOptions(
                    Application_Model_Sp_Categoria::getSCategoriaN1()
                );
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Select('a_catn2');
            $element->setAttribs(
                array('class'=>'span3 aSelectCat', 'size'=>15, 'dependencia'=>'#a_catn3', 'nivel'=>'2')
            );
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Select('a_catn3');
            $element->setAttribs(
                array('class'=>'span3 aSelectCat', 'size'=>15, 'dependencia'=>'#a_catn4', 'nivel'=>'3')
            );
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Select('a_catn4');
            $element->setAttribs(
                array('class'=>'span3 aSelectCat', 'size'=>15, 'nivel'=>'4')
            );
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_catn1', 'a_catn2', 'a_catn3', 'a_catn4'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Button('a_btnCaten1');
            $element->setLabel("Agregar")
                ->setAttribs(array('class' => 'btn btn-primary abtnNC', 'nivel'=>'1', 'padre_id'=>'0'));
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Button('a_btnCaten2');
            $element->setLabel("Agregar")
                ->setAttribs(array('class' => 'btn btn-primary abtnNC', 'nivel'=>'2'));
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Button('a_btnCaten3');
            $element->setLabel("Agregar")
                ->setAttribs(array('class' => 'btn btn-primary abtnNC', 'nivel'=>'3'));
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Button('a_btnCaten4');
            $element->setLabel("Agregar")
                ->setAttribs(array('class' => 'btn btn-primary abtnNC', 'nivel'=>'4'));
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_btnCaten1', 'a_btnCaten2', 'a_btnCaten3', 'a_btnCaten4'), 
            'grup2'
        );            
            
        $this->addStandarDecotadorV1_1();
    }

}