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
require_once 'Categoria.php';
require_once 'Modulo.php';
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
class CategoriaController 
    extends Devnet_Controller_Action_Default
{
    
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function indexAction() 
    {
        $idTotal = explode('-', $this->_request->getParam('id'));
        $idCategoria = $idTotal[0];                
        //Deberia estar en el ini para un controlador
        $mCategoria = new Categoria();
        $categoria = $mCategoria->getCategoriaId($idCategoria);                
        if ($categoria[0]->ADULTO == 1 && $this->session->aceptaContenidoAdulto <> 1) {
            $this->_redirect('/adultos');   
        } else { 
            $this->view->categoria = $categoria[0];
            $this->view->headTitle($this->view->categoria->TIT . ' | Kotear.pe');
            $this->view->arbolCategoria = $mCategoria->getCategoriaArbol($idCategoria);        
            $this->view->tituloModulo5 = $this->modulo(5);
            $this->view->modulo5 = $this->moduloAvisos(5, $idCategoria);
            $this->view->rutaimagen150 = $this->getRutaImagen('thumbs');
            $this->view->rutaimagen300 = $this->getRutaImagen('img');
        }        
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
     * Visualiza los avisos relacionados a la categoria padre
     * con el modulo tipo 2 de acuerdo a los criterios
     * Número de registros a visualizar
     * Código del Módulo tipo 2 [3, 4]
     * @param integer $idModulo Codigo del modulo relacionado [5]
     * @param integer $idCategoria Codigo de la categoria padre
     * @uses Clase::methodo()
     * @return type desc
     */
    private function moduloAvisos ($idModulo, $idCategoria) 
    {
        $modulo = new Modulo();
        return $modulo->getModuloAvisos($idModulo, $idCategoria);
    }

    /**
     * Concatena la ruta de la imagen adecuada
     * @param pe name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function getRutaImagen ($tipoImagen = 'thumbnails') 
    {
        $frontController = Zend_Controller_Front::getInstance();
        $fileshare = $frontController->getParam('bootstrap')
                                     ->getOption('fileshare');
        return $urlImagen = $fileshare['url'] .'/'. $fileshare[$tipoImagen] . '/';
    }
 
    /**
     *
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    private function getListarCategorias ($idCategoria) 
    {
        $mCategoria = new Categoria();
        $arraycategoria = $mCategoria->getSubCategoriaHijo($idCategoria);
        //var_dump($arraycategoria);
        $salida.="<ul>";
        for ($i=0;$i<count($arraycategoria);$i++) {
            $salida.="<li>".$arraycategoria[$i]->TIT."---".$arraycategoria[$i]->NIVEL;
            if ($arraycategoria[$i]->NIVEL==4) {
                $salida	.="+";
                break;
            }
            if (count($arraycategoria[$i]->hijos)>0) {
                $salida.="<a href=".$this->getListarCategorias($arraycategoria[$i]->ID_CATEGORIA).
                         "</a><br></br>";
            }
            $salida.="</li>";
        }
        $salida.="</ul>";
        return $salida;
    }
}