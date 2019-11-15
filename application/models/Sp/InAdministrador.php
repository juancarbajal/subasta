<?php
/**
 * Admin class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Model
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */

/**
 * Admin class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Model
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class Application_Model_Sp_InAdministrador
    extends App_Db_Table_Abstract
{
    protected $_name = 'IN_ADMINISTRADOR';
    protected $_primary = 'ID_ADMINISTRADOR';
    
    /**
     * Descripcion
     * 
     * @param array $idUser Variables
     * 
     * @return void
     */
    function getAdministradorForAuth($idUser)
    {
        return $this->getAdapter()->fetchRow(
            "EXECUTE IN_SP_ADMINISTRADOR_FOR_AUTH ?", $idUser
        );
    }
    
}
?>