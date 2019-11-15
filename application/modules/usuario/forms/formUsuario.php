<?php
/**
 * @author ander
 *
 */
require_once 'Ubigeo.php';
require_once 'UsuarioPortal.php';

class Usuario_formUsuario extends Devnet_Form_Usuario
{
    
    public function init()
    {
//        $up = new UsuarioPortal();
                
        $element = new Zend_Form_Element_Text('nombre');
        $element->setAttribs(array('id'=>'names', 'class'=>'input-xlarge'))
          ->setRequired(true)
          //->addValidator(new Zend_Validate_StringLength(6, 12))
          ->addValidator(new Zend_Validate_NotEmpty())
//          ->addvalidator(new Zend_Validate_Alpha(true))
        ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('apellido');
        $element->setAttribs(array('id'=>'surnames', 'class'=>'input-xlarge'))
          ->setRequired(true)
          //->addValidator(new Zend_Validate_StringLength(6, 12))
          ->addValidator(new Zend_Validate_NotEmpty())
//          ->addvalidator(new Zend_Validate_Alpha(true))
        ;
        $this->addElement($element);
        
        $element = new Devnet_Form_Element_Select_Department('ubication_departement');
//        $e = new Zend_Form_Element_Select('ubication_departement');
        $element->setLabel('Departamento')
          ->setAttribs(array('class' => 'span2'))
//          ->addMultiOption('', '')
//          ->addMultiOptions(
//                Ubigeo::getDepartamento()
//            )
          ->setRequired(true);
        $this->addElement($element);
        
        $element = new Devnet_Form_Element_Select_Province('ubication_province');
        //$e = new Zend_Form_Element_Select('ubication_province');
        $element->setLabel('Provincia')
          ->setAttribs(array('class' => 'span2'))
          ->addMultiOption('', '')
//          ->addValidator(new Zend_Validate_NotEmpty())
          ->setRequired(true)
          ;
        $this->addElement($element);
        
        $element = new Devnet_Form_Element_Select_District('ubication_district');
        //$e = new Zend_Form_Element_Select('ubication_district');
        $element->setLabel('Distrito')
          ->setAttribs(array('class' => 'span2'))
          ->addMultiOption('', '')
//          ->addValidator(new Zend_Validate_NotEmpty())
          ->setRequired(true)
          ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('telefono');
        $element->setAttribs(array('id'=>'phone-one', 'class'=>'input-xlarge'))
          ->setRequired(true)
          //->addValidator(new Zend_Validate_StringLength(6, 12))
          ->addValidator(new Zend_Validate_NotEmpty())
          ->addvalidator(new Zend_Validate_Alnum());
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Text('telefono2');
        $element->setAttribs(array('id'=>'phone-two', 'class'=>'input-xlarge'))
//          ->setRequired(true)
//          //->addValidator(new Zend_Validate_StringLength(6, 12))
//          ->addValidator(new Zend_Validate_NotEmpty())
//          ->addvalidator(new Zend_Validate_Alnum())
          ;
        $this->addElement($element);
        
        $element = new Zend_Form_Element_Checkbox("suscripcionNews");
        $element->setAttribs(array('class'=>'checkbox'));
        $this->addElement($element);

    }
    
    public function isValid($values)
    {
        $values = $this->_modifyElements($values);
        return parent::isValid($values);
    }
    
    protected function _modifyElements($values)
    {
        $mUsuarioPortal = new UsuarioPortal();
        
        $dataProvince = Ubigeo::getProvinciaJosonValidate();
        if (empty($dataProvince)) return $values;
        $element = $this->getElement('ubication_province');
        $element->addMultiOptions($dataProvince);
        
        $dataDistrict = Ubigeo::getDistritoJosonValidate();
        if (empty($dataDistrict)) return $values;
        $element = $this->getElement('ubication_district');
        $element->addMultiOptions($dataDistrict);
        
        return $values;
    }
    
    public function setProvinciaJosonValidate ($input='')
    {
        $element = $this->getElement('ubication_province');
        if (empty($input)) $element->addMultiOptions(Ubigeo::getProvinciaJosonValidate());
        elseif (!empty($input['K_ID_DEPARTAMENTO'])) 
            $element->addMultiOptions(Ubigeo::getProvinciaByIdDepa($input['K_ID_DEPARTAMENTO']));
        else $element->addMultiOptions($input);
    }
    
    public function setDistritoJosonValidate ($input='')
    {
        $element = $this->getElement('ubication_district');
        if (empty($input)) $element->addMultiOptions(Ubigeo::getDistritoJosonValidate());
        elseif (!empty($input['K_ID_DEPARTAMENTO'])) 
            $element->addMultiOptions(Ubigeo::getDistritoByIdDepaProv($input['K_ID_DEPARTAMENTO'], $input['K_ID_PROVINCIA']));
        else $element->addMultiOptions($input);
    }

}