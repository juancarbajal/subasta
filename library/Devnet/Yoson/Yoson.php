<?php

/**
 * Description of Yoson
 *
 * @author Luis Mercado
 */
class Devnet_Yoson_Yoson extends Zend_Controller_Plugin_Abstract
{
    
/**
     *
     * @var Zend_Controller_Front
     */
    protected $_req = null;

    /**     
     * *
     * @var Zend_View
     */
    protected $_view = null;
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        
        $this->_view = $layout->getView();
        
        $modulo = $request->getModuleName();
        $controlador = $request->getControllerName();
        $accion = $request->getActionName();
        
        $host = $this->_view->BaseUrl() . '/';
        $static = URL_STATIC;
        $element = URL_ELEMENTS;
        $version = App_Config::getStaticVersion();
        
        $this->_view->yoson = <<<EOD
<!-- yOSON -->
    <script type="text/javascript">
    // <![CDATA[
      var yOSON = {
         module     : "$modulo",
         controller : "$controlador",
         action     : "$accion",
         AppCore    : { /*Implementacion de Core en archivo Core.js      */ },
         AppSandbox : { /*Implementacion de Sandbox en archivo Sandbox.js*/ },
         AppSchema  : { modules:{}, requires:{} /*, others:{}*/             },
         peruID     : {
            urlBase    :'',
            urlReceiver:'',
            urlPortal  :'',
            urlProxy   :''
         },
         baseHost  : '$host',
         statHost  : '$static',
         eHost     : '$element',
         statVers  : '$version',
         min       : ''
      };    
    // ]]>
    </script>
<!-- End yOSON Inline Tag -->   
EOD;
    }
}