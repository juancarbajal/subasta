<?php

/**
 * Permite gestionar la publicacion de avisos
 *
 * Gestion de avisos para su publicacion en 4 etapas. Registro, edicion, eliminacion.
 *
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer el archivo LICENSE
 * @version    1.0
 * @since      Archivo disponible desde su version 1.0
 */
require_once 'Aviso.php';
require_once 'Ubigeo.php';
require_once 'AvisoInfo.php';
require_once 'UsuarioPortal.php';
require_once 'Categoria.php';

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
class Usuario_PublicacionController 
    extends Devnet_Controller_Action_Default
{

    /**
     * Verificación de inicializacion de sesion .
     * @return void
     */
    function init()
    {
        parent::init();
        
        if ($this->getRequest()->getActionName() == 'registro-destaque') {
            if (!isset($this->identity->ID_USR)) {
                $this->session->requiereLoginUrl = $this->view->baseUrl() . 
                        '/usuario/publicacion/registro-destaque';
            }
        } elseif ($this->getRequest()->getActionName() != 'dar-permisos' && 
            $this->getRequest()->getActionName() != 'box-tipo-destaque') {
            if (!isset($this->identity->ID_USR) && $this->getRequest()->getActionName() != 'lista-ubigeo') {
                $this->session->requiereLoginUrl = $_SERVER['REQUEST_URI'];
                $this->_redirect($this->view->baseUrl() . '/usuario/acceso');
            }
        }
    }
    
    /**
     * Ander
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     * */
    function imageUploadAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $result = new stdClass();
        
//        $frontController = Zend_Controller_Front::getInstance();
//        $imd = $frontController->getParam('bootstrap')->getOption('fileshare');
        $imd = $this->getConfig()->fileshare->toArray();
//        var_dump($imd);exit;
        $name = '';
        $result->code = 0;
        $result->msg = '';
        if ($this->_request->isPost()) {
            try {
                $imageManagement = new Devnet_ImageManagement(
                    $imd['host'], $imd['username'], $imd['password'], $imd['thumbs'],
                    $imd['thumbnails'], $imd['image'], $imd['original'], $imd['img']
                );
                $file = $_FILES["photo_product"];
//                var_dump($this->session->img);exit;
                if (!($imageManagement->openFtp())) {
                    throw new Exception('Error coneccion');
                } elseif (!is_uploaded_file($file['tmp_name'])) {
                    throw new Exception('Error uploaded');
                } 
                
                //-->>verificar y/o crea las carpetas
                @$imageManagement->newDirectory(
                    array($imd['img'], $imd['thumbs'], $imd['thumbnails'], $imd['images'], $imd['original']),
                    $this->identity->ID_USR
                );
//                @$imageManagement->closeFtp();
                
//                elseif(!in_array(0, $this->session->img)){
//                    throw new Exception('Error maximo de imagenes');
//                }
                $arrExtension = explode('.', strtolower($file['name']));
                $num = count($arrExtension) - 1;
                $extension = $arrExtension[$num];
                if (!($extension == 'jpg' || $extension == 'gif' || $extension == 'png')) {
                    throw new Exception('Archivo Invalido');
                } elseif ($file['size'] > 1048576) {
                    throw new Exception('Archivo supero el tamaño requerido');
                } else {
                    $name = time();
                    $nameFull = $name . '.' . $extension;
                    $ruta = $imd['fileFoto'].'/'.$imd['original'].'/'.$this->identity->ID_USR.'/'.$nameFull;
//                    $ruta = $imd['fileTemp'] . $nameFull;
                    $remoto = $file['tmp_name'];
                    if (!$imageManagement->upImage($ruta, $remoto)) {
                        throw new Exception('Error ftp');
                    }
                }
                
                $this->session->img[$name] = $nameFull;
//                //Registro en Sesion
//                if (!isset($this->session->img)) $this->session->img = array(0, 0, 0, 0, 0, 0);
//                foreach ($this->session->img as $index => $valor) {
//                    if ($valor === 0) {
//                        $this->session->img[$index] = $nameFull;
//                        break;
//                    }
//                }
                //Fin Registro en Sesion
                $result->status = 1;
                $result->code = 1;
                $result->new_image = $nameFull;
                $result->url = $imd['url'].'/'.$imd['original'].'/'.$this->identity->ID_USR.'/'.$nameFull;
//                $result->url = $imd['url'] . '/temp/' . $nameFull;
                $result->id = $name;//$name;
                $result->url_del = $this->view->baseUrl() . '/usuario/publicacion/image-delete/image/'.$name;
            } catch (Exception $e) {
                $result->status = 0;
                $result->msg = $e->getMessage();
            }
        } else {
            $result->status = 0;
            $result->msg = 'No se ha enviado el archivo por el medio indicado.';
        }
        echo json_encode($result);
        die();
    }
    
    /** 
     * Ander
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function imageDeleteAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
//        if ($this->_request->isPost()) {
            $result = new stdClass();
            try {
//                $frontController = Zend_Controller_Front::getInstance();
//                $imd = $frontController->getParam('bootstrap')->getOption('fileshare');
                $imd = $this->getConfig()->fileshare->toArray();
//                $imageManagement = new Devnet_ImageManagement($imd['host'], $imd['username'], 
//                  $imd['password']);
//                if (!($imageManagement->openFtp())){
//                    throw new Exception('Error coneccion');
//                }
                
                foreach ($this->session->img as $index => $valor) {
                    if ($index == $this->_request->getParam('idfoto')) {
                        if ($this->session->imgtm[$index] !== 0) {
                            $this->session->imgtm[$index] = 0;
                        }
//                        $this->session->img[$index] = 0;
                        $ruta = $imd['fileFoto'].'/'.$imd['original'].'/'.$this->identity->ID_USR.'/'.$valor;
//                        $ruta = $imd['fileTemp'] . $valor;
//                        $imageManagement->delete($ruta);
                        unset($this->session->img[$index]);
//                        $this->reordenarImagen();
                        break;
                    }
                }
//                $imageManagement->closeFtp();
                $result->code = 1;
            } catch (Exception $e) {
                $result->code = 0;
                $result->msg = $e->getMessage();
            }
            echo json_encode($result);
            die();
//        }
    }
    
    /** 
     * Ander
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    private function imageResize($arrImg, $nomAviso, $html)
    {
        if (is_array($arrImg)) {
            try {
                $imd = $this->getConfig()->fileshare->toArray();

                foreach ($arrImg as $index => $valor) {
                    $nameFull = $valor;
                    //$nameFull = $this->session->img[$valor];
//                    $ruta = $imd['fileFoto'].$imd['original'].'/'.$this->identity->ID_USR. '/'.$nameFull;
//                    $rutaNueva = $imd['fileFoto'].'img/'.$this->identity->ID_USR. '/'.$nomAviso.'-'.
//                      $nameFull;
                    
//                    $img = new Devnet_Zendimage();
//                    $img->loadImage($ruta);
//                    if ($img->width > $img->height) $img->resize('100', 'width');
//                    else $img->resize('100', 'height');
//                    $img->save($rutaNueva);
        
////                    echo $nomAviso.' '.$nameFull.;exit;
                    $url = sprintf(
                        '%s/TransformImage.php?nombreaviso=%s&nomfichero=%s&fileuser=%s',
                        'http://' . $imd['host'],
                        $nomAviso,
                        $nameFull,
                        $this->identity->ID_USR
                    );
                    fopen($url, 'r');
////                    if(!$imageManagement->rename($ruta, $rutaNueva)){
////                        throw new Exception('Error rename ');
////                    }
                    $html = str_replace($nameFull, $nomAviso . $nameFull, $html);
                }
                
                if (!($this->session->imgtm[0] === 0)) {
                    if (is_array($this->session->imgtm)) {
                        
                        $imageManagement = new Devnet_ImageManagement(
                            $imd['host'], $imd['username'], $imd['password']
                        );
                        
                        foreach ($this->session->imgtm as $index => $valor) :
                            if ($valor != 0) {
                                // aperturo la conexion ftp
                                $imageManagement->openFtp();
//                                $root = '/home/apkotear/public/';
                                foreach (array('original', 'thumbs', 'thumbnails', 'img', 'images') 
                                    as $index) {
                                    $ruta = $imd['fileFoto'] . '/' . $imd[$index] .'/'. 
                                        $this->identity->ID_USR . '/' . $valor;
                                    $imageManagement->delete($ruta);
                                }
                                @$imageManagement->closeFtp;
                            }
                        endforeach;
                    }
                }
//                    Zend_Registry::get('logCron')->write(array(
//                    'mensaje' => "PROCESO SATISFACTORIO ",
//                    'prioridad' => Zend_Log::WARN,
//                    'metodo' => 'Redimension de imagenes'));
                
                    
                
//                return true;
            } catch (Exception $e) {
//                $result->msg = $e->getMessage();
                
                Zend_Registry::get('logCron')->write(
                    array(
                        'mensaje' => "ERROR ".$e->getMessage(),
                        'prioridad' => Zend_Log::WARN,
                        'metodo' => 'Redimension de imagenes'
                    )
                );
                
                //echo $e->getMessage();
//                return false;
            }
        } else {
//            return false;
        }
        return $html;
//        die();
    }
    
    /**
     * Ander
     * Paso 1
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function registroDestaqueAction()
    {
        $this->_helper->layout->setLayout('publicacion');
        
        require_once APPLICATION_PATH . '/modules/usuario/forms/formPublicacion.php';
        require_once 'Publicacion.php';
        require_once 'Destaque.php';
        
        $mAviso = new Aviso();
        $mDestaque = new Destaque();
        $mPublicacion = new Publicacion();
        $form   = new Usuario_formPublicacion();
        
//        $form->addToken();
        
        $idAviso = $this->_request->getParam('cod', '');
        
        $publicacionEstado = 1;
        $sesionPasoOne = "";
        
        $arrEditAviso = new stdClass();//$arrEditAviso['idDestaque'] = 0;
        $arrPasos = new stdClass();
        $arrPasos->paso1 = true;
        $arrPasos->paso2 = true;
        $arrPasos->paso3 = true;
        $arrPasos->paso4 = true;
        $arrPasos->pasoSgte = 2;
        try{
            if (!empty($idAviso)) {
                //$arrAviso = $mAviso->getDatosAvisoEditar($idAviso);
                $arrEditAviso = $mPublicacion->getPublicacion($idAviso, $this->identity->ID_USR);
                //var_dump($arrAviso);exit;
                if ($arrEditAviso->K_EST == 1) {
                    //Destacar
                    $publicacionEstado = 3;
                    $arrPasos->paso2 = false;
                    $arrPasos->pasoSgte = 3;
                    $arrEditAviso->idDestaque = $arrEditAviso->K_ID_DESTAQUE;
                    $form->addIdAviso($idAviso);
                } elseif ($arrEditAviso->K_EST == 5) {
                    //republicar
                    $publicacionEstado =  4;
                    $arrPasos->paso2 = false;
                    $arrPasos->pasoSgte = 3;
                    $form->addIdAviso($idAviso);
                } //else throw new Exception('Error cod..' . $arrEditAviso->K_EST);   
            }
            $arrDestaquesActivas = $mDestaque->getDestaquesActivas();
            //var_dump($arrDestaquesActivas);exit;
            $arrEditAviso->idAviso = $idAviso;
            $this->view->arrDestaquesActivas = $arrDestaquesActivas;
            $this->view->publicacionEstado = $publicacionEstado;
            $this->view->arrPaso = $arrPasos;
            $this->view->arrEditAviso = $arrEditAviso;
            $this->view->form = $form;
            if (isset($this->session->sesionPaso1)) {
                $sesionPasoOne = ($this->session->sesionPaso1 == 1)
                                    ?'Tiempo de espera agotado!.':'Datos incorrectos!.';
            }
            $this->view->sesionPaso1 = $sesionPasoOne;
            unset($this->session->sesionPaso1);
            
        } catch (Exception $exc) {
            //echo $exc->getMessage();exit;
            //$this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-destaque/error/1');
        }
        
    }

    /**
     * Ander
     * Paso 2 Permite registrar una categoria
     * El registro de categoria solo se da para avisos nuevos, no se puede editar la categoria
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function registroCategoriaAction()
    {
        $this->_helper->layout->setLayout('publicacion');
        
        require_once APPLICATION_PATH . '/modules/usuario/forms/formPublicacion.php';
        
        $categoria = new Categoria();
        $form      = new Usuario_formPublicacion();
        $valToken = false;
        
        $form->addDestaque();
        
        try{
            $allParams  = $this->getRequest()->getPost();
            $idDestaque = $allParams['destaqueId'];
//            $idDestaque = $this->getRequest()->getPost('destaqueId','');
//            $idDestaque = $this->_request->getParam('destaqueId','');
            if (!$form->isValid($allParams)) {
//                $valToken = $form->atoken->getErrors();
//                $valToken = !empty($valToken);
                throw new Exception('Error Token falso');
            }
            
            $form->addToken();
            
            $arrDestaque = $this->_validarDestaque($idDestaque);
            if (!$arrDestaque) throw new Exception('Error Datos Destaque');
            
            $arrCategoria['cat1'] = $categoria->getCategorias(1);
            $arrCategoria['cat2Json'] = Zend_Json::encode($categoria->getCategorias(2));
            $arrCategoria['cat3Json'] = Zend_Json::encode($categoria->getCategorias(3));
            $arrCategoria['cat4Json'] = Zend_Json::encode($categoria->getCategorias(4));        
        } catch (Exception $exc) {
            //echo $exc->getMessage();exit;
            $this->session->sesionPaso1 = ($valToken)?1:2;
            $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-destaque/error/1');
        }
        $this->view->publicacionEstado = 1;
        $this->view->arrCategoria = $arrCategoria;
        $this->view->form = $form;
        //->Puglin Dax
        $this->view->daxTagOpc = $idDestaque;
    }
    
    /**
     * Ander
     * Paso 3 - Permite registrar los datos del aviso
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function registroDatosAction()
    {
        $this->_helper->layout->setLayout('publicacion');
        
        require_once 'Agrupador.php';
        require_once 'Publicacion.php';
        
        require_once APPLICATION_PATH . '/modules/usuario/forms/formPublicacion.php';
        
        $mUbigeo = new Ubigeo();
        $mAviso = new Aviso();
        $mPublicacion = new Publicacion();
        $mAgrupador = new Agrupador();
        $form = new Usuario_formPublicacion();
        $isPostId = true;
        
        $arrPasos = new stdClass();
        $arrPasos->paso1 = true;
        $arrPasos->paso2 = true;
        $arrPasos->paso3 = true;
        $arrPasos->paso4 = true;
        
        $imd = $this->getConfig()->fileshare->toArray();
        $publicacionEstado = 1;
        $valToken = false;
        
        $form->addToken();
        $form->addDestaque();
        $form->addCategoriasHidden();        
        
        $arrImg = array();
        
        //->Sesion para elimanar img
        $this->session->imgtm = array();
        
        try{
            $allParams  = $this->getRequest()->getPost();
            //$idAviso    = $this->getRequest()->getPost('idAviso','');
            $idAviso    = $allParams['idAviso'];
            $destaqueId = $allParams['destaqueId'];
            
            $cod = $this->_request->getParam('cod', '');
            
            if (empty($idAviso)) {
                $idAviso = $cod;
                $isPostId = false;
            }
            
            if (!empty($idAviso)) {
                $form->addRegistroDatos();
//                if(!empty($cod)) $form->disabledEditar();
                if (!empty($idAviso)) {
                    
                    $form->addIdAviso($idAviso);
                    
                    $arrEditAviso = $mPublicacion->getPublicacion($idAviso, $this->identity->ID_USR);
                    if ($arrEditAviso->K_EST == 1 && !$isPostId) {//->Actualiza
                        $form->disabledEditar();
                        $publicacionEstado = 2;
                        $arrPasos->paso1 = false;
                        $arrPasos->paso2 = false;
                    } elseif ($arrEditAviso->K_ID_DESTAQUE < $destaqueId && $arrEditAviso->K_EST == 1 
                            && $isPostId) {
                        //Destaque
                        $form->disabledEditar();
                        $publicacionEstado = 3;
                        $arrPasos->paso2 = false;
                        $arrEditAviso->idDestaque = $arrEditAviso->K_ID_DESTAQUE;
                        if ($destaqueId==9 || $destaqueId == 10)
                            $form->addPrintedText();
                    } elseif ($arrEditAviso->K_EST == 5  && $isPostId) {
                        //republicar
                        $publicacionEstado =  4;
                        $arrPasos->paso2 = false;
                        if ($destaqueId==9 || $destaqueId == 10) $form->addPrintedText();
                    } else throw new Exception('Error Edicion..');
                    
                    
                    $allParams['product_type']          = $arrEditAviso->K_ID_TIPO_PRODUCTO;
                    $allParams['announcement_title']    = $arrEditAviso->K_TITULO;
                    $allParams['price']                 = $arrEditAviso->K_PRECIO;
                    $allParams['informacion_adicional'] = $arrEditAviso->K_HTML;
                    $allParams['currency']              = $arrEditAviso->K_ID_TIPO_MONEDA;
                    
                    $allParams['destaqueId']            = ($isPostId)
                                                            ?$destaqueId:$arrEditAviso->K_ID_DESTAQUE;
                    
                    $allParams['categoriaId1']          = $arrEditAviso->L1;
                    $allParams['categoriaId2']          = $arrEditAviso->L2;
                    $allParams['categoriaId3']          = $arrEditAviso->L3;
                    $allParams['categoriaId4']          = $arrEditAviso->L4;
                    $allParams['categoriaText1']        = $arrEditAviso->L1_NOM;
                    $allParams['categoriaText2']        = $arrEditAviso->L2_NOM;
                    $allParams['categoriaText3']        = $arrEditAviso->L3_NOM;
                    $allParams['categoriaText4']        = $arrEditAviso->L4_NOM;
                    
                    $allParams['payment_method']        = explode(",", $arrEditAviso->K_MEDIO_PAGOS);
                    //$arrImg                             = explode(",", $arrEditAviso->K_IMAGENES);
                    //$allParams['ids_hidden_ad']         = $arrEditAviso->K_IMAGENES;
                    $arrImgAll                          = explode(",", $arrEditAviso->K_IMAGENES);
                    $this->session->imgDel = array();
                    $increment = 1;
                    foreach ($arrImgAll as $value) {
                        $_key = time()+$increment;
                        $increment++;
                        $arrImg[$_key] = $value;
                        $this->session->img[$_key] = $value;
                        $this->session->imgDel[$_key] = $value;
                    }
                    //echo $arrEditAviso->K_ID_DEPARTAMENTO;exit;
                    //$allParams['ubication_departement'] = explode(",", $arrEditAviso->K_ID_DEPARTAMENTO);
                    $allParams['ubication_departement'] = $arrEditAviso->K_ID_DEPARTAMENTO;
                    $allParams['ubication_province']    = $arrEditAviso->K_ID_PROVINCIA;
                    $allParams['ubication_district']    = $arrEditAviso->K_ID_DISTRITO;
                    
                    $destaqueId = $allParams['destaqueId'];
                    $form->setProvinciaJosonValidate(
                        array('K_ID_DEPARTAMENTO' => $arrEditAviso->K_ID_DEPARTAMENTO)
                    );
                    $form->setDistritoJosonValidate(
                        array(
                            'K_ID_DEPARTAMENTO' => $arrEditAviso->K_ID_DEPARTAMENTO,
                            'K_ID_PROVINCIA' => $arrEditAviso->K_ID_PROVINCIA
                        )
                    );
                    $form->setDefaults($allParams);
                }
            } else {
                if (!$form->isValid($allParams)) {
                    $valToken = $form->atoken->getErrors();
                    $valToken = !empty($valToken);
                    throw new Exception('Error form');
                }
                $form->_letterFreeMax = $arrDestaque->NUM_TEXTO_IMPRESO;
                if ($destaqueId==9 || $destaqueId == 10) $form->addPrintedText();
                $form->addRegistroDatos();
            }
            
            $arrDestaque = $this->_validarDestaque($destaqueId);
            //var_dump($arrDestaque);exit;
            if (!$arrDestaque) throw new Exception('Error Datos Destaque');
            
            $arrUbigeo['depa'] = Zend_Json::encode($mUbigeo->getListJoson(1));
            $arrUbigeo['prov'] = Zend_Json::encode($mUbigeo->getListJoson(2));
            $arrUbigeo['dist'] = Zend_Json::encode($mUbigeo->getListJoson(3));
            
            $form->addToken(3);
            
        } catch (Exception $exc) {
//            echo $exc->getMessage();exit;
            $this->session->sesionPaso1 = ($valToken)?1:2;
            $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-destaque/error/1');
        }
            
        //var_dump($arrDestaque);exit;
        $this->view->telefonoUser = $this->identity->FONO1;
        $this->view->arrDestaque = $arrDestaque;
        $this->view->form = $form;
        $this->view->arrPaso = $arrPasos;
        $this->view->arrUbigeo = $arrUbigeo;
        $this->view->destaqueId = $destaqueId;
        $this->view->publicacionEstado = $publicacionEstado;
        $this->view->arrImg = $arrImg;
        $this->view->ruta = $imd['url'] . '/'.$imd['original'].'/' . $this->identity->ID_USR . '/';
        //->Puglin Dax
        $this->view->daxTagOpc = $destaqueId;
    }

    /**
     * Ander
     * Paso 4
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function confirmarPublicacionAction()
    {
        $this->_helper->layout->setLayout('publicacion');
        
        require_once APPLICATION_PATH . '/modules/usuario/forms/formPublicacion.php';
        require_once APPLICATION_PATH . '/modules/usuario/forms/formUsuarioFacturacion.php';
        require_once 'Aviso.php';
        require_once 'Pagos.php';
        require_once 'Publicacion.php';
        
        $mAviso = new Aviso();
        $mPagos = new Pagos();
        $mPublicacion = new Publicacion();
        $lDatosKotear = new Devnet_DatosKotear();
        $lUtils = new Devnet_Utils();
        $arrDatos = new stdClass();
        $arrPasos = new stdClass();
        $arrPasos->paso1 = true;
        $arrPasos->paso2 = true;
        $arrPasos->paso3 = true;
        $arrPasos->paso4 = true;
        
        $valToken = false;
        
        $imd = $this->getConfig()->fileshare->toArray();
        
        $form = new Usuario_formPublicacion();
        $form->addDestaque();//->paso1
        $form->addCategoriasHidden();//->paso2
        $form->addRegistroDatos(true);//->paso3
        $form->setDistritoJosonValidate();//->paso3 validar
        $form->setProvinciaJosonValidate();//->paso3 validar
//        $form->addToken();
        
        $formFacturacion = new Usuario_formUsuarioFacturacion();
        
        try {
            
            //if (!$this->getRequest()->isPost()) throw new Exception('Error Solo por Post..');
            
            $allParams      = $this->getRequest()->getPost();
            $idAviso        = $allParams['idAviso'];
            $vieneDePaso    = $allParams['paso'];
            //->problem safari
            if($vieneDePaso==3) $form->addToken(3);
            else $form->addToken(4);
            $destaqueId     = $allParams['destaqueId'];
            $titulo         = $allParams['announcement_title'];
            $titulo         = strip_tags($titulo);
            $info_adi       = $allParams['informacion_adicional'];
            $info_adi       = strip_tags($info_adi);

            $estModeracion = $mAviso->moderarAviso($titulo . ' ' . $info_adi);
            if ($estModeracion->ERROR == 2) throw new Exception('Error Malcriado..');
            
            $arrDestaque = $this->_validarDestaque($destaqueId);
            if (!$arrDestaque) throw new Exception('Error en destaque');
            $form->_letterFreeMax = $arrDestaque->NUM_TEXTO_IMPRESO;
            
            
            //->facturacion
            $datosUsuraioFac = $mPagos->getDatosClienteFac($this->identity->ID_USR);
            $validaFacturacion = (empty($datosUsuraioFac));
            if (!$validaFacturacion) {
                $allParams['customer_name'] = $datosUsuraioFac->RazonSocial;
//                if ($datosUsuraioFac->RUC != "") {
                    $allParams['document_number'] = $datosUsuraioFac->RUC;
//                    $allParams['document_type'] = 1;
//                }elseif ($datosUsuraioFac->DNI != "") {
//                    $allParams['document_number'] = $datosUsuraioFac->DNI;
//                    $allParams['document_type'] = 2;
//                }
                $allParams['address'] = $datosUsuraioFac->Direccion;
                $formFacturacion->setDefaults($allParams);
                $formFacturacion->disabledAll();
            }
            
            if (!empty($idAviso)) {
                
                $form->addIdAviso($idAviso);
                $arrEditAviso = $mPublicacion->getPublicacion($idAviso, $this->identity->ID_USR);
                
                $allParams['categoriaId1'] = $arrEditAviso->L1;
                $allParams['categoriaId2'] = $arrEditAviso->L2;
                $allParams['categoriaId3'] = $arrEditAviso->L3;
                $allParams['categoriaId4'] = $arrEditAviso->L4;
                
                $entraPaso4 = false;
                $guardaPaso4 = false;
                                
                if ($arrEditAviso->K_ID_DESTAQUE == $destaqueId && $arrEditAviso->K_EST == 1) {
                    //->ACTUALIZA LA INFO SOLO CUANDO ESTA ACTIVO
                    $allParams['announcement_title'] = $arrEditAviso->K_TITULO;
                    $guardaPaso4 = true;
                    $publicacionEstado = 2;
                    $arrPasos->paso1 = false;
                    $arrPasos->paso2 = false;
                    
                } elseif ($arrEditAviso->K_ID_DESTAQUE < $destaqueId && $arrEditAviso->K_EST == 1) {
                    //->DESTAQUE
                    $allParams['announcement_title'] = $arrEditAviso->K_TITULO;
                    $publicacionEstado = 3;
                    
                    if ($destaqueId == 9 || $destaqueId == 10) $form->addPrintedText();
                    
                    if ($vieneDePaso == 3) {
                        $entraPaso4 = true;
                        $arrPasos->paso2 = false;
                    } else { 
                        $form->addConfirmacion();
                        $guardaPaso4 = true;
                    }
                    
                } elseif ($arrEditAviso->K_EST == 5) {
                    //->REPUBLICAR
                    $publicacionEstado = 4;
                    if ($destaqueId == 9 || $destaqueId == 10) $form->addPrintedText();
                    
                    if ($vieneDePaso == 3 && $destaqueId != 2) {
                        $entraPaso4 = true;
                        $arrPasos->paso2 = false;
                    } else {
                        if ($destaqueId != 2) $form->addConfirmacion();
                        $guardaPaso4 = true;
                    }
                } else throw new Exception('Error Infiltrado');
                
                if (!$form->isValid($allParams)) {
                    $valToken = ($form->atoken3)?$form->atoken3->getErrors():$form->atoken4->getErrors();
                    $valToken = !empty($valToken);
                    throw new Exception('Error edit form');
                }
                //foreach ($form->getElements() as $key => $e){
                //  var_dump($key);var_dump($e->getErrors());};exit;
                
                if ($entraPaso4) {
                    //var_dump($form->getValues());exit;
                    $form->addConfirmacion();
                    $arrDatos->impresoFecPublicacion = $lDatosKotear->impresoFechaPublicacion();
                    $arrDatos->fecDiasPublicacionWeb = 
                        $lUtils->convertFecha('d/m, Y', $lUtils->sumaDiasAFechas($arrDestaque->DIAS_PUB));
                    $arrDatos->impresoFecCierre = $lDatosKotear->impresoFechaCierre();
                    //->problem safari
                    $form->addToken(4);
                } elseif ($guardaPaso4) {
                    //->Validar Facturacion
                    if ($validaFacturacion && $allParams['voucher']==2 && 
                        !$formFacturacion->isValid($allParams)) 
                        throw new Exception('Error edit form Facturacion');
                    
                    $allPostParams = $form->getValues();
                    if ($validaFacturacion)
                        $allPostParams = array_merge($allPostParams, $formFacturacion->getValues());
                    $this->guardarPublicacionAction($allPostParams, $arrDestaque, $publicacionEstado);
                }
                //if(!$form->isValid($allParams)) throw new Exception('Error edit form'); 
                    //foreach ($form->getElements() as $key => $e){var_dump($key);
                    //  var_dump($e->getErrors());};exit;
                
            } else {
                $publicacionEstado = 1;
                if ($destaqueId == 9 || $destaqueId == 10) $form->addPrintedText();
                if ($vieneDePaso != 3) $form->addConfirmacion(); //paso4
                
                if (!$form->isValid($allParams)) {
                    $valToken = ($form->atoken3)?$form->atoken3->getErrors():$form->atoken4->getErrors();
                    $valToken = !empty($valToken);
                    throw new Exception('Error nuevo form');
                }
                //foreach ($form->getElements() as $key => $e){
                //  var_dump($key);var_dump($e->getErrors());} exit;
                
                $allPostParams = $form->getValues();
                
                if ($vieneDePaso=='3' && $arrDestaque->ID_DESTAQUE != 2) {
//                    if($arrDestaque->ID_DESTAQUE == 2){
//                        //->Gratis
//                        $this->guardarPublicacionAction($allPostParams, $arrDestaque, $publicacionEstado);
//                    } else {
                        //Pagos
                        $form->addConfirmacion();
                        $arrDatos->impresoFecPublicacion = $lDatosKotear->impresoFechaPublicacion();
                        $arrDatos->fecDiasPublicacionWeb = 
                            $lUtils->convertFecha(
                                'd/m, Y', $lUtils->sumaDiasAFechas($arrDestaque->DIAS_PUB)
                            );
                        $arrDatos->impresoFecCierre = $lDatosKotear->impresoFechaCierre();
                        //->problem safari
                        $form->addToken(4);
//                    }
                } else {
                    //->Validar Facturacion
                    if ($validaFacturacion && $allParams['voucher']==2 && 
                        !$formFacturacion->isValid($allParams))
                        throw new Exception('Error edit form Facturacion');
                    
                    $this->guardarPublicacionAction($allParams, $arrDestaque, $publicacionEstado);
                }
            }
            
            $arrFotos = explode("-", $allParams['ids_hidden_ad']);
            $photosSession = $this->session->img;
            $primerFoto = $photosSession[$arrFotos[0]];
            
        } catch (Exception $exc) {
//            echo $exc->getMessage();exit;
            $this->session->sesionPaso1 = ($valToken)?1:2;
            $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-destaque/error/1');
        }
        
        $this->view->arrDatos = $arrDatos;
        $this->view->arrPaso = $arrPasos;
        $this->view->arrDestaque = $arrDestaque;
        $this->view->form = $form;
        $this->view->formFacturacion = $formFacturacion;
        
//        $this->view->destaqueId = $destaqueId;
        $this->view->publicacionEstado = $publicacionEstado;
        $this->view->primerFoto = $imd['url'] . '/'. $imd['original'] . '/' . $this->identity->ID_USR . '/' .
            $primerFoto;
        //->Puglin Dax
        $this->view->daxTagOpc = $destaqueId;
    }

    /**
     * Ander
     * Fin de pasos... Guardar informacion
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function guardarPublicacionAction($allParams, $arrDestaque, $publicacionEstado = 1)
    {
        require_once 'Aviso.php';
        require_once 'AvisoDestaque.php';
        require_once 'FotoAviso.php';
        require_once 'Devnet/StandardAnalyzer/SpanishStemmer.php';
        require_once 'KotearPagos.php';
//        require_once 'Destaque.php';
        require_once 'Pagos.php';
        require_once 'Publicacion.php';
        require_once 'Duracion.php';
        
        $mKP = new KotearPagos();        
        $mAviso = new Aviso();
        $mPagos = new Pagos();
//        $mAvisoDestaque = new AvisoDestaque();
        $mFotoAviso = new FotoAviso();
        $stemm = new StandardAnalyzer_SpanishStemmer();
        $mDestaque = new Destaque();
        $mPublicacion = new Publicacion();
        $mDuracion = new Duracion();
//        $temp = new Zend_Session_Namespace('dataTemp');
        
        $zfFilterAlNum = new Zend_Filter_Alnum();
        $zfFilterAlNum->setAllowWhiteSpace(TRUE);
        
        try {
                        
            if (empty($allParams)) throw new Exception('Error no existe parametros');
//            $arrDestaque = $this->_validarDestaque($destaqueId);
            if (empty($arrDestaque)) throw new Exception('Error en destaque');
            
            $K_ID_AVISO = $allParams['idAviso'];
            $K_ID_TIPO_PRODUCTO = $allParams['product_type'];
            $K_NAME_TIPO_PRODUCTO = ($K_ID_TIPO_PRODUCTO==1)?"Nuevo":"Usado";
            //$K_TIT = $this->view->escape(strip_tags($allParams['announcement_title']));
            $K_TIT = strip_tags($allParams['announcement_title']);
            $K_TAG = implode(' ', $stemm->getTags($this->view->utils->repeticionesPalabras($K_TIT)));
            $K_PRECIO = $allParams['price'];
            $K_HTML = empty($allParams['informacion_adicional'])?'':$allParams['informacion_adicional'];
            $K_URL = $this->view->utils->convertSEO($K_TIT);
            $K_ID_MONEDA = $allParams['currency'];
            $K_NAME_MONEDA = ($K_ID_MONEDA==1)?"S/.":"US$.";
            $K_ID_UBIGEO = $allParams['ubication_district'];
            //->ID_CATEGORIA
                $categoriaId[0] = $allParams['categoriaId4'];
                $categoriaId[1] = $allParams['categoriaId3'];
                $categoriaId[2] = $allParams['categoriaId2'];
                $categoriaId[3] = $allParams['categoriaId1'];
                foreach ($categoriaId as $value) :
                    if ($value != 0 && $value != '') {
                        $K_ID_CATEGORIA = $value;
                        break;
                    }
                endforeach;
            $K_ID_MEDIO_PAGO = implode(',', $allParams['payment_method']);
            //->FOTOS
                $ids_imgs = explode("-", $allParams['ids_hidden_ad']);
                $photosSession = $this->session->img;
                $photosInput = array_flip($ids_imgs);
                //$photos = array_intersect_key($photosSession, $photosInput);
                foreach ($photosInput as $key => $value) {
                    $photos[$key] = $photosSession[$key];
                }
                $arrPhoto = $mFotoAviso->generarArrayFoto(
                    $photos, $K_URL, $arrDestaque->CANT_FOTO, $this->session->imgDel
                );
                $K_FOTOS = implode(",", $arrPhoto['name']);
                
            //->ID_DESTAQUE
                $destaqueId = $allParams['destaqueId'];
                $arrDestaqueComb = $mDestaque->getCombinacionDestaque($destaqueId);
                if(empty($arrDestaqueComb)) throw new Exception('Error no se pudo guardar aviso');
                $K_ID_DESTAQUE = implode(",", $arrDestaqueComb);
            //->TEXTO IMPRESO
                $K_TIT_IMPRESO = empty($allParams['announcement_title_impress'])
                    ?'':$allParams['announcement_title_impress'];
                $K_TEXT_IMPRESO = empty($allParams['letter_free'])?'':$allParams['letter_free'];
                $K_MEDIO_PAGO = $allParams['means_of_payment'];
                $K_MONTO = $arrDestaque->MONTO; ///monto destaca
                $K_ID_TIPO_FACTURA = $allParams['voucher'];    
            //->ID_DURACION
                $arrDuracion = $mDuracion->getIdDuracionPorDuracionDias($arrDestaque->DIAS_PUB);
                $K_ID_DURACION = $arrDuracion['ID_DURACION'];
            
            //->FACTURACION
                $K_TIPO_DOCUMENTO = 2;//$allParams['document_type'];
                $K_NUM_DOCUMENTO = $allParams['document_number'];
                $K_RAZON_SOCIAL = trim($allParams['customer_name']);
                $K_NOMBRE = $zfFilterAlNum->filter(trim($this->identity->NOM));
                $K_APE_PATERNO = $zfFilterAlNum->filter(trim($this->identity->APEL));
                $K_DIRECCION = $allParams['address'];
                
            $inputA['K_ID_AVISO'] = $K_ID_AVISO;
            $inputA['K_ID_TIPO_PRODUCTO'] = $K_ID_TIPO_PRODUCTO;
            $inputA['K_TIT'] = $K_TIT;
            $inputA['K_TAG'] = $K_TAG;
            $inputA['K_PRECIO'] = $K_PRECIO;
//            $inputA['K_HTML'] = $K_HTML;//debido al redimencionamiento de img
            $inputA['K_EST'] = ($destaqueId==2)?1:3;
            $inputA['K_URL'] = $K_URL;
            $inputA['K_ID_DURACION'] = $K_ID_DURACION;
            $inputA['K_ID_MONEDA'] = $K_ID_MONEDA;
            $inputA['K_ID_USR'] = $this->identity->ID_USR;
            $inputA['K_ID_UBIGEO'] = $K_ID_UBIGEO;
            $inputA['K_ID_CATEGORIA'] = $K_ID_CATEGORIA;
            $inputA['K_ID_MEDIO_PAGO'] = $K_ID_MEDIO_PAGO;
            $inputA['P_DESTAQUE_POST_PAGO'] = 1;    
            $inputA['K_ID_DESTAQUE'] = $K_ID_DESTAQUE;
            $inputA['K_TEXT_IMPRESO'] = $K_TEXT_IMPRESO;
            $inputA['K_TIT_IMPRESO'] = $K_TIT_IMPRESO;
            $inputA['K_FOTOS'] = $K_FOTOS;
            $inputA['K_MEDIO_PAGO'] = $K_MEDIO_PAGO;
            $inputA['K_MONTO'] = $K_MONTO; ///monto destaca
            $inputA['K_ID_TIPO_FACTURA'] = $K_ID_TIPO_FACTURA;
            
            $inputA['K_TIPO_DOCUMENTO'] = $K_TIPO_DOCUMENTO;
            $inputA['K_NUM_DOCUMENTO'] = $K_NUM_DOCUMENTO;
            $inputA['K_RAZON_SOCIAL'] = $K_RAZON_SOCIAL;
            $inputA['K_NOMBRE'] = $K_NOMBRE;
            $inputA['K_APE_PATERNO'] = $K_APE_PATERNO;
            $inputA['K_APE_MATERNO'] = $K_APE_PATERNO;
            $inputA['K_DIRECCION'] = $K_DIRECCION;
            
            if ($publicacionEstado == 1) {
                $subject = 'Publicación de su aviso';
                
                $inputA['K_HTML'] = $this->imageResize($arrPhoto['key'], $K_URL, $K_HTML);
                $arrAvisoPublicado = $mPublicacion->guardarPublicacion($inputA);
                //var_dump($arrAvisoPublicado);exit;
                if(!$arrAvisoPublicado) throw new Exception('Error Guardar el aviso');
                $idAviso = $arrAvisoPublicado->ID_AVISO;
                $idOperacion = $arrAvisoPublicado->ID_OPERACION;

                //->FACTURACION
                $datosUsuraioFac = $mPagos->getDatosClienteFac($this->identity->ID_USR);
                $validador = (empty($datosUsuraioFac));
                if (!empty($K_NUM_DOCUMENTO) && $arrDestaque->ID_DESTAQUE != 2 && $K_ID_TIPO_FACTURA == 2 &&
                        $validador)
                    $mPagos->guardarDatosFacturacion($inputA);
            } elseif ($publicacionEstado == 2) {
                $subject = 'Edición de su aviso';
                $photosSessionDel = $this->session->imgDel;
                $arrPhotoNew = array_diff($arrPhoto['key'], $photosSessionDel);
                $inputA['K_HTML'] = $this->imageResize($arrPhotoNew, $K_URL, $K_HTML);
                
                $arrAvisoUpd = $mAviso->actualizaAviso($inputA);
                if(!$arrAvisoUpd) throw new Exception('Error Guardar el aviso.');
                $idAviso = $K_ID_AVISO;
                $mFotoAviso->guardarFotoAviso($inputA);
                
                // LIMPIAR CACHE DE MEDIO DE PAGO
                $cache = Zend_Registry::get('cache');
                $cache->remove('medioPagoAviso'.$idAviso);
            } elseif ($publicacionEstado == 3 || $publicacionEstado == 4) {
                
                $subject = ($publicacionEstado==3)?'Destacar su aviso':'Republicar su aviso';
                
                $photosSessionDel = $this->session->imgDel;
                $arrPhotoNew = array_diff($arrPhoto['key'], $photosSessionDel);
                $inputA['K_HTML'] = $this->imageResize($arrPhotoNew, $K_URL, $K_HTML);
                
                $arrAvisoPublicado = $mPublicacion->actualizaPublicacion($inputA);
//                var_dump($arrAvisoPublicado);exit;
                if(!$arrAvisoPublicado) throw new Exception('Error Guardar el aviso..');
                $idAviso = $arrAvisoPublicado->ID_AVISO;
                $idOperacion = $arrAvisoPublicado->ID_OPERACION;
                
                //->FACTURACION
                $datosUsuraioFac = $mPagos->getDatosClienteFac($this->identity->ID_USR);
                $validador = (empty($datosUsuraioFac));
                if(!empty($K_NUM_DOCUMENTO) && $arrDestaque->ID_DESTAQUE != 2 && $K_ID_TIPO_FACTURA == 2 &&
                        $validador)
                    $mPagos->guardarDatosFacturacion($inputA);
                
                // LIMPIAR CACHE DE MEDIO DE PAGO
                $cache = Zend_Registry::get('cache');
                $cache->remove('medioPagoAviso'.$idAviso);
                
            } else new Exception('Error publicacion estado');
            
            $K_RUTA_URL = $this->view->baseUrl().'/aviso/'.$idAviso.'-'.$K_URL;
            
            $arrDestaqueAviso = $mAviso->getDestaquesAviso($idAviso);
            //->Correo
            if (count($arrDestaqueAviso) > 0) {
                foreach ($arrDestaqueAviso as $index) :
                    if ($index->ID_DESTAQUE >3) {
                        $detalle_destaque = '<p>' . $index->TIT . ' - Monto: S/. ' . $index->MONTO;
                        $arrayDetalleDestaque[0] = $index->TIT . ' - Monto: S/. ' . $index->MONTO;
                    } else {
                        $arrayDetalleDestaque[0] = 'Destaque Gratuito';
                        $detalle_destaque = '<p> ' . $arrayDetalleDestaque[0];
                    }
                endforeach;
            }
            
            /*
            * Estado de los destaque
            * 0:
            * 1: Posee destaques
            * 2: Posee destaques pendiente de pago
            */
            $estadoAviso = 1;
            //if ($varBus == 1) {$nameTemplate = 'confirmaravisocaso1';}
            //-> Tiene cargos por destaques
            if ($mDestaque->tieneDestaquesCargos($idAviso)) {                
                $estadoAvisoDestaques = 2;
                $nameTemplate = 'confirmaravisocaso2';
            } else {
                $estadoAvisoDestaques = 1;
                $nameTemplate = 'confirmaravisocaso1';
            }
            
            $arrayReplace = array('[nombre]' => $this->identity->NOM,
                        '[monto]' => $K_PRECIO,
                        '[moneda]' =>  $K_NAME_MONEDA,
                        '[estado]' => $K_NAME_TIPO_PRODUCTO,
                        '[cantidad]' => 1,
                        '[detalle_destaque]' => $detalle_destaque,
                        '[titulo]' => $K_TIT,
                        '[ruta]' => $K_RUTA_URL);
            
            $this->enviarCorreo($nameTemplate, $subject, $arrayReplace);
            
            $this->session->publicacionEstado = $publicacionEstado;
            //->Asignamos el estado del destaque del aviso
            $this->session->fnp['publicacionDestaqueEstado'] = $estadoAvisoDestaques;
            $this->session->fnp['msj'] = 'los datos se enviaron correctamente';
            $this->session->fnp['rutaAviso'] = $K_RUTA_URL;
            $this->session->fnp['destaque'] = $arrayDetalleDestaque;
            $this->session->fnp['nombre'] = $this->identity->NOM;
            $this->session->fnp['monto'] = $K_MONTO;
            $this->session->fnp['moneda'] = $K_ID_MONEDA;
            $this->session->fnp['fecha_publicacion'] = '';$this->_request->getParam('w');
            $this->session->fnp['cantidad'] = 1;
            $this->session->fnp['titulo'] = $K_TIT;
            $this->session->fnp['estado'] = $estadoAviso;
            $this->session->fnp['estadoArticuloText'] = $K_NAME_TIPO_PRODUCTO;
            $this->session->fnp['idenckp'] = $mKP->encriptar($transaccionKotearPagos);
            $this->session->fnp['email'] = $this->identity->EMAIL;
            $this->session->fnp['telefono'] = $this->identity->FONO1;
            $this->session->fnp['rutakp'] = '/usuario/pagos/kotear-pagos-pago-destaques';
            $this->session->fnp['id_aviso'] = $idAviso;
            
            if ($arrDestaque->ID_DESTAQUE == 2 || $publicacionEstado == 2) {
                $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/fin-publicacion');
            } else {
                $this->_redirect(
                    $this->view->baseUrl() . '/pagos/index/enviar-medio-encriptado/medioPago/'.
                    $allParams['means_of_payment'].'/idOperacion/'.$idOperacion.'/monto/'.$arrDestaque->MONTO
                );
            }
        } catch (Exception $exc) {
//            echo $exc->getMessage();exit;
            $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-destaque/error/1');
        }
    }
        
    /**
     * Permite visualizar la pantalla de fin de publicacion de acuerdo a cada proceso
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function finPublicacionAction()
    {
        $this->getResponse()->setHeader('Expires', '0', true)
            ->setHeader(
                'Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0', true
            )
            ->setHeader('Pragma', 'no-cache', true);

        if (isset($this->session->fnp)) {
            switch ($this->session->publicacionEstado) {
                case 1 :
                    $this->view->headTitle('Publicar Aviso - Confirmación | Kotear.pe');
                    break;
                case 2 :
                    $this->view->headTitle('Editar Aviso - Confirmación | Kotear.pe');
                    break;
                case 3 :
                    $this->view->headTitle('Destacar Aviso - Confirmación | Kotear.pe');
                    break;
                case 4 :
                    $this->view->headTitle('Republicar Aviso - Confirmación | Kotear.pe');
                    break;
                default :
                    break;
            }
            $this->view->msj = $this->session->fnp['msj'];
            $this->view->rutaAviso = $this->session->fnp['rutaAviso'];
            $this->view->destaque = $this->session->fnp['destaque'];
            $this->view->nombre = $this->session->fnp['nombre'];
            $this->view->monto = $this->session->fnp['monto'];
            $this->view->moneda = $this->session->fnp['currency'];
            $this->view->cantidad = $this->session->fnp['cantidad'];
            $this->view->titulo = strip_tags($this->session->fnp['titulo']);
            $this->view->estado = $this->session->fnp['estado'];
            $this->view->publicacionEstado = getdate();//$this->session->fnp['publicacionEstado'];
            $this->view->publicacionDestaqueEstado = 1;// ;$this->session->fnp['publicacionDestaqueEstado'];
            $this->view->estadoArticulo = "2";//$this->session->fnp['estadoArticuloText'];
            $this->view->rutakp = $this->session->fnp['rutakp'];
            $this->view->idenckp = $this->session->fnp['idenckp'];
            //KOTEAR PAGOS
            $this->view->idTransaccion = $this->session->publicacionTransaccion;
            //KOTEAR PAGOS FIN
            $this->view->email = $this->session->fnp['email'];
            $this->view->telefono = $this->session->fnp['telefono'];
            unset($this->session->fnp);
            unset($this->session->img);
            unset($this->session->imgtm);
            unset($this->session->nuevo);
        } else {
            $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-categoria');
        }
        $this->loadPublicacionEstado();
    }
    
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function registroAction()
    {
        $this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-datos');
    }


    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */    
    function registroProductoAction()
    {
        //$this->_redirect($this->view->baseUrl() . '/usuario/publicacion/registro-datos');
    }    


    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function suspensionAction()
    {
        if ($this->_request->getParam('nivel') == 1) {
            $this->view->nivelLeve = $this->_request->getParam('nivel');
        }
        if ($this->_request->getParam('nivel') == 2) {
            $this->view->nivelModerado = $this->_request->getParam('nivel');
        }
    }


    /**
     * 
     * @param type name desc
     * @param unknown_type $listaRubro
     * @param unknown_type $listaSubrubroOne
     * @param unknown_type $listaSubrubroTwo
     * @param unknown_type $listaSubrubroThree
     * @uses Clase::methodo()
     * @return type desc
     */
    function generarCondicionJs($listaRubro, $listaSubrubroOne, $listaSubrubroTwo, $listaSubrubroThree)
    {
        $salida.="var encontrado=0;";
        foreach ($listaRubro as $index):
            if ($index->K_ADULTO == true) {
                $salida .= "
                    if((padre == {$index->K_ID_CATEGORIA}) && (encontrado==0)){
		        document.getElementById('hrubro_adulto').value='true';
		        encontrado=1;
		        document.getElementById('destaqueprincipal').value='2';
		        document.getElementById('textdestaque1').value='Sin destaque';
		        document.getElementById('precioDestaque1').value='0';
		        }\n";
            } else {
                $salida .= "if((padre == {$index->K_ID_CATEGORIA}) && encontrado==0) {
                    document.getElementById('hrubro_adulto').value='';}\n";
            }
        endforeach;
        $salida .= $this->generarCondicionListaJS($listaSubrubroOne, 'subrubro1');
        $salida .= $this->generarCondicionListaJS($listaSubrubroTwo, 'subrubro2');
        $salida .= $this->generarCondicionListaJS($listaSubrubroThree, 'subrubro3');
        return $salida;
    }

    /**
     * @param array $lista
     * @param unknown_type $itemName
     * @return string
     */
    function generarCondicionListaJS($lista, $itemName)
    {
        $cadena = '';
        foreach ($lista as $index):
            $padreid = ($index->K_ID_PADRE == "") ? 0 : $index->K_ID_PADRE;
            $cadena .= "if( padre == $padreid){ registra_item('$itemName', 
                {$index->K_ID_CATEGORIA},'{$index->K_TIT}');}\n";
        endforeach;
        return $cadena;
    }

    /**
     *
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     * */
    function uploadAction()
    {
        $isAjax = $this->_request->getParam('isAjax');
        if (!isset($isAjax) || ($isAjax == false))
            $this->_redirect($this->baseUrl() . 'usuario/publicacion/registro-datos');
        //     $this->_helper->layout->setLayout('clear');
        //      $this->log->err('upload ini IMG : '.json_encode($this->session->img));
        //$this->log->err('upload ini IMGTM : '.json_encode($this->session->imgtm));
        $frontController = Zend_Controller_Front::getInstance();
        $imd = $frontController->getParam('bootstrap')->getOption('fileshare');
        $result = new stdClass();
        if ($this->_request->isPost()) {
//            $cantidadAvisos = 
//            $this->session->objDestaque->CANT_FOTO

//fileshare.type = ftp
//fileshare.host = 10.195.170.192
//fileshare.url = http://e.kotear.pe
//fileshare.urlint = http://10.195.170.192
//fileshare.fileFoto= /home/apkotear/public/
//fileshare.username = apkotear
//fileshare.password = Comercio2k10
//fileshare.thumbs = thumbs
//fileshare.thumbnails = thumbnails
//fileshare.img = img
//fileshare.images = images
//fileshare.original = original
//fileshare.images = images
//fileshare.imagenesdes = /migracion/imgtipo3/            
            
            try {
                $imageManagement = new Devnet_ImageManagement(
                    $imd['host'],
                    $imd['username'],
                    $imd['password'],
                    $imd['thumbs'],
                    $imd['thumbnails'],
                    $imd['image'],
                    $imd['original'],
                    $imd['img']
                );
                $imageManagement->openFtp();
                $result->msg = '';
                if (is_uploaded_file($_FILES["image"]['tmp_name'])) {
                    $extension = explode('.', strtolower($_FILES['image']['name']));
                    $num = count($extension) - 1;
                    if (!($extension[$num] == 'jpg' || $extension[$num] == 'gif')) {
                        throw new Exception('Archivo Invalido');
                    } elseif ($_FILES['image']['size'] > 1048576) {
                        throw new Exception('Archivo supero el tamaño requerido');
                    } else {
                        $nombreFichero = time() .
                                '.' .
                                $extension[$num];
                    }
                    if ($result->msg == '') {
                        $error_imagen = 1;
                        $local = $_FILES['image']['name'];
                        $remoto = $_FILES['image']['tmp_name'];
                        $ruta = '/home/apkotear/public/' . $imd['original'] . '/'
                                //. $_POST['SESSID']//'6'//$iduser->ID_USR
                                . $this->identity->ID_USR
                                . '/'
                                . $nombreFichero;
                        $imageManagement->upImage($ruta, $remoto);
                    }
                }//is_uploaded_file ($_FILES["image"]['tmp_name']
                //Registro en Sesion
                if (!isset($this->session->img))
                    $this->session->img = array(0, 0, 0, 0, 0, 0);
                foreach ($this->session->img as $index => $valor) {
                    if ($valor === 0) {
                        $this->session->img[$index] = $nombreFichero;
                        break;
                    }
                }
                //Fin Registro en Sesion
                $result->code = 1;
                $result->url = $imd['url'] . '/original/' . $this->identity->ID_USR . '/' . $nombreFichero;
                $result->id = $nombreFichero;
                $result->url_del = $this->view->baseUrl() . '/usuario/publicacion/eliminar-imagen?idfoto=' .
                    $result->id;
                echo json_encode($result);
                die();
            } catch (Exception $e) {
                $result->code = 0;
                $result->msg = $e->getMessage();
                echo json_encode($result);
                die();
            }
        } else {
            $result = new stdClass();
            $result->code = 0;
            $result->msg = 'No se ha enviado el archivo por el medio indicado.';
            echo json_encode($result);
            die();
        }
    }
    
    /**
     * Permite registrar los datos del aviso a traves de la capa de datos
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    function registrarAviso($datosAviso, $estado, &$transaccion, $estModeracion, &$idAviso, 
        $infAdicionalReturn)
    {
//        var_dump($datosAviso);Exit;
        require_once 'Devnet/StandardAnalyzer/SpanishStemmer.php';
        try {
            foreach (array('hsubrubro3', 'hsubrubro2', 'hsubrubro1', 'hrubro') as $value) :
                if ($datosAviso["$value"] != 0) {
                    $idClasificacion = $datosAviso["$value"];
                    break;
                }
            endforeach;
            require_once 'Aviso.php';
            require_once 'Duracion.php';
            require_once 'Destaque.php';
            $duracion = new Duracion();
            $destaque = new Destaque();
            $objDestaque = $this->_validarDestaque(base64_decode($datosAviso['destaque']));
            $duracion = $duracion->getIdDuracionPorDuracionDias($objDestaque->DIAS_PUB);
            $aviso = new Aviso();
            $stemm = new StandardAnalyzer_SpanishStemmer();
            $idAviso = $datosAviso['aviso'];
            $arrayDatos = array('ID_AVISO' => $idAviso,
                'ID_TIPO_PRODUCTO' => $datosAviso['estadoArticulo'],
                'TIT' => strip_tags($datosAviso['Titulo']),
                'SUBTIT' => strip_tags($datosAviso['subtitulo']),
                'TAG' => implode(
                    ' ',
                    $stemm->getTags(
                        $this->view->utils->repeticionesPalabras(strip_tags($datosAviso['Titulo']))
                    )
                ),
                'STOCK' => 1,
                'PRECIO' => $datosAviso['preciofinal'],
                'HTML' => $infAdicionalReturn,
                'IMG_DEF' => '',
                'EST' => $estado,
                'URL' => $this->view->utils->convertSEO(strip_tags($datosAviso['Titulo'])),
                'ID_TIPO_AVISO' => 1,
                'ID_DURACION' => $duracion['ID_DURACION'],
                'ID_REPUBLICACION' => $datosAviso['duracionRepublicacionAutomatica'],
                'ID_MONEDA' => $datosAviso['moneda'],
                'ID_USR' => $this->identity->ID_USR,
                'VISITAS' => 0,
                'ID_UBIGEO' =>$datosAviso['ubigeo'],
                'TEXT_IMPRESO'=> $datosAviso['impreso_des_linea1_hidden'],
                'TIT_IMPRESO'=> $datosAviso['imp_tit_hidden']
            );
//            var_dump($this->session->img);exit;
            $frontController = Zend_Controller_Front::getInstance();
            $imd = $frontController->getParam('bootstrap')->getOption('fileshare');
            $listdestaq='';
//            var_dump($destaque->getCombinacionDestaque($objDestaque->ID_DESTAQUE));exit;
            if (
                $aviso->registroDatos(
                    $arrayDatos,
                    $destaque->getCombinacionDestaque($objDestaque->ID_DESTAQUE),
                    $this->session->img,
                    $idClasificacion,
                    $datosAviso['lista_mediospago'],
                    $this->identity->ID_USR,
                    $idAviso,
                    $transaccion,
                    $estModeracion == true,
                    $objDestaque->CANT_FOTO,
                    $listdestaq
                )
            ) {
                $this->session->listdestaq=$listdestaq;                
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {$this->view->telefono = $this->session->fnp['telefono'];
            return false;
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function registroImagenAction()
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isPost()) {
            if (!isset($this->session->img))
                $this->session->img = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            foreach ($this->session->img as $index => $valor) {
                if ($valor === 0) {
                    $this->session->img[$index] = $this->_request->getParam('idfoto');
                    break;
                }
            }
        }
        $this->_request->getParam('idfoto');
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function cambiarOrdenImagen($imgOne, $imgTwo)
    {
        $temp = $this->session->img[$imgOne];
        $this->session->img[$imgOne] = $this->session->img[$imgTwo];
        $this->session->img[$imgTwo] = $temp;
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function eliminarImagenAction()
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isPost()) {
            $result = new stdClass();
            try {
                $frontController = Zend_Controller_Front::getInstance();
                $imd = $frontController->getParam('bootstrap')->getOption('fileshare');
                $imageManagement = new Devnet_ImageManagement(
                    $imd['host'], $imd['username'], $imd['password']
                );
                foreach ($this->session->img as $index => $valor) {
                    if ($valor == $this->_request->getParam('idfoto')) {
                        if ($this->session->imgtm[$index] !== 0) {
                            $this->session->imgtm[$index] = 0;
                        }
                        $this->session->img[$index] = 0;
                        $this->reordenarImagen();
                        break;
                    }
                }
                $imageManagement->closeFtp();
                $result->code = 1;
                echo json_encode($result);
                die();
            } catch (Exception $e) {
                $result->code = 0;
                $result->msg = $e->getMessage();
                echo json_encode($result);
                die();
            }
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function reordenarImagen()
    {
        for ($i = 1; $i < count($this->session->img); $i++) {
            if ($this->session->img[$i - 1] == '') {
                $this->session->img[$i - 1] = $this->session->img[$i];
                $this->session->img[$i] = 0;
            }
        }
    }

    /**
     *
     * @param type name desc
     * @uses Class::metodo()
     * @return type desc
     * */
    function reordenarImagenesAction()
    {
        $result = new StdClass();
        try {
            $imagenes = $this->_request->getParam('fotos');
            if ($imagenes) {
                $imagenes = explode(',', $imagenes);
                for ($i = 0; $i < count($this->session->img); $i++) {
                    if (isset($imagenes[$i])) {
                        if ($imagenes[$i] == '')
                            $this->session->img[$i] = 0;
                        else
                            $this->session->img[$i] = $imagenes[$i];
                    }
                }
                $result->code = 1;
            } else
                $result->code = 0;
            $this->json($result);
        } catch (Exception $e) {
            $result->code = 0;
            $result->msg = $e->getMessage();
            $this->json($result);
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function guardarBorradorAction()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $imd = $frontController->getParam('bootstrap')->getOption('template'); 
        if (
            $this->redimensionarImagen(
                strip_tags($this->_request->getParam('Titulo')), $this->_request->getParam('inf_adicional'),
                $infAdicionalReturn
            )
        ) {
            if (
                $this->registrarAviso(
                    $this->_request->getParams(), 4, $transaccion, $estModeracion, $idAviso, 
                    $infAdicionalReturn
                )
            ) {
                $this->session->fnp['msj'] = 'el aviso se registro correctamente';
                $this->_redirect($this->view->baseUrl() . '/usuario/venta/inactivas/opc/categoria/codigo/0');
                unset($this->session->img);
                unset($this->session->imgtm);
            } else {
                $this->session->fnp['msj'] = 'hubo problemas al registrar el aviso';
            }
        } else {
            $this->session->fnp['msj'] = 'hubo problemas con la carga de imagenes';
        }
        $this->loadPublicacionEstado();
        unset($this->session->nuevo);
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function moderarAvisoAction()
    {
        require_once 'Aviso.php';
        $aviso = new Aviso();
        if ($this->_request->isXMLHttpRequest()) {
            $moderacion = $aviso->moderarAviso(
                $this->_request->getParam('inf_adicional') . $this->_request->getParam('subtitulo') . 
                $this->_request->getParam('Titulo')
            );
            $this->json(array('estado' => $moderacion->ERROR, 'datos' => explode('|', $moderacion->MSJ)));
        }
    }

    /**
     * Descripcion
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    private function redimensionarImagen($nomAviso, $infAdicional, &$infAdicionalReturn)
    {
        $filter = new Devnet_Filter_Alnum();
        $nomAviso = $filter->filter($nomAviso, '-', 0);
        try {
//            $frontController = Zend_Controller_Front::getInstance();
//            $imd = $frontController->getParam('bootstrap')->getOption('fileshare');
            $imd = $this->getConfig()->fileshare->toArray();
            if (is_array($this->session->img)) {
                foreach ($this->session->img as $index => $valor) {
                    if ($valor != 0) {
                        $url = sprintf(
                            '%s/TransformImage.php?nombreaviso=%s&nomfichero=%s&fileuser=%s',
                            //$imd['url'],
                            'http://' . $imd['host'],
                            $nomAviso,
                            $valor,
                            $this->identity->ID_USR
                        );
                        fopen($url, 'r');
                        $this->session->img[$index] = $nomAviso . $valor;
                        $infAdicional = str_replace($valor, $nomAviso . $valor, $infAdicional);
                    }
                }
            }
            if (!($this->session->imgtm[0] === 0)) {
                $imageManagement = new Devnet_ImageManagement(
                    $imd['host'], $imd['username'], $imd['password']
                );
                if (is_array($this->session->imgtm)) {
                    foreach ($this->session->imgtm as $index => $valor) :
                        if ($valor != 0) {
                            // aperturo la conexion ftp
                            $imageManagement->openFtp();
//                            $root = '/home/apkotear/public/';
                            $root = $imd['fileFoto'];
                            foreach (array('original', 'thumbs', 'thumbnails', 'img', 'images') as $index) {
                                $imageManagement->delete(
                                    $root . $imd[$index] . '/' . $this->identity->ID_USR . '/' . $valor
                                );
                            }
                            @$imageManagement->closeFtp;
                        }
                    endforeach;
                }
            }
            $infAdicionalReturn = $infAdicional;
            return true;
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            return false;
        }
    }

    /**
     * @param string $templateName Nombre de template para el correo
     * @param string $subject Título del correo a enviar
     * @param array $arrayReplace Lista de valores a reemplazar
     * @return string|string
     */
    private function enviarCorreo($templateName, $subject, $arrayReplace)
    {
        try {
            $template = new Devnet_TemplateLoad($templateName);
            $template->replace($arrayReplace);
//            var_dump($templateName);
//            echo "<br><br>";
//            var_dump($subject);
//            echo "<br><br>";
//            var_dump($arrayReplace);
//            echo "<br><br>";
//            var_dump($template->getTemplate());
//            echo "<br><br>";
//            var_dump($this->identity->EMAIL);
//            var_dump($this->identity->EMAIL);
//            exit;
            if (empty($this->getConfig()->correo->disable)) {
                $correo = Zend_Registry::get('mail');
                $correo->addTo($this->identity->EMAIL, $this->identity->APODO)
                        ->setSubject($subject)
                        ->setBodyHtml($template->getTemplate());
                //print_r($template->getTemplate());
                //exit;
                $correo->send();
            }
            return true;
        } catch (Exception $e) {
            $this->log->err($e->getMessage());
            return true;
        }
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function verAction()
    {
        $idTotal = explode('-', $this->_request->getParam('id'));
        $idAviso = $idTotal[0];
        $arrDatos = $this->db->fetchAll('EXEC KO_SP_AVISO_MONEDA_QRY ?', array($idAviso));
        $this->view->aviso = $arrDatos[0];
        $this->session->avisoUrl = $_SERVER['REQUEST_URI'];
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function guardarValoreSession($arraycampos)
    {
        $this->session->aviso = $arraycampos;
        return $this->session->aviso;
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getRepublicacion()
    {
        require_once 'Republicacion.php';
        $republicacion = new Republicacion();
        return $republicacion->getList();
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getMedioPago()
    {
        require_once 'MedioPago.php';
        $medioPago = new MedioPago();
        return $medioPago->getList();
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getMonedas()
    {
        require_once 'Moneda.php';
        $moneda = new Moneda();
        return $moneda->getList();
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getTipoProductos()
    {
        require_once 'TipoProducto.php';
        $tipoProducto = new TipoProducto();
        return $tipoProducto->getList();
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getDuracion($idTipoAviso = null)
    {
        require_once 'TipoAviso.php';
        $tipoAviso = new TipoAviso();
        return $tipoAviso->getTipoAvisoDuracion($idTipoAviso);
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getDestaque($idTipoDestaque)
    {
        require_once 'TipoDestaque.php';
        $tipoDestaque = new TipoDestaque();
        return $tipoDestaque->getTipoDestaque($idTipoDestaque);
    }
    /**
     * Lista de ubigeos
     */
    function listaUbigeoAction ()
    {
        if ($this->_request->isXMLHttpRequest()) {
            require_once 'Ubigeo.php';
            $paramQ = strtolower($this->getRequest()->getParam("term"));
            $result = array();
            $ubigeo = new Ubigeo();
            foreach ($ubigeo->getList($paramQ) as $row) {
                array_push(
                    $result,
                    array(
                        "id" => $row->ID_UBIGEO,
                        "label" => $row->NOM,
                        "value" => strip_tags($row->NOM)
                    )
                );
            }
            $this->_helper->json($result);
        }
    }
    
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getUbigeos()
    {
        require_once 'Ubigeo.php';
        $ubigeo = new Ubigeo();
        return $ubigeo->getList();
    }

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function temimgAction()
    {
        $this->_helper->layout->setLayout('simple');
        $arrConfig = $this->getConfigIni();
        $imd = $arrConfig->fileshare->toArray();
        $this->view->imd = $imd;
        $this->view->img = $this->session->img;
        $this->view->idUsr = $this->identity->ID_USR;
    }

    /**
     * Acción de eliminar aviso
     * @param type name desc
     * @uses Clase::metodo()
     * @return type desc
     */
    public function eliminarAvisoAction()
    {
        if ($this->_request->isXMLHttpRequest()) {
            require_once 'Aviso.php';
            $aviso = new Aviso();
            //$datosAviso=$aviso->existAviso($this->_request->getParam('cod'),$this->identity->ID_USR);
            //echo $this->identity->ID_USR; die;
            $result = $aviso->deleteAviso($this->_request->getParam('cod'), $this->identity->ID_USR);
            // print_r($result);die;
            $code = $result->K_ERROR;
            $msg = $result->K_MSG; //'El aviso ha sido eliminado satisfactoriamente';
            /* $this->view->mensaje = $result->MSJ;
              if (count($datosAviso) > 0) {
              if ($datosAviso->EST == 1 || $datosAviso->EST == 2) {
              $aviso->desactivarAviso($this->_request->getParam('cod'),$this->identity->ID_USR);
              $code=0;
              $msg='El aviso se desactivo correctamente';
              $data=$aviso->existAviso($this->_request->getParam('cod'),$this->identity->ID_USR);
              //$this->_redirect($this->view->baseUrl() . '/usuario/venta/inactivas/estado/16');
              } else {
              $code=1;
              $msg='No se puede cambiar el estado de este aviso';
              $data=$datosAviso;
              }
              } else {
              $code=1;
              $msg='No existe el aviso';
              } */

            if ($code == 0) {
                $this->json(array('code' => $code, 'msg' => $msg));
                $this->_redirect($this->view->baseUrl() . '/usuario/venta/inactivas');
            } else {
                $this->json(array('code' => $code, 'msg' => $msg));
            }
            //$this->view->response = array('code' => $code, 'msg' => $msg, 'data' => $data);
        }
    }

    /**
     * Carga el tipo de Publicacion que se esta manejando
     * @return void
     */
    public function loadPublicacionEstado()
    {
        if (!isset($this->session->publicacionEstado))
            $this->session->publicacionEstado = 1;
        $this->view->publicacionEstado = $this->session->publicacionEstado;
    }

    /**
     * Carga el tipo de Publicacion que se esta manejando
     * @return void
     */
    public function darPermisosAction()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $imd = $frontController->getParam('bootstrap')->getOption('fileshare');
        $imageManagement = new Devnet_ImageManagement($imd['host'], $imd['username'], $imd['password']);
        $imageManagement->openFtp();
        $imageManagement->getPermisos($this->_request->getParam('nomFichero'));
        $imageManagement->closeFtp();
    }

    /**
     * Concatena la ruta de la imagen adecuada
     * @param pe name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    function getRutaImagen($tipoImagen='thumbnails')
    {
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')
                        ->getOption('fileshare');
        return $urlImagen = $fileshare['url'] . '/' . $fileshare[$tipoImagen] . '/';
    }

    function validarCategoria($rubro, $subrubroOne, $subrubroTwo, $subrubroThree)
    {
        $aviso = new Aviso();
        //print_r(array($rubro, $subrubro1, $subrubro2, $subrubro3));
        $rubro = (($rubro == '') or ($rubro == null)) ? 0 : $rubro;
        if ($rubro == 0)
            return false;
        $subrubroOne = (($subrubroOne == '') or ($subrubroOne == null)) ? 0 : $subrubroOne;
        $subrubroTwo = (($subrubroTwo == '') or ($subrubroTwo == null)) ? 0 : $subrubroTwo;
        $subrubroThree = (($subrubroThree == '') or ($subrubroThree == null)) ? 0 : $subrubroThree;
        $rubroFinal = ($subrubroThree != 0) 
            ? $subrubroThree : (($subrubroTwo != 0) 
                ? $subrubroTwo : (($subrubroOne != 0) ? $subrubroOne : $rubro));
        //echo $rubroFinal;
        $rubroDb = $aviso->getCategoriaAviso($rubroFinal);
        if (
            ($rubroDb[0][0] != $rubro) or ($rubroDb[1][0] != $subrubroOne) or 
            ($rubroDb[2][0] != $subrubroTwo) or ($rubroDb[3][0] != $subrubroThree)
        )
            return false;
        return true;
    }
    
    private function _validarDestaque($destaque)
    {
        require_once 'Destaque.php';

        $objDestaque = new Destaque();
        return $objDestaque->getDestaque($destaque);
    }
    
    public function textoAvisoAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->_request->isXmlHttpRequest()) {
            require_once('AvisoImpreso.php');
            $avisoImpreso = new AvisoImpreso($this->getRequest()->getParams());
            echo Zend_Json::encode($avisoImpreso->getImpreso());
        }
    }
    
    public function buscarCiudadAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            echo '[]';
            return false;
        }
        $post = $this->getRequest()->getParams();
        
        if (!empty($post['q'])) {
            $busquedaCiudadModelo = new Application_Model_BusquedaCiudadModelo();
            echo '[' . implode(',', $busquedaCiudadModelo->buscar($post['q'], $post['n'])) . ']';
        }
    }
    
    function boxTipoDestaqueAction ()
    {
            $this->_helper->layout()->disableLayout();
            $img = array(
                1 => array('img'=>'highlight-free.jpg', 'title'=>'Tu aviso Básico en la web'),
                2 => array('img'=>'highlight-silver.jpg', 'title'=>'Tu aviso Silver en la web'),
                3 => array('img'=>'highlight-gold.jpg', 'title'=>'Tu aviso Gold en la web', 'flag'=>1),
                4 => array(
                    'img'=>'highlight-platinium.jpg',
                    'title'=>'Tu aviso Platinum en la web',
                    'flag'=>1
                )
            );
            $this->view->data = $img[$this->getRequest()->getParam('id')];
    }	
}