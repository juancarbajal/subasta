<?php
/**
 * @author ander
 *
 */
require_once 'Ubigeo.php';
require_once 'UsuarioPortal.php';

class Usuario_formRegistro extends Devnet_Form_Usuario
{
    
    public function init()
    {
        $up = new UsuarioPortal();
                
        $e = new Zend_Form_Element_Text('apodo');
        $e->setAttrib('id', 'txt-nickname')
          ->setAttrib('class', 'input-large')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_StringLength(6, 30));
//          ->addErrorMessage("Apodo incorrecto.");
        $this->addElement($e);
               
        /*
        $validator = new Devnet_Validator();
        $apodoValidator = new Zend_Validate();
        $apodoValidator->addValidator(new Zend_Validate_StringLength(6, 12));
        $validator->add('apodo', $apodoValidator);
        */
        
        $e = new Zend_Form_Element_Password('clave');
        $e->setAttrib('id', 'txt-password')
          ->setAttrib('class', 'input-large password-strong')
          ->addFilter(new Zend_Filter_StringTrim())
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty())
          ->addValidator(new Zend_Validate_StringLength(6, 20));
//          ->addErrorMessage("Clave Incorrecta.");
        $this->addElement($e);
        
        
        $e = new Zend_Form_Element_Password('claveRep');
        $e->setAttrib('id', 'txt-repeat-password')
          ->setAttrib('class', 'input-large')
          ->addFilter(new Zend_Filter_StringTrim())
          ->setRequired(true)
          ->addValidator(new Zend_Validate_StringLength(6, 20))
          ->addValidator(new Zend_Validate_Identical('clave'));         
        $this->addElement($e);
        
        /*
        $claveValidator = new Zend_Validate();
        $claveValidator->addValidator(new Zend_Validate_StringLength(4, 12))
                               ->addvalidator(new Zend_Validate_Alnum());
        $validator->add('clave', $claveValidator);
        */
                
        $e = new Zend_Form_Element_Text('nombre');
        $e->setAttrib('id', 'txt-names')
          ->setAttrib('class', 'input-large')
          ->setRequired(true)
//          ->addvalidator(new Zend_Validate_Alpha(true))
          ;
        $this->addElement($e);
        
        /*
        $nombresValidator = new Zend_Validate();
        $nombresValidator->addvalidator(new Zend_Validate_Alpha(true));
        $validator->add('nombre', $nombresValidator);
        */
        
        $e = new Zend_Form_Element_Text('apellido');
        $e->setAttrib('id', 'txt-surnames')
          ->setAttrib('class', 'input-large')
          ->setRequired(true)
//          ->addvalidator(new Zend_Validate_Alpha(true))
          ;
        $this->addElement($e);
        
        /*
        $emailValidator = new Zend_Validate();
        $emailValidator->addValidator(new Zend_Validate_EmailAddress());
        $validator->add('email', $emailValidator);
        */
        
        $e = new Zend_Form_Element_Text('email');
        $e->setAttrib('id', 'txt-email')
          ->setAttrib('class', 'input-large')
          ->setRequired(true)
          ->addValidator(new Zend_Validate_EmailAddress())
//          ->addError('email', ($up->existeEmail($this->_request->getParam('email')))
//            ? 'El email actualmente esta siendo usado': null)
            ;
        $this->addElement($e);
                
        /*
        $validator->add('apellido', $nombresValidator, 'Apellido(s)');
        */
        
        $e = new Zend_Form_Element_Select('tipodocumento');
        $e->setAttribs(array('id'=>'slt-document'))
          ->setAttrib('class', 'input-small')
          ->addMultiOptions(array(''=>'', '05'=>'DNI', '06'=>'Pasaporte', '07'=>'RUC'))
          ->setRequired(true)
          ->addValidator(new Zend_Validate_NotEmpty());
        $this->addElement($e);
        
        /*
        $tipoDocumentoValidator = new Zend_Validate();
        $tipoDocumentoValidator->addValidator(new Zend_Validate_NotEmpty());
        $validator->add('tipodocumento', $tipoDocumentoValidator, 'Tipo de Documento');
        */
        
        $e = new Zend_Form_Element_Text('numerodocumento');
        $e->setAttrib('id', 'txt-document')
          ->setAttrib('class', 'input-medium')
          ->setRequired(true)          
          ->addValidator(new Zend_Validate_NotEmpty())
          ->addValidator(new Zend_Validate_Digits())
//          ->addError('numerodocumento', ($up->existeDoc($this->_request->getParam('numerodocumento')))
//                ? 'El número de documento actualmente esta siendo usado' : null)
            ;
        $this->addElement($e);        
                        
        /*
        $ubigeoValidator = new Zend_Validate();
        $ubigeoValidator->addValidator(new Zend_Validate_Alnum(true))
                ->addValidator(new Zend_Validate_NotEmpty());
        $validator->add('ubigeo', $ubigeoValidator, 'Ubigeo');
        */
        
        /*
        $nroDocumentoValidator = new Zend_Validate();            
        $nroDocumentoValidator->addValidator(new Zend_Validate_Digits());
        $validator->add('numerodocumento', $nroDocumentoValidator, 'Número de Documento');
        */
        
        $e = new Zend_Form_Element_Text('telefono');
        $e->setAttrib('id', 'txt-phone-1')
          ->setAttrib('class', 'input-large')
          ->setRequired(true)          
          ->addValidator(new Zend_Validate_NotEmpty())
          ->addValidator(new Zend_Validate_Alnum(true));
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('telefono2');
        $e->setAttrib('id', 'txt-phone-2')  
          ->setAttrib('class', 'input-large')
//          ->addValidator(new Zend_Validate_Alnum(true))
        ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Select('ubication_departement');
        $e->setLabel('Departamento')
          ->setAttribs(array('class' => 'input-large'))
          ->addMultiOption('', 'Seleccione una opción')
          ->addMultiOptions(
                Ubigeo::getDepartamento()
            )
          ->setRequired(true);
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Select('ubication_province');
        $e->setLabel('Provincia')
          ->setAttribs(array('class' => 'input-large'))
          ->addMultiOption('', 'Seleccione una opción')
//          ->addValidator(new Zend_Validate_NotEmpty())
          ->setRequired(true)
          ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Select('ubication_district');
        $e->setLabel('Distrito')
          ->setAttribs(array('class' => 'input-large'))
          ->addMultiOption('', 'Selecciones una opción')
//          ->addValidator(new Zend_Validate_NotEmpty())
          ->setRequired(true)
          ;
        $this->addElement($e);
        
//        $e = new Zend_Form_Element_Select('ubigeo');
//        $e->setAttrib('id', 'txt-ubication')
//          ->setRequired(true)
//          ->addvalidator(new Zend_Validate_Alpha(true))
//          ->addValidator(new Zend_Validate_NotEmpty());
//        $this->addElement($e);
        /*
        $telefonoValidator = new Zend_Validate();
        $telefonoValidator->addValidator(new Zend_Validate_Alnum(true));
        $validator->add('telefono', $telefonoValidator, 'Teléfono');         
         */
        
        $e = new Zend_Form_Element_Checkbox('terms');
        $e->setRequired(true);
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Checkbox('emailing');
        $this->addElement($e);
        
        
//        $e = new Zend_Form_Element_Captcha('palabraSeguridad');
////        $e->setAttrib('id', 'captcha')                    
////          ->addError('captcha', ($this->_request->getParam('palabraSeguridad') != $this->session->captchaWord)
////                    ? 'La palabra de seguridad no corresponde': null, 'Palabra de seguridad')
////            ;
//         
//        $this->addElement($e);
        
//        $captcha = new Zend_Form_Element_Captcha(  
//            'captcha', // This is the name of the input field  
//            array('label' => 'Ingresa la palabra que lees en el recuadro:',  
//                'captcha' => array( // Here comes the magic...  
//                    // First the type...  
//                    'captcha' => 'Devnet_Captcha',  
//                    // Length of the word...  
//                    'wordLen' => 8,  
//                    // Captcha timeout, 5 mins  
//                    'timeout' => 300,  
//                    // What font to use...  
//                    'font' => '/usr/share/fonts/truetype/freefont/FreeMonoBold.ttf',  
//                    // Where to put the image  
//                    'imgDir' => '/var/www/project/public/captcha/',  
//                    // URL to the images  
//                    // This was bogus, here's how it should be... Sorry again :S  
//                    'imgUrl' => 'http://project.com/captcha/',  
//                )
//            )
//        ); 
//        
////        $captcha->setCaptcha(new Devnet_Captcha($this->_getConfig()->captcha->toArray()));
//        
//        /*
//         * $options['wordLen']=(isset($options['wordLen']))?$options['wordLen']:8;
//         * $options['timeout']=(isset($options['timeout']))?$options['timeout']:300;		
//         * $options['font']=(isset($options['font']))?$options['font']:'/usr/share/fonts/truetype/freefont/FreeMonoBold.ttf';
//         * $options['imgDir']=(isset($options['imgDir']))?$options['imgDir']:APPLICATION_PATH.'/../public/img/captchas';
//        
//        $options['imgUrl']=(isset($options['imgUrl']))?$options['imgUrl']:$options['baseUrl'].'/img/captchas'; 
//        $options['name']=(isset($options['name']))?$options['name']:'captcha';
//		$options['dotNoiseLevel']=(isset($options['dotNoiseLevel']))?$options['dotNoiseLevel']:50;
//        $options['lineNoiseLevel']=(isset($options['lineNoiseLevel']))?$options['lineNoiseLevel']:4;
//        $options['width']=(isset($options['width']))?$options['width']:290;
//        $options['height']=(isset($options['height']))?$options['height']:80;
//         */
//        $this->addElement($captcha);
        
//        $captcha = new Zend_Form_Element_Captcha(  
//                'captcha', // This is the name of the input field  
//                array('label' => 'Write the chars to the field',  
//                'captcha' => array( // Here comes the magic...  
//                // First the type...  
//                'captcha' => 'Image'
////                // Length of the word...  
////                'wordLen' => 6,  
////                // Captcha timeout, 5 mins  
////                'timeout' => 300,  
////                // What font to use...  
////                'font' => '/path/to/font/FontName.ttf',  
////                // Where to put the image  
////                'imgDir' => '/var/www/project/public/captcha/',  
////                // URL to the images  
////                // This was bogus, here's how it should be... Sorry again :S  
////                'imgUrl' => 'http://project.com/captcha/',  
//                )
//                    )
//                );  
//        $captcha->setCaptcha(new Devnet_Captcha($this->_getConfig()->captcha->toArray()));
//        $this->addElement($captcha);
        
        $captcha = new Devnet_Captcha($this->_getConfig()->captcha->toArray());
        //var_dump($captcha);exit;
        $captchaElement = new Zend_Form_Element_Captcha('captcha', array('captcha' => $captcha));
        $captchaElement->setLabel('')
                       ->setAttrib('class', 'captcha_class input-large')
                       ->setRequired(true);
        $this->addElement($captchaElement);
        
        //Revision de Base de Datos               
        
        
        
        
        /*
        $e->addError('claveRep', ($this->_request->getParam('clave') != $this->_request->getParam('claveRep'))
                                                                     ? 'La confirmación de la contraseña debe ser igual que la contraseña. '
                                                                     : null, 'Confirmación de contraseña');
        
        $e->addError('condiciones', (! $this->_request->getParam('condiciones'))
                                                                            ? 'No se han aceptado las condiciones del sitio'
                                                                            : null, 'Condiciones');
        */

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
        if(empty($dataProvince)) return $values;
        $e = $this->getElement('ubication_province');
        $e->addMultiOptions($dataProvince);
        
        $dataDistrict = Ubigeo::getDistritoJosonValidate();
        if(empty($dataDistrict)) return $values;
        $e = $this->getElement('ubication_district');
        $e->addMultiOptions($dataDistrict);
        
        $e = $this->getElement('apodo');
        if($mUsuarioPortal->existeApodo($values['apodo'])) $e->setErrors(array('El apodo actualmente esta siendo usado'));
        
        $e = $this->getElement('email');
        if($mUsuarioPortal->existeEmail($values['email'])) $e->addError('El email actualmente esta siendo usado');
        
        $e = $this->getElement('numerodocumento');
        if($mUsuarioPortal->existeDoc($values['numerodocumento'])) $e->addError('El número de documento actualmente esta siendo usado');
        
        return $values;
    }
//    
//    public function populate(array $values)
//    {
//        $values = $this->_modifyElements($values);   
//        return parent::populate($values);
//    }

}