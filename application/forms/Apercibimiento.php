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
class Application_Form_Apercibimiento
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
    public function busqueda()
    {
        $this->setMethod('post')
            ->setAttribs(
                array(
                    'id'=>'apfbFormSearch',
                    'form'=>'apercibimiento/form'
                )
            )
            ->setAction('/apercibimiento/buscar');
            
            $element = new Zend_Form_Element_Text('a_titulo');
            $element->setLabel('Titulo:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('a_dias');
            $element->setLabel('Días:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
            $element = new Zend_Form_Element_Checkbox('a_estado');
            $element->setLabel('Estado:');
            //->setAttribs(array('class' => 'fCheck'))
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_titulo', 'a_dias', 'a_estado'), 
            'grup1'
        );
            $element = new Zend_Form_Element_Text('a_nivelMotivo');
            $element->setLabel('Nivel de Motivo:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('a_tipoMotivo');
            $element->setLabel('Tipo Motivo:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
            $element = new Zend_Form_Element_Checkbox('a_mail');
            $element->setLabel('Mail:');
            //->setAttribs(array('class' => 'fCheck'))
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_nivelMotivo', 'a_tipoMotivo', 'a_mail'), 
            'grup2'
        );
        
        $element = new Zend_Form_Element_Submit('a_buscar');
        $element->setLabel("Buscar")
            ->setAttribs(array('class' => 'btn btn-primary', 'id' => 'apfbBtnSearch'));
        $this->addElement($element);
        
        $this->addStandarDecotadorV1();
    }
}