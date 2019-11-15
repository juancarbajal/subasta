<?php
/**
 * @author ander
 *
 */
require_once 'Ubigeo.php';

class Usuario_formPublicacion extends Devnet_Form_Usuario
{
    protected $_titleMax = '100';
    protected $_priceMax = '11';
    protected $_photoSize = '30';
    
    protected $_textImpressMax = '14';
    public $_letterFreeMax = '200';
    
    public function init()
    {
        
    }
    
    /** Paso4 **/
    public function addConfirmacion()
    {
        $e = new Zend_Form_Element_Radio('voucher');
        $e->setLabel('')
          ->addMultiOptions(array('1' => ' Boleta','2' => ' Factura'))
//          ->addMultiOptions(array('1' => ' Boleta'))
          ->setValue('1')
          ->setSeparator('')
          ->setAttrib('label_class', 'radio inline')
          ->setRequired(true)
          ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Radio('means_of_payment');
        $e->setLabel('')
//          ->addMultiOptions(array('1' => ' Boleta','2' => ' Factura'))
          ->addMultiOptions(array('1' => ' Pago Efectivo', '2' => ' VISA', '3' => ' MasterCard'))
          ->setValue('1')
          ->setSeparator('')
          ->setAttrib('label_class', 'radio inline')
          ->setRequired(true)
          ;
        $this->addElement($e);
    }
    
    /** Paso3 **/
    public function addRegistroDatos($isData=false)
    {

        $float = new Zend_Validate_Float(new Zend_Locale('es_PE'));
        
        $e = new Zend_Form_Element_Text('announcement_title');
        $e->setLabel('Titulo del Aviso:')
          ->setAttribs(array('maxlength' => $this->_titleMax, 'class' => 'input-xxlarge'))
          ->addValidator(
              new Zend_Validate_StringLength(array('max' => $this->_titleMax))
            )
          ->setRequired(true)
          ->errMsg = '¡Se requiere!';
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Radio('product_type');
        $e->setLabel('El producto es')
          ->addMultiOptions(array('1' => ' Nuevo','2' => ' Usado'))
          ->setValue('1')
          ->setSeparator('')
          ->setAttrib('label_class', 'radio inline')
          ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('price');
        $e->setLabel('Precio')
          ->setAttribs(array('maxlength' => $this->_priceMax, 'class' => 'inline input-small'))
          ->addValidator(
              new Zend_Validate_StringLength(array('max' => $this->_priceMax))
            )
          ->addValidator($float)
          ->setRequired(true)
          ->errMsg = '¡Se requiere!';
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Radio('currency');
        $e->setLabel('')
          ->addMultiOptions(array('1' => ' Soles','2' => ' Dolares'))
          ->setValue('1')
          ->setSeparator('')
          ->setAttrib('label_class', 'radio inline')
          ;
        $this->addElement($e);
                
        $e = new Zend_Form_Element_Select('ubication_departement');
        $e->setLabel('Departamento')
          ->setAttribs(array('class' => 'span2'))
          ->addMultiOption('', '')
          ->addMultiOptions(
                Ubigeo::getDepartamento()
            )
          ->setRequired(true);
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Select('ubication_province');
        $e->setLabel('Provincia')
          ->setAttribs(array('class' => 'span2'))
          ->addMultiOption('', '')
//          ->addValidator(new Zend_Validate_NotEmpty())
          ->setRequired(true)
          ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Select('ubication_district');
        $e->setLabel('Distrito')
          ->setAttribs(array('class' => 'span2'))
          ->addMultiOption('', '')
//          ->addValidator(new Zend_Validate_NotEmpty())
          ->setRequired(true)
          ;
        $this->addElement($e);
        
        $e = new Zend_Form_Element_MultiCheckbox('payment_method');
        $e->setLabel('Medios de Pago')
          ->addMultiOption('1', 'Efectivo')
          ->addMultiOption('2', 'Cheque Certificado')
          ->addMultiOption('3', 'Desposito / Transferencia Bancaria')
          ->addMultiOption('4', 'Giro Postal')
          ->addMultiOption('5', 'Contra Reembolso')
          ->addMultiOption('6', 'Tarjeta de Crédito')
          ->setValue(array('1'))
          ->setRequired(true);
        $this->addElement($e);
        
        if (!$isData) {
            $e = new Zend_Form_Element_File('photo_product');
            $e->setLabel('Carga de imágenes')
    //            ->setDestination(APPLICATION_PATH.'/tmp/data')
    //            ->addValidator('Count', false, 1) // ensure only 1 file
    //            ->addValidator('Size', false, 307200) // limit to 300k
    //            ->addValidator('Extension', false, 'jpg') // only JPEG
                ->setAttribs(array('size' => $this->_photoSize));
            $this->addElement($e);
        }
            
        
        //IMGS hidden
        $e = new Zend_Form_Element_Hidden('ids_hidden_ad');
        $e->setRequired(true);
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Textarea('informacion_adicional');
        $e->setLabel('Información adicional')
          ->setAttribs(array('cols' => '20', 'rows'=>'8'));
        $this->addElement($e);
        
    }
    
    /* Paso 3 -> impreso Gold Platinium */
    public function addPrintedText()
    {
        $e = new Zend_Form_Element_Text('announcement_title_impress');
        $e->setLabel('Título del aviso')
            ->setAttribs(array('maxlength' => $this->_textImpressMax, 'class' => 'span7'))
            ->addValidator(
                new Zend_Validate_StringLength(array('max' => $this->_textImpressMax))
              )
            ->setRequired(true)
            ->errMsg = '¡Se requiere!';
        $this->addElement($e);
        
        $e = new Zend_Form_Element_Text('letter_free');
        $e->setLabel('Añade palabras libres a tu impreso')
            ->setAttribs(array( //'maxlength' => $this->_letterFreeMax, 
                'class' => 'span7'))
//            ->addValidator(
//                new Zend_Validate_StringLength(array('max' => $this->_letterFreeMax))
//            )
            ->setRequired(true)
            ->errMsg = '¡Se requiere!';
        $this->addElement($e);
        
    }
    
    //->Algunos campos no se podra habilitar paso 3
    public function disabledEditar ()
    {
        $this->getElement('announcement_title')->setAttrib("disable", true);
//        $this->getElement('product_type')->setAttrib("disable", true);
//        $this->getElement('price')->setAttrib("disable", true);
//        $this->getElement('currency')->setAttrib("disable", true);
//        $this->getElement('ubication_departement')->setAttrib("disable", true);
//        $this->getElement('ubication_province')->setAttrib("disable", true);
//        $this->getElement('ubication_district')->setAttrib("disable", true);
        
    }
    
    public function setProvinciaJosonValidate ($input='')
    {
        $e = $this->getElement('ubication_province');
        if(empty($input)) $e->addMultiOptions(Ubigeo::getProvinciaJosonValidate());
        elseif (!empty($input['K_ID_DEPARTAMENTO'])) 
            $e->addMultiOptions(Ubigeo::getProvinciaByIdDepa($input['K_ID_DEPARTAMENTO']));
        else $e->addMultiOptions($input);
    }
    
    public function setDistritoJosonValidate ($input='')
    {
        $e = $this->getElement('ubication_district');
        if(empty($input)) $e->addMultiOptions(Ubigeo::getDistritoJosonValidate());
        elseif (!empty($input['K_ID_DEPARTAMENTO'])) 
            $e->addMultiOptions(Ubigeo::getDistritoByIdDepaProv($input['K_ID_DEPARTAMENTO'], $input['K_ID_PROVINCIA']));
        else $e->addMultiOptions($input);
    }
    
    // Paso 2
    public function addCategoriasHidden ($array = array())
    {
        $this->addElement('hidden', 'categoriaId1', array(
//            'validators' => array(
//                'alnum',
//                array('regex', false, '/^[a-z]/i')
//            ),
            'required' => true,
            'value' => $array['categoriaId1']
//            'filters'  => array('StringToLower'),
        ));        
        $this->addElement('hidden', 'categoriaId2', 
            array('value' => $array['categoriaId2'], 'required' => true));
        $this->addElement('hidden', 'categoriaId3', 
            array('value' => $array['categoriaId3']));
        $this->addElement('hidden', 'categoriaId4', 
            array('value' => $array['categoriaId4']));
        
        $this->addElement('hidden', 'categoriaText1', 
            //array('value' => $array['categoriaText1'], 'required' => true));
            array('value' => $array['categoriaText1']));
        $this->addElement('hidden', 'categoriaText2', 
            //array('value' => $array['categoriaText2'], 'required' => true));
            array('value' => $array['categoriaText2']));
        $this->addElement('hidden', 'categoriaText3', 
            array('value' => $array['categoriaText3']));
        $this->addElement('hidden', 'categoriaText4', 
            array('value' => $array['categoriaText4']));
        
    }
    
    // Paso 1
    public function addDestaque ($value)
    {
        $e = new Zend_Form_Element_Hidden('destaqueId');
        $e->setRequired(true)
          ->errMsg = '¡Se requiere!';
        if(!empty($value)) $e->setValue($value);
        $this->addElement($e);
        //$this->addElement('hidden', 'destaqueId', array('value' => $value, 'required' => true));
    }
    
    public function addIdAviso ($value=null)
    {
        $e = new Zend_Form_Element_Hidden('idAviso');
        $e->setRequired(true)
          ->errMsg = '¡Se requiere!';
        if(!empty($value)) $e->setValue($value);
        $this->addElement($e);
    }
    
    public function addToken($id=null)
    {
        $e = new Zend_Form_Element_Hash('atoken'.$id);
        $e->setTimeout($this->_getConfig()->form->hash->timeout);
        $this->addElement($e);
    }

}