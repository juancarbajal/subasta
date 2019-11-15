<?php
/**
 * @author ander
 *
 */

class Usuario_formUsuarioFacturacion extends Devnet_Form_Usuario
{
    private $_maxlengthRuc = '11';
    private $_maxlengthDir = '40';
    private $_maxlengthRazSoc = '40';
    
    public function init()
    {
//        $e = new Zend_Form_Element_Select('document_type');
//        $e->setLabel('Tipo Documento')
//          ->setAttribs(array('class' => 'input-large'))
//          ->addMultiOptions(
//                array('1'=>'RUC', '2'=>'DNI')
//            )
//          ->setRequired(true);
//        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('document_number');
        $e->setLabel('Número RUC')
          ->setAttribs(array('class'=>'input-xlarge', 'maxlength'=>$this->_maxlengthRuc))
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty())
          ->addvalidator(new Zend_Validate_Int())
          ->addValidator(new Zend_Validate_StringLength(
                array('min'=>$this->_maxlengthRuc, 'max'=>$this->_maxlengthRuc)
            ))
            ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('customer_name');
        $e->setLabel('Razón Social')
          ->setAttribs(array('class'=>'input-xxlarge', 'maxlength'=>$this->_maxlengthRazSoc))
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty())
//          ->addvalidator(new Zend_Validate_Alpha(true));
          ->addValidator(new Zend_Validate_StringLength(
                array('max'=>$this->_maxlengthRazSoc)
            ))
            ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('address');
        $e->setLabel('Dirección')
          ->setAttribs(array('class'=>'input-xxlarge', 'maxlength'=>$this->_maxlengthDir))
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty())
//          ->addvalidator(new Zend_Validate_Alpha(true));
          ->addValidator(new Zend_Validate_StringLength(
                array('max'=>$this->_maxlengthDir)
            ))
            ;
        $this->addElement($e);
                
//        $e = new Devnet_Form_Element_Select_Department('ubication_departement');
////        $e = new Zend_Form_Element_Select('ubication_departement');
//        $e->setLabel('Departamento')
//          ->setAttribs(array('class' => 'span2'))
////          ->addMultiOption('', '')
////          ->addMultiOptions(
////                Ubigeo::getDepartamento()
////            )
//          ->setRequired(true);
//        $this->addElement($e);
//        
//        $e = new Devnet_Form_Element_Select_Province('ubication_province');
//        //$e = new Zend_Form_Element_Select('ubication_province');
//        $e->setLabel('Provincia')
//          ->setAttribs(array('class' => 'span2'))
//          ->addMultiOption('', '')
////          ->addValidator(new Zend_Validate_NotEmpty())
//          ->setRequired(true)
//          ;
//        $this->addElement($e);
//        
//        $e = new Devnet_Form_Element_Select_District('ubication_district');
//        //$e = new Zend_Form_Element_Select('ubication_district');
//        $e->setLabel('Distrito')
//          ->setAttribs(array('class' => 'span2'))
//          ->addMultiOption('', '')
////          ->addValidator(new Zend_Validate_NotEmpty())
//          ->setRequired(true)
//          ;
//        $this->addElement($e);
//        
////        $e = new Zend_Form_Element_Checkbox('terms_conditions');
////        $e->setLabel('Acepto los términos y condiciones')
////          ->setAttribs(array('class' => 'span2'))
////          ->setRequired(true)
////          ;
////        $this->addElement($e);
        
    }
    
    public function disabledAll()
    {        
        $elementObjs = $this->getElements();
        foreach ($elementObjs as $element) {
            $element->setAttrib('disabled', true);
        }
    }
    
    public function addButton()
    {        
        $e = new Zend_Form_Element_Button('save');
        $e->setLabel('Guardar')
          ;
        $this->addElement($e);
    }

}