<?php
/**
 * Form class file
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
 * Form class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Form
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class Application_Form_Usuario 
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
     * @param array $options Variables
     * 
     * @return void
     */
    public function formulario($options)
    {
        $this->setMethod('post')
            ->setAttribs(array('id'=>'apfbForm'));
        
            $element = new Zend_Form_Element_Text('f_apodo');
            $element->setLabel('Apodo:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setRequired(true)
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Hidden('f_id');
            $element->setRequired(true);
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_apodo', 'f_id'), 
            'grup1'
        );
        
            $element = new Zend_Form_Element_Text('f_email');
            $element->setLabel('E-mail:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('f_clave');
            $element->setLabel('Clave:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_email', 'f_clave'), 
            'grup2'
        );
        
            $element = new Zend_Form_Element_Text('f_nombre');
            $element->setLabel('Nombre(s):')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('f_apellidos');
            $element->setLabel('Apellidos:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_nombre', 'f_apellidos'), 
            'grup3'
        );
        
            $element = new Zend_Form_Element_Select('f_tipDoc');
            $element->setLabel('Tipo DOC:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_TipoDocumento::getSTipoDocumento()
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
        
            $element = new Zend_Form_Element_Text('f_nDoc');
            $element->setLabel('N° DOC:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un N DOC Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('f_tipDoc', 'f_nDoc'), 
            'grup4'
        );
        
            $element = new Zend_Form_Element_Text('f_tel1');
            $element->setLabel('Telf. 1:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Text('f_tel2');
            $element->setLabel('Telf. 2:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->setDescription("")
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_tel1', 'f_tel2'), 
            'grup5'
        );
        
            $element = new App_Form_Element_ApParagraph('f_hUbi');
            $element->setLabel('&nbsp;');
            $element->setValue("<span class='span3'><b>Ubigeo:</b></span>");
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Select('f_tipoUsuario');
            $element->setLabel('Usuario:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_TipoUsuario::getSTipoUsuario()
                )
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_hUbi', 'f_tipoUsuario'), 
            'grup6'
        );
        
            $element = new App_Form_Element_ApKoDepartment('f_departamento');
            $element->setLabel('Departamento:')
                ->setAttribs(array('class'=>'span3'))
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);

            $element = new Zend_Form_Element_Select('f_estadoUsuario');
            $element->setLabel('Estado:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_EstadoUsuario::getSEstadoUsuario()
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_departamento', 'f_estadoUsuario'), 
            'grup7'
        );
        
            $element =  new App_Form_Element_ApKoProvince(
                'f_provincia', array('K_ID_DEPARTAMENTO' => $options['K_ID_DEPARTAMENTO'])
            );
            $element->setLabel('Provincia:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
        
        $this->addDisplayGroup(
            array('f_provincia'), 
            'grup8'
        );
        
            $element =  new App_Form_Element_ApKoDistrict(
                'f_distrito', 
                array(
                    'K_ID_DEPARTAMENTO' => $options['K_ID_DEPARTAMENTO'],
                    'K_ID_PROVINCIA' => $options['K_ID_PROVINCIA']
                )
            );
            $element->setLabel('Distrito:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->setDescription("(*)")
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('f_distrito'), 
            'grup9'
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
            ->setAttribs(array('id'=>'apfbFormSearch', 'form'=>'usuario/form'))
            ->setAction('/usuario/buscar');
        
            $element = new Zend_Form_Element_Text('a_apodo');
            $element->setLabel('Apodo:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($element);

            $element = new Zend_Form_Element_Select('a_tipDoc');
            $element->setLabel('Tipo DOC:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_TipoDocumento::getSTipoDocumento()
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);

        $this->addDisplayGroup(
            array('a_apodo', 'a_tipDoc'), 
            'grup1',
            array('decorators' => $this->standarDecoradorGroup)
        );
        
            $element = new Zend_Form_Element_Text('a_email');
            $element->setLabel('E-mail:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se requiere un email Correcto!';
            $this->addElement($element);
        
            $element = new Zend_Form_Element_Text('a_nDoc');
            $element->setLabel('N° DOC:')
                ->setAttribs(array('maxlength' => $this->_maxDesc, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se requiere un N DOC Correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_email', 'a_nDoc'), 
            'grup2',
            array('decorators' => $this->standarDecoradorGroup)
        );
        
            $element = new Zend_Form_Element_Select('a_tipoUsuario');
            $element->setLabel('Usuario:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_TipoUsuario::getSTipoUsuario()
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
            
            $element = new Zend_Form_Element_Select('a_estadoUsuario');
            $element->setLabel('Estado:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_EstadoUsuario::getSEstadoUsuario()
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_tipoUsuario', 'a_estadoUsuario'), 
            'grup3',
            array('decorators' => $this->standarDecoradorGroup)
        );

            $element = new Zend_Form_Element_Text('a_fecIni');
            $element->setLabel('Desde:')
                ->setAttribs(array('maxlength' => $this->_maxFec, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxFec))
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se requiere un Fecha Correcta!';
            $this->addElement($element);

            $element = new Zend_Form_Element_Text('a_fecFin');
            $element->setLabel('Hasta:')
                ->setAttribs(array('maxlength' => $this->_maxFec, 'class' => 'span3'))
                ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxFec))
                )
                ->addDecorators($this->standarDecorador)
                ->errMsg = '¡Se requiere un Fecha Correcta!';
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_fecIni', 'a_fecFin'), 
            'grup4',
            array('decorators' => $this->standarDecoradorGroup)
        );
        
        $element = new Zend_Form_Element_Submit(
            'a_buscar',
            array(
                'decorators' => $this->standarDecoradorBtn,
                'label' => 'Buscar',
                'class' => 'btn btn-primary',
                'id' => 'apfbBtnSearch',
            )
        );
        $this->addElement($element);
        
        $this->setDecorators($this->standarDecoradorForm);
        
    }
}