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
class Usuario_CuentaController
    extends  Devnet_Controller_Action
{

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function init ()
    {
        parent::init();
        if (! isset($this->identity)) {
            $this->session->requiredLoginUrl = $_SERVER['REQUEST_URI'];
            $this->_redirect("/usuario/acceso");
        }
    }

   /**
     * Visualiza la informacion en el panel cuenta de usuario
     * @param pe name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function indexAction()
    {
        $this->_redirect("/usuario/venta/activas/order/2");
//        $this->view->headTitle('Mi cuenta | Kotear.pe');
//        require_once 'UsuarioPortal.php' ;
//        $usuario= new UsuarioPortal();
//        require_once 'Aviso.php';
//        $aviso= new Aviso();
//        $this->view->preguntasSinContestar=$usuario->preguntasSinContestar($this->identity->ID_USR);
//        $this->view->numeroCalificacionesPendientes=$usuario->numeroCalificacionesPendientes(
//              $this->identity->ID_USR
//        );
//        $this->view->avisosPorCaducar=$usuario->avisosPorCaducar($this->identity->ID_USR, 3);
//        $this->view->avisosSeguimiento=$aviso->getAvisoSeguimiento($this->identity->ID_USR);
//        $this->view->saldo=$usuario->pendienteDePago($this->identity->ID_USR);
//        $this->view->gratuito = $aviso->getServicioGratuito();
//        
//        $this->view->ransma=1;
//  
//        //  echo 'aqui';
//        //  exit;
  
    }
}
