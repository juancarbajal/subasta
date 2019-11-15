<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pagos
 *
 * @author nazart
 */
class Base_Migracion 
    extends Devnet_Db_Table
{
    protected $_name = 'ttrabajo.PARAMETRO_FOTO';
    protected $_primary = 'ID_FOTO';

    public function __construct($options=array())
    {
        $options['db']=$this->_db=Zend_Registry::get('KotearMigracion');
        parent::__construct($options);
    }
    //put your code here
}
