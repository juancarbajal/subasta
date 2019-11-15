/*!
 *
 * yOSON AppCore
 *
 * @Description: Codigo para la manipulacion de los modulos en la aplicacion
 * @Dependency : yOSON.AppSandbox & yOSON.AppScript in appSandBox.js
 *
 */
yOSON.AppCore=(function(){var d=new yOSON.AppSandbox();var b={};var a=false;window.cont=0;var c=function(g){var e=b[g].definition(d);var f,h;if(!a){for(f in e){h=e[f];if(typeof h=="function"){e[f]=function(i,j){return function(){try{return j.apply(this,arguments)}catch(k){Console.log(i+"(): "+k.message)}}}(f,h)}}}return e};return{addModule:function(e,g,f){var f=(typeof(f)=="undefined")?[]:f;if(typeof(b[e])=="undefined"){b[e]={definition:g,instance:null,dependency:f}}else{throw'module "'+e+'" is already defined, Please set it again'}},getModule:function(e){if(e&&b[e]){return b[e]}else{throw'structureline58 param "sModuleId" is not defined or module not found'}},runModule:function(g,e){console.log(g);if(b[g]!==undefined){if(e===undefined){var e={}}e.moduleName=g;var f=this.getModule(g);var h=f.instance=c(g);if(h.hasOwnProperty("init")){Console.log("|runModule"+(++window.cont)+"|:---> "+g);if(f.dependency.length>0){yOSON.AppScript.charge([].copy(f.dependency),function(){h.init(e)},g+window.cont,1)}else{h.init(e)}}else{throw' ---> init function is not defined in the module "'+b[g]+'"'}}else{throw'module "'+g+'" is not defined or module not found'}},runModules:function(e){for(var f in e){this.runModule(e[f])}}}})();