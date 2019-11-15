<?php

/**
 * Descripción Corta
 *
 * Descripción Larga
 *
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer el archivo LICENSE
 * @version    4.0
 * @since      Archivo disponible desde su version 1.0
 */

require_once 'Categoria.php';
require_once 'Modulo.php';
require_once 'DiccionarioTag.php';
require_once 'UsuarioPortal.php';

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

class IndexController
    extends Devnet_Controller_Action_Default
{

    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function indexAction () 
    {
        $datos = $this->_cacheHome();
        foreach ($datos as $key => $val) {
            $this->view->assign($key, $val);
        }
    }
        
    /**
     * Action de la busqueda de Kotear
     * @param type name desc     
     * @uses Clase::methodo()     
     */
    public function busquedaAction ()
    {
        require_once 'Ubigeo.php';
        $mUbigeos = new Ubigeo();                
        $mCategoria = new Categoria();
        $arrCategoria['cat1'] = $mCategoria->getCategoriasActivas(1);
        
        $arrCategoria['cat2Json'] = Zend_Json::encode($mCategoria->getCategoriasActivas(2));
        $arrCategoria['cat3Json'] = Zend_Json::encode($mCategoria->getCategoriasActivas(3));
                
        $this->view->arrCategoria = $arrCategoria;
        $this->view->listCiudadesActivas = $mUbigeos->getListCiudadesActivas();
    }
    
    private function _cacheHome()
    {
        $cache = Zend_Registry::get('cache');
        $cacheHomeId = 'cacheHome';
        if (!$result = $cache->load($cacheHomeId)) {
            $result = array(
                'categoriasBuscador' => $this->categoriasBuscadorL1(-1),
                'categoriasHome' => $this->categoriasL1(1),
                'moduloItems' => $this->moduloItems(1),
                'tituloModulo3' => $this->modulo(3),
                'modulo3' => $this->moduloAvisos(3),
                'tituloModulo4' => $this->modulo(4),
                'modulo4' => $this->moduloAvisos(4),
                'loMasBuscado' => $this->verLoMasBuscado(),
                'vendedorSemana' => $this->verVendedorSemana(3),
                'loMasContactado' => $this->verloMasContactado(4),
                'rutaimagen75' => $this->getRutaImagen(),
                'rutaimagen150' => $this->getRutaImagen('thumbs'),
                'rutaimagen300' => $this->getRutaImagen('img'),
                'rutaimagenOriginal' => $this->getRutaImagen('original'),
                'categoriasLayout' => $this->busquedaCategoriaJson()                
            );
            $cache->save($result, $cacheHomeId);
        }
        return $result;
    }

    /**
     * Visualiza las categorias de acuerdo a los criterios ingresados
     * 1 No adultos
     * 0 Visible Home
     * -1 Todos
     * @param integer $tipoModulo Tipo de modulo, en este caso se trata del 1
     * @param integer $idModulo ID del Modulo que se desea visualizar
     * @uses Clase::methodo()
     * @return type desc
     */
    private function categoriasBuscadorL1($parametro)
    {
        $mCategoria = new Categoria();
        return $this->getUrlSeo($mCategoria->getCategoriasBuscadorL1($parametro), 'TIT', 'URL');
    }
    
    /**
     * Visualiza las categorias de acuerdo a los criterios ingresados
     * 1 No adultos
     * 0 Visible Home
     * -1 Todos
     * @param integer $tipoModulo Tipo de modulo, en este caso se trata del 1
     * @param integer $idModulo ID del Modulo que se desea visualizar
     * @uses Clase::methodo()
     * @return type desc
     */
    private function categoriasL1($parametro)
    {
        $mCategoria = new Categoria();
        return $this->getUrlSeo($mCategoria->getCategoriasL1($parametro), 'TIT', 'URL');
    }

    /**
     * Visualiza los items relacionados con el modulo tipo 1 de acuerdo a los criterios
     * Código del Módulo tipo 1
     * @param integer $tipoModulo Tipo de modulo, en este caso se trata del 1
     * @param integer $idModulo ID del Modulo que se desea visualizar
     * @uses Clase::methodo()
     * @return type desc
     */
    private function moduloItems ($tipoModulo)
    {
        $modulo = new Modulo();
        return $modulo->getModuloItems($tipoModulo);
    }

    /**
     * Visualiza los avisos relacionados con el modulo tipo 2 de acuerdo a los criterios
     * Número de registros a visualizar
     * Código del Módulo tipo 2 [3, 4]
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function moduloAvisos ($idModulo)
    {
        $modulo = new Modulo();
        return $modulo->getModuloAvisos($idModulo);
    }
    
    /**
     * Visualiza los datos de un modulo
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function modulo ($idModulo) 
    {
        $modulo = new Modulo();
        return $modulo->getModulo($idModulo);
    }

    /**
     * Visualiza las palabras (tags) mas buscadas
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function verLoMasBuscado ()
    {
        $tags = new DiccionarioTag();
        return $tags->getLoMasBuscado();
    }

    /**
     * Visualiza el vendedor de la Semana con 3 avisos
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function verVendedorSemana($nroAvisos)
    {
        require_once 'UsuarioPortal.php';
        $usuario = new UsuarioPortal();
        $vendedorSemana['vendedor'] = $usuario->getVendedorSemana();
        if ($vendedorSemana != NULL) {
            $vendedorSemana['publicaciones'] = 
                $this->getUrlSeo(
                    $usuario->getUsuarioPublicaciones(
                        $vendedorSemana['vendedor'][0]->ID_USR, $nroAvisos
                    ), 'TIT', 'SEOURL'
                );
            return $vendedorSemana;
        }
    }

    /**
     * Ver los N mas contactados del portal
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function verloMasContactado ($nroResultados)
    {
        require_once 'Aviso.php';
        $ventas = new Aviso();
        return $this->getUrlSeo($ventas->getloMasContactado($nroResultados), 'TIT', 'SEOURL');
    }
    
    /**
     * Concatena la ruta de la imagen adecuada
     * @param pe name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function getRutaImagen($tipoImagen = 'thumbnails')
    {
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')
                ->getOption('fileshare');
        return $urlImagen = $fileshare['url'] .'/'. $fileshare[$tipoImagen] . '/';
    }
    
    private function busquedaCategoriaJson()
    {            
        require_once 'Categoria.php';
        $mCategoria = new Categoria();
        $categorias = $mCategoria->getCategoriaNivel(1);
        $categoriasJson = array();
        
        foreach ($categorias as $categoria) {                 
            $categoriasTwo = $mCategoria->getCategoriaNivel(2, $categoria['ID_CATEGORIA']);
            $categoriasJson[$categoria['ID_CATEGORIA']] = array(
                                                            'idCategoria' => $categoria['ID_CATEGORIA'],
                                                            'nombre' => $categoria['NOM_CATEGORIA']
                                                            );
            foreach ($categoriasTwo as $categoriaTwo) {
                $categoriasThree = $mCategoria->getCategoriaNivel(3, $categoriaTwo['ID_CATEGORIA']);
                $categoriasJson[$categoria['ID_CATEGORIA']]['childs'] = array(
                                                            'idCategoria' => $categoriaTwo['ID_CATEGORIA'],
                                                            'nombre' => $categoriaTwo['NOM_CATEGORIA']
                                                            );
                foreach ($categoriasThree as $categoriaThree) {
                    $categoriasJson[$categoria['ID_CATEGORIA']]['childs']['childs'] = array(
                        'idCategoria' => $categoriaThree['ID_CATEGORIA'],
                        'nombre' => $categoriaThree['NOM_CATEGORIA']
                    );
                }
            }
        }
        return Zend_Json::encode($categoriasJson);
    }
}