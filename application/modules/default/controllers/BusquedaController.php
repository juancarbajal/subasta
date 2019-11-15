<?php
/**
 * Busqueda class file
 *
 * PHP Version 5.3
 *
 * @category Busqueda
 * @package  Busqueda
 * @author   @author Ander <anderson.poccorpachi@ec.pe>
 * @link     http://kotear.pe/busqueda
 */
require 'Busqueda.php';
require 'Categoria.php';
require 'Ubigeo.php';

/**
 * Busqueda class
 *
 * The class holding the root Recipe class definition
 *
 * @category Busqueda
 * @package  Busqueda
 * @author   @author Ander <anderson.poccorpachi@ec.pe>
 * @link     http://kotear.pe/busqueda
 */
class BusquedaController extends Devnet_Controller_Action_Default
{
    public $defaultFiltro;
    
    /**
     * Index default
     * 
     * Realiza la busqueda por parametros enviados
     * 
     * @return busqueda
     */
    public function indexAction() 
    {
        if ($this->_request->isGet()) {
            $defaultFiltro = '';
            $this->view->urlbusqueda = $_SERVER["REQUEST_URI"];
            $paramQ = $this->_request->getParam('q', '');            
            
            $categs = $this->_request->getParam('categs', '-1');
            //var_dump($categs);
            //echo '-------------------';
            
            $categsTwo = $this->_request->getParam('categs2', '-1');
            $categsThree = $this->_request->getParam('categs3', '-1');
            $categs = ($categsTwo != -1)?(($categsThree != -1)?$categsThree:$categsTwo):$categs;
            
            //var_dump($categs);
            //exit;
            
            $allParams = $this->_request->getParams();
            $arrayZend = array('controller'=>'busqueda','action'=>'index','module'=>'default','q'=>''
                ,'categs'=>'-1','categoriaText2-global'=>'','categoriaText3-global'=>'');
            $result = array_diff_assoc($allParams, $arrayZend);
            //if($categs == -1 && empty($q))
            if (empty($result)) {
                $this->_redirect($this->view->baseUrl() . '/busqueda/categoria');
            }
            
            $parametros = $this->_parametrosBusqueda($allParams, $defaultFiltro);
            $parametros['categs'] = $categs;
            //var_dump($parametros['categs']);
            //exit;
            
            
            //echo count($parametros); exit;
            //$palabraBuscada = $this->view->utils->repeticionesPalabras($parametros['q']); 
            // en caso de letra repetida, sera solo un caracter
            //
            // Metodo de validacion
            $procesarBusquedaTipo = 'FILTRADO';
            if (count($parametros) == 5) {
                if (isset($categs) && (($categs == $defaultFiltro) || ($categs == -1))) {
                    if (isset($paramQ) && ($paramQ <> '')) {
                        if (strlen($paramQ) < 2) {
                            $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                        } else {
                            // Se realiza la busqueda por categs y q
                            $procesarBusquedaTipo = 'CENTRAL';
                        }
                    } else {
                        $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                    }
                } else {
                    // Busqueda con categoria, por defecto el q deberia estar en blanco
                    if (isset($paramQ) && ($paramQ <> '')) {
                        // En caso q no este en blanco, validar que no sea menor a 2
                        if (strlen($paramQ) < 2) {
                            $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                        } else {
                            // Se realiza la busqueda por categs y q
                            $procesarBusquedaTipo = 'CENTRAL';
                        }
                    } else {
                        // Se realiza la busqueda por categs y q
                        $procesarBusquedaTipo = 'CENTRAL';
                    }
                }
            } else {
                if ((count($parametros) == 4) && (isset($paramQ)) && (strlen($paramQ) < 2)) {
                        $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                }
            }
            
            // Verificamos la categoria ingresada
            if ((isset($parametros['categs'])) && $parametros['categs'] > 0) {
                $mCategoria = new Categoria();
                $categoria = $mCategoria->getCategoriaId($parametros['categs']);
                //var_dump($categoria);
                //exit;
                
                if ($categoria[0]->ADULTO == 1 && $this->session->aceptaContenidoAdulto <> 1) {
                    $this->_redirect($this->view->baseUrl() . '/adultos');
                } else {
                    $this->view->aceptaContenidoAdulto = $this->session->aceptaContenidoAdulto;
                }
                $nombreCategoria = $categoria[0]->TIT . ' - ';
                $nivelCategoria = $categoria[0]->NIVEL;
                //Sacamos la categoria del aviso en caso se busque por categorias
                include_once 'Agrupador.php';
                $cat = new Agrupador();
                $this->view->menuCategorias = $cat->getLevels($parametros['categs']);
            } else {
                $this->view->menuCategorias = 0;
            }

            // Verificamos el apodo, para la colocacion en el titulo
            if (isset($parametros['apodo']) && $parametros['apodo'] <> '') {
                $this->view->usuarioExiste = 1;
                include_once 'UsuarioPortal.php';
                $usuarioExiste = new UsuarioPortal();
                $this->view->textoVendedor = 'Vendedor ' . $parametros['apodo'];
                if ($usuarioExiste->existeApodo($parametros['apodo']) == 0) {
                    $this->view->usuarioExiste = 0;
                }
            }

            // Generamos el titulo correcto que deberia visualizarse
            $titulo = $nombreCategoria;

            if (isset($parametros['q']) && ($parametros['q'] <> '' && strlen($parametros['q']) > 1)) {
                include_once 'Devnet/StandardAnalyzer/SpanishStemmer.php';
                $stemm = new StandardAnalyzer_SpanishStemmer();
                $busqueda = $this->view->utils->repeticionesPalabras($parametros['q']);
                //validamos la longitud de la palabra que queda, al menos debe ser 1 caracter
                if (strlen($busqueda) > 0) {
                    $parametros['q'] = implode(' ', $stemm->getTags($busqueda));
                    if ($parametros['q'] <> null) {
                        $this->view->textoBusqueda = $paramQ;
                        $this->view->headTitle($titulo . $paramQ . ' | Kotear.pe');
                        $this->view->headMeta()->appendName('keywords', $titulo . $paramQ);
                        $this->view->headMeta()->appendName('description', $titulo . $paramQ);
                    } else {
                        $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                    }
                } else {
                    $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                }
            } else {
                $this->view->headTitle($nombreCategoria . 'Búsqueda avanzada | Kotear.pe');
                $this->view->headMeta()->appendName(
                    'keywords', $nombreCategoria . 'Búsqueda avanzada | Kotear.pe'
                );
                $this->view->headMeta()->appendName(
                    'description', $nombreCategoria . 'Búsqueda avanzada | Kotear.pe'
                );
            }

            // Variable de aceptacion de contenido adulto, nos permite saber el tipo de busqueda
            if (isset($this->session->aceptaContenidoAdulto) && $this->session->aceptaContenidoAdulto == 1) {
                $parametros['adulto'] = $this->session->aceptaContenidoAdulto;
            } else {
                $parametros['adulto'] = 0;
            }

            // Realizamos la busqueda, verificando que tipo de proceso de busqueda debemos de realizar
            
            $avisosTotal = $this->_buscarAvisos($parametros, $defaultFiltro, $procesarBusquedaTipo);
            
            //var_dump($avisosTotal);exit;
            
            $avisosRegistrosTotal = 0;
            foreach ($avisosTotal as $i) {
                if ($i->indicador == 1) {
                    $avisos[] = $i;
                    $avisosRegistrosTotal ++;
                } elseif ($i->indicador == 3) {
                    $ubigeos[] = $i;
                } elseif ($i->indicador == 4) {
                    $categorias[] = $i;
                } else {
                    $adulto[] = $i;
                }
            }
            //if ($procesarBusquedaTipo <> 'CENTRAL' && $procesarBusquedaTipo == 'PRUEBA-BORRAR') {
            $avisosRegistrosTotal = (!isset($avisos[0]->TOTAL)) ? 0 : $avisos[0]->TOTAL;
            
            //var_dump($avisos);Exit;
            
            //}
            $this->view->paginatorTotalRegistros = $avisosRegistrosTotal;

            //Validamos contenido adulto y total de avisos
            $this->view->resultadoContenidoAdulto = $adulto[0]->TOTAL;
            $this->view->resultadoTotalAvisos = $avisosRegistrosTotal;

            if ($avisosRegistrosTotal <= 0) {
                if ($adulto[0]->TOTAL > 0) {
                    //No existen avisos pero existen en adultos
                    unset($avisos);
                } else {                    
                    // Realizamos la busqueda por la palabra corregida, obtenemos la palabra corregida
                    /*require_once 'DiccionarioTag.php';
                    $dt = new DiccionarioTag();
                    $corregida = $dt->getPalabraBuscada($busqueda);
                    if ($corregida->PALABRA_CORREG <> NULL && $corregida->NRO_RESULT <> 0) {
                        $busquedaCorregida = 1;
                        $palabraCorregida = $corregida->PALABRA_CORREG;
                        $this->view->palabraCorregida = $palabraCorregida;
                        $parametros['q'] = implode(' ',$stemm->getTags($palabraCorregida));
                        // Buscamos avisos con la palabra corregida
                      $avisosTotal = $this->_buscarAvisos($parametros, $defaultFiltro, $procesarBusquedaTipo);
                    }*/
                }
            }

            // Validar el ingreso de una frase o palabra buscada
            if (isset($busqueda) && $busqueda<>'') {
                $this->_registrarPalabrasBuscadas($busqueda, $avisosRegistrosTotal);
                $parametros['q'] = $busqueda;
            } else {
                $parametros['q'] = '';
            }

            $page = $this->_request->getParam('page', 1);
            
            if (!isset($page) || ($page == $default)) {
                    $page = 1;
                    $parametros['page'] = $page;
            } else {
                if ($avisosRegistrosTotal > $itemCountPerPage) {
                    $page = $this->_request->getParam('page', 1);
                    $parametros['page'] = $page;                    
                } else {
                     $page = 1;
                     $parametros['page'] = $page;                     
                }
            }
            $this->view->paginaActual = $page;
            // Generamos la url de variables
            $validaNivel = 0;
            $this->view->variables = '';
            foreach ($parametros as $p => $v) :
                $this->view->variablesVista = $this->view->variablesVista . '&' . $p . '=' . $v;
                if (($v <> $defaultFiltro) && (!in_array($p, array('categs', 'adulto')))) {
                    $validaNivel = 1;
                }
                if ($p == 'page') {
                    $this->view->variables = $this->view->variables . '&page=1';
                } else {
                    $this->view->variables = $this->view->variables . '&' . $p . '=' . $v;
                }
            endforeach;
            $this->view->rutaimagen = $this->_getRutaImagen();            
            /****************************************************************/
            // Generamos los filtros dinamico
            $this->_filtroCategorias($parametros, $categorias, $nivelCategoria, $validaNivel, $defaulFiltro);
            $this->_filtrosBusqueda($parametros, $ubigeos, $defaulFiltro);
            /***************************************************************/
            if ($avisosRegistrosTotal > 0) {
                // Set pagination settings
                $itemCountPerPage = 30;
                $pageRange = 10;
                //if ($procesarBusquedaTipo <> 'CENTRAL' && $procesarBusquedaTipo == 'PRUEBA-BORRAR') {
                $maximo = $page * $itemCountPerPage;
                $minimo = $maximo - ($itemCountPerPage - 1);
                $varJ = 0;
                for ($i=1;$i<=$avisosRegistrosTotal;$i++) {
                    if ($i >= $minimo && $i <= $maximo) {
                        $arrAvisos[$i] = $avisos[$varJ];
                        $varJ++;
                    } else {
                        $arrAvisos[$i] = null;
                    }
                }
                //$avisos = $avisos1;
                //}
                // Create paginator
                //$paginator = Zend_Paginator::factory($avisos, $adapter);
                $paginator = Zend_Paginator::factory($arrAvisos);
                /*$fO = array('lifetime' => 300, 'automatic_serialization' => true);
                $bO = array('cache_dir'=>'../var/cache');
                $cache = Zend_cache::factory('Core', 'File', $fO, $bO);
                Zend_Paginator::setCache($cache);*/
                $paginator->setItemCountPerPage($itemCountPerPage)
                    ->setCurrentPageNumber($page)
                    ->setPageRange($pageRange);
                //$paginator->setRowCount($avisos);
                // Create paginator control partial view
                Zend_View_Helper_PaginationControl::setDefaultViewPartial('busqueda/paginador.phtml');
                $this->view->avisos = $paginator;
                /***************************************************************************/
                $this->view->parametros = $allParams;
                //$paginator->setCacheEnabled('false');
                // Borra todos los items de la cache, deberia ser al comenzar una nueva busqueda
                //$paginator->clearPageItemCache();
                $this->session->parametrosPaginador = $parametros;
                $this->view->parametrosPaginadorItem = $this->session->parametrosPaginador;
                /*30-07-2010 : Cache*/
                //                $paginator->setCacheEnabled(false);
                /*30-07-2010 : Cache Fin*/
            }
        } else {
            $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
        }        
    }
    
    /**
     * Permite obtener filtros dinamicos de las categorias
     * 
     * @param string $parametros   Description
     * @param string $categorias   Description
     * @param string $nivel        Description
     * @param string $validaNivel  Description
     * @param string $defaulFiltro Description
     * 
     * @return categorias
     */
    private function _filtroCategorias($parametros, $categorias, $nivel, $validaNivel, $defaulFiltro) 
    {
        unset($filtro);
        for ($i=0; $i<=count($categorias);$i++) {
            if ($i == 0) {
                if ($nivel == 1 && $validaNivel <> 1) {
                    $filtro[$i]['NOMBRE'] = 'Arbol de Categorias';
                    $filtro[$i]['URL'] = $this->view->baseUrl() . '/busqueda/categoria';
                    $filtro[$i]['BASEURL'] = 'q=';
                } else {
                    $filtro[$i]['NOMBRE'] = 'Categoría';
                    $filtro[$i]['URL'] = $i-1;
                    $filtro[$i]['BASEURL'] = str_replace(
                        "categs=" . $parametros['categs'], "categs=" . $defaulFiltro, $this->view->variables
                    );
                }
            } else {
                if ($nivel <> 1) {
                    $filtro[$i]['NOMBRE'] = $categorias[$i-1]->DESCRIPCION . ' (' . 
                        $categorias[$i-1]->TOTAL .')';
                } else {
                    $filtro[$i]['NOMBRE'] = $categorias[$i-1]->DESCRIPCION . ' (' . 
                        $categorias[$i-1]->TOTAL .')';
                }
                $filtro[$i]['URL'] = $categorias[$i-1]->ID_AVISO;
                $filtro[$i]['BASEURL'] = str_replace(
                    "categs=" . $parametros['categs'], "categs=" . $categorias[$i-1]->ID_AVISO, 
                    $this->view->variables
                );
            }
            if ($nivel <> 1 && $i == 0) {
                $filtro[$i]['ACTIVE'] = 'active';
            } else {
                if (($parametros['categs'] == $filtro[$i]['URL'])) {
                    $filtro[$i]['ACTIVE'] = 'active';
                }
            }
        }
        $this->view->categoriasL2 = $filtro;
    }

    /**
     * Permite obtener los filtros de una busqueda
     * 
     * @param string $parametros   Description
     * @param string $ubigeos      Description
     * @param string $defaulFiltro Description
     * 
     * @return filtros
     */
    private function _filtrosBusqueda($parametros, $ubigeos, $defaulFiltro) 
    {
        unset($filtro);
        for ($i=0; $i<=count($ubigeos);$i++) {
            if ($i == 0) {
                $filtro[$i]['NOMBRE'] = 'Ubicación';
                $filtro[$i]['URL'] = $defaulFiltro;
                $filtro[$i]['BASEURL'] = str_replace(
                    "ubic=" . $parametros['ubic'], "ubic=" . $defaulFiltro, $this->view->variables
                );
            } else {
                $filtro[$i]['NOMBRE'] = $ubigeos[$i-1]->DESCRIPCION . ' (' . $ubigeos[$i-1]->TOTAL .')';
                $filtro[$i]['URL'] = $ubigeos[$i-1]->ID_AVISO;
                $filtro[$i]['BASEURL'] = str_replace(
                    "ubic=" . $parametros['ubic'], "ubic=" . $ubigeos[$i-1]->ID_AVISO, $this->view->variables
                );
            }
            if ($parametros['ubic'] == $filtro[$i]['URL']) {
                $filtro[$i]['ACTIVE'] = 'active';
            }
        }
        $this->view->ubigeos = $filtro;

        unset($filtro);
        $vistas = array (1 => array('NOMBRE' => 'Lista',
                        'VALOR' => 1),
                            2 => array('NOMBRE' => 'Galeria',
                                    'VALOR' => 2)
        );
        for ($i=1; $i<=count($vistas);$i++) {
            $filtro[$i]['NOMBRE'] = $vistas[$i]['NOMBRE'];
            $filtro[$i]['URL'] = $vistas[$i]['VALOR'];
            $filtro[$i]['BASEURL'] = str_replace(
                "vw=" . $parametros['vw'], "vw=" . $i, $this->view->variablesVista
            );
            if ($parametros['vw'] == $filtro[$i]['URL']) {
                    $filtro[$i]['ACTIVE'] = 'active';
            } else {
                if ($parametros['vw'] == $defaulFiltro && $parametros['vw'] <> 2) {
                    $filtro[1]['ACTIVE'] = 'active';
                }
            }
        }
        $this->view->vistas = $filtro;


        include_once 'TipoProducto.php';
        $tipoProducto = new TipoProducto();
        unset($filtro);
        $registros = $tipoProducto->getList();
        for ($i=0; $i<=count($registros);$i++) {
            if ($i == 0) {
                $filtro[$i]['NOMBRE'] = 'Estado';
                $filtro[$i]['URL'] = $defaulFiltro;
                $filtro[$i]['BASEURL'] = str_replace(
                    "tp=" . $parametros['tp'], "tp=" . $defaultFiltro, $this->view->variables
                );
            } else {
                $filtro[$i]['NOMBRE'] = $registros[$i-1]->DES;
                $filtro[$i]['URL'] = $registros[$i-1]->ID_TIPO_PRODUCTO;
                $filtro[$i]['BASEURL'] = str_replace(
                    "tp=" . $parametros['tp'], "tp=" . $i, $this->view->variables
                );
            }
            if ($parametros['tp'] == $filtro[$i]['URL']) {
                $filtro[$i]['ACTIVE'] = 'active';
            }
        }
        $this->view->estados = $filtro;
        
        include_once 'Moneda.php';
        $monedas = new Moneda();
        unset($filtro);
        $registros = $monedas->getBusquedaList();
        for ($i=0; $i<=count($registros);$i++) {
            if ($i == 0) {
                $filtro[$i]['NOMBRE'] = 'Moneda';
                $filtro[$i]['URL'] = $defaulFiltro;
                $filtro[$i]['BASEURL'] = str_replace(
                    "tm=" . $parametros['tm'], "tm=" . $defaultFiltro, $this->view->variables
                );
            } else {
                $filtro[$i]['NOMBRE'] = $registros[$i-1]->DES_CORTA . ' ' . $registros[$i-1]->SIMB;
                $filtro[$i]['URL'] = $registros[$i-1]->ID_MONEDA;
                $filtro[$i]['BASEURL'] = str_replace(
                    "tm=" . $parametros['tm'], "tm=" . $i, $this->view->variables
                );
            }
            if ($parametros['tm'] == $filtro[$i]['URL']) {
                $filtro[$i]['ACTIVE'] = 'active';
            }
        }
        $this->view->monedas = $filtro;

        /*
        require_once 'UsuarioRango.php';
        $usuarioRango = new UsuarioRango();
        unset($filtro);
        $registros = $usuarioRango->getUsuarioRangos();
        for ($i=0; $i<=count($registros);$i++) {
            if ($i == 0) {
                $filtro[$i]['NOMBRE'] = 'Reputación Indistinta';
                $filtro[$i]['URL'] = $defaulFiltro;
                $filtro[$i]['BASEURL'] = str_replace("rep=" . $parametros['rep'],"rep=" . $defaultFiltro, 
                    $this->view->variables);
            }
            else {
                $filtro[$i]['NOMBRE'] = $registros[$i-1]->DES;
                $filtro[$i]['URL'] = $registros[$i-1]->RANGO_INICIAL;
                $filtro[$i]['BASEURL'] = str_replace(
                "rep=" . $parametros['rep'],"rep=" . $registros[$i-1]->RANGO_INICIAL, $this->view->variables);
            }
            if ($parametros['rep'] == $filtro[$i]['URL']) {
                $filtro[$i]['ACTIVE'] = 'active';
            }
        }
        $this->view->reputaciones = $filtro;

        require_once 'UsuarioPortal.php';
        $tipoUsuario = new UsuarioPortal();
        unset($filtro);
        $registros = $tipoUsuario->getTipoUsuario();
        for ($i=0; $i<=count($registros);$i++) {
            if ($i == 0) {
                $filtro[$i]['NOMBRE'] = 'Vendedor Indistinto';
                $filtro[$i]['URL'] = $defaulFiltro;
                $filtro[$i]['BASEURL'] = str_replace("tv=" . $parametros['tv'],"tv=" . $defaultFiltro, 
                    $this->view->variables);
            }
            else {
                $filtro[$i]['NOMBRE'] = $registros[$i-1]->DES;
                $filtro[$i]['URL'] = $registros[$i-1]->ID_TIPO_USUARIO;
                $filtro[$i]['BASEURL'] = str_replace("tv=" . $parametros['tv'],"tv=" . $i, 
                    $this->view->variables);
            }
            if ($parametros['tv'] == $filtro[$i]['URL']) {
                $filtro[$i]['ACTIVE'] = 'active';
            }
        }
        $this->view->tiposvendedor = $filtro;
        */

        $precios = array (0 => array('NOMBRE' => 'Precio',
                        'PMIN' => $defaulFiltro,
                        'PMAX' => $defaulFiltro,
                ),
                1 => array('NOMBRE' => 'Menos de 200',
                        'PMIN' => 1,
                        'PMAX' => 200,
                ),
                2 => array('NOMBRE' => 'entre 200 y 400',
                        'PMIN' => 200,
                        'PMAX' => 400,
                ),
                3 => array('NOMBRE' => 'entre 400 y 1000',
                        'PMIN' => 400,
                        'PMAX' => 1000,
                ),
                4 => array('NOMBRE' => 'más de 1000',
                        'PMIN' => 1000,
                        'PMAX' => 1000000,
                )
        );
        unset($filtro);
        for ($i=0; $i<count($precios);$i++) {
            if ($i == 0) {
                $filtro[$i]['NOMBRE'] = $precios[$i]['NOMBRE'];
                $filtro[$i]['URL'] = $defaulFiltro;
                $filtro[$i]['BASEURL'] = str_replace(
                    "pmin=" . $parametros['pmin'] . '&' . "pmax=" . $parametros['pmax'], "pmin=" . 
                    $defaultFiltro . "&pmax=" . $defaultFiltro, $this->view->variables
                );
            } else {
                $filtro[$i]['NOMBRE'] = $precios[$i]['NOMBRE'];
                $filtro[$i]['URL'] = $precios[$i]['PMIN'];
                $filtro[$i]['BASEURL'] = str_replace(
                    "pmin=" . $parametros['pmin'] . '&' . "pmax=" . $parametros['pmax'], "pmin=" . 
                    $precios[$i]['PMIN'] . '&' . "pmax=" . $precios[$i]['PMAX'], $this->view->variables
                );
            }
            if ($parametros['pmin'] == $filtro[$i]['URL']) {
                $filtro[$i]['ACTIVE'] = 'active';
            }
        }
        $this->view->precios = $filtro;

        $ordenamientos = array (0 => array('NOMBRE' => 'Ordenados por defecto',
                        'VALOR' => $defaultFiltro),
                1 => array('NOMBRE' => 'Ordenados por menor precio',
                        'VALOR' => 1),
                2 => array('NOMBRE' => 'Ordenados por mayor precio',
                        'VALOR' => 2),
                3 => array('NOMBRE' => '',
                        'VALOR' => 3),
                4 => array('NOMBRE' => '',
                        'VALOR' => 4),
                5 => array('NOMBRE' => 'Ordenados por menos visitados',
                        'VALOR' => 5),
                6 => array('NOMBRE' => 'Ordenados por más visitados',
                        'VALOR' => 6)
        );
        unset($filtro);
        for ($i=0; $i<=count($ordenamientos);$i++) {
            if ($i == 0) {
                $filtro[$i]['NOMBRE'] = $ordenamientos[$i]['NOMBRE'];
                $filtro[$i]['URL'] = $defaulFiltro;
                $filtro[$i]['BASEURL'] = str_replace(
                    "ord=" . $parametros['ord'], "ord=" . $defaultFiltro, $this->view->variables
                );
            } else {
                $filtro[$i]['NOMBRE'] = $ordenamientos[$i]['NOMBRE'];
                $filtro[$i]['URL'] = $ordenamientos[$i]['VALOR'];
                $filtro[$i]['BASEURL'] = str_replace(
                    "ord=" . $parametros['ord'], "ord=" . $i, $this->view->variables
                );
            }
            if ($parametros['ord'] == $filtro[$i]['URL']) {
                $filtro[$i]['ACTIVE'] = 'active';
            }
        }
        $this->view->ordenamientos = $filtro;
    }

    /**
     * Permite redistribuir los parametros enviados para la busqueda
     * 
     * @param string $parametros Description
     * @param string $default    Description
     * 
     * @return parametros
     */
    private function _parametrosBusqueda($parametros, $default) 
    {
        if (!isset($parametros['id'])) {
            $array['id'] = $default;
        } else {
            $array['id'] = $default;
        }
        
        if (!isset($parametros['q'])) {
            $array['q'] = '';
        } else {
            $array['q'] = $parametros['q'];
        }
        
        if (isset($parametros['exclude']) && $parametros['exclude'] <> $default) {
            $array['exclude'] = $parametros['exclude'];
        } else {
            $array['exclude'] = '';
        }
        
        if (isset($parametros['categs']) && $parametros['categs'] <> $default) {
            $array['categs'] = $parametros['categs'];
        } else {
            $array['categs'] = $default;
        }
        
        if (isset($parametros['usuario']) && $parametros['usuario'] <> $default) {
            $array['usuario'] = $parametros['usuario'];
        } else {
            $array['usuario'] = '';
        }
        
        if (isset($parametros['apodo']) && $parametros['apodo'] <> $default) {
            $array['apodo'] = $parametros['apodo'];
        } else {
            $array['apodo'] = '';
        }
        
        if (isset($parametros['tv']) &&  $parametros['tv'] <> $default) {
            if ( $parametros['tv'] == '-1') {
                $array['tv'] = $default;
            } else {
                $array['tv'] = $parametros['tv'];
            }
        } else {
            $array['tv'] = $default;
        }
        
        if (isset($parametros['rep']) && $parametros['rep'] <> $default) {
            if ( $parametros['rep'] == '-1') {
                $array['rep'] = $default;
            } else {
                $array['rep'] = $parametros['rep'];
            }
        } else {
            $array['rep'] =  $default;
        }
        
        if (isset($parametros['pmin']) && $parametros['pmin'] <> $default) {
            $array['pmin'] = $parametros['pmin'];
        } else {
            $array['pmin'] = $default;
        }
        
        if (isset($parametros['pmax']) && $parametros['pmax'] <> $default) {
            $array['pmax'] = $parametros['pmax'];
        } else {
            $array['pmax'] = $default;
        }
        // variable de tipo moneda
        if (!isset($parametros['tm'])) {
            $array['tm'] = $default;
        } else {
            $array['tm'] = $parametros['tm'];
        }
        // variables de ubicaciones
        if (isset($parametros['ubic']) && $parametros['ubic'][0] <> $default) {
            if ($parametros['ubic'] <> '' && isset($parametros['ub'])) {
                if ($parametros['ubic'][0] == -1 && isset($parametros['ubic'])) {
                    $array['ubic'] = $default;
                } else {
                    $array['ubic'] = implode(',', $parametros['ubic']);
                }
            } else {
                $array['ubic'] = $parametros['ubic'];
            }
        } else {
            $array['ubic'] = $default;
        }
        // variables de tipo producto
        if (isset($parametros['tp1'])) {
            if (($parametros['tp1']==1) && (!isset($parametros['tp2']))) {
                $array['tp'] = 1;
            } else {
                $array['tp'] = $default;
            }
        } else {
            if (isset($parametros['tp2'])) {
                if (($parametros['tp2']==2) && (!isset($parametros['tp1']))) {
                    $array['tp'] = 2;
                } else {
                    $array['tp'] = $default;
                }
            } else {
                if (isset($parametros['tp'])) {
                    $array['tp'] = $parametros['tp'];
                } else {
                    $array['tp'] = $default;
                }
            }
        }
        // variables de tipo aviso, verificamos si existe
        if (isset($parametros['ta1'])) {
            if (($parametros['ta1']==1) && (!isset($parametros['ta2']))) {
                $array['ta'] = $parametros['ta1'];
            } else {
                $array['ta'] = $default;
            } 
        } else {
            if (isset($parametros['ta2'])) {
                if (($parametros['ta2']==2) && (!isset($parametros['ta1']))) {
                    $array['ta'] = $parametros['ta2'];
                } else {
                    $array['ta'] = $default;
                }
            } else {
                if (isset($parametros['ta'])) {
                    $array['ta'] = $parametros['ta'];
                } else {
                    $array['ta'] = $default;
                }
            }
        }
        // variable de visualizacion de modulos
        if (isset($parametros['mod']) &&  $parametros['mod'] <> $default) {
            $array['mod'] = $parametros['mod'];
        } else {
            $array['mod'] = $default;
        }
        // variable de ordenamiento por precio
        if (!isset($parametros['ord'])) {
            $array['ord'] = $default;
        } else {
            $array['ord'] = $parametros['ord'];
        }
        // variable de visualizacion de vistas
        if (isset($parametros['vw']) &&  $parametros['vw'] <>  $default) {            
            $array['vw'] = $parametros['vw'];
        } else {
            $array['vw'] = $default;
        }
        // variable de visualizacion de paginas
        if (isset($parametros['page']) &&  $parametros['page'] <>  $default) {
            $array['page'] = $parametros['page'];
        } else {
            $array['page'] = 1;//$default;
        }
        return $array;
    }

    /**
     * Visualiza datos generados de busqueda avanzada
     * 
     * @return valor
     */
    public function avanzadaAction() 
    {
        $this->_redirect($this->view->baseUrl());
        /*$this->view->headTitle('Búsqueda avanzada | Kotear.pe');
        $categorias = new Categoria();
        $this->view->categoriasBuscador = $categorias->getCategoriasL1(-1);
        
        //$this->view->categoriasBuscador = $categorias->getCategoriaCombo();
        $ubigeos = new Ubigeo();
        $this->view->listarCiudades = $ubigeos->getListCiudadesActivas();
        include_once 'UsuarioPortal.php';
        $tipoVendedor = new UsuarioPortal();
        $this->view->listarTiposUsuario = $tipoVendedor->getTipoUsuario();
        include_once 'TipoCambio.php';
        $tipoCambio = new TipoCambio();
        $cambio = $this->view->tipoCambio = $tipoCambio->getTipoCambio();
        include_once 'UsuarioRango.php';
        $usuarioRangos = new UsuarioRango();
        $cambio = $this->view->usuarioRangos = $usuarioRangos->getUsuarioRangos();*/
    }

    /**
     * Realiza la busqueda a traves de tags en todos los avisos de la aplicacion
     * 
     * @param array  $array                Description
     * @param string $defaultFiltro        Description
     * @param string $procesarBusquedaTipo Description
     * 
     * @return valor
     */
    private function _buscarAvisos($array, $defaultFiltro = '-1', $procesarBusquedaTipo = 'CENTRAL') 
    {
        $front = Zend_Controller_Front::getInstance();
        $cache = $front->getParam('bootstrap')->getResource('cachemanager')->getCache('file');
        $arr = $array;
        $arr['defaultFiltro'] = $defaultFiltro;
        $queryString = sha1(implode('_', $arr));
        if (!$result = $cache->load($queryString)) {
            $resultado = new Busqueda();
            $result = $resultado->getBusquedaAvisosPerformance($array, $defaultFiltro, $procesarBusquedaTipo);
            $cache->save($result, $queryString);			 
        }
        return $result;
    }

    /**
     * Permite consultar sobre la base de las palabras buscadas para generar las sugerencias
     * 
     * @return valor
     */
    public function autocompleteAction() 
    {
        if ($this->_request->isXMLHttpRequest()) {
            $parametro = $this->_request->getParams('q');
            $busqueda = new Busqueda();
            $this->json($busqueda->suggestFull($parametro['q']));
            
            /*Busqueda con diccionario*/
            //require_once 'DiccionarioTag.php';
            //$resultado = new DiccionarioTag();
            //$this->json($resultado->getAutocomplete($parametro['q']));
        }
    }

    /**
     * Pagindor
     * 
     * @return valor
     */
    public function paginadorAction () 
    {
        if ($this->_request->isGet()) {
            $this->view->parametrosPaginadorItem = $this->session->parametrosPaginador;
        }
    } //end function

    /**
     * listar
     * 
     * @return valor
     */
    public function listaAction () 
    {
        if ($this->_request->isGet()) {
            $this->view->rutaimagen = $this->_getRutaImagen('thumbnails');
        }
    } //end function

    /**
     * galeria
     * 
     * @return valor
     */
    public function galeriaAction () 
    {
        if ($this->_request->isGet()) {
            $this->view->rutaimagen = $this->_getRutaImagen('thumbs');
        }
    }

    /**
     * Visualiza la busqueda por categoria
     * 
     * @return valor
     */
    public function categoriaAction() 
    {
        $this->view->headTitle('Búsqueda por categorias | Kotear.pe');
        $parametro = $this->_request->getParam('buscar');
        if (isset($parametro)) {
            $this->view->mensaje = 1;
        }
        $mCategoria = new Categoria();
        $this->view->arbolCategoria = $this->getUrlSeo(
            $mCategoria->getCategoriaArbol(-1), 'NOM_CATEGORIA', 'URL'
        );
    }

    /**
     * Registra las palabras buscadas en KO_DICCIONARIO_TAG
     * 
     * @param string $paramQ        Description
     * @param string $nroResultados Description
     * 
     * @return valor
     */
    private function _registrarPalabrasBuscadas ($paramQ, $nroResultados) 
    {
        include_once 'DiccionarioTag.php';
        $tags = new DiccionarioTag();
        $tags->insertPalabraBuscada(array('q' => $paramQ, 'r' => $nroResultados));
    }

    /**
     * Concatena la ruta de la imagen adecuada
     * 
     * @param string $tipoImagen Description
     * 
     * @return valor
     */
    private function _getRutaImagen($tipoImagen='thumbnails') 
    {
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')->getOption('fileshare');
        return $urlImagen = $fileshare['url'] .'/'. $fileshare[$tipoImagen] . '/';
    }
    
    /**
     * Envio de correo por lightbox
     * 
     * @return valor
     */
    public function envioCorreoAction() 
    {
        $this->_helper->layout->setLayout('clear');
        if ($this->_request->isXMLHttpRequest()) {
            try {
                include_once 'AvisoInfo.php';
                $parametros = $this->_request->getParams($this->_request->isXMLHttpRequest());

                $frontController = Zend_Controller_Front::getInstance();
                $app = $frontController->getParam('bootstrap')->getOption('app');

                $aviso = new AvisoInfo();
                $body = '<br/><br/>';
                for ($i=0; $i<count($parametros['result']); $i++) {
                    $avisos[$i] = $aviso->getInfo($parametros['result'][$i]);
                    if ($avisos[$i]->K_TIPO_VENTA <> 'Subasta') {
                        $precio = number_format($avisos[$i]->K_PRECIO_FINAL, 2, '.', ',');
                    } else {
                        $precio = number_format($avisos[$i]->K_PRECIO_BASE, 2, '.', ',');
                    }
                    $body.= "<a href='". $app['url'] . "/aviso/" . $avisos[$i]->K_ID_AVISO . "-" . 
                            $avisos[$i]->K_URL . "'>" . $avisos[$i]->K_TITULO . '</a><br/>';
                    $body.= "Articulo " . $avisos[$i]->K_TIPO_PRODUCTO . " - " . $avisos[$i]->K_TIPO_VENTA . 
                            " - ";
                    $body.= $avisos[$i]->K_SIMB_MONEDA . " " . $precio . " (" . $avisos[$i]->K_UBIGEO . ") " .
                            $avisos[$i]->VENC_CADUCIDAD ."<br/><br/>";
                }
                include_once 'UsuarioPortal.php';
                $mUsuarioPortal = new UsuarioPortal();
                $datosUsuario =  $mUsuarioPortal->find($this->identity->ID_USR);
                $parametros['nombre'] = $datosUsuario->NOM;
                $parametros['mensaje'] = $this->view->escape($this->_request->getParam('message'));
                $parametros['avisos'] = $body;
                $parametros['de'] = $this->_request->getParam('email');
                $parametros['para'] = $this->_request->getParam('email2');
                $parametros['copia'] = $this->_request->getParam('copia');
                $retorno = $this->_enviarCorreoAmigo($parametros);
                if ($retorno == 1) {
                    $this->json(array('code' => 0 , 'msg' => 'Se ha enviado un email a su amigo.'));
                }
            } catch(Exception $e) {
                $this->json(
                    array('code' => 2 , 'msg' => 'Error en en servidor, por favor intente mas tarde.')
                );
            }
        }
    }

    /**
     * enviar correo
     * 
     * @param string $parametros Description
     * 
     * @return valor
     */
    private function _enviarCorreoAmigo($parametros) 
    {
        $frontController = Zend_Controller_Front::getInstance();
        $app = $frontController->getParam('bootstrap')->getOption('app');
        $template = new Devnet_TemplateLoad('envioEmailBusquedaListado');
        $template->replace(
            array(
                '[nombre]' => $this->view->escape($parametros['nombre']),
                '[mensaje]' => $this->view->escape($parametros['mensaje']),
                '[avisos]' => $parametros['avisos']
            )
        );
        $email = Zend_Registry::get('mail');
        $email->setFrom($parametros['de'], $parametros['nombre']);
        $email->addTo($parametros['para'], $parametros['para'])
            ->setSubject('Un amigo te recomienda algo!')
            ->setBodyHtml($template->getTemplate());
        if ($parametros['copia'] == 1) {
            $email->addTo($parametros['de'], $parametros['de']);
        }
        $email->send();
        return 1;
    }

    /**
     * Impresion de avisos seleccionados en listado de busqueda
     * 
     * @return valor
     */
    public function impresionAction() 
    {
        $this->_helper->layout->setLayout('clear');

        $codigosAvisos = explode(',', $this->_request->getParam('avisos'));
        include_once 'AvisoInfo.php';
        $aviso = new AvisoInfo();
        for ($i=0; $i<count($codigosAvisos);$i++) {
            $avisos[$i] = $aviso->getInfo($codigosAvisos[$i], null);
        }        
        $this->view->avisos = $avisos;
        $this->view->rutaimagen = $this->_getRutaImagen();
    }

    /**
     * rss
     * 
     * @return valor
     */
    public function rssAction()
    {
        $this->getResponse()->setHeader('Content-Type', 'application/rss+xml; charset=utf-8');
        //->appendBody($content);
        $this->_helper->layout->setLayout('clear');
        //$this->helper->layout->disableLayout();
        if ($this->_request->isGet()) {
            $defaultFiltro = '';
            $this->view->urlbusqueda = $_SERVER["REQUEST_URI"];
            $paramQ = $this->_request->getParam('q');
            $categs = $this->_request->getParam('categs');
            $parametros = $this->_parametrosBusqueda($this->_request->getParams(), $defaultFiltro);
            //$palabraBuscada = $this->view->utils->repeticionesPalabras($parametros['q']); 
            // en caso de letra repetida, sera solo un caracter
            // Metodo de validacion
            $procesarBusquedaTipo = 'FILTRADO';
            if (count($this->_request->getParams()) == 5) {
                if (isset($categs) && (($categs == $defaultFiltro) || ($categs == -1))) {
                    if (isset($paramQ) && ($paramQ <> '')) {
                        if (strlen($paramQ) < 2) {
                            $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                        } else {
                            // Se realiza la busqueda por categs y q
                            $procesarBusquedaTipo = 'CENTRAL';
                        } 
                    } else {
                        $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                    }
                } else {
                    // Busqueda con categoria, por defecto el q deberia estar en blanco
                    if (isset($paramQ) && ($paramQ <> '')) {
                        // En caso q no este en blanco, validar que no sea menor a 2
                        if (strlen($paramQ) < 2) {
                            $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                        } else {
                            // Se realiza la busqueda por categs y q
                            $procesarBusquedaTipo = 'CENTRAL';
                        }
                    } else {
                        // Se realiza la busqueda por categs y q
                        $procesarBusquedaTipo = 'CENTRAL';
                    }
                }
            } else {
                if ((count($this->_request->getParams()) == 4) && (isset($paramQ)) && (strlen($paramQ) < 2)) {
                        $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                }
            }
            // Verificamos la categoria ingresada
            if ((isset($parametros['categs'])) && $parametros['categs'] > 0) {
                $mCategoria = new Categoria();
                $categoria = $mCategoria->getCategoriaId($parametros['categs']);
                if ($categoria[0]->ADULTO == 1 && $this->session->aceptaContenidoAdulto <> 1) {
                    $this->_redirect($this->view->baseUrl() . '/adultos');
                } else {
                    $this->view->aceptaContenidoAdulto = $this->session->aceptaContenidoAdulto;
                }
                $nombreCategoria = $categoria[0]->TIT . ' - ';
                $nivelCategoria = $categoria[0]->NIVEL;
                //Sacamos la categoria del aviso en caso se busque por categorias
                include_once 'Agrupador.php';
                $cat = new Agrupador();
                $this->view->menuCategorias = $cat->getLevels($parametros['categs']);
            } else {
                $this->view->menuCategorias = 0;
            }

            // Verificamos el apodo, para la colocacion en el titulo
            if (isset($parametros['apodo']) && $parametros['apodo'] <> '') {
                $this->view->textoVendedor = 'Vendedor ' . $parametros['apodo'];
                $apodoVendedor = $this->view->textoVendedor . ' - ';
            }

            // Generamos el titulo correcto que deberia visualizarse
            $titulo = $nombreCategoria;

            if (isset($parametros['q']) && ($parametros['q'] <> '' && strlen($parametros['q']) > 1)) {
                include_once 'Devnet/StandardAnalyzer/SpanishStemmer.php';
                $stemm = new StandardAnalyzer_SpanishStemmer();
                $busqueda = $this->view->utils->repeticionesPalabras($parametros['q']);
                //validamos la longitud de la palabra que queda, al menos debe ser 1 caracter
                if (strlen($busqueda) > 0) {
                    $parametros['q'] = implode(' ', $stemm->getTags($busqueda));
                    if ($parametros['q'] <> null) {
                        $this->view->textoBusqueda = $paramQ;
                        $this->view->headTitle($titulo . $paramQ . ' | Kotear.pe');
                        $this->view->headMeta()->appendName('keywords', $titulo . $paramQ);
                        $this->view->headMeta()->appendName('description', $titulo . $paramQ);
                    } else {
                        $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                    }
                } else {
                    $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
                }
            } else {
                $this->view->headTitle($nombreCategoria . 'Búsqueda avanzada | Kotear.pe');
                $this->view->headMeta()->appendName(
                    'keywords', $nombreCategoria . 'Búsqueda avanzada | Kotear.pe'
                );
                $this->view->headMeta()->appendName(
                    'description', $nombreCategoria . 'Búsqueda avanzada | Kotear.pe'
                );
            }

            // Variable de aceptacion de contenido adulto, nos permite saber el tipo de busqueda
            if (isset($this->session->aceptaContenidoAdulto) && $this->session->aceptaContenidoAdulto == 1) {
                $parametros['adulto'] = $this->session->aceptaContenidoAdulto;
            } else {
                $parametros['adulto'] = 0;
            }

            // Realizamos la busqueda, verificando que tipo de proceso de busqueda debemos de realizar
            $avisosTotal = $this->_buscarAvisos($parametros, $defaultFiltro, $procesarBusquedaTipo);
            $avisosRegistrosTotal = 0;
            foreach ($avisosTotal as $i) :
                if ($i->indicador == 1) {
                    $avisos[] = $i;
                    $avisosRegistrosTotal ++;
                } elseif ($i->indicador == 3) {
                    $ubigeos[] = $i;
                } elseif ($i->indicador == 4) {
                    $categorias[] = $i;
                } else {
                    $adulto[] = $i;
                }
            endforeach;

            //Validamos contenido adulto y total de avisos
            $this->view->resultadoContenidoAdulto = $adulto[0]->TOTAL;
            $this->view->resultadoTotalAvisos = $avisosRegistrosTotal;

            if ($avisosRegistrosTotal <= 0) {
                if ($adulto[0]->TOTAL > 0) {
                    //No existen avisos pero existen en adultos
                    unset($avisos);
                }
            }

            // Validar el ingreso de una frase o palabra buscada
            if (isset($busqueda) && $busqueda<>'') {
                $this->_registrarPalabrasBuscadas($busqueda, count($avisos));
                $parametros['q'] = $busqueda;
            } else {
                $parametros['q'] = '';
            }

            // Generamos la url de variables
            $validaNivel = 0;
            $this->view->variables = '';
            foreach ($parametros as $p => $v) :
                $this->view->variables = $this->view->variables . '&' . $p . '=' . $v;
                if (($v <> $defaultFiltro) && (!in_array($p, array('categs', 'adulto')))) {
                    $validaNivel = 1;
                }
            endforeach;
            $this->view->rutaimagen = $this->_getRutaImagen();

            if ($avisosRegistrosTotal > 0) {
                $this->view->avisos = $avisos;
            }
        } else {
            $this->_redirect($this->view->baseUrl() . '/busqueda/categoria/buscar/todo');
        }
    }
}
