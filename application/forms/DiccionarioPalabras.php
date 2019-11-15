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
class Application_Form_DiccionarioPalabras
    extends App_Form_Admin
{
    
    private $_maxLengthOrden = '3';
    
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
            ->setAttribs(array('id'=>'apfbForm'));
        
            $element = new Zend_Form_Element_Text('f_tag');
            $element->setLabel('Tag:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setRequired(true)
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Hidden('f_id');
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_tag', 'f_id'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Text('f_url');
            $element->setLabel('URL:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Select('f_prioridad');
            $element->setLabel('Prioridad:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4',)
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione Prioridad!';
            $this->addElement($element);
            
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_url', 'f_prioridad'), 
            'grup2'
        );
        
            $element = new Zend_Form_Element_Text('f_orden');
            $element->setLabel('Orden:')
                ->setAttribs(array('maxlength' => $this->_maxLengthOrden,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxLengthOrden))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Checkbox('f_estado');
            $element->setLabel('Activo:')
                ->setAttribs(array('class' => 'span3'))
                ->setCheckedValue('1')
                ->setUncheckedValue('0');
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_orden', 'f_estado'), 
            'grup3'
        );
        
            $element = new Zend_Form_Element_Button(
                'f_cerrar',
                array(
                    'label' => 'Cerrar',
                    'class' => 'btn apbtnCloseModal'
                )
            );
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Submit(
                'f_guardar',
                array(
                    'label' => 'Guardar',
                    'class' => 'btn btn-primary',
                    'id' => 'apbtnGuardar',
                )
            );
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('f_cerrar', 'f_guardar'), 
            'grup10'
        );
        
        $hash = new Zend_Form_Element_Hash('auth_hash');
        $this->addElement($hash);
        
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
            ->setAttribs(
                array(
                    'id'=>'apfbFormSearch',
                    'form'=>'diccionario-palabra/form'
                )
            )
            ->setAction('/diccionario-palabra/buscar');
            
            $element = new Zend_Form_Element_Text('a_tag');
            $element->setLabel('Tag:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Select('a_prioridad');
            $element->setLabel('Prioridad:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    array('1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4')
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione Prioridad!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_tag', 'a_prioridad'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Text('a_orden');
            $element->setLabel('Orden:')
                ->setAttribs(array('maxlength' => $this->_maxLengthOrden,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxLengthOrden))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Checkbox('a_estado');
            $element->setLabel('Activo:')
                ->setAttribs(array('class' => 'span3'))
                ->setCheckedValue('1')
                ->setUncheckedValue('0')
                ->setChecked(true);
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_orden', 'a_estado'), 
            'grup2'
        );
        
        $element = new Zend_Form_Element_Submit('a_buscar');
        $element->setLabel("Buscar")
            ->setAttribs(array('class' => 'btn btn-primary', 'id' => 'apfbBtnSearch'));
        $this->addElement($element);
        
        $this->addStandarDecotadorV1();
        
    }

}