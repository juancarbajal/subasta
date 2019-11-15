<?php

/**
 * Description
 *
 * @author Ander
 */
class App_Form_Element_ApDate 
    extends Zend_Form_Element_Xhtml
{
    public $helper = 'apFormElementDate';
    
    public function isValid ($value, $context = null)
    {
        if (is_array($value)) {
            $value = $value['year'] . '-' .
                $value['month'] . '-' .
                $value['day'];
            
            if ($value == '--') {
                $value = null;
            }
        }
        
        return parent::isValid($value, $context);
    }

    public function getValue()
    {
        if(is_array($this->_value)) {
            $value = $this->_value['year'] . '-' .
                $this->_value['month'] . '-' .
                $this->_value['day'];
            
            if($value == '--') {
                $value = null;
            }
            
            $this->setValue($value);
        }
        return parent::getValue();
    }
    
}