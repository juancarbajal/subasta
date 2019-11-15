<?php

/**
 * Description of Dax
 *
 * @author Raul Sedano
 */
require_once 'SeoGroupCT.php';

class Devnet_ClickTale_ClickTale extends Zend_Controller_Plugin_Abstract
{
    /**
     $
     * *
     * @var Zend_View
     */
    protected $_view = null;

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        $this->_view = $layout->getView();
        $this->scClickTale();
        $this->scCXSearch();
    }


    public function scClickTale(){
    $url=$this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost() . $this->getRequest()->getRequestUri();

    $SeoGroupCT =new SeoGroupCT();
    $list = $SeoGroupCT->listSEOGroupParam();
    $this->_view->ClickTale_Top='';
    $this->_view->ClickTale_Bottom='';

    //echo $url;

    foreach($list as $item){        
        if ($item->URL == $url){
           $ClickTale_Top='
           <!-- ClickTale Top part -->
           <script type="text/javascript">
           var WRInitTime=(new Date()).getTime();
           </script>
           <!-- ClickTale end of Top part -->';
           $this->_view->ClickTale_Top=$ClickTale_Top;
           $ClickTale_Bottom='
           <!-- ClickTale Bottom part -->
           <div id="ClickTaleDiv" style="display: none;"></div>
           <script type="text/javascript">
           if(document.location.protocol!=\'https:\')
             document.write(unescape("%3Cscript%20src=\'http://s.clicktale.net/WRd.js\'%20type=\'text/javascript\'%3E%3C/script%3E"));
           </script>
           <script type="text/javascript">
           if(typeof ClickTale==\'function\') ClickTale('.$item->COD_CLICKTALE.',1,"www");
           </script>
           <!-- ClickTale end of Bottom part -->';
           $this->_view->ClickTale_Bottom=$ClickTale_Bottom;
          }          
        }
    }


    public function scCXSearch(){

    $scCXSearch= '
    <!-- cXense script begin -->
    <div id="cX-root" style="display:none"></div>
    <script type="text/javascript">
      var cX = cX || {}; cX.callQueue = cX.callQueue || [];
      cX.callQueue.push([\'setAccountId\', \'9222268904439147011\']);
      cX.callQueue.push([\'setSiteId\', \'9222268904439147022\']);
      cX.callQueue.push([\'sendPageViewEvent\']);
    </script>

    <script type="text/javascript">
      (function() { try { var scriptEl = document.createElement(\'script\'); scriptEl.type = \'text/javascript\'; scriptEl.async = \'async\';
      scriptEl.src = (\'https:\' == document.location.protocol) ? \'https://scdn.cxense.com/cx.js\' : \'http://cdn.cxense.com/cx.js\';
      var targetEl = document.getElementsByTagName(\'script\')[0]; targetEl.parentNode.insertBefore(scriptEl, targetEl); } catch (e) {};} ());
    </script>
    <!-- cXense script end -->';
    $this->_view->scCXSearch=$scCXSearch;
    }



    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        parent::postDispatch($request);
        $response = $this->getResponse();       
    }
}