<?php

/**
 * @author ander
 *
 */
require_once 'Ubigeo.php';

class Devnet_Form_Element_Select_District extends Zend_Form_Element_Select
{
 
    protected $_years = array();
 
    public function __construct($spec, $options = null)
    {
        parent::__construct($spec, $options);
    }
 
//    public function init()
//    {
//        
//        $this->addMultiOption('', '')
//             ->addMultiOptions(Ubigeo::getDepartamento());
//    }
 
}
