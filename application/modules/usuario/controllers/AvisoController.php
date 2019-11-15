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
require_once 'UsuarioPortal.php';
require_once 'Categoria.php';
require_once 'Compras.php';

class Usuario_AvisoController
    extends Devnet_Controller_Action_Default
{
    /**-
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function verAction ()
    {
        //Verificamos si existe contenido adulto en la categoria
        /*
        if (isset ($parametros['categs'])) {
            $c = new Categoria();
            $categoria = $c->getCategoriaId($parametros['categs']);
            if ($categoria[0]->ADULTO == 1 && $this->session->aceptaContenidoAdulto <> 1) {
                $this->_redirect('/adultos');
            }
        }         
         */
        $this->view->headScript()->appendFile('http://assets.pinterest.com/js/pinit.js', 'text/javascript');
        
        require_once 'AvisoInfo.php';
        require_once 'MedioPago.php';
        require_once 'Mensaje.php';
        require_once 'Categoria.php';
        require_once 'Ubigeo.php';
        
        $mUbigeos = new Ubigeo();                
        $mCategoria = new Categoria();
        
        $idTotal = explode('-', $this->_request->getParam('id'));
        $idAviso = $idTotal[0];
        
        if ($idAviso==0) {
            $this->_redirect($this->view->baseUrl());
        }

        $this->session->idAviso = $idAviso;
        $this->session->avisoUrl = $_SERVER['REQUEST_URI'];
        
        $aviso = new AvisoInfo();
        
        // Detalle de Avisos
        $avisoDetalle = $aviso->getInfo($idAviso);
        if ($avisoDetalle->K_ERROR == 1) {            
            $this->getResponse()->setHttpResponseCode(404);
        } else {
            //Aumenta el numero de visitas del Aviso
//            $aviso->visitar($idAviso); 
            $avisoDetalle->K_NUM_VISITAS=$avisoDetalle->K_NUM_VISITAS;
            $this->view->data = $avisoDetalle;
        }
        
        $this->session->usuarioAvisoValidacion = $aviso->getValidacion($idAviso, $this->identity->ID_USR);
        $titulo = $avisoDetalle->K_TITULO;
        
        //$this->view->data = $aviso->getInfo($this->view->infoBasica->K_ID_AVISO);                
        $this->session->avisoTitulo = $avisoDetalle->K_TITULO;
        $this->session->usuarioAvisoVer = $avisoDetalle;
               
        
        //Seccion de Medios de Pago
        $mMedioPago = new MedioPago();
        $this->view->medioPagos = $mMedioPago->getMedioDePagoAviso($idAviso);
                  
        //Seccion de fotos
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
        $foto = new Foto();
        $fotos = $foto->findByAviso($idAviso);
        $this->view->urls = array();
        $this->view->prioridad = array();
                
        if (count($fotos) == 0) {
            $this->view->prioridad['url'] = $fileshare['url']
                                          . '/' . $fileshare['img']
                                          . '/none.gif';
            $this->view->prioridad['zoom'] = $fileshare['url']
                                           . '/' . $fileshare['images']
                                           . '/none.gif';
            $this->view->prioridad['thumbnails'] = $fileshare['url']
                                           . '/' . $fileshare['thumbnails']
                                           . '/none.gif';
        } else {
            $urlImg = $fileshare['url'] . '/' . $fileshare['img'] . '/' . $fotos[0]->ID_USR . '/%s' ;
            $urlImages = $fileshare['url'] . '/' . $fileshare['images'] . '/' . $fotos[0]->ID_USR . '/%s' ;
            $urlThumbnails = $fileshare['url'] . '/' . $fileshare['thumbnails'] . '/' . 
                $fotos[0]->ID_USR . '/%s' . App_Config::getStaticVersion();
            foreach ($fotos as $f) {
                if ($f->PRIO == 1) {
                    $this->view->prioridad['url'] = sprintf($urlImg, $f->NOM);
                    $this->view->prioridad['zoom'] = sprintf($urlImages, $f->NOM);
                    $this->view->prioridad['thumbnails'] = sprintf($urlThumbnails, $f->NOM);
                }
                $this->view->urls[$f->ID_FOTO]['thumbnails'] = sprintf($urlThumbnails, $f->NOM);
                $this->view->urls[$f->ID_FOTO]['img'] = sprintf($urlImg, $f->NOM);
                $this->view->urls[$f->ID_FOTO]['zoom'] = sprintf($urlImages, $f->NOM);
            }
        }
        //Fin Seccion de fotos
        
        //Preguntas y Respuestas
        $mMensaje = new Mensaje();        
        $this->view->preguntasRespuestas = $mMensaje->getPreguntasRespuestasAviso($idAviso);
        //Fin Preguntas y Respuestas

        
        //Seccion de Ventas Activas
        $mUsuarioPortal = new UsuarioPortal();
        $this->view->ventasActivas = $this->getUrlSeo(
            $aviso->getVentasActivasAviso($idAviso, 9), 'TIT', 'URL_SEO'
        );

        for ($i = 0; $i < count($this->view->ventasActivas); $i++) {
            $this->view->ventasActivas[$i]->RUTA_IMAGEN = $fileshare['url']
            . '/' . $fileshare['thumbs']
            . '/' . $this->view->ventasActivas[$i]->RUTA_IMAGEN;
        }
        
        $app = $frontController->getParam('bootstrap')->getOption('app');

        if (isset($this->identity->ID_USR)) {
            require_once 'Seguimiento.php';
            $mSeguimiento = new Seguimiento();
            $this->view->tieneSeguimiento = $mSeguimiento->tieneSeguimiento(
                $this->identity->ID_USR, $idAviso
            );
        }
        else $this->view->tieneSeguimiento = false;
        // Fin de Seccion de Ventas Activas
        
        
        //Menu de categorias
        $arbolcategoria=$aviso->getTreeCategorias($idAviso);
        $arrCate = $arbolcategoria[0];
//        $fintTit = ($arrCate->L4==0)
//            ?$arrCate->L3==0?($arrCate->L2==0?$arrCate->L1_NOM:$arrCate->L2_NOM):$arrCate->L3_NOM
//            :$arrCate->L4_NOM;
        $fintTit = ($arrCate->L1==0)?'Kotear':$arrCate->L1_NOM;
        
        $titulo.=' - '.$fintTit;//headTitle
        
        $this->view->menuCategorias = $arbolcategoria;
        $emailUsuario =  $mUsuarioPortal->find($this->identity->ID_USR);
        $avisoDatos = new Aviso();
        $this->view->motivo = $avisoDatos->listarMotivo();
        $this->view->emailUsuario =  $emailUsuario->EMAIL;
        $this->view->nomUsuario =  $emailUsuario->NOM;
        $this->view->apelUsuario =  $emailUsuario->APEL;
        $ext = explode("-", $emailUsuario->FONO1);
        $this->view->fonoUsuario =  $ext[0] . $ext[1];
        if (!isset($this->identity)) {
            $this->session->requiereLoginUrl = $this->session->avisoUrl;
        }
        
        /*Generacion de Captcha*/
        $frontController = Zend_Controller_Front::getInstance();
        $captchaOp = $frontController->getParam('bootstrap')->getOption('captcha');
        $captchaOp['name'] = 'palabraSeguridad';
        $captchaOp['baseUrl'] = $this->view->baseUrl();
        $captcha = new Devnet_Captcha($captchaOp);
        $captcha->generate();
        $this->session->claveRecuperarCaptchaWord = $captcha->getWord();
        $this->view->captcha = $captcha;
        /*Generacion de Captcha - Fin*/        

        //Url de Aviso        
        $this->view->avisoUrl = $this->session->avisoUrl;
        
        //Cambiar los Datos del vendededor
        if (!($avisoDetalle->K_EST_AVISO == 1 || $avisoDetalle->K_EST_AVISO == 3) 
             || $avisoDetalle->K_EST_USUARIO == 5 || $avisoDetalle->K_FLAG_MODERACION == 1) {
            $categoriaSugerencia= new Categoria();            
            $listSugerencia=$categoriaSugerencia->getSugerenciaCategoria($idAviso);
            $this->view->listSugerencia=$listSugerencia;
            
            for ($i = 0; $i < count($this->view->listSugerencia); $i++) {
            $this->view->listSugerencia[$i]->RUTA_IMAGEN = $fileshare['url']
            . '/' . $fileshare['thumbs']
            . '/' . $this->view->listSugerencia[$i]->RUTA_IMAGEN;
            }
            $avisoDetalle->K_APODO_VENDEDOR = 'xxxxxxx';
            $avisoDetalle->K_EMAIL = 'xxxxxxx';
            $avisoDetalle->K_TELEFONO = 'xxxxxxx';
            $avisoDetalle->K_NOM_UBIGEO = 'xxxxxxx';
        }
        
        $arrCategoria['cat1'] = $mCategoria->getCategoriasActivas(1);
        $arrCategoria['cat2Json'] = Zend_Json::encode($mCategoria->getCategoriasActivas(2));
        $arrCategoria['cat3Json'] = Zend_Json::encode($mCategoria->getCategoriasActivas(3));
        
        $urlPagina = $this->view->baseUrl().'/aviso/'.$avisoDetalle->K_ID_AVISO.'-'.$avisoDetalle->K_URL;
        
        $this->defHeadMeta = array(
            'data'=>array(
                'title' => $titulo,
                'description'=> $fintTit,
                'ogUrl'=> $urlPagina,
                'ogImg'=> $this->view->prioridad['thumbnails']
            )
        );
        
        $this->view->urlPagina = $urlPagina;
        $this->view->arrCategoria = $arrCategoria;
        $this->view->arrCiudadesActivas = $mUbigeos->getListCiudadesActivas();
        $this->view->data=$avisoDetalle;
    }

    private function _enviarDatosVendedor($idAviso)
    {
        $compra = new Compras();
        $aviso = new Aviso();
        $aviso = $aviso->getDatos($idAviso);
        $aviso = $aviso[0];
        $avisoUrl =  $this->view->BaseUrl() . '/aviso/' . $aviso->ID_AVISO . '-' . $aviso->URL;
        $mUsuarioPortal = new UsuarioPortal();
        $usuarioVendedor = $mUsuarioPortal->find($aviso->ID_USR);
        $usuarioComprador =  $mUsuarioPortal->find($this->identity->ID_USR);
        $envio = $compra->enviarEmail($idAviso);
        // -------------------------  ENVIO DATOS VENDEDOR AL COMPRADOR ------------------------------
        $templateVendedor = new Devnet_TemplateLoad('EnvContactoVend');
        $templateVendedor->replace(
            array(
                // AVISO
                '[COMPRADOR]' => $usuarioComprador->APODO,
                '[TITULO]' => $aviso->TIT,
                '[URL_AVISO]' => (string)$avisoUrl,
                '[MONEDA]' => $this->session->usuarioAvisoVer->K_SIMB_MONEDA,
                '[PRECIO]' => number_format($this->session->usuarioAvisoVer->K_PRECIO_FINAL, 2, '.', ','),
                //COMPRADOR
                '[APODO]' => $usuarioVendedor->APODO,
                '[NOMBRE]' => $usuarioVendedor->NOM . ' '. $usuarioVendedor->APEL,
                '[TELEFONO]' =>  $usuarioVendedor->FONO1 . ' ' . $usuarioVendedor->FONO2,
                '[EMAIL]' => $usuarioVendedor->EMAIL,
                '[UBICACION]' => $usuarioVendedor->LOCALIDAD
            )
        );
        $correo = Zend_Registry::get('mail');
        $correo = new Zend_Mail('utf-8');
        $correo->addTo($usuarioComprador->EMAIL, $usuarioComprador->APODO)
                ->clearSubject()
                ->setSubject('Datos del Vendedor')
                ->setBodyHtml($templateVendedor->getTemplate());
       try {
           $correo->send();
           return 1;
       } catch (Exception $exc) {
           return $e->getMessage();
       }
    }


    function denunciarAction()
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isXMLHttpRequest()) {
            $avisoDatos = new Aviso();
            $mUsuarioPortal = new UsuarioPortal();
            $comentario = $this->_request->getParam('comentario');
            $email = $this->_request->getParam('email');
            $denunciante = $this->_request->getParam('nombres')." ".$this->_request->getParam('apellidos');
            $idUsuario = $this->identity->ID_USR;//$this->_request->getParam('idUsuario');
            $idAviso = $this->_request->getParam('idAviso');
            $motivo =  $this->_request->getParam('slcmotivo');
            $telefono = $this->_request->getParam('telefono');
            $usuarioPortal = $mUsuarioPortal->find($this->identity->ID_USR);           

            $retornoMotivo = $avisoDatos->insertarMotivo(
                $idUsuario, $comentario, $usuarioPortal->APODO, $motivo, '5', $idAviso
            );
            if ($retornoMotivo->K_ERROR == 0) {
                try {
                    $compra = new Compras();
                    $envio = $compra->enviarEmail($idAviso);
                    /*====================================================================================*/
                    $template = new Devnet_TemplateLoad('denunciar');
                    $template->replace(
                        array(
                            '[USUARIO]' =>  $this->view->escape($denunciante),
                            '[AVISO]' => $this->view->escape($envio[0]->TIT),
                            '[CODIGO]' => $idAviso,
                        )
                    );

                    $correo = Zend_Registry::get('mail');
                    $correoAdmin = new Zend_Mail('utf-8');
                    $correo->addTo($email, $denunciante)
                           ->clearSubject()
                           ->setSubject('Recibimos tu Denuncia')
                           ->setBodyHtml($template->getTemplate());
                    $correo->send();
                    
                } catch (Exception $exc) {
//                    echo $exc->getMessage();exit();
                }
                
                try {
                    /*====================================================================================*/
                    //Obtenemos los datos de configuracion para el envio de correo
                    $frontController = Zend_Controller_Front::getInstance();
                    $configadmin = $frontController->getParam('bootstrap')->getOption('configadmin');
                    $templateAdmin = new Devnet_TemplateLoad('denuncia_admin');
                    $templateAdmin->replace(
                        array(
                             '[IDUSR]' =>  $idUsuario,
                             '[USUARIO]' =>  $this->view->escape($denunciante),
                             '[AVISO]' => $this->view->escape($envio[0]->TIT),
                             '[IDAVISO]' => $idAviso,
                        )
                    );
                    
                    $correoAdmin->addTo($configadmin['administrator'])
                                ->clearSubject()
                                ->setSubject('Denuncia del Aviso'. $this->view->escape($envio[0]->TIT))
                                ->setBodyHtml($templateAdmin->getTemplate());                
                    $correoAdmin->send();
                } catch (Exception $exc) {
//                    echo $exc->getMessage();exit();
                }
                $this->json(array('code' => 0, 'msg' => 'Se registraron los Datos Correctamente'));
            } else {
                $this->json(array('code' => 1, 'msg' => 'No se registraron los Datos'));
            }
         }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    /*
    function verVentaSubastaAction ()
    {
        //venta subasta
        $this->view->data = $this->session->usuarioAvisoVer;
        $this->view->idAviso = $this->session->idAviso;
        $this->view->validacion = $this->session->usuarioAvisoValidacion;
        $this->view->avisoUrl=$this->session->avisoUrl;
    }
    */
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function verImpresionAction ()
    {
        require_once 'MedioPago.php';
        require_once 'AvisoInfo.php';
        require_once 'Foto.php';
        $idAviso = $this->_request->getParam('id');
        $this->view->headTitle('Vista de Impresion | Kotear.pe');
        $this->_helper->layout->setLayout('print');
        
        //Datos del aviso
        $aviso = new AvisoInfo();
        $avisoDetalle = $aviso->getInfo($idAviso);
        
        //Medio de Pago
        $mMedioPago = new MedioPago();
        $this->view->medioPagos = $mMedioPago->getMedioDePagoAviso($idAviso);
        $foto = new Foto();
        $fotos = $foto->findByAvisoPrioridad($idAviso);
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
        $this->view->img = $fileshare['url'] . '/' . $fileshare['thumbs'] . '/' . $fotos->ID_USR . '/' . 
                $fotos->NOM;
        
        //Cambiar los Datos del vendededor
        if ($avisoDetalle->K_EST_AVISO == 5 || $avisoDetalle->K_EST_USUARIO == 5 ||
            $avisoDetalle->K_FLAG_MODERACION == 1) {    
            $avisoDetalle->K_APODO_VENDEDOR = 'xxxxxxx';
            $avisoDetalle->K_EMAIL = 'xxxxxxx';
            $avisoDetalle->K_TELEFONO = 'xxxxxxx';
            $avisoDetalle->K_NOM_UBIGEO = 'xxxxxxx';
        }        
        $this->view->data = $avisoDetalle;
    }

    /**
     * Visualiza en formato impresion avisos seleccionados de un listado
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function verImpresionListadoAction ($avisos)
    {
        require_once 'MedioPago.php';
        require_once 'Mensaje.php';
        require_once 'AvisoInfo.php';
        require_once 'Foto.php';
        $idAviso = $this->_request->getParam('id');
        $this->view->headTitle('Vista de Impresion | Kotear.pe');
        $this->_helper->layout->setLayout('print');
        $aviso = new AvisoInfo();
        $this->view->data = $aviso->getInfo($idAviso, $this->identity->ID_USR);
        //Medio de Pago
        $mMedioPago = new MedioPago();
        $this->view->medioPagos = $mMedioPago->getMedioDePagoAviso($idAviso);
        //Preguntas y Respuestas
        $mMensaje = new Mensaje();
        $this->view->preguntasRespuestas = $mMensaje->getPreguntasRespuestasAviso($idAviso);
        $mFoto = new Foto();
        $fotos = $mFoto->findByAvisoPrioridad($idAviso);
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
        $this->view->img = $fileshare['url'] . '/' . $fileshare['images'] . '/' . $fotos->ID_USR
            . '/' . $fotos->NOM;
    }

    /**
     * Envio de correo por lightbox
     * @return void
     */
    function envioCorreoAmigoAction() 
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isXMLHttpRequest()) {
            if (!isset($this->identity->ID_USR)) {
                $this->session->requiereLoginUrl = $this->session->avisoUrl;
                $this->json(
                    array(
                    'code' => 1,
                    'msg' => 'Ud. debe Iniciar sessión para poder enviar correos a un amigo. '
                    )
                );
            }
            require_once('AvisoInfo.php');
            try{
                $paramDe = $this->_request->getParam('de');
                $para = $this->_request->getParam('para');
                $mensaje =  $this->_request->getParam('mensaje');
                $copia = $this->_request->getParam('copia');
                $idAviso = $this->_request->getParam('id');

                $mAvisoInfo = new AvisoInfo();
                $data = $mAvisoInfo->getInfo($idAviso, $this->identity->ID_USR);

                $mUsuarioPortal = new UsuarioPortal();
                $datosUsuario =  $mUsuarioPortal->find($this->identity->ID_USR);

                $frontController = Zend_Controller_Front::getInstance();
                $app = $frontController->getParam('bootstrap')->getOption('app');
                $template = new Devnet_TemplateLoad('aviso_mensaje_amigo');
                $template->replace(
                    array(
                        '[nombre]' => $datosUsuario->NOM,
                        '[mensaje]' => $this->view->escape($mensaje),
                        '[aviso]' => $data->K_TITULO,
                        //'[urlaviso]' => $fileshare['app'] . '/aviso/' . $idAviso . '-' . 
                        //      $this->view->utils->convertSEO($data->K_TITULO),
                        '[urlaviso]' => $app['url'] . $this->session->avisoUrl,
                        '[moneda]' => $data->K_SIMB_MONEDA,
                        '[precio]' => ($data->K_TIPO_VENTA == 'SUBASTA')
                                            ? $data->K_OFERTA_MINIMA
                                            : $data->K_PRECIO_FINAL,
                        '[fechafinaliza]' => $data->K_FECHA_CADUCIDAD
                    )
                );
                $correo = Zend_Registry::get('mail');
                $correo->addTo($para, $para)
                    ->setSubject('Kotear.pe - Un Amigo te envia un mail.')
                    ->setBodyHtml($template->getTemplate());
                if ($copia == 1) $correo->addTo($paramDe, $paramDe);
                $correo->send();
                $this->json(array('code' => 0 , 'msg' => 'Se ha enviado un email a su amigo.'));
            } catch(Exception $e){
                $this->json(
                    array(
                        'code' => 2 , 
                        'msg' => 'Error en en servidor, por favor intente más tarde.'
                    )
                );
            }
        }
    }

    /**
     * Accion de recibir alerta del aviso
     * @return void
     */
    function recibirAlertaAction()
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isXMLHttpRequest()) {
            if (!isset($this->identity->ID_USR)) {
                $this->session->requiereLoginUrl = $this->session->avisoUrl;
                $this->json(
                    array(
                        'code' => 1, 
                        'msg' => 'Ud. debe Iniciar sessión para colocar el aviso en seguimiento.'
                    )
                );
            }
            require_once 'Seguimiento.php';
            $idAviso = $this->_request->getParam('id');
            $seguimiento = new Seguimiento();
            $result = $seguimiento->iniciarSeguimiento($this->identity->ID_USR, $idAviso);
            if ($result->K_ERROR == 0) {
                $this->json(array('code' => 0, 'msg' => 'Ahora Ud. ha colocado este aviso en seguimiento.'));
            } else {
                $this->json(array('code' => 2, 'msg' => $result->K_MSG));
            }
        }
    }

    /**
     * Accion de recibir alerta del aviso
     * @return void
     */
    function cancelarAlertaAction()
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isXMLHttpRequest()) {
            if (!isset($this->identity->ID_USR)) {
                $this->session->requiereLoginUrl = $this->session->avisoUrl;
                $this->json(
                    array(
                        'code' => 1,
                        'msg' => 'Ud. debe Iniciar sessión para colocar el aviso en seguimiento.'
                    )   
                );
            }
            require_once 'Compras.php';
            $compras = new Compras();
            $idAviso = $this->_request->getParam('id');
            $compras->delComprasSeguimiento($idAviso, $this->identity->ID_USR);
            $this->json(array('code' => 0, 'msg' => 'Ahora Ud. ha cancelado este aviso en seguimiento.'));
        }
    }
   

    /**
     * Permite activar un aviso desactivado por el usuario
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function activarAvisoAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            $aviso= new Aviso();
            $datosAviso=$aviso->existAviso($this->_request->getParam('cod'), $this->identity->ID_USR);
            if (count($datosAviso) > 0) {
                if ($datosAviso->EST == 16) {
                    $resAviso=$aviso->activarAviso($this->_request->getParam('cod'), $this->identity->ID_USR);
                    $code=0;
                    //$msg='El aviso activo correctamente';
                    $msg = $resAviso->K_MSG;
                    //$data = $aviso->existAviso($this->_request->getParam('cod'),$this->identity->ID_USR);
                    $data = $datosAviso;
                    //$this->_redirect($this->view->baseUrl().
                    //  '/usuario/venta/activas/opc/categoria/codigo/0');
                } else {
                 $code = 1;
                 $msg='No se puede cambiar el estado de este aviso';
                 $data=$datosAviso;
                }
            } else {
                $code=1;
                $msg='El Aviso no existe';
            }

            if ($code == 0) {
                $this->json(array('code' => $code, 'msg' => $msg));
                $this->_redirect($this->view->baseUrl() . '/usuario/venta/activas/opc/categoria/codigo/0');
            } else {
                $this->json(array('code' => $code, 'msg' => $msg));
            }
        }
    }

    /**
     * Permite retirar destaques de un aviso pendiente de pago
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function retirarDestaquesAvisoAction()
    {
        // Verificación de inicializacion de sesion .
        /*
        if (!isset($this->identity->ID_USR)) {
            $this->session->requiereLoginUrl = $_SERVER['REQUEST_URI'];
            $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
            }
        */
        if ($this->_request->isXMLHttpRequest()) {        
            $aviso= new Aviso();
            $datosAviso = $aviso->existAviso($this->_request->getParam('cod'), $this->identity->ID_USR);
            if (count($datosAviso) > 0) {
                $resAviso = $aviso->retirarDestaquesAviso(
                    $this->_request->getParam('cod'), $this->identity->ID_USR
                );
                $code = 0;
                $msg = $resAviso->K_MSG;
                //$data = $aviso->existAviso($this->_request->getParam('cod'),$this->identity->ID_USR);
                $data = $datosAviso;
                //$this->_redirect($this->view->baseUrl().'/usuario/venta/activas/opc/categoria/codigo/0');
            } else {
                $code = 1;
                $msg = 'El Aviso no existe';
            }
            if ($code == 0) {
                $this->json(array('code' => $code, 'msg' => $msg));
                $this->_redirect($this->view->baseUrl() . '/usuario/venta/inactivas/opc/categoria/codigo/0');
            } else {
                $this->json(array('code' => $code, 'msg' => $msg));
            }
        }
    }

    public function desactivarAvisoAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            $aviso= new Aviso();
            $datosAviso = $aviso->existAviso($this->_request->getParam('cod'), $this->identity->ID_USR);
            if (count($datosAviso) > 0) {
                if ($datosAviso->EST == 1 || $datosAviso->EST == 2) {
                 $result = $aviso->desactivarAviso($this->_request->getParam('cod'), $this->identity->ID_USR);
                 $code = $result->K_ERROR;
                 $msg = $result->K_MSG; //'El aviso ha sido eliminado satisfactoriamente';                 
                } else {
                 $code = 1;
                 $msg = 'No se puede cambiar el estado de este aviso';
                 $data=$datosAviso;
                }
            } else {
                $code = 1;
                $msg = 'No existe el aviso';
            }

            if ($code == 0) {
                $this->json(array('code' => $code, 'msg' => $msg));
                $this->_redirect($this->view->baseUrl() . '/usuario/venta/inactivas/estado/16');
            } else {
                $this->json(array('code' => $code, 'msg' => $msg));
            }
            //$this->view->response = array('code' => $code, 'msg' => $msg, 'data' => $data);
        }
    }

    /**
     * Republicacion de aviso
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     **/
    function republicarAction ()
    {
        //if(isset($this->_request->getParams())) {
            try{
                $idAviso = $this->_request->getParam('cod');
                if (empty($idAviso )) throw new Exception('Error: seleccione aviso.');
                require_once 'Republicacion.php';
                $republicacion = new Republicacion();
                $res = $republicacion->masiva($idAviso, $this->identity->ID_USR);
                $this->_redirect(
                    $this->view->baseUrl() . '/usuario/venta/inactivas/error/' . $res->K_ERROR . '/msg/' . 
                    $res->K_MSG
                );
            } catch(Exception $e) {
                $this->_redirect(
                    $this->view->baseUrl() . '/usuario/venta/inactivas/error/1/msg/' . $e->getMessage()
                );
            }
        //}
    }

    /**
     * Actualizar Contactos del aviso
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     **/
    /*
    public function actualizarContactosAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $idAviso = $this->getRequest()->getParam('data', NULL);
            if (!empty($idAviso)) {
                if ($idAviso == $this->session->idAviso) {
                    require_once 'AvisoInfo.php';
                    $aviso = new AvisoInfo();
                    $cantContacto = $aviso->actualizarContactos($idAviso);
                    $code = $cantContacto->CONTACTOS;                    
                    
                    if ($this->identity != null) {
                        $var = $this->_enviarDatosVendedor($idAviso);
                        if ($var != 1) {
                            $this->log->err('No se enviaron los mensajes');
                        }
                    }
                    $this->json(array('code' => $code));
                }
            }
        }
    }
     */
     /**
     * Ver Datos del Vendedor
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     **/
    /*
    public function verDatosVendedorAction()
    {
        //$this->view->headMeta()->appendName("robots", "noindex, nofollow");
        foreach ($this->getRequest()->getParams() as $key => $value) {
            $this->view->assign($key, $value);
        }
        $this->render('datos-contacto');
    }     
     */
    
}