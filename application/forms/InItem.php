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
class Application_Form_InItem
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
     * @param array $tipo Variables
     * 
     * @return void
     */
    public function formularioEnlaces($idModulo)
    {
        $this->setMethod('post')
            ->setAttribs(array('id'=>'apfbFormEnlace'));
        
            $element = new Zend_Form_Element_Text('f_nombre');
            $element->setLabel('Nombre:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setRequired(true)
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('f_orden');
            $element->setLabel('Orden:')
                ->setAttribs(
                    array(
                        'maxlength' => 2,
                        'class' => 'span3',
                        'placeholder' => 'Ingrese un numero de dos digitos'
                    )
                )
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => 2))
                )
                ->setRequired(true)
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_nombre', 'f_orden'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Select('f_modulo');
            $element->setLabel('Modulo:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_IkModulo::getSIkModulo($idModulo)
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione Prioridad!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('f_link');
            $element->setLabel('Link:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('f_modulo', 'f_link'), 
            'grup2'
        );
                    
            $element = new Zend_Form_Element_Checkbox('f_estado');
            $element->setLabel('Activo:')
                ->setAttribs(array('class' => 'span3'))
                ->setCheckedValue('1')
                ->setUncheckedValue('0');
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Hidden('f_id');
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('f_estado', 'f_id'), 
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
                    'id' => 'apfbFormItem',
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
    public function formularioEspecialActualizar($idModulo)
    {
        $this->setMethod('post')
            ->setAttribs(array('id'=>'apfbFormEspecial'));
        
            $element = new App_Form_Element_ApParagraph('f_hname');
            $element->setLabel('Cod Aviso Modulo:');
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_hname'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Select('f_modulo');
            $element->setLabel('Modulo:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_IkModulo::getSIkModulo($idModulo)
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione Prioridad!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Checkbox('f_estado');
            $element->setLabel('Activo:')
                ->setAttribs(array('class' => 'span3'))
                ->setCheckedValue('1')
                ->setUncheckedValue('0');
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_modulo', 'f_estado'), 
            'grup2'
        );
        
            $element = new App_Form_Element_ApParagraph('f_hdesc');
            $element->setLabel('Codigo Aviso:');
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_hdesc'), 
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
                    'id' => 'apfbFormItem',
                )
            );
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('f_cerrar', 'f_guardar'), 
            'grup10'
        );
        
        $element = new Zend_Form_Element_Hidden('f_id');
        $this->addElement($element);
        
        $hash = new Zend_Form_Element_Hash('auth_hash');
        $this->addElement($hash);
        
        $this->addStandarDecotadorV2();
    }
    
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function formularioEspecialNuevo()
    {
        $element = new Zend_Form_Element_Select('f_modulo');
        $element->setLabel('Modulo:')
            ->setAttribs(array('class'=>'span3'))
            ->addMultiOption('', '')
            ->addMultiOptions(
                Application_Model_Sp_IkModulo::getSIkModulo(2)
            )
            ->setDescription("(*)")
            ->errMsg = '¡Se seleccione Prioridad!';
        $this->addElement($element);
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function busqueda()
    {
        $this->setMethod('post')
            ->setAttribs(array('id'=>'afbFormSearch', 'form-edit'=>'/item/form'))
            ->setAction('/item/buscar');
        
            $element = new Zend_Form_Element_Select('a_tipoModulo');
            $element->setLabel('Tipo Modulo:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_InTipoModulo::getSInTipoModulo()
                )
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_tipoModulo'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Select('a_modulo');
            $element->setLabel('Modulo:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                //->addMultiOptions(
                    //InTipoModulo::getSInTipoModulo()
                //)
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('a_codigoAviso');
            $element->setLabel('Código aviso:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un Codigo Aviso Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_modulo', 'a_codigoAviso'), 
            'grup2'
        );
        
            $element = new Zend_Form_Element_Text('a_nombreItem');
            $element->setLabel('Nombre Item:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
            $element = new Zend_Form_Element_Text('a_link');
            $element->setLabel('Link:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
            $element = new Zend_Form_Element_Checkbox('a_activo');
            $element->setLabel('Activo:')
                ->setCheckedValue('1')
                ->setUncheckedValue('0')
                ->setChecked(true);
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_nombreItem', 'a_link', 'a_activo'), 
            'grup3'
        );
        
        $element = new Zend_Form_Element_Submit('a_buscar');
        $element->setLabel("Buscar")
            ->setAttribs(array('class' => 'btn btn-primary', 'id' => 'apfbBtnSearch'));
        $this->addElement($element);
        
        
        $this->addStandarDecotadorV1();
        
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function busquedaEspecial()
    {
        $this->setMethod('post')
            ->setAttribs(array('id'=>'afbFormSearch', 'form-edit'=>'/item/form'))
            ->setAction('/item/buscar');
        
            $element = new Zend_Form_Element_Text('a_codigoAviso');
            $element->setLabel('Código aviso:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un Codigo Aviso Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('a_apodo');
            $element->setLabel('Código aviso:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un Codigo Aviso Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_codigoAviso', 'a_apodo'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Select('a_tipoDestaque');
            $element->setLabel('Modulo:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_Destaque::getSDestaque()
                )
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_tipoDestaque'), 
            'grup2'
        );
        
            $element = new Zend_Form_Element_Text('a_fecIni');
            $element->setLabel('Código aviso:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un Codigo Aviso Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('a_fecFin');
            $element->setLabel('Código aviso:')
                ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->errMsg = '¡Se requiere un Codigo Aviso Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_fecIni', 'a_fecFin'), 
            'grup3'
        );
        
        $element = new Zend_Form_Element_Submit('a_buscar');
        $element->setLabel("Buscar")
            ->setAttribs(array('class' => 'btn btn-primary', 'id' => 'apfbBtnSearch'));
        $this->addElement($element);
        
        
        $this->addStandarDecotadorV1();
        
    }

}