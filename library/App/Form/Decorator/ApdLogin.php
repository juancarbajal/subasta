<?php 
class App_Form_Decorator_ApdLogin
    extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<label for="%s">%s</label><input id="%s" name="%s" type="text" value="%s"/>';
 
    public function render()
    {
        $element = $this->getElement();
        $name    = htmlentities($element->getFullyQualifiedName());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $value   = htmlentities($element->getValue());
        $markup  = sprintf($this->_format, $name, $label, $id, $name, $value);
        return $markup;
    }
}