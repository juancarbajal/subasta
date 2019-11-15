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
class Application_Form_TipoDestaque
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
                    'form'=>'tipo-destaque/form'
                )
            )
            ->setAction('/tipo-destaque/buscar');
        
            $element = new Zend_Form_Element_Text('a_codigo');
            $element->setLabel('Código:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('a_titulo');
            $element->setLabel('Título:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_codigo', 'a_titulo'), 
            'grup1'
        );
            $element = new Zend_Form_Element_Text('a_prioridad');
            $element->setLabel('Prioridad:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_prioridad'), 
            'grup2'
        );
        
        $element = new Zend_Form_Element_Submit('a_buscar');
        $element->setLabel("Buscar")
            ->setAttribs(array('class' => 'btn btn-primary', 'id' => 'apfbBtnSearch'));
        $this->addElement($element);
        
        $this->addStandarDecotadorV1();
        
    }

}