<?php

class App_View_Helper_ReduceString extends Zend_View_Helper_HtmlElement
{

    public function ReduceString($cad, $max, $separate)
    {
        return (strlen($cad) > $max ? substr($cad, 0, $max) . $separate : $cad);
    }

}
