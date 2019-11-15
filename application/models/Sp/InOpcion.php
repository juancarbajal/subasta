<?php
/**
 * InOpcion class file
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
class Application_Model_Sp_InOpcion
    extends App_Db_Table_Abstract
{
    protected $_name = 'IN_USUARIO';
    protected $_primary = 'ID_USUARIO';
    
    /**
     * Descripcion
     * 
     * @param int $K_ID_USR Variables
     * 
     * @return void
     */
    public function getOpcionForAuth($K_ID_USR)
    {
        try {
            $result = $this->getAdapter()->fetchAll(
                "EXEC IN_SP_OPCION_FOR_AUTH " . $K_ID_USR
            );
            return $result;
        } catch (Exception $exc) {
            return null;
            //echo $exc->getMessage();exit;
        }
    }
}