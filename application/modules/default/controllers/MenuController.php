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
class MenuController
    extends Devnet_Controller_Action_Default
{

   /**
     * Visualiza un layout que permite realizar busqueda desde cualquier pagina que lo contenga
     * @param pe name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function menuUsuarioAction()
    {
        require_once 'UsuarioPortal.php' ;
        $usuario= new UsuarioPortal();
        require_once 'Aviso.php';
        $mAviso= new Aviso();
        $this->view->preguntasSinContestar=$usuario->preguntasSinContestar($this->identity->ID_USR);
        $this->view->numeroCalificacionesPendientes=$usuario->numeroCalificacionesPendientes(
            $this->identity->ID_USR
        );
        $this->view->avisosPorCaducar=$usuario->avisosPorCaducar($this->identity->ID_USR, 3);
        $this->view->avisosSeguimiento=$mAviso->getAvisoSeguimiento($this->identity->ID_USR);
        require_once 'Aviso.php';
        $mAviso = new Aviso();
        //echo $aviso->getServicioGratuito();
        if ($mAviso->getServicioGratuito()==1) $this->view->saldo = 0;
        else $this->view->saldo=$usuario->pendienteDePago($this->identity->ID_USR);
    }

   /**
     * Visualiza un layout que permite realizar busqueda desde cualquier pagina que lo contenga
     * @param pe name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function preguntasSinContestar()
    {
        require_once 'Categoria.php';
        $mCategoria = new Categoria();
        $this->view->categoriasBuscador = $mCategoria->getCategoriasL1(-1);
    }
    /** 
     * 
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     **/
    public function navegacionAction ()
    { 
        require_once 'Aviso.php';
        $mAviso = new Aviso();
        $this->view->servicioGratuito = $mAviso->getServicioGratuito();
        $navBar = $this->_request->getParam('nav');
        $navOpc = $this->_request->getParam('opc');
        $this->view->nav = $navBar;
        $this->view->opc = $navOpc;
    } //end function navegacionAction
    
}