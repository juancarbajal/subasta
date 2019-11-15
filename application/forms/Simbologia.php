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
class Application_Form_Simbologia
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
                    'form'=>'simbologia/form'
                )
            )
            ->setAction('/simbologia/buscar');
            
            $e = new Zend_Form_Element_Select('a_tipoSimbologia');
            $e->setLabel('Tipo Simbología:')
                ->setAttribs(array('class'=>'span3'))
                ->addMultiOption('', '')
                ->addMultiOptions(
                    Application_Model_Sp_TipoSimbologia::getSTipoSimbologia()
                )
                ->errMsg = '¡Se seleccione un tipo Doc correcto!';
            $this->addElement($e);
            
        $this->addDisplayGroup(
                array('a_tipoSimbologia'), 
                'grup1'
        );
            
            $e = new Zend_Form_Element_Text('a_descripcion');
            $e->setLabel('Descripcion:')
              ->setAttribs(array('maxlength' => $this->_maxDesc,'class' => 'span3'))
              ->addValidator(
                    new Zend_Validate_StringLength(array('max' => $this->_maxDesc))
                )
              ->errMsg = '¡Se requiere un apodo Correcto!';
            $this->addElement($e);
            
        $this->addDisplayGroup(
                array('a_descripcion'), 
                'grup2'
        );
        
        $element = new Zend_Form_Element_Submit('a_buscar');
        $element->setLabel("Buscar")
            ->setAttribs(array('class' => 'btn btn-primary', 'id' => 'apfbBtnSearch'));
        $this->addElement($element);
        
        $this->addStandarDecotadorV1();
        
    }

}