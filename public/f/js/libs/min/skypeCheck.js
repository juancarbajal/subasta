var swfobject=function(){var aq="undefined",aD="object",ab="Shockwave Flash",X="ShockwaveFlash.ShockwaveFlash",aE="application/x-shockwave-flash",ac="SWFObjectExprInst",ax="onreadystatechange",af=window,aL=document,aB=navigator,aa=false,Z=[aN],aG=[],ag=[],al=[],aJ,ad,ap,at,ak=false,aU=false,aH,an,aI=true,ah=function(){var a=typeof aL.getElementById!=aq&&typeof aL.getElementsByTagName!=aq&&typeof aL.createElement!=aq,e=aB.userAgent.toLowerCase(),c=aB.platform.toLowerCase(),h=c?/win/.test(c):/win/.test(e),j=c?/mac/.test(c):/mac/.test(e),g=/webkit/.test(e)?parseFloat(e.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,d=!+"\v1",f=[0,0,0],k=null;if(typeof aB.plugins!=aq&&typeof aB.plugins[ab]==aD){k=aB.plugins[ab].description;if(k&&!(typeof aB.mimeTypes!=aq&&aB.mimeTypes[aE]&&!aB.mimeTypes[aE].enabledPlugin)){aa=true;d=false;k=k.replace(/^.*\s+(\S+\s+\S+$)/,"$1");f[0]=parseInt(k.replace(/^(.*)\..*$/,"$1"),10);f[1]=parseInt(k.replace(/^.*\.(.*)\s.*$/,"$1"),10);f[2]=/[a-zA-Z]/.test(k)?parseInt(k.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0}}else{if(typeof af.ActiveXObject!=aq){try{var i=new ActiveXObject(X);if(i){k=i.GetVariable("$version");if(k){d=true;k=k.split(" ")[1].split(",");f=[parseInt(k[0],10),parseInt(k[1],10),parseInt(k[2],10)]}}}catch(b){}}}return{w3:a,pv:f,wk:g,ie:d,win:h,mac:j}}(),aK=function(){if(!ah.w3){return}if((typeof aL.readyState!=aq&&aL.readyState=="complete")||(typeof aL.readyState==aq&&(aL.getElementsByTagName("body")[0]||aL.body))){aP()}if(!ak){if(typeof aL.addEventListener!=aq){aL.addEventListener("DOMContentLoaded",aP,false)}if(ah.ie&&ah.win){aL.attachEvent(ax,function(){if(aL.readyState=="complete"){aL.detachEvent(ax,arguments.callee);aP()}});if(af==top){(function(){if(ak){return}try{aL.documentElement.doScroll("left")}catch(a){setTimeout(arguments.callee,0);return}aP()})()}}if(ah.wk){(function(){if(ak){return}if(!/loaded|complete/.test(aL.readyState)){setTimeout(arguments.callee,0);return}aP()})()}aC(aP)}}();function aP(){if(ak){return}try{var b=aL.getElementsByTagName("body")[0].appendChild(ar("span"));b.parentNode.removeChild(b)}catch(a){return}ak=true;var d=Z.length;for(var c=0;c<d;c++){Z[c]()}}function aj(a){if(ak){a()}else{Z[Z.length]=a}}function aC(a){if(typeof af.addEventListener!=aq){af.addEventListener("load",a,false)}else{if(typeof aL.addEventListener!=aq){aL.addEventListener("load",a,false)}else{if(typeof af.attachEvent!=aq){aM(af,"onload",a)}else{if(typeof af.onload=="function"){var b=af.onload;af.onload=function(){b();a()}}else{af.onload=a}}}}}function aN(){if(aa){Y()}else{am()}}function Y(){var d=aL.getElementsByTagName("body")[0];var b=ar(aD);b.setAttribute("type",aE);var a=d.appendChild(b);if(a){var c=0;(function(){if(typeof a.GetVariable!=aq){var e=a.GetVariable("$version");if(e){e=e.split(" ")[1].split(",");ah.pv=[parseInt(e[0],10),parseInt(e[1],10),parseInt(e[2],10)]}}else{if(c<10){c++;setTimeout(arguments.callee,10);return}}d.removeChild(b);a=null;am()})()}else{am()}}function am(){var g=aG.length;if(g>0){for(var h=0;h<g;h++){var c=aG[h].id;var l=aG[h].callbackFn;var a={success:false,id:c};if(ah.pv[0]>0){var i=aS(c);if(i){if(ao(aG[h].swfVersion)&&!(ah.wk&&ah.wk<312)){ay(c,true);if(l){a.success=true;a.ref=av(c);l(a)}}else{if(aG[h].expressInstall&&au()){var e={};e.data=aG[h].expressInstall;e.width=i.getAttribute("width")||"0";e.height=i.getAttribute("height")||"0";if(i.getAttribute("class")){e.styleclass=i.getAttribute("class")}if(i.getAttribute("align")){e.align=i.getAttribute("align")}var f={};var d=i.getElementsByTagName("param");var k=d.length;for(var j=0;j<k;j++){if(d[j].getAttribute("name").toLowerCase()!="movie"){f[d[j].getAttribute("name")]=d[j].getAttribute("value")}}ae(e,f,c,l)}else{aF(i);if(l){l(a)}}}}}else{ay(c,true);if(l){var b=av(c);if(b&&typeof b.SetVariable!=aq){a.success=true;a.ref=b}l(a)}}}}}function av(b){var d=null;var c=aS(b);if(c&&c.nodeName=="OBJECT"){if(typeof c.SetVariable!=aq){d=c}else{var a=c.getElementsByTagName(aD)[0];if(a){d=a}}}return d}function au(){return !aU&&ao("6.0.65")&&(ah.win||ah.mac)&&!(ah.wk&&ah.wk<312)}function ae(f,d,h,e){aU=true;ap=e||null;at={success:false,id:h};var a=aS(h);if(a){if(a.nodeName=="OBJECT"){aJ=aO(a);ad=null}else{aJ=a;ad=h}f.id=ac;if(typeof f.width==aq||(!/%$/.test(f.width)&&parseInt(f.width,10)<310)){f.width="310"}if(typeof f.height==aq||(!/%$/.test(f.height)&&parseInt(f.height,10)<137)){f.height="137"}aL.title=aL.title.slice(0,47)+" - Flash Player Installation";var b=ah.ie&&ah.win?"ActiveX":"PlugIn",c="MMredirectURL="+af.location.toString().replace(/&/g,"%26")+"&MMplayerType="+b+"&MMdoctitle="+aL.title;if(typeof d.flashvars!=aq){d.flashvars+="&"+c}else{d.flashvars=c}if(ah.ie&&ah.win&&a.readyState!=4){var g=ar("div");h+="SWFObjectNew";g.setAttribute("id",h);a.parentNode.insertBefore(g,a);a.style.display="none";(function(){if(a.readyState==4){a.parentNode.removeChild(a)}else{setTimeout(arguments.callee,10)}})()}aA(f,d,h)}}function aF(a){if(ah.ie&&ah.win&&a.readyState!=4){var b=ar("div");a.parentNode.insertBefore(b,a);b.parentNode.replaceChild(aO(a),b);a.style.display="none";(function(){if(a.readyState==4){a.parentNode.removeChild(a)}else{setTimeout(arguments.callee,10)}})()}else{a.parentNode.replaceChild(aO(a),a)}}function aO(b){var d=ar("div");if(ah.win&&ah.ie){d.innerHTML=b.innerHTML}else{var e=b.getElementsByTagName(aD)[0];if(e){var a=e.childNodes;if(a){var f=a.length;for(var c=0;c<f;c++){if(!(a[c].nodeType==1&&a[c].nodeName=="PARAM")&&!(a[c].nodeType==8)){d.appendChild(a[c].cloneNode(true))}}}}}return d}function aA(e,g,c){var d,a=aS(c);if(ah.wk&&ah.wk<312){return d}if(a){if(typeof e.id==aq){e.id=c}if(ah.ie&&ah.win){var f="";for(var i in e){if(e[i]!=Object.prototype[i]){if(i.toLowerCase()=="data"){g.movie=e[i]}else{if(i.toLowerCase()=="styleclass"){f+=' class="'+e[i]+'"'}else{if(i.toLowerCase()!="classid"){f+=" "+i+'="'+e[i]+'"'}}}}}var h="";for(var j in g){if(g[j]!=Object.prototype[j]){h+='<param name="'+j+'" value="'+g[j]+'" />'}}a.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+f+">"+h+"</object>";ag[ag.length]=e.id;d=aS(e.id)}else{var b=ar(aD);b.setAttribute("type",aE);for(var k in e){if(e[k]!=Object.prototype[k]){if(k.toLowerCase()=="styleclass"){b.setAttribute("class",e[k])}else{if(k.toLowerCase()!="classid"){b.setAttribute(k,e[k])}}}}for(var l in g){if(g[l]!=Object.prototype[l]&&l.toLowerCase()!="movie"){aQ(b,l,g[l])}}a.parentNode.replaceChild(b,a);d=b}}return d}function aQ(b,d,c){var a=ar("param");a.setAttribute("name",d);a.setAttribute("value",c);b.appendChild(a)}function aw(a){var b=aS(a);if(b&&b.nodeName=="OBJECT"){if(ah.ie&&ah.win){b.style.display="none";(function(){if(b.readyState==4){aT(a)}else{setTimeout(arguments.callee,10)}})()}else{b.parentNode.removeChild(b)}}}function aT(a){var b=aS(a);if(b){for(var c in b){if(typeof b[c]=="function"){b[c]=null}}b.parentNode.removeChild(b)}}function aS(a){var c=null;try{c=aL.getElementById(a)}catch(b){}return c}function ar(a){return aL.createElement(a)}function aM(a,c,b){a.attachEvent(c,b);al[al.length]=[a,c,b]}function ao(a){var b=ah.pv,c=a.split(".");c[0]=parseInt(c[0],10);c[1]=parseInt(c[1],10)||0;c[2]=parseInt(c[2],10)||0;return(b[0]>c[0]||(b[0]==c[0]&&b[1]>c[1])||(b[0]==c[0]&&b[1]==c[1]&&b[2]>=c[2]))?true:false}function az(b,f,a,c){if(ah.ie&&ah.mac){return}var e=aL.getElementsByTagName("head")[0];if(!e){return}var g=(a&&typeof a=="string")?a:"screen";if(c){aH=null;an=null}if(!aH||an!=g){var d=ar("style");d.setAttribute("type","text/css");d.setAttribute("media",g);aH=e.appendChild(d);if(ah.ie&&ah.win&&typeof aL.styleSheets!=aq&&aL.styleSheets.length>0){aH=aL.styleSheets[aL.styleSheets.length-1]}an=g}if(ah.ie&&ah.win){if(aH&&typeof aH.addRule==aD){aH.addRule(b,f)}}else{if(aH&&typeof aL.createTextNode!=aq){aH.appendChild(aL.createTextNode(b+" {"+f+"}"))}}}function ay(a,c){if(!aI){return}var b=c?"visible":"hidden";if(ak&&aS(a)){aS(a).style.visibility=b}else{az("#"+a,"visibility:"+b)}}function ai(b){var a=/[\\\"<>\.;]/;var c=a.exec(b)!=null;return c&&typeof encodeURIComponent!=aq?encodeURIComponent(b):b}var aR=function(){if(ah.ie&&ah.win){window.attachEvent("onunload",function(){var a=al.length;for(var b=0;b<a;b++){al[b][0].detachEvent(al[b][1],al[b][2])}var d=ag.length;for(var c=0;c<d;c++){aw(ag[c])}for(var e in ah){ah[e]=null}ah=null;for(var f in swfobject){swfobject[f]=null}swfobject=null})}}();return{registerObject:function(a,e,c,b){if(ah.w3&&a&&e){var d={};d.id=a;d.swfVersion=e;d.expressInstall=c;d.callbackFn=b;aG[aG.length]=d;ay(a,false)}else{if(b){b({success:false,id:a})}}},getObjectById:function(a){if(ah.w3){return av(a)}},embedSWF:function(k,e,h,f,c,a,b,i,g,j){var d={success:false,id:e};if(ah.w3&&!(ah.wk&&ah.wk<312)&&k&&e&&h&&f&&c){ay(e,false);aj(function(){h+="";f+="";var q={};if(g&&typeof g===aD){for(var o in g){q[o]=g[o]}}q.data=k;q.width=h;q.height=f;var n={};if(i&&typeof i===aD){for(var p in i){n[p]=i[p]}}if(b&&typeof b===aD){for(var l in b){if(typeof n.flashvars!=aq){n.flashvars+="&"+l+"="+b[l]}else{n.flashvars=l+"="+b[l]}}}if(ao(c)){var m=aA(q,n,e);if(q.id==e){ay(e,true)}d.success=true;d.ref=m}else{if(a&&au()){q.data=a;ae(q,n,e,j);return}else{ay(e,true)}}if(j){j(d)}})}else{if(j){j(d)}}},switchOffAutoHideShow:function(){aI=false},ua:ah,getFlashPlayerVersion:function(){return{major:ah.pv[0],minor:ah.pv[1],release:ah.pv[2]}},hasFlashPlayerVersion:ao,createSWF:function(a,b,c){if(ah.w3){return aA(a,b,c)}else{return undefined}},showExpressInstall:function(b,a,d,c){if(ah.w3&&au()){ae(b,a,d,c)}},removeSWF:function(a){if(ah.w3){aw(a)}},createCSS:function(b,a,c,d){if(ah.w3){az(b,a,c,d)}},addDomLoadEvent:aj,addLoadEvent:aC,getQueryParamValue:function(b){var a=aL.location.search||aL.location.hash;if(a){if(/\?/.test(a)){a=a.split("?")[1]}if(b==null){return ai(a)}var c=a.split("&");for(var d=0;d<c.length;d++){if(c[d].substring(0,c[d].indexOf("="))==b){return ai(c[d].substring((c[d].indexOf("=")+1)))}}}return""},expressInstallCallback:function(){if(aU){var a=aS(ac);if(a&&aJ){a.parentNode.replaceChild(aJ,a);if(ad){ay(ad,true);if(ah.ie&&ah.win){aJ.style.display="block"}}if(ap){ap(at)}}aU=false}}}}();if(typeof SkypeDetection=="undefined"){SkypeDetection=function(){var a="http://api.skype.com/detection/detection_as3.swf";var h="skypedetectionswf";var p="skypedetectioncontainer";var b=false;var q=false;var f=false;var l=[];var n=[];var k=5000;var m=function(){var r=document.createElement("div");r.id=p;r.style.position="absolute";r.style.width="5px";r.style.height="5px";r.style.top="0px";r.style.left="-10px";var s=document.body&&document.body.appendChild(r);if(!s){e("Seems like container creating failed.");return}window.setTimeout(d,10)};var d=function(){if(typeof YAHOO!="undefined"&&YAHOO.widget&&YAHOO.widget.SWF){e("Using YUI SWF module to embed Flash content");var s=new YAHOO.widget.SWF(p,a,{version:9,fixedAttributes:{allowScriptAccess:"always",width:5,height:5}});q=true;h=s._id}else{if(window.jQuery&&$&&$.flash&&typeof $.flash.create=="function"){e("Using jquery-swfobject to embed Flash content");$("#"+p).flash({swf:a,id:h,width:5,height:5,hasVersion:9,params:{allowscriptaccess:"always"}});q=true}else{if(window.jQuery&&$&&$.fn.flash){e("Using jquery-flash to embed Flash content");$("#"+p).flash({id:h,src:a,width:5,height:5,allowscriptaccess:"always",version:"9.0"});q=true}else{if(typeof swfobject!="undefined"&&swfobject.embedSWF){e("Using SWFObject 2.x to embed Flash content");swfobject.embedSWF(a,p,5,5,"9.0",null,null,{allowScriptAccess:"always"},{id:h},g)}else{if(typeof deconcept!="undefined"&&deconcept.SWFObject){e("Using SWFObject 1.5 to embed Flash content");var r=new SWFObject(a,h,5,5,"9.0");r.addParam("allowScriptAccess","always");r.write(p);q=true}else{e("No supported way of embedding Flash was found");o();return}}}}}window.setTimeout(o,k)};var g=function(r){if(r.success==false){e("Flash embedding via SWFObject embedding failed");o()}else{if(r.success==true){e("SWFObject callback indicated success");q=true}}};var o=function(){if(!SkypeDetection.ready){e("Detection seems to have failed, calling failure callbacks");for(var r=0;r<n.length;r++){n[r]()}}};var i=function(){e("Detection succeeded, calling success callbacks");for(var r=0;r<l.length;r++){l[r]()}};var e=function(r){if(b&&typeof console!="undefined"&&console.log){console.log("[SkypeDetection] "+r)}};var j=function(r,t){for(var s=0;s<r.length;s++){if(r[s]===t){return}}r.push(t)};var c=function(){var s=document.getElementById(h);try{var t=s.getData()}catch(u){e("Getting data with swf.getData() failed, likely reason is browser issue with ExternalInterface setup");o();return}SkypeDetection.installed=s.isInstalled();e("Reading detection data, Skype is "+(SkypeDetection.installed?"installed":"not installed"));if(SkypeDetection.installed){SkypeDetection.version=t.version;SkypeDetection.platform=t.platform;SkypeDetection.language=t.language;e("Using Skype version '"+t.version+"' on '"+t.platform+"' platform in language '"+t.language+"'");if(s.getSharedObjectData){try{t=s.getSharedObjectData()}catch(u){e("Could not read swf.getSharedObjectData()")}if(t.ui_timezone){SkypeDetection.internal.profileTimezone=t.ui_timezone}if(t.os_timezone){SkypeDetection.internal.osTimezone=t.os_timezone}else{SkypeDetection.internal.osTimezone=parseInt(new Date().getTimezoneOffset()/60)}if(t.ui_installdate){if(typeof t.ui_installdate=="string"){t.ui_installdate=parseInt(t.ui_installdate)}if(isNaN(t.ui_installdate)||t.ui_installdate==0){SkypeDetection.internal.profileAge=-1}else{SkypeDetection.internal.profileAge=Math.floor(((new Date()).getTime()/1000-t.ui_installdate)/60/60/24)}}}if(s.getSessionData){try{t=s.getSessionData()}catch(u){e("Could not read swf.getSessionData()")}if(t.username){SkypeDetection.internal.username=t.username;var r=(new Date()).getTime()/1000;if(typeof t.expires!="undefined"&&t.expires<r){SkypeDetection.internal.username="";try{s.clearSessionData()}catch(u){}}}}}i()};return{setVerbose:function(r){b=r;e("Enabled verbose mode")},setReady:function(){e("Flash detection code indicated to JS that it is ready");SkypeDetection.ready=true;window.setTimeout(c,10)},detect:function(r,s){r&&j(l,r);s&&j(n,s);if(SkypeDetection.ready){e("Detection has already been run before");window.setTimeout(SkypeDetection.installed?i:o,10)}else{if(!q&&!f){f=true;e("Creating detection Flash helper");window.setTimeout(m,10)}else{e("Unhandled case, marked not ready and flash somehow created?")}}},isQualifiedVersion:function(s){if(!SkypeDetection.ready||!SkypeDetection.installed){return false}var r=SkypeDetection.version;e("Comparing detected version "+r+" to required version "+s);r=r.split(".");s=s.split(".");try{if(parseInt(r[0])>parseInt(s[0])||(parseInt(r[0])==parseInt(s[0])&&parseInt(r[1])>parseInt(s[1]))||(parseInt(r[0])==parseInt(s[0])&&parseInt(r[1])==parseInt(s[1])&&parseInt(r[3])>=parseInt(s[3]))){return true}}catch(t){}return false},ready:false,version:null,platform:null,language:null,installed:null,internal:{username:null,profileTimezone:null,osTimezone:null,profileAge:null}}}()}(function(){var _verbose=false;var _hasSkype=false;var _currentURI;var _notice;var _template='<div style="width: 540px; height: 305px; background: white url(http://download.skype.com/share/skypebuttons/oops/bg.png) top left no-repeat; position: relative; font: 14px Verdana, sans-serif;"><span style="position: absolute; left: 40px; top: 44px; font: 24px/24px Verdana, sans-serif; color: white; font-weight: 500;">Hello!</span><span style="position: absolute; left: 40px; top: 90px; width: 230px; font: 14px/18px Verdana, sans-serif; color: white;">Skype buttons require that you have the latest version of Skype installed. Don&rsquo;t worry, you only need to do this once.</span><span style="position: absolute; left: 290px; top: 90px; width: 220px; font: 14px/18px Verdana, sans-serif; color: white;">Skype is a little piece of software that lets you make free calls over the internet.<br /><a href="http://www.skype.com/go/features" style="color: white">Learn more about Skype</a></span><span style="position: absolute; left: 40px; top: 200px; font: 14px/18px Verdana, sans-serif; color: black; width: 460px;">Skype is free, easy and quick to download and install.<br /> It works with Windows, Mac OS X, Linux and your mobile device.</span><form action="http://www.skype.com/go/download" method="get" target="_blank" style="position: absolute; margin: 0; padding: 0; left: 40px; top: 255px; width: 460px;"><input type="submit" value="Download Skype" style="float: left;" /><input type="button" name="haveskype" value="Already have Skype" style="float: right;" /></form></div></div>';var log=function(msg){if(_verbose&&console&&console.log){console.log("[skypeCheck.js] "+msg)}};if(typeof SkypeDetection!="object"||typeof swfobject!="object"||!swfobject.addDomLoadEvent){log("Needed dependencies (SkypeDetection, SWFObject 2.x) were not found! Not checking for Skype");return}var addListener=function(obj,ev,fn){if(obj&&typeof obj.addEventListener!="undefined"){obj.addEventListener(ev,fn,false)}else{if(obj&&typeof obj.attachEvent!="undefined"){obj.attachEvent("on"+ev,fn)}else{log("No supported way to add event listener was found")}}};var addLinkChecks=function(){var links=document.getElementsByTagName("A");var l;for(var i=0;i<links.length;i++){l=links[i];if(l.href&&l.href.indexOf("skype:")==0){addListener(l,"click",linkClickCheck);continue}}};var linkClickCheck=function(e){if(!e){var e=window.event}var target=e.target||e.srcElement||null;if(target){while(target.tagName!="A"&&target.parentElement){target=target.parentElement}}if(SkypeDetection.installed||_hasSkype){log("Skype was detected, passing link through to Skype");return}else{log("Skype seems not to be installed");target&&target.href&&(_currentURI=target.href);showNotice();e.preventDefault&&e.preventDefault();e.stopPropagation&&e.stopPropagation();e.returnValue&&(e.returnValue=false);return false}};var showNotice=function(){var clientWidth=0,clientHeight=0;if(!_notice){if(document&&document.documentElement&&document.documentElement.clientWidth){clientWidth=document.documentElement.clientWidth;clientHeight=document.documentElement.clientHeight}else{if(document&&document.body&&document.body.clientWidth){clientWidth=document.body.clientWidth;clientHeight=document.body.clientHeight}}log("Creating notice element");_notice=document.createElement("DIV");_notice.id="skypeCheckNotice";_notice.style.position="absolute";_notice.style.zIndex="10000";
/*@cc_on
            @if (@_jscript_version == 5.6)
            _notice.style.position = "absolute";
            @end
            @*/
_notice.style.top=Math.max(0,Math.floor(clientHeight/2-152))+"px";_notice.style.left=Math.max(0,Math.floor(clientWidth/2-270))+"px";_notice.innerHTML=_template;document.body.appendChild(_notice);var f=_notice.getElementsByTagName("input");(f.length==2)&&addListener(f[1],"click",hasSkype);f.length&&addListener(f[0].parentElement,"submit",onDownloading)&&f[0].focus()}log("Showing notice element");_notice.style.visibility="visible"};var hasSkype=function(){log("User indicated having Skype, hiding notice, opening Skype URI "+_currentURI);_hasSkype=true;_notice.style.visibility="hidden";_currentURI&&location.replace(_currentURI);_currentURI=null};var onDownloading=function(){var i=_notice.getElementsByTagName("input");if(i.length>1){i[1].style["float"]="";i[1].value="I have Skype installed now";i[0].style.display="none"}};var skypeCheck=function(){return SkypeDetection.ready&&SkypeDetection.installed};swfobject.addDomLoadEvent(addLinkChecks);swfobject.addDomLoadEvent(SkypeDetection.detect);window.skypeCheck=skypeCheck})();