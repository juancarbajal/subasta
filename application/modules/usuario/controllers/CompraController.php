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
require_once 'Mensaje.php';
require_once 'Compras.php';
require_once 'UsuarioPortal.php';
class Usuario_CompraController extends Devnet_Controller_Action
{
    /**
     * Accion de confirmar compra
     * @return void
     */
    function confirmarAction ()
    {
        $this->view->headTitle('Confirmar compra | Kotear.pe');
        if (!isset($this->identity)) {
            $this->session->requiereLoginUrl =  $this->session->avisoUrl;
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
        } else {
            if ($this->_request->isPost()) {
                $idAviso = $this->_request->getParam('id');
                $cantidad = $this->_request->getParam('cantidad');
                if (isset($this->session->usuarioAvisoVer)) {
                    $this->view->data = $this->session->usuarioAvisoVer;
                } else {
                    require_once 'AvisoInfo.php';
                    $mAvisoInfo = new AvisoInfo();
                    $this->view->data = $mAvisoInfo->getInfo($idAviso);
                }
                $frontController = Zend_Controller_Front::getInstance();
                $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
                $this->view->imagen = $fileshare['url'] . '/' . $fileshare['img'] . '/' . 
                    $this->view->data->K_FOTO_PRINCIPAL;
                $this->view->cantidad = ($this->_request->getParam('cantidad'))
                    ? $this->_request->getParam('cantidad') : 1;
                $this->view->oferta = ($this->_request->getParam('oferta'))
                    ? $this->_request->getParam('oferta') : $this->data->K_PRECIO_FINAL;
                $ofertaAutomatica = ($this->_request->getParam('ofertaAutomatica')) ? 1 : 0;
                $this->view->total = number_format($this->view->cantidad * $this->view->oferta, 2, '.', '');
                $this->view->idAviso = $this->_request->getParam('id');
                $this->session->compraConfirmar = $this->_request->getParams();
                $this->view->ofertaAutomatica = $ofertaAutomatica;

            } else {
                $this->_redirect($this->view->baseUrl());
            } //end else
        }
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function confirmarTerminoAction ()
    {
        $this->view->headTitle('Confirmar compra | Kotear.pe');
        if ($this->_request->isPost()) {
            $idAviso = $this->_request->getParam('id');
            require_once 'Oferta.php';
             $oferta = new Oferta();
            /*
            $result = $oferta->comprar($this->session->compraConfirmar['id'],
                $this->identity->ID_USR,
                ($this->session->compraConfirmar['cantidad']) 
                   ? $this->session->compraConfirmar['cantidad'] : 1,
                $this->_request->getParam('total'),
                ($this->session->compraConfirmar['ofertaAutomatica']) ? 1 : 0);
             */
             $result = $oferta->comprar(
                 $this->_request->getParam('idAviso'),
                 $this->identity->ID_USR,
                 $this->_request->getParam('cantidad'),
                 $this->_request->getParam('total'),
                 $this->_request->getParam('ofertaAutomatica')
             );
             
            if ($result->K_ERROR > 0) {
                $this->view->msg = $result->K_MSG;
            } elseif (!isset($result->K_ID_OFERTA)) {
                $this->view->msg = "No se ha podido generar una oferta por el producto.";
            } else {
                $idPerdedor = $result->K_PERDEDOR_SUBASTA;
                $idGanador = $result->K_GANADOR_SUBASTA;
                //Seleccionamos el mensaje a mostrar si gano la puja o  no pudo ganar la puja
                if ($this->identity->ID_USR == $result->K_GANADOR_SUBASTA) {
                    $this->view->msgResultado = 
                        '<b>Oferta confirmada:</b> &#161;Eres el actual ganador de esta subasta&#33;';
                } else {
                    $this->view->msgResultado = 
                        '<b>Oferta insuficiente:</b> &#161;Otro usuario ya super&oacute; tu oferta&#33;';
                }
                
                require_once 'AvisoInfo.php';
                require_once 'Aviso.php';
                // envio de correo si estock es 0
                $aviso = new AvisoInfo();
                $avisoDatos = new Aviso();
                $compra = new Compras();
                $mUsuarioPortal = new UsuarioPortal();

                $cantidad = $this->_request->getParam('cantidad');
                $idAviso = $this->_request->getParam('id');
                (!isset($idAviso))?$idAviso=$this->_request->getParam('idAviso'):0;
                $tipoAviso = $aviso->getTipoAviso($idAviso);
                if ($tipoAviso == 1) { 
                    //Si es compra directa
                    //Envio de correo
                    $usuarioPortal = $mUsuarioPortal->find($this->identity->ID_USR);
                    //Envio de email cuando el stock llegue a tener valor 0
                    $result = $aviso->getInfo($idAviso);
                    if ($result->K_STOCK == 0) {
                        foreach ($avisoDatos->getDestaquesAviso($this->_request->getParam('id')) as $value) {
                            $destaque .='<br>'.$value->TIT.' '.'(S/.'.$value->MONTO.')';
                        }
                        $envioStock = $compra->enviarEmail($idAviso);
                        $template = new Devnet_TemplateLoad('stockCero');
                        $template->replace(
                            array(
                                '[NOMBRE]' =>  $envioStock[0]->NOM ." ".$envioStock[0]->APEL,
                                '[TITULO]' => $this->view->escape($result->K_TITULO),
                                '[CODIGO]' => $idAviso,
                                '[FIN]' => $result->K_FECHA_CADUCIDAD,
                                '[PRECIO]' => $result->K_SIMB_MONEDA." ".$result->K_PRECIO_FINAL,
                                '[VISITAS]' => $result->K_NUM_VISITAS,
                                '[VENTAS]' => $result->K_NUM_COMPRAS,
                                '[DESTAQUE]' => $destaque
                            )
                        );

                        $correo = Zend_Registry::get('mail');
                        $correo = new Zend_Mail('utf-8');
                        $correo->addTo($envioStock[0]->EMAIL, $envioStock[0]->NOM)
                                ->clearSubject()
                               ->setSubject(
                                   'Tu Aviso se '.$this->view->escape($envio[0]->TIT). ' quedo sin Stock'
                               )
                               ->setBodyHtml($template->getTemplate());
                        $correo->send();
                    }

                    $envio = $compra->enviarEmail($idAviso);
                   //---------------------- ENVIO COMPRADOR ------------------------------
                    $templateComprador = new Devnet_TemplateLoad('enviocompra');
                    $templateComprador->replace(
                        array(
                            '[COMPRADOR]' =>  $usuarioPortal->APODO,
                            '[TITULO]' => $this->view->escape($envio[0]->TIT),
                            '[URL_AVISO]' =>  $this->view->BaseUrl() . '/aviso/' . $idAviso,
                            '[MONEDA]' => $envio[0]->SIMB,
                            '[PRECIO]' => number_format($envio[0]->PRECIO, 2, '.', ','),
                            '[CANTIDAD]' => $this->_request->getParam('cantidad'),
                            '[TOTAL]' =>$this->_request->getParam('total'),
                            //VENDEDOR
                            '[APODO]' => $this->view->escape($envio[0]->APODO),
                            '[NOMBRE]' =>$envio[0]->NOM ." ".$envio[0]->APEL,
                            '[TELEFONO]' => $envio[0]->FONO1.' '.$envio[0]->FONO2,
                            '[EMAIL]' => $envio[0]->EMAIL,
                            '[UBICACION]' => $envio[0]->LOCALIDAD
                        )
                    );
                    
                    $correoComprador = Zend_Registry::get('mail');
                    $correoVendedor = new Zend_Mail('utf-8');
                    $correoComprador->addTo($usuarioPortal->EMAIL, $usuarioPortal->NOM)
                        ->clearSubject()
                        ->setSubject('Datos del Vendedor '. $this->view->escape($envio[0]->TIT))
                        ->setBodyHtml($templateComprador->getTemplate());
                    
                    $resultOne = $correoComprador->send();
                    // -------------------------  ENVIO VENDEDOR ------------------------------
                    $templateVendedor = new Devnet_TemplateLoad('envioventa');
                    $templateVendedor->replace(
                        array(
                            '[ARTICULO]' =>  $this->view->escape($envio[0]->TIT),
                            '[VENDEDOR]' => $envio[0]->APODO,
                            '[TITULO]' => $this->view->escape($envio[0]->TIT),
                            '[URL_AVISO]' =>  $this->view->BaseUrl() . '/aviso/' . $idAviso,
                            '[MONEDA]' => $envio[0]->SIMB,
                            '[PRECIO]' => number_format($envio[0]->PRECIO, 2, '.', ','),
                            '[CANTIDAD]' => $this->_request->getParam('cantidad'),
                            '[TOTAL]' =>$this->_request->getParam('total'),
                            //COMPRADOR

                            '[APODO]' => $usuarioPortal->APODO,
                            '[NOMBRE]' => $usuarioPortal->NOM ." ".$usuarioPortal->APEL,
                            '[TELEFONO]' =>  $usuarioPortal->FONO1.' '.$usuarioPortal->FONO2,
                            '[EMAIL]' =>  $usuarioPortal->EMAIL,
                            '[UBICACION]' => $usuarioPortal->LOCALIDAD
                        )
                    );
                    
                    $correoVendedor->addTo($envio[0]->EMAIL, $envio[0]->NOM)
                       ->clearSubject()
                       ->setSubject('Datos del Comprador '. $this->view->escape($envio[0]->TIT))
                       ->setBodyHtml($templateVendedor->getTemplate());

                   $resultTwo = $correoVendedor->send();
                } else {
                    //Si es subasta
                    //$aviso = new AvisoInfo();
                    //$anteriorGanador = $aviso->getAnteriorGanador($idAviso);
                    $usuario = new UsuarioPortal();
                    //Subasta
                    if (isset($idPerdedor)) {
                        $perdedor = $usuario->find($idPerdedor);
                        $template = new Devnet_TemplateLoad('superaron_oferta');
                        $template->replace(
                            array(
                                '[apodo]' => $perdedor->APODO,
                                '[urlaviso]' => $this->view->baseUrl().$this->session->avisoUrl,
                                '[aviso]' => $this->session->avisoTitulo
                            )
                        );

                            //$email = Zend_Registry::get('mail');
                        $email = new Zend_Mail('utf-8');
                        $email->addTo($perdedor->EMAIL, $perdedor->APODO)
                              ->setSubject('Tu oferta ha sido superada')
                              ->setBodyHtml($template->getTemplate());
                        $email->send();
                        $this->log->err("Correo de Perdedor: " . $perdedor->EMAIL);
                    }
                }
                //Fin Envio de correo
            }
            $this->view->volver = $this->session->avisoUrl;
            if (isset($this->session->usuarioAvisoVer)) {
                $this->view->data = $this->session->usuarioAvisoVer;
            } else {
                require_once 'AvisoInfo.php';
                $aviso = new AvisoInfo();
                $this->view->data = $aviso->getInfo($idAviso);
            }
            $frontController = Zend_Controller_Front::getInstance();
            $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
            $this->view->imagen = $fileshare['url'] . '/' . $fileshare['thumbnails'] . '/' . 
                $this->view->data->K_FOTO_PRINCIPAL;
            $this->view->cantidad = ($this->_request->getParam('cantidad')) 
                ? $this->_request->getParam('cantidad') : 1;
            $this->view->oferta = ($this->_request->getParam('oferta')) 
                ? $this->_request->getParam('oferta') : $this->view->data->K_PRECIO_FINAL;
            $this->view->total = $this->_request->getParam('total');
            $this->view->vendedor = $oferta->getVendedor($this->view->data->K_ID_AVISO);
            //Envio de Email
        } else {
            $this->_redirect($this->view->baseUrl());
        } //end else
    } //end function
    /**
     * Historial de Compras
     * @return void
     */
    function historialComprasAction ()
    {
        $compra = new Compras();
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
        $this->session->pageHistory = $_SERVER['REQUEST_URI'];
        $this->view->listarhcTodas = $compra->historialCompras($this->identity->ID_USR, '0', '0', '0');
        $this->view->ruta = $fileshare[url] . '/' . $fileshare['thumbs'] . '/';

        switch ($this->_request->getParam('opc')) {
            case 'categoria':
                if ($this->_request->getParam('codigo') == '0') {
                    $this->view->listarHC = $compra->historialCompras($this->identity->ID_USR, '0', '0', '0');
                    break;
                } else {
                    $this->view->listarHC = $compra->historialCompras(
                        $this->identity->ID_USR, $this->_request->getParam('codigo'), '0', '0'
                    );
                    $this->view->codigo=$this->_request->getParam('codigo');
                    break;
                }
            case 'calificacion':
                $calificacion = $this->_request->getParam('codigo');
                if ($calificacion == 1) {
                    $this->view->listarHC = $compra->historialCompras(
                        $this->identity->ID_USR, '0', '0', $calificacion
                    );
                    $this->view->codclasificacion=$calificacion;
                    break;
                }
                if ($calificacion == 2) {
                    $this->view->listarHC = $compra->historialCompras(
                        $this->identity->ID_USR, '0', '0', $calificacion
                    );
                    $this->view->codclasificacion = $calificacion;
                    break;
                }if ($calificacion == 5) {
                    $this->view->listarHC = $compra->historialCompras(
                        $this->identity->ID_USR, '0', '0', $calificacion
                    );
                    $this->view->codclasificacion=$calificacion;
                    break;
                }
            case 'fecha':
                if ($this->_request->getParam('codigo') == 3) {
                    $this->view->listarHC = $compra->historialCompras($this->identity->ID_USR, '0', '0', '3');
                    $this->view->codfecha=$this->_request->getParam('codigo');
                    break;
                } else {
                    $this->view->listarHC = $compra->historialCompras($this->identity->ID_USR, '0', '0', '4');
                    $this->view->codfecha=$this->_request->getParam('codigo');
                    break;
                }
        }        
    } //end function
    /**
     * Preguntas realizadas
     * @return void
     */
    function preguntasRealizadasAction ()   
    {
//        require_once 'TransaccionCierre.php';
        require_once 'Aviso.php';
        
        $this->view->headTitle('Preguntas realizadas | Kotear.pe');
        
        if (!isset($this->identity)) {
            $this->session->requiereLoginUrl =  $_SERVER['REQUEST_URI'];
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
        } else {
            $mAviso = new Aviso();
            $arrRender = new stdClass();
            
            $arrRender->tab = 'cuenta';
            $arrRender->page = 'compra/_preguntas-realizadas';
            $arrRender->active = $this->getRequest()->getActionName();
            $fileshare = $this->getConfig()->fileshare->toArray();   
            $question = $this->_request->getParam('pregunta', '');
            $page  = $this->_request->getParam('page', '');
            $this->session->pageHistory = $_SERVER['REQUEST_URI'];
            $input['K_ID_USR'] = $this->identity->ID_USR;
            $input['K_PARAM'] = $question;
            $input['K_NUM_REGISTROS'] = $this->_nroPagMin;
            $input['K_NUM_PAGINA'] = $page;
            $data = $mAviso->listarPreguntasRealizadas($input);
            //var_dump($data);exit;
            $paginador = $this->_paginador($data[0]->TOTAL, $page);

            $this->view->filter = $input;
            $this->view->paginador = $paginador;
    //        $this->view->nroRanMin = $this->_nroPagMin;
            $this->view->data = $data;
            $this->view->arrRender = $arrRender;
            $this->view->ruta=$fileshare['url'].'/'.$fileshare['thumbnails'].'/';
        }
    } //end function

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function preguntasontestadasAction ($idUsuario, $tipoPregunta)
    {
        $this->view->listarPC = $this->db->fetchAll(
            "EXEC KO_SP_MENSAJE_SEL ?,?", array($this->identity->ID_USR , 1)
        );
    }
    //end function
    function seguimientoAction ()
    {
        if (!isset($this->identity)) {
            $this->session->requiereLoginUrl =  $this->session->avisoUrl;
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
        }
        
        require_once 'Compras.php';
        
        $this->view->headTitle('Avisos en Seguimiento | Kotear.pe');
        
        $mCompras = new Compras();
        $arrRender = new stdClass();
        
        $arrRender->tab = 'perfil';
        $arrRender->page = 'compra/_seguimiento';
        $arrRender->active = $this->getRequest()->getActionName();
        
        $fileshare = $this->getConfig()->fileshare->toArray();
////        $frontController = Zend_Controller_Front::getInstance();
////        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
////        $opc    = $this->_request->getParam('opc');
        $title = $this->_request->getParam('title');
        $fechaDe = $this->_request->getParam('fechade');
        $order = $this->_request->getParam('order');
        $page  = $this->_request->getParam('page');
//        $this->session->pageHistory = $_SERVER['REQUEST_URI'];
        $input['K_ID_USR'] = $this->identity->ID_USR;
        $input['K_TIT'] = $title;
        $input['K_FILTRO'] = ($order >= 1 && $order <= 4)?$order:0;
        $input['K_FILTRO_FECHA'] = ($fechaDe >= 1 && $fechaDe <= 2)?$fechaDe:0;
        $input['K_NUM_REGISTROS'] = $this->_nroPagMin;
        $input['K_NUM_PAGINA'] = $page;
        $data = $mCompras->listarComprasSeguimiento($input);
//        //var_dump($data);Exit;
        $paginador = $this->_paginador($data[0]->TOTAL, $page);
        
//        $nroPorPage = $beneficios->getItemCountPerPage();
//        $nroPage = $beneficios->getCurrentPageNumber();
//        $nroReg = $beneficios->getCurrentItemCount();
        
        $this->view->filter = $input;
        $this->view->paginador = $paginador;
        $this->view->data = $data;
        $this->view->arrRender = $arrRender;
        $this->view->ruta = $fileshare['url'].'/'.$fileshare['thumbnails'].'/';
        
    }

    /**
     * Acción de eliminar seguimiento
     */
    function eliminarSeguimientoAction ()
    {
        $this->view->headTitle('Avisos en Seguimiento | Kotear.pe');
        if (!isset($this->identity)) {
            $this->session->requiereLoginUrl =  $this->session->avisoUrl;
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
        } else {
            require_once 'Compras.php';
            $compras = new Compras();
            $compras->delComprasSeguimiento(
                $this->_request->getParam('idSeguimiento'), $this->identity->ID_USR
            );
            $this->_redirect($this->view->baseUrl() . '/usuario/compra/seguimiento');
        }
    }

     /**
     * Funcion para el desplazamiento del menu
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function menuAction()
    {

    }
} //end class