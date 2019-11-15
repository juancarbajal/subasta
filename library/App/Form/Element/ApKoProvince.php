<?php

/**
 * Description
 *
 * @author Ander
 */
class App_Form_Element_ApKoProvince
    extends Zend_Form_Element_Select
{
    
    public function __construct($spec, $options)
    {
        parent::__construct($spec, $options);
        
        $this->addMultiOption('', '');
        if (!empty($options['K_ID_DEPARTAMENTO'])) {
            $this->addMultiOptions(
                Application_Model_Sp_Ubigeo::getProvinciaByIdDepa($options['K_ID_DEPARTAMENTO'])
            );
        }
    }
}