<?php

/**
 * @author ander
 *
 */
class Devnet_Form_Usuario
        extends Zend_Form
{

    protected function _getConfig()
    {
        return Zend_Registry::get('config');
    }

}
