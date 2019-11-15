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
class Usuario_ReputacionController 
    extends Devnet_Controller_Action
{
    function init()
    {
        $this->_redirect($this->view->baseUrl());
    }
//    /** getRecibidas
//     *
//     * @param type name desc
//     * @uses Clase::methodo()
//     * @return type desc
//     */    
//    function verAction ()
//    {
//    	require_once 'Reputacion.php';
//    	require_once 'UsuarioPortal.php';
//        require_once 'Calificacion.php';
//        $up = new UsuarioPortal();
//        $idUsuario = $this->_request->getParam('cod');
//        if (isset($idUsuario)) {
//            $usuario = $up->find($idUsuario);
//            if ($usuario->K_ERROR == 1) {
//                $this->_redirect($this->view->baseUrl());
//            }
//            else {
//                $this->view->cod=$this->_request->getParam('cod');
//            }
//        }
//        else {
//            if ($this->identity->ID_USR <> '') {
//    		$idUsuario = $this->identity->ID_USR;
//            }
//            else {
//                // Redireccionamos a otra pagina
//                $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
//            }
//        }
//        $this->view->idUsuarioCalificado = $idUsuario;
//    	$reputacion = new Reputacion();
//    	$this->view->data = $reputacion->verReputacion($idUsuario);
//        $this->view->idUsuario = $idUsuario;
//    	$this->view->usuario = $up->find($idUsuario);
//    	
//        $calificacion= new Calificacion();       
//        $this->view->listarCR = $calificacion->getRecibidas($idUsuario, $this->_request->getParam('op'));
//        $this->view->op = $this->_request->getParam('op');
//        $this->view->usr = $this->identity->ID_USR;
//        switch ($this->view->op) {
//            case 8:
//                $this->view->agrupador = 'ID_AVISO';
//                break;
//            case 9:
//                $this->view->agrupador = 'ORDEN';
//                break;
//            case 10:
//                $this->view->agrupador = 'APODO';
//                break;
//        }
//        $motivoDenuncia = new Calificacion();
//        $this->view->listarMotivos = $motivoDenuncia->getMotivosDenuncia(3);
//    }

    /**
     * Denunciar calificacion
     * @param type name desc
     * @Tuses Clase::methodo()
     * @return type desc
     */
    function denunciarCalificacionAction()
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isXMLHttpRequest()) {
            require_once 'UsuarioPortal.php';
            $mUsuarioPortal = new UsuarioPortal();
            $parametros = $this->_request->getParams($this->_request->isXMLHttpRequest());
            $parametros['idUsr'] = $this->identity->ID_USR;
            $parametros['apodo'] = $this->identity->APODO;
            $parametros['idTipoNotificacion'] = 4;
            $moderacion = $mUsuarioPortal->moderacionSuspension($parametros['mensaje']);
            if ($moderacion[0]->ERROR == 1) {
                $flag = 1;
            }
            if ($moderacion[0]->ERROR == 2) {
                $this->json(
                    array(
                        'code' => 2,
                        'msg' => 'Modificar esta(s) palabra(s) por favor: ' . 
                        substr(rtrim(str_replace('|', ', ', $moderacion[0]->MSJ)), 2)
                    )
                );
            } else {
                if ($moderacion[0]->ERROR == 0) {
                    $flag = 0;
                }
                $data['idUsr'] = $this->identity->ID_USR;
                $data['mensaje'] = $parametros['mensaje'];
                $data['apodo'] = $this->identity->APODO;
                $data['idMotivo'] = $parametros['idMotivo'];
                $data['idTipoNotificacion'] = 4;
                $data['idTransaccion'] = $parametros['idTransaccion'];

                require_once 'Reputacion.php';
                $reclamoCalificacion = new Reputacion();
                $retorno = $reclamoCalificacion->registrarNotificacion($data);
                $this->view->valorretorno = $retorno;
                if ($retorno->K_ERROR == 0) {
                    $this->json(array('code' => 0, 'msg' => 'Se registro satisfactoriamente la denuncia.'));
                }
            }
        }
    }

}
