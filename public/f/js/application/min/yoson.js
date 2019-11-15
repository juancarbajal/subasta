/*!
 *
 * yOSON AppSandbox
 *
 * @Description: Gestiona todos los oyentes y los notificadores de la aplicación
 * @type Object
 *
 */
yOSON.AppSandbox=function(){return{trigger:function(e,a){Console.log("|trigger-->");Console.log(a);var d;if(typeof(yOSON.AppSandbox.aActions[e])!="undefined"){var b=yOSON.AppSandbox.aActions[e].length;for(var c=0;c<b;c++){d=yOSON.AppSandbox.aActions[e][c];d.handler.apply(d.module,a)}}},stopEvents:function(h,d){var c=[];var g=h.length;for(var a=0;a<g;a++){var f=h[a];var b=yOSON.AppSandbox.aActions[f].length;for(var e=0;e<b;e++){if(d!=yOSON.AppSandbox.aActions[f][e].module){c.push(yOSON.AppSandbox.aActions[f][e])}}yOSON.AppSandbox.aActions[f]=c;if(yOSON.AppSandbox.aActions[f].length==0){delete yOSON.AppSandbox.aActions[f]}}},events:function(f,a,c){Console.log("|events-->");Console.log(a);var e=f.length;for(var b=0;b<e;b++){var d=f[b];if(typeof yOSON.AppSandbox.aActions[d]=="undefined"){yOSON.AppSandbox.aActions[d]=[]}Console.log("Sandbox-listen - line:45 - notifyListen:"+d+" <--> module:"+c);yOSON.AppSandbox.aActions[d].push({module:c,handler:a})}return this},request:function(c,d,a,b){Core.ajaxCall(c,d,a,b)}}};yOSON.AppSandbox.aActions=[];
/*!
 *
 * yOSON AppScript
 *
 * @Description: Carga script Javascript o Css en la pagina para luego ejecutar funcionalidades dependientes.
 * @Dependency : yOSON.AppSandbox & yOSON.AppScript in appSandBox.js
 * @Usage yOSON.AppScript.charge('lib/plugins/colorbox.js,plugins/colorbox.css', function(){ load! } );
 *
 */
yOSON.AppScript=(function(b,h){var g="";var c="";var e="";var j={};(function(m,l){g=m+"js/";c=m+"css/";e=(false)?l:""})(b,h);var k=function(l){return(l.indexOf("//")!=-1)?l.split("//")[1].replace(/[\/\.\:]/g,"_"):l.replace(/[\/\.\:]/g,"_")};var f=function(l,m){if(!j.hasOwnProperty(k(l))){j[k(l)]={state:true,fncs:[]}}j[k(l)].fncs.push(m)};var d=function(l){j[k(l)].state=false;for(var m=0;m<j[k(l)].fncs.length;m++){if(j[k(l)].fncs[m]=="undefined"){Console.log(j[k(l)].fncs[m])}j[k(l)].fncs[m]()}};var a=function(l,n){var m=document.createElement("script");m.type="text/javascript";if(m.readyState){m.onreadystatechange=function(){if(m.readyState=="loaded"||m.readyState=="complete"){m.onreadystatechange=null;n(l)}}}else{m.onload=function(){n(l)}}m.src=l;document.getElementsByTagName("head")[0].appendChild(m)};var i=function(m,o){var n=document.createElement("link");n.type="text/css";n.rel="stylesheet";n.href=m;document.getElementsByTagName("head")[0].appendChild(n);if(document.all){n.onload=function(){Console.log(this);o(m)}}else{var l=document.createElement("img");l.src=m;l.onerror=function(){if(o){o(m)}document.body.removeChild(this)};document.body.appendChild(l)}};return{charge:function(p,r,q,n){var l=this;if(p.length==0||p=="undefined"||p==""){return false}if(p.constructor.toString().indexOf("Array")!=-1&&p.length==1){var p=p[0]}var n=(typeof(n)!="number")?1:n;Console.log("[mod:"+q+"][level:"+n+"][script:"+p+"]",(p.indexOf&&p.indexOf("ColorB")!=-1));if(p.constructor.toString().indexOf("String")!=-1){var o=(p.indexOf(".js")!=-1);var s=(p.indexOf(".css")!=-1);if(!o&&!s){return false}var m=o?g:c;if(o||s){var p=(p.indexOf("http")!=-1)?(p+e):(m+p+e);if(!j.hasOwnProperty(k(p))){f(p,r);o?a(p,d):i(p,d)}else{if(j[k(p)].state){f(p,r)}else{r()}}}}else{if(p.constructor.toString().indexOf("Array")!=-1){this.charge(p[0],function(){Console.log((n+1),(q.indexOf("popup")!=-1));l.charge(p.remove(0),r,q,(n+2))},q,(n+1))}else{Console.log(p+" - no es un Array")}}}}})(yOSON.statHost,yOSON.statVers);
/*!
 *
 * yOSON AppCore
 *
 * @Description: Codigo para la manipulacion de los modulos en la aplicacion
 * @Dependency : yOSON.AppSandbox & yOSON.AppScript in appSandBox.js
 *
 */
yOSON.AppCore=(function(){var d=new yOSON.AppSandbox();var b={};var a=false;window.cont=0;var c=function(g){var e=b[g].definition(d);var f,h;if(!a){for(f in e){h=e[f];if(typeof h=="function"){e[f]=function(i,j){return function(){try{return j.apply(this,arguments)}catch(k){Console.log(i+"(): "+k.message)}}}(f,h)}}}return e};return{addModule:function(e,g,f){var f=(typeof(f)=="undefined")?[]:f;if(typeof(b[e])=="undefined"){b[e]={definition:g,instance:null,dependency:f}}else{throw'module "'+e+'" is already defined, Please set it again'}},getModule:function(e){if(e&&b[e]){return b[e]}else{throw'structureline58 param "sModuleId" is not defined or module not found'}},runModule:function(g,e){if(b[g]!==undefined){if(e===undefined){var e={}}e.moduleName=g;var f=this.getModule(g);var h=f.instance=c(g);if(h.hasOwnProperty("init")){Console.log("|runModule"+(++window.cont)+"|:---> "+g);if(f.dependency.length>0){yOSON.AppScript.charge([].copy(f.dependency),function(){h.init(e)},g+window.cont,1)}else{h.init(e)}}else{throw' ---> init function is not defined in the module "'+b[g]+'"'}}else{throw'module "'+g+'" is not defined or module not found'}},runModules:function(e){for(var f in e){this.runModule(e[f])}},runFunction:function(g,e){if(b[g]!==undefined){if(e===undefined){var e={}}e.moduleName=g;var f=this.getModule(g);var h=f.instance=c(g);if(e.parameters!==undefined){h[e.functionName](e.parameters)}else{h[e.functionName]()}}else{throw'module "'+g+'" is not defined or module not found'}}}})();