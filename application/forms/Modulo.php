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
class Application_Form_Modulo
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
                    'form'=>'modulo/form'
                )
            )
            ->setAction('/modulo/buscar');
            
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
        
            $element = new Zend_Form_Element_Checkbox('a_activo');
            $element->setLabel('Activo:')
              //->setAttribs(array('class' => 'fCheck'))
                ->esNormal = true;              
            $this->addElement($element);
            
        $this->addDisplayGroup(
            array('a_activo'), 
            'grup2'
        );
        
        $element = new Zend_Form_Element_Submit('a_buscar');
        $element->setLabel("Buscar")
            ->setAttribs(array('class' => 'btn btn-primary', 'id' => 'apfbBtnSearch'));
        $this->addElement($element);
        
        $this->addStandarDecotadorV1();
        
    }

}