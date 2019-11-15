<?php

class App_Db_Table extends Zend_Db_Table
{
    /**
     *
     * @var Zend_Cache
     */
    function __construct($config = array(), $definition = null)
    { 
        parent::__construct($config, $definition);
    }
}