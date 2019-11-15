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
class AdultosController
    extends Devnet_Controller_Action
{

    /**
     * Visualiza la pagina de aceptacion para ver contenido adulto
     * Si acepta, se crea la sesion de aceptacion en todo el sitio y redirecciona a la pagina destino
     * Si no acepta, vuelve a la pagina origen
     * @param type name desc
     * @uses Clase::methodo()
     * @return type desc
     */
    public function indexAction() 
    {
        $this->view->headTitle('Términos y condiciones de la categoría adultos | Kotear.pe');
        if (strpos($this->session->toPage, 'autocomplete')) {
            $this->session->toPage = $this->session->fromPage;
        }
        $this->view->fromPage = $this->session->fromPage;
        $this->view->toPage = $this->session->toPage;
        if ($this->_request->isPost()) {
            if ($this->_request->getParam('aceptarContenidoAdulto')) {
                $this->session->aceptaContenidoAdulto = $this->_request->getParam('aceptarContenidoAdulto');
            } else {
                $this->session->aceptaContenidoAdulto = $this->_request->getParam('aceptarContenidoAdulto');
            }
            $this->_redirect($this->view->baseUrl() . $this->_request->getParam('toPage'));
        }
    }
}