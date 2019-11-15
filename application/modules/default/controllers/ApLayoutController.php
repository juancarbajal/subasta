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
require_once 'Modulo.php';
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
class ApLayoutController
extends Devnet_Controller_Action {

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function indexAction () 
    {
    }
        
    /**
     * Action de la busqueda de Kotear
     * @param type name desc     
     * @uses Clase::methodo()     
     */
    public function footerAction() 
    {   
        $mModulo = new Modulo();
        $this->view->apFooter = $mModulo->getModuloFooter("Footer");
//        var_dump($this->view->apFooter);
    }
}