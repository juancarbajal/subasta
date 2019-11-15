<?php

/**
 * Description
 *
 * @author Ander
 */
class App_Form_Element_ApKoDistrict
    extends Zend_Form_Element_Select
{
    
    public function __construct($spec, $options)
    {
        parent::__construct($spec, $options);
        
        $this->addMultiOption('', '');
        if (!empty($options['K_ID_DEPARTAMENTO']) && !empty($options['K_ID_PROVINCIA'])) {
            $this->addMultiOptions(
                Application_Model_Sp_Ubigeo::getDistritoByIdDepaProv(
                    $options['K_ID_DEPARTAMENTO'], $options['K_ID_PROVINCIA']
                )
            );
        }
    }
}