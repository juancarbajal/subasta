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
class Base_Pagos 
    extends Devnet_Db_Table
{
    protected $_name = 'Cliente';
    protected $_primary = 'IdCliente';
    
    public function __construct($options=array())
    {
        $options['db']=$this->_db=Zend_Registry::get('kotearPagos');
        parent::__construct($options);
    }
    //put your code here
}
