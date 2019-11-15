<?php
/**
 * Description of Error
 *
 */
class Devnet_Errorcron extends Zend_Exception
{
    public function __construct($msg = '', $code = 0, Exception $previous = null) {
        $this->_save();
        parent::__construct($msg, $code, $previous);
        
    }
    
    private function _save()
    {
        
        Zend_Registry::get('logCron')->write(array(
           'mensaje' => json_encode(array(
                                            'codigo' => $this->getCode(), 
                                            'archivo' => $this->getFile(),
                                            'mensaje' => $this->getMessage(),
                                            'linea' => $this->getLine()
                                    )),
            'trace' => $this->getTraceAsString() . $this->getPrevious()
        ));
    }
}