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
require_once 'Calificacion.php';
require_once 'Transaccion.php';
require_once 'Oferta.php';
require_once 'UsuarioPortal.php';
require_once 'Aviso.php';

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
class Usuario_CalificacionesController 
    extends Devnet_Controller_Action
{

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function init()
    {
        parent::init();
        if (!isset($this->identity)) {
            $this->session->requiredLoginUrl = $_SERVER['REQUEST_URI'];
            $this->_redirect("/usuario/acceso");
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function indexAction()
    {
        
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function registrarAction()
    {

        $this->getResponse()->setHeader('Expires', '0', true)
                ->setHeader(
                    'Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true
                )
                ->setHeader('Pragma', 'no-cache', true);

        unset($this->session->sessionCalificar);

        $calificacion = new Calificacion();
        $aviso = new Aviso();
        $mUsuarioPortal = new UsuarioPortal();
        $usuarioPortal = $mUsuarioPortal->find($this->identity->ID_USR);
        $frontController = Zend_Controller_Front::getInstance();
        $varBus = $aviso->getServicioGratuito();
        // $frontController->getParam('bootstrap')->getOption('business');
        //print_r($this->_request->getParams());
        //echo "<br/>";
        foreach ($this->session->datos as $valor):
            $array = array($this->_request->getParam('valoracion'),
                $this->identity->ID_USR,
                ($this->_request->getParam('motivoNoRealizacion'))
                    ?$this->_request->getParam('motivoNoRealizacion'):1,
                $valor->TRANS,
                $this->_request->getParam('comentario'),
                $this->_request->getParam('resultadoVenta'),
                $valor->IDOFERTA,
                $valor->COMPRADOR,
                $valor->VENDEDOR,
                $valor->ACTRV,
                $valor->ACTRC,
                $this->_request->getParam('resulMoreacion'), $varBus//['des']['free']
            );

            if ($valor->COMPRADOR == 1) {
                $nameTemplate = 'calificar_compra';
                $subjet = 'Han calificado tu compra';
                $idUsr = $valor->USROFE;
                $vendedor = $usuarioPortal->APODO;
            } else {
                if ($valor->VENDEDOR == 1) {
                    $nameTemplate = 'calificar_venta';
                    $subjet = 'Han calificado tu aviso';
                    $idUsr = $valor->USRAV;
                }
            }
            $calificacion->setCalificacion($array);
            $arrayReplace = array('[CODIGO]' => $idUsr,
                '[COMPRADOR]' => $usuarioPortal->APODO,
                '[VENDEDOR]' => $vendedor,
                '[NOMBRE]' => $this->view->escape($valor->NOMBRE),
                '[PUNTAJE]' => $calificacion->obtenerPuntaje($this->identity->ID_USR),
                '[TITAVISO]' => $this->view->escape(htmlentities($valor->TITAVISO, ENT_QUOTES)),
                '[IDAVISO]' => $valor->IDAVISO,
                '[IDUSR]' => $this->identity->ID_USR);
            $this->enviarCorreo($nameTemplate, $subjet, $arrayReplace, $valor->EMAIL, $valor->APODO);
        endforeach;
        $this->_redirect($this->view->baseUrl() . '/usuario/calificaciones/calificacion-confirmacion');
        unset($this->session->datos);
    }

    function enviarCorreo($templateName, $subject, $arrayReplace, $email, $apodo)
    {
        try {
            $template = new Devnet_TemplateLoad($templateName);
            $template->replace($arrayReplace);
            $correo = Zend_Registry::get('mail');
            $correo = new Zend_Mail('utf-8');
            $correo->addTo($email, $apodo)
                    ->clearSubject()
                    ->setSubject($subject)
                    ->setBodyHtml($template->getTemplate());
            $correo->send();
            return true;
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            return false;
        }
    }

    function calificacionConfirmacionAction()
    {
        //echo $this->session->calificacionPrevUrl;
        $this->view->calificacionPrevUrl = ($this->session->calificacionPrevUrl != null) 
            ? $this->session->calificacionPrevUrl : '/usuario/calificaciones/pendientes';
        $this->session->calificacionPrevUrl = '/usuario/calificaciones/pendientes';
        unset($this->session->calificacionPrevUrl);
        unset($this->session->sessionCalificar);
    }

//    function calificarAction() { getDatosTransaccion
//
//        $this->getResponse()->setHeader('Expires', '0', true)
//                ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, 
//                      pre-check=0', true)
//                ->setHeader('Pragma', 'no-cache', true);
//        $script = '
//			$(document).ready(function(){
//				$.getScript("' . $this->view->baseUrl() . '/f/js/kotear.calificar.js", function(){
//					$.Kotear.setCalificar();
//					$("#calificarfrm .controls button").bind("click", function(){
//						f_mandar();
//						return false;
//					});
//				});
//			});';
//        $this->view->headScript()->appendScript($script);
//
//        if (!isset($this->session->sessionCalificar)) {
//            $this->_redirect($this->view->baseUrl() . '/usuario/calificaciones/pendientes');
//        }
//
//
//
//        $redirec = true;
//        $calificacion = new Calificacion();
//        $this->_request->getParam("cod");
//        $this->view->escala = $this->getEscala();
//        $this->view->motivo = $this->getMotivo(1);
//        if ($this->_request->isPost()) {
//            if (count($this->_request->getParam('select')) == 0) {
//                $this->_redirect($this->view->baseUrl() . '/usuario/calificaciones/pendientes');
//            } else {
//                $this->session->datos = $calificacion->getDatosTransaccion($this->identity->ID_USR, 
//                      implode(',', $this->_request->getParam('select')));
//                $this->view->datosCalificacion = $this->session->datos;
//                $this->view->prm = 4;
//            }
//            $this->view->apodo = $this->identity->APODO;
//            $redirec = false;
//        }
//        if ($this->_request->isGet()) {
//            $trans = $this->_request->getParam('trns');
//            $this->session->datos = $calificacion->getDatosTransaccion($this->identity->ID_USR, $trans);
//            $this->view->datosCalificacion = $this->session->datos;
//            $this->view->prm = $this->session->datos[0]->REG_TRANS;
//            $this->view->apodo = $this->identity->APODO;
//            $redirec = false;
//        }
//
//        if ($redirec)
//            $this->_redirect($this->view->baseUrl() . '/usuario/calificaciones/pendientes');
//    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function verreputacionAction()
    {
        $this->view->verReputacion = $this->db->fetchAll();
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function verificarDiasTransaccionAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            if (isset($this->session->datos)) {
                foreach ($this->session->datos as $valor):
                    if ($valor->REG_TRANS <= 76) {
                        $mntd[] = $valor->TRANS;
                    }
                endforeach;
                $this->json(array('datos' => $mntd));
            }
        }
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function eliminarMenoresTresDiasAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            if (isset($this->session->datos)) {
                $array = explode(',', $this->_request->getParam('select'));
                foreach ($this->session->datos as $i => $index):
                    if (in_array($index->TRANS, $array))
                        unset($this->session->datos[$i]);
                endforeach;
                $this->json(array('datos2' => $this->session->datos[1]));
            }
        }
    }

//    /** getRealizadas
//     *
//     * @param type name desc
//     * @uses Clase::methodo()
//     * @return type desc
//     */
//    function realizadasAction() {
//        $calificacion = new Calificacion();
//        $this->view->listarCR = $calificacion->getRealizadas($this->identity->ID_USR, 
//              $this->_request->getParam('op'));
//        $this->view->op = $this->_request->getParam('op');
//        $this->view->usr = $this->identity->ID_USR;
//        switch ($this->view->op) {
//            case 8:
//                $this->view->agrupador = 'L1_NOM';
//                break;
//            case 9:
//                $this->view->agrupador = 'FEC_REG';
//                break;
//            case 10:
//                $this->view->agrupador = 'APODO';
//                break;
//        }
//    }

//    function recibidasAction() { getRecibidas
//        $calificacion = new Calificacion();
//        $this->view->listarCR = $calificacion->getRecibidas($this->identity->ID_USR, 
//              $this->_request->getParam('op'));
//
//        $this->view->op = $this->_request->getParam('op');
//        $this->view->usr = $this->identity->ID_USR;
//        switch ($this->view->op) {
//            case 8:
//                $this->view->agrupador = 'L1_NOM';
//                break;
//            case 9:
//                $this->view->agrupador = 'FEC_REG';
//                break;
//            case 10:
//                $this->view->agrupador = 'APODO';
//                break;
//        }
//    }

//    /** getPendientes
//     * Calificaciones Pendientes
//     * @param type name desc
//     * @Tuses Clase::methodo()
//     * @return type desc
//     */
//    function pendientesAction() {
//        $this->session->sessionCalificar = 1;
//
//        $calificacion = new Calificacion();
//        $this->view->listarCP = $calificacion->getPendientes($this->identity->ID_USR, 
//              $this->_request->getParam('op'), $this->_request->getParam('est'));
//        $this->view->usr = $this->identity->ID_USR;
//        $this->view->op = $this->_request->getParam('op');
//        $this->view->est = $this->_request->getParam('est');
//        switch ($this->view->op) {
//            case 8:
//                $this->view->agrupador = 'L1_NOM';
//                break;
//            case 9:
//                $this->view->agrupador = 'FEC_REG';
//                break;
//            case 10:
//                $this->view->agrupador = 'APODO';
//                break;
//        }
//    }

    /**
     * Replicar calificacion
     * @param type name desc
     * @Tuses Clase::methodo()
     * @return type desc
     */
    function replicarAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            require_once 'UsuarioPortal.php';
            $mUsuarioPortal = new UsuarioPortal();
            $parametros = $this->_request->getParams($this->_request->isXMLHttpRequest());
            $parametros['idUsr'] = $this->identity->ID_USR;
            $parametros['apodo'] = $this->identity->APODO;

            $moderacion = $mUsuarioPortal->moderacionSuspension($parametros['comentario']);
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
                require_once 'MensajeDetalle.php';
                $replica = new MensajeDetalle();
                $parametros['tipoMensaje'] = '4';
                $parametros['idUsuario'] = $this->identity->ID_USR;
                $retorno = $replica->registrarReplica($parametros);
                $this->view->valorretorno = $retorno;
                if ($retorno->K_ERROR == 0) {
                    $this->json(
                        array(
                            'code' => 0, 
                            'msg' => 'Se registro satisfactoriamente la replica al comentario.'
                        )
                    );
                }
            }
        }
    }

    /**
     * Denunciar calificacion
     * @param type name desc
     * @Tuses Clase::methodo()
     * @return type desc
     */
    function denunciarAction()
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

                require_once 'Calificacion.php';
                $reclamoCalificacion = new Calificacion();
                //var_dump($reclamoCalificacion);
                $retorno = $reclamoCalificacion->registrarNotificacion($data);
                //$this->view->valorretorno = $retorno;
                $retorno->K_ERROR = 0;
                if ($retorno->K_ERROR == 0) {
                    $this->json(array('code' => 0, 'msg' => 'Se registro satisfactoriamente la denuncia.'));
                }
            }
        }
    }

    function getEscala()
    {
        return $this->db->fetchAll("SELECT * FROM KO_ESCALA ORDER BY ID_ESCALA DESC");
    }

    function getMotivo($tipo)
    {
        //return $this->db->fetchAll("SELECT * FROM KO_MOTIVO WHERE ID_TIPO_MOTIVO=1");
        $motivosCalificacion = new Calificacion();
        return $motivosCalificacion->getMotivosDenuncia($tipo);
        
    }

    function moderarComentarioAction()
    {
        require_once 'Aviso.php';
        $aviso = new Aviso();
        if ($this->_request->isXMLHttpRequest()) {
            $moderacion = $aviso->moderarAviso($this->_request->getParam('comentario'));
            $this->json(array('estado' => $moderacion->ERROR, 'datos' => explode('|', $moderacion->MSJ)));
        }
    }

}
