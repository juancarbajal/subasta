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
class Pagos_EnvioController extends Devnet_Controller_Action
{
    /**
     * @var type
     */
    public $dbKotear;
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function init()
    {

      $frontController = Zend_Controller_Front::getInstance();
      $dba = $frontController->getParam('bootstrap')->getOption('kotearpagos');
      $this->dbKotear = Zend_Db::factory($dba['dbkp']['adapter'], $dba['dbkp']['params']);
    }

  /**
   * Descripcion
   * @param type name desc
   * @uses Clase::metodo()
   * @return type desc
   */
  public function testAction()
  {
     $this->dbKotear->fetchAll('select * from Cargo');
  }
}