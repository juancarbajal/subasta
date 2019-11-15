<?php

/**
 * Description
 *
 * @author Ander
 */
class App_Form_Element_ApKoDepartment
    extends Zend_Form_Element_Select
{
    public function init() {
        $mUbigeo = new Application_Model_Sp_Ubigeo();
        $this->addMultiOption('', '')
            ->addMultiOptions($mUbigeo->getSDepartamento());
    }
}