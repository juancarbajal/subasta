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

require_once 'Aviso.php';
require_once 'AvisoInfo.php';
require_once 'MensajeDetalle.php';
require_once 'UsuarioPortal.php';
require_once 'Compras.php';

class Usuario_VentaController
    extends Devnet_Controller_Action
{
    function init()
    {
        parent::init();
        if (!$this->identity) {
            $this->session->requiredLoginUrl = $_SERVER['REQUEST_URI'];
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');

        }
        unset($this->session->img);
        unset($this->session->imgtm);
    }

    function activasAction() 
    {
        $this->view->headTitle('Ventas Activas | Kotear.pe');
        
        $mAviso    = new Aviso();
        $arrRender = new stdClass();
        
        $arrRender->tab = 'cuenta';
        $arrRender->page = 'venta/_activas';
        $arrRender->active = $this->getRequest()->getActionName();
        $fileshare = $this->getConfig()->fileshare->toArray();
//        $frontController = Zend_Controller_Front::getInstance();
//        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
//        $opc    = $this->_request->getParam('opc');
        $title = $this->_request->getParam('title');
        $fechaDe = $this->_request->getParam('fechade');
        $order = $this->_request->getParam('order');
        $page  = $this->_request->getParam('page');
        $this->session->pageHistory = $_SERVER['REQUEST_URI'];
        $input['K_ID_USR'] = $this->identity->ID_USR;
        $input['K_TIT'] = $title;
        $input['K_FILTRO'] = ($order >= 1 && $order <= 12)?$order:0;
        $input['K_FILTRO_FECHA'] = ($fechaDe >= 1 && $fechaDe <= 2)?$fechaDe:0;
        $input['K_NUM_REGISTROS'] = $this->_nroPagMin;
        $input['K_NUM_PAGINA'] = $page;
        $data = $mAviso->listarVentasActivas($input);
        //var_dump($data);Exit;
        $paginador = $this->_paginador($data[0]->TOTAL, $page);
        
        $this->view->filter = $input;
        $this->view->paginador = $paginador;
//        $this->view->nroRanMin = $this->_nroPagMin;
        $this->view->data = $data;
        $this->view->arrRender = $arrRender;
        $this->view->ruta=$fileshare['url'].'/'.$fileshare['thumbnails'].'/';
        
    }

    function inactivasAction()
    {
        
        $this->view->headTitle('Ventas Inactivas | Kotear.pe');
        
        $mAviso = new Aviso();
        $arrRender = new stdClass();
        
        $arrRender->tab = 'cuenta';
        $arrRender->page = 'venta/_inactivas';
        $arrRender->active = $this->getRequest()->getActionName();
        $fileshare       = $this->getConfig()->fileshare->toArray();
        $title = $this->_request->getParam('title');
        $order = $this->_request->getParam('order');
        $fechaDe = $this->_request->getParam('fechade');
        $page  = $this->_request->getParam('page');
        $this->session->pageHistory = $_SERVER['REQUEST_URI'];
        
        $error = $this->_request->getParam('error');
        $msg = $this->_request->getParam('msg', '');
        if ($error) {
            $this->view->error = $error;
            $this->view->msg = $msg;
        }
        $input['K_ID_USR'] = $this->identity->ID_USR;
        $input['K_TIT'] = $title;
        $input['K_FILTRO'] = ($order >= 1 && $order <= 12)?$order:0;
        $input['K_FILTRO_FECHA'] = ($fechaDe >= 1 && $fechaDe <= 12)?$fechaDe:0;
        $input['K_NUM_REGISTROS'] = $this->_nroPagMin;
        $input['K_NUM_PAGINA'] = $page;
        $data = $mAviso->listarVentasNoActivas($input);
        $paginador = $this->_paginador($data[0]->TOTAL, $page);
        
        $this->view->filter = $input;
        $this->view->paginador = $paginador;
//        $this->view->nroRanMin = $this->_nroPagMin;
        $this->view->data = $data;
        $this->view->arrRender = $arrRender;
        $this->view->ruta=$fileshare['url'].'/'.$fileshare['thumbnails'].'/';
        
    }

    function pendientePagoAction() 
    {
        require_once 'TransaccionCierre.php';
        //require_once APPLICATION_PATH . '/modules/usuario/forms/formApToken.php';
        
        $this->view->headTitle('Pendiente de Pago | Kotear.pe');
        
        $mAviso = new Aviso();
        $arrRender = new stdClass();
        $form = new Application_Form_ApToken();
        
        $error = $this->_request->getParam('error');
        $msg = $this->_request->getParam('msg', '');
        if ($error) {
            $this->view->error = $error;
            $this->view->msg = $msg;
        }
        
        $arrRender->tab = 'cuenta';
        $arrRender->page = 'venta/_pendiente-pago';
        $arrRender->active = $this->getRequest()->getActionName();
        $fileshare = $this->getConfig()->fileshare->toArray();   
        $title = $this->_request->getParam('title');
        $fechaDe = $this->_request->getParam('fechade');
        $order = $this->_request->getParam('order');
        $page  = $this->_request->getParam('page');
        $this->session->pageHistory = $_SERVER['REQUEST_URI'];
        $input['K_ID_USR'] = $this->identity->ID_USR;
        $input['K_TIT'] = $title;
        $input['K_FILTRO'] = ($order >= 1 && $order <= 12)?$order:0;
        $input['K_FILTRO_FECHA'] = ($fechaDe >= 1 && $fechaDe <= 2)?$fechaDe:0;
        $input['K_NUM_REGISTROS'] = $this->_nroPagMin;
        $input['K_NUM_PAGINA'] = $page;
        $data = $mAviso->listarPendientePago($input);
        //var_dump($data);exit;
        $paginador = $this->_paginador($data[0]->TOTAL, $page);
        
        $this->view->filter = $input;
        $this->view->paginador = $paginador;
//        $this->view->nroRanMin = $this->_nroPagMin;
        $this->view->data = $data;
        $this->view->form = $form;
        $this->view->arrRender = $arrRender;
        $this->view->ruta=$fileshare['url'].'/'.$fileshare['thumbnails'].'/';
        
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function preguntasRecibidasAction()
    {
        require_once 'TransaccionCierre.php';
                
        $this->view->headTitle('Preguntas Recibidas| Kotear.pe');
        
        $mAviso = new Aviso();
        $arrRender = new stdClass();
        
        $arrRender->tab = 'cuenta';
        $arrRender->page = 'venta/_preguntas-recibidas';
        $arrRender->active = $this->getRequest()->getActionName();
        $fileshare = $this->getConfig()->fileshare->toArray();   
        $question = $this->_request->getParam('pregunta', '');
        $page = $this->_request->getParam('page', '');
        $this->session->pageHistory = $_SERVER['REQUEST_URI'];
        $input['K_ID_USR'] = $this->identity->ID_USR;
        $input['K_PARAM'] = $question;
        $input['K_NUM_REGISTROS'] = $this->_nroPagMin;
        $input['K_NUM_PAGINA'] = $page;
        $data = $mAviso->listarPreguntasRecibidas($input);
        //var_dump($data);exit;
        $paginador = $this->_paginador($data[0]->TOTAL, $page);
        
        $this->view->filter = $input;
        $this->view->paginador = $paginador;
//        $this->view->nroRanMin = $this->_nroPagMin;
        $this->view->data = $data;
        $this->view->arrRender = $arrRender;
        $this->view->ruta=$fileshare['url'].'/'.$fileshare['thumbnails'].'/';
    }
    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function agrupar($data)
    {
        $cont = count($data);
        $new = array();
        $incrementI=0;
        //while($i < $cont){
        while ($incrementI<$cont) {
            if (!isset($data[$incrementI]->ASIGNADO)) {
                //si no ha sido asignado
                $incrementJ = $incrementI;
                while ($data[$incrementI]->ID_AVISO == $data[$incrementJ]->ID_AVISO ) {
                    //Si existe el indice
                    if ($data[$incrementJ]->result == 'P') {
                        //Si es una pregunta
                        if (($data[$incrementI]->ID_USR == $data[$incrementJ]->ID_USR) && 
                            !isset($data[$incrementJ]->ASIGNADO)) {
                            //si es el mismo usuario
                            $new[] = $data[$incrementJ]; //insertamos registro
                            $data[$incrementJ]->ASIGNADO = 1;
                            if ($data[$incrementJ+1]->result == 'R') {
                                $new[] = $data[$incrementJ+1];
                                $data[$incrementJ+1]->ASIGNADO = 1;
                                $incrementJ++;
                            }
                        }
                    }
                    $incrementJ++; //avanzamos al siguiente mensaje
                }
            }
            $incrementI++; // avanzamos al siguiente mensaje
        }
        return $new;
    } // end function
    function preguntasContestadasAction()
    {
        $this->view->listarPC = $this->verPreguntasContestadas($this->identity->ID_USR);
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function guardaRespuestaAction()
    {
        //if ($this->_request->isXMLHttpRequest()) {
        if ($this->_request->isPost()) {
            $idUsuario = $this->identity->ID_USR;
            $comentario = $this->_request->getParam('comment');
            $idMensaje = $this->_request->getParam('idmensaje');
            $comprador = $this->_request->getParam('comprador');
            $mMensajeDetalle = new MensajeDetalle();
            $mensajeDetalle = $mMensajeDetalle->find($idMensaje);
            $mUsuarioPortal = new UsuarioPortal();
            $moderacion = $mUsuarioPortal->moderacionSuspension($comentario);
            if ($moderacion[0]->ERROR==1) {
                $flag = 1;
            }
            if ($moderacion[0]->ERROR==0) {
                $flag = 0;
            }
            if ($moderacion[0]->ERROR==2) {
                $this->json(
                    array(
                        'code'=>2,
                        'msg'=>'Modificar esta(s) palabra(s) por favor: '.$moderacion[0]->MSJ
                    )
                );
            } else {
                $insert = new MensajeDetalle();
                $retorno = $insert->insertRespuesta(
                    $comentario,
                    '2',
                    $mensajeDetalle->ID_MENSAJE,
                    $idUsuario,
                    $flag
                );
                if ($retorno[0]->K_ERROR == 0) {
                    try {
                        $mAvisoInfo = new AvisoInfo();
                        $datosAviso = $mAvisoInfo->obtenerDatos($mensajeDetalle->ID_REGISTRO);
                        $emailDueno = $mUsuarioPortal->find($mensajeDetalle->IDCOMPRADOR);
                        $datosUsuario = $mUsuarioPortal->find($this->identity->ID_USR);
                        $emailModelo = new UsuarioPortal();
                        $retornoEmail = $emailModelo->extraerEmail($mensajeDetalle->APODO);
                        // Envio de correo a usuario que realizo la pregunta
                        $correoUsuario = $retornoEmail[0]->K_MSG;
                        $this->_helper->layout->setLayout('clear');
                        $template = new Devnet_TemplateLoad('envio_respuesta');
                        $template->replace(
                            array(
                                '[COMPRADOR]' => $emailDueno->APODO,
                                '[VENDEDOR]' => $datosUsuario->APODO,
                                '[URL_AVISO]' => $mensajeDetalle->ID_REGISTRO . '-' . $datosAviso[0]->URL,
                                '[AVISO]' => $datosAviso[0]->TIT
                            )
                        );
                        $this->json(array('code'=>0, 'msg'=>'Su respuesta fue enviada con Exito'));
                    } catch (Exception $e) {
                        $this->log->err($e->getMessage());
                    }
                } else {
                    $this->json(array('code'=>3, 'msg'=>'No se pudo registrar su respuesta'));
                }
            }
        }
    }//function

    /**
     * Permite eliminar una pregunta de acuerdo al criterio del usuario
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function eliminarPreguntaAction ()
    {
        $this->view->volver=$this->session->avisoUrl;
        if ($this->_request->isGet()) {
            $mMensajeDetalle = new MensajeDetalle();
            // Consultamos el detalle mensaje
            $mensajeDetalle = $mMensajeDetalle->find($this->_request->getParam('id'));
            $data['idMensaje'] = $mensajeDetalle->ID_MENSAJE;
            $data['idDetalleMensaje'] = $mensajeDetalle->ID_DETALLE_MENSAJE;
            $data['idTipoMensaje'] = 2;
            $data['idUsr'] = $this->identity->ID_USR;
            if (count($mMensajeDetalle->getNroRespuestasMensaje($data)) == 0) {
                // Eliminamos el detalle mensaje
                $mMensajeDetalle->eliminarDetalleMensaje($data['idDetalleMensaje']);
                require_once 'Mensaje.php';
                $mMensaje = new Mensaje();
                $mUsuarioPortal = new UsuarioPortal();
                // Eliminamos el mensaje
                $mMensaje->eliminarMensaje($data['idMensaje']);
                try {
                    $mAvisoInfo = new AvisoInfo();
                    $datosAviso = $mAvisoInfo->obtenerDatos($mensajeDetalle->ID_REGISTRO);
                    $emailDueno = $mUsuarioPortal->find($mensajeDetalle->IDCOMPRADOR);
                    $datosUsuario = $mUsuarioPortal->find($this->identity->ID_USR);
                    $emailModelo = new UsuarioPortal();
                    $retornoEmail = $emailModelo->extraerEmail($mensajeDetalle->APODO);
                    // Envio de correo a usuario que realizo la pregunta
                    $correoUsuario = $retornoEmail[0]->K_MSG;
                    $this->_helper->layout->setLayout('clear');
                    $template = new Devnet_TemplateLoad('eliminar_pregunta');
                    $template->replace(
                        array(
                            '[COMPRADOR]' => $emailDueno->APODO,
                            '[VENDEDOR]' => $datosUsuario->APODO,
                            '[URL_AVISO]' => $mensajeDetalle->ID_REGISTRO . '-' . $datosAviso[0]->URL,
                            '[AVISO]' => $datosAviso[0]->TIT
                        )
                    );
                    //Nuevo envio de mail
                    $correo = Zend_Registry::get('mail');
                    $correo->addTo($emailDueno->EMAIL, $emailDueno->NOM)
                        ->setSubject('¡Eliminaron tu pregunta!')->setBodyHtml($template->getTemplate());
                    $correo->send();
                    $this->json(array('code'=>0, 'msg'=>'La pregunta recibida fue eliminada.'));
                }   catch (Exception $e) {
                    $this->json(
                        array(
                            'code'=>1,
                            'msg'=>'Ha ocurrido un error interno del sistema. Intentelo mas tarde por favor.'
                        )
                    );
                    $this->log->err($e->getMessage());
                }
            } else {
                echo "Existen respuestas o la pregunta no es del usuario";
            }
        }
    } //end function

//    /** listaAvisoCompradores
//     * Permite visualizar los compradores de un determinado aviso. A demas  de todos los filtros.
//     * @param type name desc
//     * @uses Clase::methodo()
//     * @return type desc
//     */
//    function misCompradoresAction() {
//        $aviso = new Aviso();
//        $this->session->sessionCalificar = 1;
//        $this->view->headTitle('Detalle de la venta - Mis compradores | Kotear.pe');
//        $avisoInfo = new AvisoInfo();
//        $frontController = Zend_Controller_Front::getInstance();
//        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
//        $this->session->pageHistory = $_SERVER['REQUEST_URI'];
//        $this->view->ruta=$fileshare[url].'/'.$fileshare['thumbnails'].'/';
//        $this->view->datosAviso = $avisoInfo->getInfo($this->_request->getParam('cod'));
//        $this->view->codigoAviso = $this->_request->getParam('cod');
//        $this->view->d1 = $this->_request->getParam('d1');
//        $this->view->misCompradores = $aviso->listaAvisoCompradores($this->_request->getParam('cod'),
//        $this->identity->ID_USR ,
//        $this->_request->getParam('filtro'));
//        $this->view->cod=$this->_request->getParam('cod');
//    }
    /**
     * Historial de Ventas
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function historialAction()
    {
        $this->session->sessionCalificar = 1;
        $this->view->headTitle('Historial de Ventas | Kotear.pe');
        require_once 'Oferta.php';
        $oferta = new Oferta();
        $this->view->filtroCategoria = (($this->_request->getParam('fc')!=null) && 
            ($this->_request->getParam('fc')>=0))?$this->_request->getParam('fc'):-1;
        $this->view->filtroCalificacion = (($this->_request->getParam('fl')!=null) && 
            ($this->_request->getParam('fl')>=0))?$this->_request->getParam('fl'):-1;
        $this->view->filtroDias = (($this->_request->getParam('fd')!=null) && 
            ($this->_request->getParam('fd')>=0))?$this->_request->getParam('fd'):-1;
        $this->view->filtroEstadoCalificacion = (($this->_request->getParam('fc')!=null) && 
            ($this->_request->getParam('fe')>=0))?$this->_request->getParam('fe'):-1;
        $this->view->apodo = $this->_request->getParam('apodo');
        $this->view->apodo = ($this->view->apodo=='Apodo')?'':$this->view->apodo;
        /*QueryString*/
        $queryString = $this->view->baseUrl() . '/usuario/venta/historial/?apodo=%s&fc=%s&fl=%s&fd=%s&fe=%s';
        $this->view->qsCategoria =  sprintf(
            $queryString, '', '%s', $this->view->filtroCalificacion, $this->view->filtroDias,
            $this->view->filtroEstadoCalificacion
        );
        $this->view->qsCalificacion = sprintf(
            $queryString, '', $this->view->filtroCategoria, '%s', $this->view->filtroDias,
            $this->view->filtroEstadoCalificacion
        );
        $this->view->qsDias = sprintf(
            $queryString, '', $this->view->filtroCategoria, $this->view->filtroCalificacion, '%s',
            $this->view->filtroEstadoCalificacion
        );
        $this->view->qsEstadoCalificacion = sprintf(
            $queryString, '', $this->view->filtroCategoria, $this->view->filtroCalificacion,
            $this->view->filtroDias, '%s'
        );

        $data = $oferta->getHistorialVenta(
            $this->identity->ID_USR,
            $this->view->apodo,
            $this->view->filtroCategoria,
            $this->view->filtroCalificacion,
            $this->view->filtroDias,
            $this->view->filtroEstadoCalificacion
        );
        $this->view->totalCategoria = array();
        //Totales
        if (($this->view->filtroCategoria == -1) && ($this->view->filtroCalificacion == -1) &&
                ($this->view->filtroEstadoCalificacion == -1) && ($this->view->filtroDias == -1) &&
                ($this->view->apodo == '') ) {
            foreach ($data as $row) {
                if(isset($this->view->totalCategoria[$row->AVISO_CATEGORIA_ID]))
                    $this->view->totalCategoria[$row->AVISO_CATEGORIA_ID]['cont']++;
                else
                    $this->view->totalCategoria[$row->AVISO_CATEGORIA_ID] = array(
                        'cont' => 1, 'nom' => $row->AVISO_CATEGORIA_NOM
                    );
            }
            $this->session->historialCompraTotalCategoria = $this->view->totalCategoria;
        } else {
            $this->view->totalCategoria = $this->session->historialCompraTotalCategoria;
        }
        //Paginador
        $historialCount = count($data);
        if ($historialCount>0) {
            $itemCountPerPage = 30;
            $pageRange = 10;
            $currentPage = ($historialCount > $itemCountPerPage)?$this->_request->getParam('p', 1):1;
            $this->view->paginator = Zend_Paginator::factory($data);
            $this->view->paginator->setCurrentPageNumber($currentPage)
                                                      ->setItemCountPerPage($itemCountPerPage)
                                                      ->setPageRange($pageRange);
            //$front = Zend_Controller_Front::getInstance();
            //$cache = $front->getParam('bootstrap')->getResource('cachemanager')->getCache('file');
            //Zend_Paginator::setCache($cache);
            $this->view->historialCount = $historialCount;
            $this->view->data = $this->view->paginator->getCurrentItems();
        } else {
            $this->view->data= array();
            $this->view->paginator = null;
        }
        //$this->session->calificacionPrevUrl = $_SERVER['REQUEST_URI'];
    }
    
    function paginadorAction()
    {

    }
    
    function masOportunidadesAction()
    {
        $this->view->headTitle('Ventas Activas | Kotear.pe');
        
        $mAviso    = new Aviso();
        $arrRender = new stdClass();
        
        $arrRender->tab = 'masoportunidades';
        $arrRender->page = 'venta/_mas-oportunidades';
        $arrRender->active = $this->getRequest()->getActionName();
        $this->view->arrRender = $arrRender;
        
    }
}
