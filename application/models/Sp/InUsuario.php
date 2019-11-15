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
class Application_Model_Sp_InUsuario 
    extends App_Db_Table_Abstract
{
    protected $_name = 'IN_USUARIO';
    protected $_primary = 'ID_USUARIO';
    
    /**
     * Descripcion
     * 
     * @param string $apodo Variables
     * @param string $clave Variables
     * @param string $tipo  Variables
     * 
     * @return void
     */
    public function validarUsuario ($apodo, $clave, $tipo)
    {
        $result = $this->getAdapter()->fetchRow(
            "EXECUTE IN_SP_USUARIO_LOGEO ?, ?", array($apodo, $tipo)
        );
        $helperClave = new App_Controller_Action_Helper_Clave();
        if (!$helperClave->checkClave($clave, $result->K_CLAVE)) {
            $result->K_ERROR = 2;
            $result->K_MSG = 'Email o clave incorrecta..';
        }
        return $result;
    }
    
}
?>