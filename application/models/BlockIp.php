<?php

require_once ('Base/BlockIp.php');
require_once 'AvisoInfo.php';

/**
 * Description of BlockIp
 *
 * @author ander
 */
class BlockIp extends Base_BlockIp
{
    public function validar($array)
    {
        $select  = $this->_db->select()
                ->from($this->_name);
        $select->reset('columns')->columns(new Zend_Db_Expr('COUNT(1)'));
        foreach ($array as $key => $val) {
            $select->where("$key = ?", $val);
        }
        return (int)$this->getAdapter()->fetchOne($select);
    }
    
    public function eliminarIps()
    {
        $this->_db->delete($this->_name, new Zend_Db_Expr("DATEADD(MI, -60,GETDATE()) > HORA"));
    }    
}