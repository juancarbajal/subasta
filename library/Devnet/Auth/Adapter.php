<?php
  /**
   * Descripción Corta
   * 
   * Descripción Larga
   * 
   * @copyright  Leer archivo COPYRIGHT
   * @license    Leer el archivo LICENSE
   * @version    1.0
   * @since      Archivo disponible desde su version 1.0
   */
  /**
   * Descripción Corta
   * Descripción Larga
   * @category   
   * @package    
   * @subpackage    
   * @copyright  Leer archivo COPYRIGHT 
   * @license    Leer archivo LICENSE
   * @version    Release: @package_version@
   * @link
   */
class Devnet_Auth_Adapter 
    extends Zend_Auth_Adapter_DbTable 
{
    protected $_email;
    protected $_emailColumn;
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function __construct(Zend_Db_Adapter_Abstract $zendDb, $tableName = null, $identityColumn = null,
                                $emailColumn = null, $credentialColumn = null, $credentialTreatment = null)
    {
        parent::__construct($zendDb, $tableName, $identityColumn, $credentialColumn, $credentialColumn);
        if (null !== $emailColumn){
            $this->setEmailColumn($emailColumn);
        }
    }
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function setEmail ($email) 
    { 
        $this->_email=$email;
    } //end function
    /**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function setEmailColumn ($emailColumn) 
    { 
        $this->_emailColumn=$emailColumn;
    } //end function
	/**
     * 
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    protected function _authenticateCreateSelect()
    { 
        if (empty($this->_credentialTreatment) || (strpos($this->_credentialTreatment, '?') === false)) {
            $this->_credentialTreatment = '?';
        }
        $credentialExpression = new Zend_Db_Expr(
                                                 '(CASE WHEN ' .
                                                 $this->_zendDb->quoteInto(
                                                                           $this->_zendDb->quoteIdentifier($this->_credentialColumn, true)
                                                                           . ' = ' . $this->_credentialTreatment, $this->_credential
                                                                           )
                                                 . ' THEN 1 ELSE 0 END) AS '
                                                 . $this->_zendDb->quoteIdentifier(
                                                                                   $this->_zendDb->foldCase('zend_auth_credential_match')
                                                                                   )
                                                 );
        $dbSelect = clone $this->getDbSelect();
        $dbSelect->from($this->_tableName, array('*', $credentialExpression))
            ->where('('.$this->_zendDb->quoteIdentifier($this->_identityColumn, true) 
                    . ' = ? ) OR (' 
                    . $this->_zendDb->quoteIdentifier($this->_emailColumn, true) 
                    . '= ? )', 
                    $this->_identity,$this->_email);
        return $dbSelect;
    } //end function    
}