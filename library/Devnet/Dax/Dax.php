<?php

/**
 * Description of Dax
 *
 * @author Luis Mercado
 */
class Devnet_Dax_Dax extends Zend_Controller_Plugin_Abstract
{
    
/**
     *
     * @var Zend_Controller_Front
     */
    protected $_req = null;

    /**
     *
     * @var string
     */
    protected $_tag = null;
    /**
     $
     * *
     * @var Zend_View
     */
    protected $_view = null;

    /**
     *
     * @var array
     */
    protected $_map = null;

    /**
     *
     * @var string
     */
    protected $_key = null;
    protected $_default = 'otros.otros';
    protected $_url=null;
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        $this->_view = $layout->getView();
        $this->_capturarUrl();
        $this->_view->dax = $this->_getDax();
    }
    
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        $this->_view = $layout->getView();
        
        if($this->_view->daxTagOpc){
            
            $this->_key = $this->getRequest()->getControllerName() . '.' 
                . $this->getRequest()->getActionName();
            $this->_checkPublicacion($this->_view->daxTagOpc);
            $this->_view->dax = $this->_getDax();
        }
        
        parent::postDispatch($request);
        $response = $this->getResponse();
        if (($response->isException())) {
            $this->_tag = 'otros.404';
            return TRUE;
        }
    }
    
    /**
     * Utilizado en postDispatch para poner el tipo de plan
     * @return boolean
     */
    protected function _checkPublicacion($_tagOpc)
    {
        
//        $this->_key = $this->getRequest()->getControllerName() . '.' 
//                . $this->getRequest()->getActionName();
        $arrTipo = array (2 => 'basico', 5 => 'silver', 9 => 'gold', 10 => 'platinum');
        $opcTip = $arrTipo[$_tagOpc];
        $map = array(
        //->PostDispatch
            'publicacion.registro-categoria' => 'publicacion.paso2.'.$opcTip,
            'publicacion.registro-datos' => 'publicacion.paso3.'.$opcTip,
            'publicacion.confirmar-publicacion' => 'publicacion.paso4.'.$opcTip,
            'publicacion.fin-publicacion' => 'publicacion.paso4.'.$opcTip,
            //<-
        );
        
        if (array_key_exists($this->_key, $map)) {
            $this->_tag = $map[$this->_key];
        } else {
            $this->_tag = $this->_tag;
        }
    }
    
    private function _getDax()
    {
        return <<<EOD
            <!-- Begin comScore Inline Tag 1.1111.15 -->
            <script type="text/javascript">
            // <![CDATA[
            function udm_(a){var b="comScore=",c=document,d=c.cookie,e="",f="indexOf",g="substring",h="length",i=2048,j,k="&ns_",l="&",m,n,o,p,q=window,r=q.encodeURIComponent||escape;if(d[f](b)+1)for(o=0,n=d.split(";"),p=n[h];o<p;o++)m=n[o][f](b),m+1&&(e=l+unescape(n[o][g](m+b[h])));a+=k+"_t="+ +(new Date)+k+"c="+(c.characterSet||c.defaultCharset||"")+"&c8="+r(c.title)+e+"&c7="+r(c.URL)+"&c9="+r(c.referrer),a[h]>i&&a[f](l)>0&&(j=a[g](0,i-8).lastIndexOf(l),a=(a[g](0,j)+k+"cut="+r(a[g](j+1)))[g](0,i)),c.images?(m=new Image,q.ns_p||(ns_p=m),m.src=a):c.write("<","p","><",'img src="',a,'" height="1" width="1" alt="*"',"><","/p",">")}
            udm_('http'+(document.location.href.charAt(4)=='s'?'s://sb':'://b')+'.scorecardresearch.com/b?c1=2&c2=6906602&ns_site=clasificados-kotear&name=$this->_tag');
            // ]]>
            </script>
            <noscript><p><img src="http://b.scorecardresearch.com/p?c1=2&amp;c2=6906602&amp;ns_site=clasificados-kotear&amp;name=$this->_tag" height="1" width="1" alt="*"></p></noscript>
            <!-- End comScore Inline Tag -->   
EOD;
    }
    
    private function _capturarUrl()
    {
        $this->_key = $this->getRequest()->getControllerName() . '.' 
                . $this->getRequest()->getActionName();
        
        $this->_url = $this->_view->baseUrl();

        // Secciones estaticas que no cambian en el site map
        $this->_map = array(
            'publicacion.registro-destaque' => 'publicacion.paso1',
            'edicion.index' => 'usuario.mi-cuenta.portada',
            'compra.seguimiento' => 'usuario.en-seguimiento',
            'facturacion.index' => 'usuarios.datos-de-facturacion',
            'venta.activas' => 'usuario.mi-cuenta.avisos-activos',
            'venta.pendiente-pago' => 'usuario.mi-cuenta.pendiente-de-pago',
            'venta.inactivas' => 'usuario.mi-cuenta.avisos-inactivos',
            'venta.preguntas-recibidas' => 'usuario.mi-cuenta.preguntas-recibidas',
            
            'index.index' => 'portada.inicio',
            'busqueda.avanzada' => 'busqueda.avanzada.portada',
            'busqueda.categoria' => 'categorias.portada',
            'adultos.index' => 'categorias.adultos.portada-18-anos',
            'acceso.index' => 'usuario.login',
            'registro.index' => 'usuario.formulario-de-registro',
            'registro.confirmar-correo.index' => 'usuario..formulario-registro-confirmar',
            'cuenta.index' => 'usuario.mi-cuenta.portada',
            'index.cargos-pagados' => 'usuario.mi-cuenta.cargos-pagados',
            'index.pendiente-de-pago' => 'usuario.mi-cuenta.cargos-pendientes',
            'index.datos-facturacion' => 'usuario.mi-cuenta.mis-datos-de-facturacion',
            'index.error404' => '404.404'
        );
        $this->_checkSetup();
    }
    
    protected function _checkSetup()
    {
        if (is_null($this->_tag)) {

            if ($this->_checkBusquedaCategoria()) {
                return TRUE;
            }

            if ($this->_checkVenta()) {
                return TRUE;
            }

            if ($this->_checkDestaques()) {
                return TRUE;
            }

            if ($this->_checkCompra()) {
                return TRUE;
            }

            if ($this->_checkVer()) {
                return TRUE;
            }

            if ($this->_checkSeguimiento()) {
                return TRUE;
            }

            if ($this->_checkMap()) {
                return TRUE;
            }

        }
    }

    protected function _checkCompra()
    {
        if ($this->getRequest()->getControllerName() == 'compra'
            && $this->getRequest()->getActionName() == 'historial-compras') {
                $codigo = $this->getRequest()->getParam('codigo');
                switch ($codigo) {
                    #Principal
                    case 0:
                        $this->_tag = 'usuario.mi-cuenta.historial-de-compras';
                        break;
                    #Semana
                    case 3:
                        $this->_tag = 'usuario.mi-cuenta.historial-de-compras.por-fecha';
                        break;
                    #Mes
                    case 4:
                        $this->_tag = 'usuario.mi-cuenta.historial-de-compras.por-fecha';
                        break;
                    #Todas las categorias
                    default :
                        $this->_tag = 'usuario.mi-cuenta.historial-de-compras.categoria';
                        break;
                }
                return TRUE;
        }

        if ($this->getRequest()->getControllerName() == 'compra'
            && $this->getRequest()->getActionName() == 'preguntas-realizadas') {
                $codigo = $this->getRequest()->getParam('codigo');
                switch ($codigo) {
                    #Principal
                    case 0:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-realizadas';
                        break;
                    #Preguntas no Contestadas
                    case 1:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-no-contestadas';
                        break;
                    #Preguntas Contestadas
                    case 2:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-contestadas';
                        break;
                    #Semana
                    case 3:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-realizadas-por-fecha';
                        break;
                    #Mes
                    case 4:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-realizadas-por-fecha';
                        break;
                }
                return TRUE;
        }

        return FALSE;
    }

    protected function _checkVenta()
    {
        if ($this->getRequest()->getControllerName() == 'venta'
            && $this->getRequest()->getActionName() == 'activas') {
                $codigo = $this->getRequest()->getParam('codigo');
                switch ($codigo) {
                    #Principal
                    case 0:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos';
                        break;
                    #Compra inmediata
                    case 1:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-tipo';
                        break;
                    #Subasta
                    case 2:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-tipo';
                        break;
                    #Semana
                    case 3:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-fecha';
                        break;
                    #Mes
                    case 4:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-fecha';
                        break;
                    #Sin Calificar
                    case 5:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-estados';
                        break;
                    #Calificadas
                    case 6:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-estados';
                        break;
                    #Sin Destaques
                    case 7:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-destaque';
                        break;
                    #Destaques pendientes
                    case 8:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-destaque';
                        break;
                    #Destaques Activos
                    case 9:
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-destaque';
                        break;
                    #Todas las categorias
                    default :
                        $this->_tag = 'usuario.mi-cuenta.avisos-activos-por-categorias';
                        break;
                }
                return TRUE;
        }
        
        if ($this->getRequest()->getControllerName() == 'venta'
            && $this->getRequest()->getActionName() == 'inactivas') {
            $estado = $this->getRequest()->getParam('estado');
            $categoria = $this->getRequest()->getParam('categoria');
            $filtro = $this->getRequest()->getParam('filtro');

                #Estados
                if(isset($estado) && $estado!=0 && (!isset($filtro) || $filtro==0)){
                $this->_tag = 'usuario.mi-cuenta.avisos-no-activos-por-estado';
                return TRUE;
                }else
                #Filtro de Categorias
                if (isset($estado) && $estado==0 && isset($categoria) && $categoria!=0 && (!isset($filtro) || $filtro==0)){
                $this->_tag = 'usuario.mi-cuenta.avisos-no-activos-por-categoria';
                return TRUE;
                }else
                #Tipo
                if (isset($filtro) && ($filtro==2 || $filtro==3)){
                $this->_tag = 'usuario.mi-cuenta.avisos-no-activos-por-tipo';
                return TRUE;
                }else
                #Fecha
                if (isset($filtro) && ($filtro==4 || $filtro==5)){
                $this->_tag = 'usuario.mi-cuenta.avisos-no-activos-por-fecha';
                return TRUE;
            }
        }

        if ($this->getRequest()->getControllerName() == 'venta'
            && $this->getRequest()->getActionName() == 'historial') {
            $fc = $this->getRequest()->getParam('fc');

            if(!isset($fc)){
                $this->_tag = 'usuario.mi-cuenta.historial-de-ventas';
                return TRUE;
            }else
                $this->_tag = 'usuario.mi-cuenta.historial-de-ventas-por-fechas';
                return TRUE;
            }

        if ($this->getRequest()->getControllerName() == 'venta'
            && $this->getRequest()->getActionName() == 'preguntas-recibidas') {
            $codigo = $this->getRequest()->getParam('codigo');

            switch ($codigo) {
                    #Principal
                    case 0:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-recibidas';
                        break;
                    #No contestadas
                    case 1:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-recibidas-no-contestadas';
                        break;
                    #Contestadas
                    case 2:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-recibidas-contestadas';
                        break;
                    #Semana
                    case 3:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-recibidas-por-fechas';
                        break;
                    #Mes
                    case 4:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-recibidas-por-fechas';
                        break;
                    #Hoy
                    case 5:
                        $this->_tag = 'usuario.mi-cuenta.preguntas-recibidas-por-fechas';
                        break;
                }
                return TRUE;
        }
        return FALSE;
    }

    protected function _checkVer()
    {
        $item = $this->getRequest()->getParam('id');
        if ($this->getRequest()->getControllerName() == 'aviso'
            && $this->getRequest()->getActionName() == 'ver' && $item != "") {
                $params = explode('-', $item);
                $this->_tag = 'categorias.'.$this->_getNombreCategoriaPorAviso($params[0]) . '.ficha.' . $item;
                return TRUE;
        }
        return FALSE;
    }

    protected function _checkBusquedaCategoria()
    {
        $categs = $this->getRequest()->getParam('categs');
        $ord = $this->getRequest()->getParam('ord');
        $q = $this->getRequest()->getParam('q');
        $granVendedor = $this->getRequest()->getParam('tv');

        if ($this->getRequest()->getControllerName() == 'busqueda'
            && $this->getRequest()->getActionName() == 'index' && $categs && (!isset($q) || $q=='')) {
            if ($categs != -1 && (!isset($granVendedor) || $granVendedor=='' || $granVendedor<2 )) {
                $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.portada';
                switch ($ord) {
                    #Lo Mas Vendidos
                    case 4:
                        $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.los-mas-vendidos';
                        break;
                    #Lo Mas visto
                    case 6:
                        $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.los-mas-vistos';
                        break;
                    #Proximos a finalizar
                    case 7:
                        $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.proximos-a-finalizar';
                        break;
                }
                return TRUE;
            }
        }
        
        if ($this->getRequest()->getControllerName() == 'busqueda'
            && $this->getRequest()->getActionName() == 'index' && isset($q) && $q!='') {            
                $this->_tag = 'busquedas.' .$this->_nameDax(trim($q));
                return TRUE;            
        }


        $id = $this->_request->getParam('id');
        if ( $this->getRequest()->getControllerName() == 'busqueda'
            && $this->getRequest()->getActionName() == 'index' && (!isset($q) || $q=='') && (!isset($categs) || $categs=='' ) && (!isset($id) || $id=='')) {
            $granVendedor = $this->getRequest()->getParam('tv');
            $ord = $this->getRequest()->getParam('ord');               
            if (isset($granVendedor) && $granVendedor == 2) {
                    $this->_tag = 'busquedas.grandes-vendedores';
                    return TRUE;
                }
                switch ($ord) {
                    case 1:
                        $this->_tag = 'busquedas.desde-un-sol';
                        break;
                    case 4:
                        $this->_tag = 'busquedas.mas-vendidos';
                        break;
                    case 6:
                        $this->_tag = 'busquedas.mas-buscados';
                        break;
                }
                return TRUE;
        }

        if ($this->getRequest()->getControllerName() == 'categoria'
            && $this->getRequest()->getActionName() == 'index') {
            $categoria=0;
            $categoria = explode('-',$this->_request->getParam('id'));            
             if ($categoria[0] != -1) {
                $this->_tag = 'categorias.' . $this->_getNombreCategoria($categoria[0]).'.portada';
                return TRUE;
             }
        }
        return FALSE;
    }
    /*
    protected function _checkBusqueda()
    {
        if ($this->getRequest()->getControllerName() == 'busqueda'
            && $this->getRequest()->getActionName() == 'index') {            
            $busqueda = $this->getRequest()->getParam('q');
            $this->_tag = 'busquedas.' . $busqueda;
            return TRUE;
        }

    }*/

    protected function _checkSeguimiento()
    {   
        $tipoFiltro = $this->getRequest()->getParam('tipoFiltro');
        $filtro = $this->getRequest()->getParam('filtro');
        $valorFiltro = $this->getRequest()->getParam('valorFiltro');

        if ($this->getRequest()->getControllerName() == 'compra'
            && $this->getRequest()->getActionName() == 'seguimiento'
            && $tipoFiltro && $filtro) {

            if ($tipoFiltro != -1  && $filtro != -1 && $valorFiltro != -1) {
                switch ($filtro) {
                    #Semana
                    case 2:
                        $this->_tag = 'usuario.mi-cuenta.seguimiento-de-cuentas-por-fecha';
                        break;
                    #Mes
                    case 3:
                        $this->_tag = 'usuario.mi-cuenta.seguimiento-de-cuentas-por-fecha';
                        break;
                    #Precio
                    case 4:
                        $this->_tag = 'usuario.mi-cuenta.seguimiento-de-cuentas-por-precio';
                        break;
                    #Compras
                    case 5:
                        $this->_tag = 'usuario.mi-cuenta.seguimiento-de-cuentas-por-compras';
                        break;
                    #Visitas
                    case 6:
                        $this->_tag = 'usuario.mi-cuenta.seguimiento-de-cuentas-por-visita';
                        break;
                    #Fecha Expiracion
                    case 7:
                        $this->_tag = 'usuario.mi-cuenta.seguimiento-de-cuentas-por-expiracion';
                        break;
                }
            }
        }
        return FALSE;
    }


    protected function _checkDestaques()
    {
        $categs = $this->getRequest()->getParam('categs');
        $granVendedor = $this->getRequest()->getParam('tv', "");
        $ord = $this->getRequest()->getParam('ord');
        
        if ($this->getRequest()->getControllerName() == 'busqueda'
            && $this->getRequest()->getActionName() == 'index' && $categs) {

           
            if ($categs != -1) {
                if ($granVendedor == 2) {
                    $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.vendedores-recomendados';
                    return TRUE;
                }
                switch ($ord) {
                     #Lo Mas visto
                    case 4:
                        $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.los-mas-vendidos';
                        break;
                    #Lo Mas visto
                    case 6:
                        $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.los-mas-vistos';
                        break;
                    #Proximos a finalizar
                    case 7:
                        $this->_tag = 'categorias.' . $this->_getNombreCategoria($categs) . '.proximos-a-finalizar';
                        break;
                }
                return TRUE;
            }
        }
        return FALSE;
    }

    protected function _checkMap()
    {
        if (is_null($this->_tag)) {
            $this->_tag = array_key_exists($this->_key, $this->_map) ? $this->_map[$this->_key]
                    : $this->_default;
            return TRUE;
        }
        return FALSE;
    }
    
    private function _nameDax($texto)
    {
        $texto = utf8_decode($texto);
        $con_acento = utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ");
        $sin_acento = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
        $texto = strtr($texto, $con_acento, $sin_acento);
        $texto = preg_replace("/[^A-Za-z0-9 _]/","",$texto);
        $texto = strtolower(trim ($texto));
        $texto = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $texto);
        
        return $texto;
    }
    
    private function _getNombreCategoria($idCategoria)
    {
        require_once 'Agrupador.php';
        
        $agrupador = new Agrupador();
        $tempCategoria = $agrupador->getLevels($idCategoria);
        $temporal = $tempCategoria[0];
        
        $nombreCategoria = $this->_nameDax(trim($temporal->L1_NOM));
        $temporal->L2_NOM = trim($temporal->L2_NOM);
        if (!empty($temporal->L2_NOM))
            $nombreCategoria .= '.' . $this->_nameDax(trim($temporal->L2_NOM));
        
        $temporal->L3_NOM = trim($temporal->L3_NOM);
        if (!empty($temporal->L3_NOM))
            $nombreCategoria .= '.' . $this->_nameDax(trim($temporal->L3_NOM));
                
        return $nombreCategoria;
    }
    
    private function _getNombreCategoriaPorAviso($idAviso)
    {
        require_once 'AvisoInfo.php';
        $aviso = new AvisoInfo();
        $temporal = $aviso->getTreeCategorias($idAviso);
        $temporal = $temporal[0];
        
        $nombreCategoria = $this->_nameDax(trim($temporal->L1_NOM));
        if (!empty($temporal->L2_NOM))
            $nombreCategoria .= '.' . $this->_nameDax(trim($temporal->L2_NOM));
        
        if (!empty($temporal->L3_NOM))
            $nombreCategoria .= '.' . $this->_nameDax(trim($temporal->L3_NOM));
        
        return $nombreCategoria;
    }
}