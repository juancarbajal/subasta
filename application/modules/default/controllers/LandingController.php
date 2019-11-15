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
class LandingController
    extends Devnet_Controller_Action
{
    /**
     * Realiza la busqueda por parametros enviados
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function indexAction()
    {
        //echo '200px';
        //exit;
         
         $this->_helper->layout->disableLayout();
         
         //$this->_helper->viewRenderer->setNoRender();
    }
}
