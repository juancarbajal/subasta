<?php

/**
 * Description of Mediakit
 * 
 * @author Raul Sedano
 */
class Devnet_Mediakit_Mediakit extends Zend_Controller_Plugin_Abstract
{
    
    protected $_pag = null;    
    protected $_seccion = null;
    protected $_scriptIni = null;
    
    protected $_controller = null;
    
    protected $_view = null;
    
    public function preDispatch(Zend_Controller_Request_Abstract $request){
        
        $layout = Zend_Layout::getMvcInstance();
        $this->_view = $layout->getView();
        $this->_controller=$this->getRequest()->getControllerName();
        $this->_Urls();                
        $this->_view->mediakit = <<<EOD
        <!-- BEGIN MEDIAKIT KOTEAR -->
        <script language="JavaScript" type="text/javascript">
            //<![CDATA[
            var eplDoc = document; var eplLL = false; 
            var eS1 = 'us.img.e-planning.net';            
            var eplArgs = { iIF:1,sV:"http://ads.us.e-planning.net/",vV:"4",sI:"8b09",sec:$this->_pag ,eIs:[$this->_seccion] };
            function eplCheckStart() {
                if (document.epl) {
                    var e = document.epl;
                    if (e.eplReady()) {
                        return true;
                    } else {
                        e.eplInit(eplArgs);
                        $this->_scriptIni
                        if (eplArgs.custom) {
                            for (var s in eplArgs.custom) {
                                document.epl.setCustomAdShow(s, eplArgs.custom[s]);
                            }
                        }
                        return e.eplReady();
                    }
                } else {
                    if (eplLL) return false;
                    if (!document.body) return false; var eS2; var dc = document.cookie; var ci = dc.indexOf("EPLSERVER=");
                    if (ci != -1) {
                        ci += 10; var ce = dc.indexOf(';', ci);
                        if (ce == -1) ce = dc.length;
                        eS2 = dc.substring(ci, ce);
                    }
                    var eIF = document.createElement('IFRAME');
                    eIF.src = 'about:blank'; eIF.id = 'epl4iframe'; eIF.name = 'epl4iframe';
                    eIF.width=0; eIF.height=0; eIF.style.width='0px'; eIF.style.height='0px';
                   eIF.style.display='none'; document.body.appendChild(eIF);

                    var eIFD = eIF.contentDocument ? eIF.contentDocument : eIF.document;
                    eIFD.open();eIFD.write('<html><head><title>e-planning</title></head><body></body></html>');eIFD.close();
                    var s = eIFD.createElement('SCRIPT'); s.src = 'http://' + (eS2?eS2:eS1) +'/layers/epl-41.js';
                    eIFD.body.appendChild(s);
                    if (!eS2) {
                        var ss = eIFD.createElement('SCRIPT');
                        ss.src = 'http://ads.us.e-planning.net/egc/4/2912';
                        eIFD.body.appendChild(ss);
                    }

                    eplLL = true;
                    return false;
                }
            }
            eplCheckStart();
            function eplSetAd(eID,custF) {
                if (eplCheckStart()) {
                    if (custF) { document.epl.setCustomAdShow(eID,eplArgs.custom[eID]); }
                    document.epl.showSpace(eID);
                } else {
                    var efu = 'eplSetAd("'+eID+'", '+ (custF?'true':'false') +');';
                    setTimeout(efu, 250);
                }
            }

            function eplAD4(eID,custF) {
                document.write('<div id="eplAdDiv'+eID+'"></div>');
                if (custF) {
                    if (!eplArgs.custom) { eplArgs.custom = {}; }
                    eplArgs.custom[eID] = custF;
                }
                eplSetAd(eID, custF?true:false);
            }
        //!>
        </script>
    <!-- END MEDIAKIT KOTEAR -->
    
EOD;
    }
        
    private function _Urls(){      
         if ($this->_controller=='busqueda'){
            $this->_pag='"Busqueda"'; 
            $this->_seccion='"Top","Middle"'; 
            $this->_scriptIni=' if (eplArgs.custom) {
                        for (var s in eplArgs.custom) {
                            document.epl.setCustomAdShow(s, eplArgs.custom[s]);
                        }
                    } ';
         }elseif($this->_controller=='aviso'){
            $this->_pag='"Ficha"'; 
            $this->_seccion='"Top","Right","Expandible"'; 
            $this->_scriptIni=' if (eplArgs.custom) {
                        for (var s in eplArgs.custom) {
                            document.epl.setCustomAdShow(s, eplArgs.custom[s]);
                        }
                    } ';
         }else{
            $this->_pag='"Portada"';
            $this->_seccion='"SuperBanner","Inferior","Expandible","Top"';
            $this->_scriptIni='';
         }
    }
/*        
        }elseif ($controller=='aviso'){            
        $this->_view->mediakit = <<<EOD
       <!-- MEDIAKIT KOTEAR -->
        <script language=\"JavaScript\" type=\"text/javascript\">
            //<![CDATA[
            var eplDoc = document; var eplLL = false;
            var eS1 = 'us.img.e-planning.net';
            var eplArgs = { iIF:1,sV:\"http://ads.us.e-planning.net/\",vV:\"4\",sI:\"8b09\",sec:\"Busqueda\",eIs:[\"Top\",\"Middle\"] };
            function eplCheckStart() {
            if (document.epl) {
                var e = document.epl;
                if (e.eplReady()) {
                    return true;
                } else {
                    e.eplInit(eplArgs);
                    if (eplArgs.custom) {
                        for (var s in eplArgs.custom) {
                            document.epl.setCustomAdShow(s, eplArgs.custom[s]);
                        }
                    }
                   return e.eplReady();
                }
            } else {
                if (eplLL) return false;
                if (!document.body) return false; var eS2; var dc = document.cookie; var ci = dc.indexOf(\"EPLSERVER=\");
                if (ci != -1) {
                    ci += 10; var ce = dc.indexOf(';', ci);
                    if (ce == -1) ce = dc.length;
                    eS2 = dc.substring(ci, ce);
                }
                var eIF = document.createElement('IFRAME');
                eIF.src = 'about:blank'; eIF.id = 'epl4iframe'; eIF.name = 'epl4iframe';
                eIF.width=0; eIF.height=0; eIF.style.width='0px'; eIF.style.height='0px';
                eIF.style.display='none'; document.body.appendChild(eIF);

                var eIFD = eIF.contentDocument ? eIF.contentDocument : eIF.document;
                eIFD.open();eIFD.write('<html><head><title>e-planning</title></head><bo'+'dy></bo'+'dy></html>');eIFD.close();
                var s = eIFD.createElement('SCRIPT'); s.src = 'http://' + (eS2?eS2:eS1) +'/layers/epl-41.js';
                eIFD.body.appendChild(s);
                if (!eS2) {
                    var ss = eIFD.createElement('SCRIPT');
                    ss.src = 'http://ads.us.e-planning.net/egc/4/2912';
                    eIFD.body.appendChild(ss);
                }
                eplLL = true;
                return false;
            }
        }
        eplCheckStart();
        function eplSetAdM(eID,custF) {
            if (eplCheckStart()) {
                if (custF) { document.epl.setCustomAdShow(eID,eplArgs.custom[eID]); }
                document.epl.showSpace(eID);
            } else {
                var efu = 'eplSetAdM(\"'+eID+'\", '+ (custF?'true':'false') +');';
                setTimeout(efu, 250);
            }
        }

        function eplAD4M(eID,custF) {
            document.write('<div id=\"eplAdDiv'+eID+'\"></div>');
            if (custF) {
                if (!eplArgs.custom) { eplArgs.custom = {}; }
                eplArgs.custom[eID] = custF;
            }
            eplSetAdM(eID, custF?true:false);
        }
        //!>
        </script>
        <!-- End MEDIAKIT KOTEAR -->   
EOD;
        }else{        
        $this->_view->mediakit = <<<EOD
        <script language=\"JavaScript\" type=\"text/javascript\">
            //<![CDATA[
            var eplDoc = document; var eplLL = false; 
            var eS1 = 'us.img.e-planning.net';
            var eplArgsP = { iIF:1,sV:\"http://ads.us.e-planning.net/\",vV:\"4\",sI:\"8b09\",sec:\"Portada\",eIs:[\"SuperBanner\",\"Inferior\",\"Expandible\",\"Top\"] };
            function eplCheckStartP() {
                if (document.epl) {var e = document.epl; if (e.eplReady()) { return true; } else {
                    e.eplInit(eplArgsP); return e.eplReady(); }
                    } else {
                if (eplLL) return false;
                if (!document.body) return false; var eS2; var dc = document.cookie; var ci = dc.indexOf(\"EPLSERVER=\");
                if (ci != -1) {
                    ci += 10; var ce = dc.indexOf(';', ci);
                    if (ce == -1) ce = dc.length;
                    eS2 = dc.substring(ci, ce);
                }
                var eIF = document.createElement('IFRAME');
                eIF.src = 'about:blank'; eIF.id = 'epl4iframe'; eIF.name = 'epl4iframe';
                eIF.width=0; eIF.height=0; eIF.style.width='0px'; eIF.style.height='0px';
                eIF.style.display='none'; document.body.appendChild(eIF);

                var eIFD = eIF.contentDocument ? eIF.contentDocument : eIF.document;
                eIFD.open();eIFD.write('<html><head><title>e-planning</title></head><body></body></html>');eIFD.close();
                var s = eIFD.createElement('SCRIPT'); s.src = 'http://' + (eS2?eS2:eS1) +'/layers/epl-41.js';
                eIFD.body.appendChild(s);
                if (!eS2) {
                    var ss = eIFD.createElement('SCRIPT');
                    ss.src = 'http://ads.us.e-planning.net/egc/4/2912';
                    eIFD.body.appendChild(ss);
                }
                eplLL = true;
                return false;
                }
            }
            eplCheckStartP();
            function eplSetAdP(eID,custF) {
                if (eplCheckStartP()) {
                    if (custF) { document.epl.setCustomAdShow(eID,eplArgsP.custom[eID]); }
                    document.epl.showSpace(eID);
                } else {
                    var efu = 'eplSetAdP(\"'+eID+'\", '+ (custF?'true':'false') +');';
                    setTimeout(efu, 250);
                }
            }
            function eplAD4P(eID,custF) {
                document.write('<div id=\"eplAdDiv'+eID+'\"></div>');
                if (custF) {
                    if (!eplArgsP.custom) { eplArgsP.custom = {}; }
                    eplArgsP.custom[eID] = custF;
                }
                eplSetAdP(eID, custF?true:false);
            }
        //!>
        </script>
EOD;
        }            
    }

//     $script="";
//        if ($controller=='busqueda'){
/*        $script="<script language=\"JavaScript\" type=\"text/javascript\">
            //<![CDATA[
            var eplDoc = document; var eplLL = false; var eS1 = 'us.img.e-planning.net';
            var eplArgs = { iIF:1,sV:\"http://ads.us.e-planning.net/\",vV:\"4\",sI:\"8b09\",sec:\"Busqueda\",eIs:[\"Top\",\"Middle\"] };
            function eplCheckStart() {
                if (document.epl) {
                    var e = document.epl;
                    if (e.eplReady()) {
                        return true;
                    } else {
                        e.eplInit(eplArgs);
                        if (eplArgs.custom) {
                            for (var s in eplArgs.custom) {
                                document.epl.setCustomAdShow(s, eplArgs.custom[s]);
                            }
                        }
                        return e.eplReady();
                    }
                } else {
                    if (eplLL) return false;
                    if (!document.body) return false; var eS2; var dc = document.cookie; var ci = dc.indexOf(\"EPLSERVER=\");
                    if (ci != -1) {
                        ci += 10; var ce = dc.indexOf(';', ci);
                        if (ce == -1) ce = dc.length;
                        eS2 = dc.substring(ci, ce);
                    }
                    var eIF = document.createElement('IFRAME');
                    eIF.src = 'about:blank'; eIF.id = 'epl4iframe'; eIF.name = 'epl4iframe';
                    eIF.width=0; eIF.height=0; eIF.style.width='0px'; eIF.style.height='0px';
                   eIF.style.display='none'; document.body.appendChild(eIF);

                    var eIFD = eIF.contentDocument ? eIF.contentDocument : eIF.document;
                    eIFD.open();eIFD.write('<html><head><title>e-planning</title></head><bo'+'dy></bo'+'dy></html>');eIFD.close();
                    var s = eIFD.createElement('SCRIPT'); s.src = 'http://' + (eS2?eS2:eS1) +'/layers/epl-41.js';
                    eIFD.body.appendChild(s);
                    if (!eS2) {
                        var ss = eIFD.createElement('SCRIPT');
                        ss.src = 'http://ads.us.e-planning.net/egc/4/2912';
                        eIFD.body.appendChild(ss);
                    }

                    eplLL = true;
                    return false;
                }
            }
            eplCheckStart();
            function eplSetAdM(eID,custF) {
                if (eplCheckStart()) {
                    if (custF) { document.epl.setCustomAdShow(eID,eplArgs.custom[eID]); }
                    document.epl.showSpace(eID);
                } else {
                    var efu = 'eplSetAdM(\"'+eID+'\", '+ (custF?'true':'false') +');';
                    setTimeout(efu, 250);
                }
            }

            function eplAD4M(eID,custF) {
                document.write('<div id=\"eplAdDiv'+eID+'\"></div>');
                if (custF) {
                    if (!eplArgs.custom) { eplArgs.custom = {}; }
                    eplArgs.custom[eID] = custF;
                }
                eplSetAdM(eID, custF?true:false);
            }
        //!>
        </script>";
*/
/*
        }elseif ($controller=='aviso'){
        $script="<script language=\"JavaScript\" type=\"text/javascript\">
            var eplDoc = document; var eplLL = false;
            var eS1 = 'us.img.e-planning.net';var eplArgs = { iIF:1,sV:\"http://ads.us.e-planning.net/\",vV:\"4\",sI:\"8b09\",sec:\"Ficha\",eIs:[\"Top\",\"Right\",\"Expandible\"] };
            function eplCheckStart() {
            if (document.epl) {
                var e = document.epl;
                if (e.eplReady()) {
                    return true;
                } else {
                    e.eplInit(eplArgs);
                    if (eplArgs.custom) {
                        for (var s in eplArgs.custom) {
                            document.epl.setCustomAdShow(s, eplArgs.custom[s]);
                        }
                    }
                    return e.eplReady();
                }
            } else {
                if (eplLL) return false;
                if (!document.body) return false; var eS2; var dc = document.cookie; var ci = dc.indexOf(\"EPLSERVER=\");
                if (ci != -1) {
                    ci += 10; var ce = dc.indexOf(';', ci);
                    if (ce == -1) ce = dc.length;
                    eS2 = dc.substring(ci, ce);
                }
                var eIF = document.createElement('IFRAME');
                eIF.src = 'about:blank'; eIF.id = 'epl4iframe'; eIF.name = 'epl4iframe';
                eIF.width=0; eIF.height=0; eIF.style.width='0px'; eIF.style.height='0px';
                eIF.style.display='none'; document.body.appendChild(eIF);

                var eIFD = eIF.contentDocument ? eIF.contentDocument : eIF.document;
                eIFD.open();eIFD.write('<html><head><title>e-planning</title></head><bo'+'dy></bo'+'dy></html>');eIFD.close();
                var s = eIFD.createElement('SCRIPT'); s.src = 'http://' + (eS2?eS2:eS1) +'/layers/epl-41.js';
                eIFD.body.appendChild(s);
                if (!eS2) {
                    var ss = eIFD.createElement('SCRIPT');
                    ss.src = 'http://ads.us.e-planning.net/egc/4/2912';
                    eIFD.body.appendChild(ss);
                }
                eplLL = true;
                return false;
            }
        }
        eplCheckStart();
        function eplSetAdM(eID,custF) {
            if (eplCheckStart()) {
                if (custF) { document.epl.setCustomAdShow(eID,eplArgs.custom[eID]); }
                document.epl.showSpace(eID);
            } else {
                var efu = 'eplSetAdM(\"'+eID+'\", '+ (custF?'true':'false') +');';
                setTimeout(efu, 250);
            }
        }

        function eplAD4M(eID,custF) {
            document.write('<div id=\"eplAdDiv'+eID+'\"></div>');
            if (custF) {
                if (!eplArgs.custom) { eplArgs.custom = {}; }
                eplArgs.custom[eID] = custF;
            }
            eplSetAdM(eID, custF?true:false);
        }
        </script>";
 */
/*
        } else {
          $script="<script language=\"JavaScript\" type=\"text/javascript\">
            //<![CDATA[
            var eplDoc = document; var eplLL = false; var eS1 = 'us.img.e-planning.net';
            var eplArgsP = { iIF:1,sV:\"http://ads.us.e-planning.net/\",vV:\"4\",sI:\"8b09\",sec:\"Portada\",eIs:[\"SuperBanner\",\"Inferior\",\"Expandible\",\"Top\"] };
            function eplCheckStartP() {
                if (document.epl) {var e = document.epl; if (e.eplReady()) { return true; } else {
                    e.eplInit(eplArgsP); return e.eplReady(); }
                    } else {
                if (eplLL) return false;
                if (!document.body) return false; var eS2; var dc = document.cookie; var ci = dc.indexOf(\"EPLSERVER=\");
                if (ci != -1) {
                    ci += 10; var ce = dc.indexOf(';', ci);
                    if (ce == -1) ce = dc.length;
                    eS2 = dc.substring(ci, ce);
                }
                var eIF = document.createElement('IFRAME');
                eIF.src = 'about:blank'; eIF.id = 'epl4iframe'; eIF.name = 'epl4iframe';
                eIF.width=0; eIF.height=0; eIF.style.width='0px'; eIF.style.height='0px';
                eIF.style.display='none'; document.body.appendChild(eIF);

                var eIFD = eIF.contentDocument ? eIF.contentDocument : eIF.document;
                eIFD.open();eIFD.write('<html><head><title>e-planning</title></head><body></body></html>');eIFD.close();
                var s = eIFD.createElement('SCRIPT'); s.src = 'http://' + (eS2?eS2:eS1) +'/layers/epl-41.js';
                eIFD.body.appendChild(s);
                if (!eS2) {
                    var ss = eIFD.createElement('SCRIPT');
                    ss.src = 'http://ads.us.e-planning.net/egc/4/2912';
                    eIFD.body.appendChild(ss);
                }
                eplLL = true;
                return false;
                }
            }
            eplCheckStartP();
            function eplSetAdP(eID,custF) {
                if (eplCheckStartP()) {
                    if (custF) { document.epl.setCustomAdShow(eID,eplArgsP.custom[eID]); }
                    document.epl.showSpace(eID);
                } else {
                    var efu = 'eplSetAdP(\"'+eID+'\", '+ (custF?'true':'false') +');';
                    setTimeout(efu, 250);
                }
            }
            function eplAD4P(eID,custF) {
                document.write('<div id=\"eplAdDiv'+eID+'\"></div>');
                if (custF) {
                    if (!eplArgsP.custom) { eplArgsP.custom = {}; }
                    eplArgsP.custom[eID] = custF;
                }
                eplSetAdP(eID, custF?true:false);
            }
        //!>
        </script>";
    
  */      
}    