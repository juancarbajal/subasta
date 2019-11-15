

/*!
 *
 * yOSON AppCore
 *
 * @Description: Codigo para la manipulacion de los modulos en la aplicacion
 * @Dependency : yOSON.AppSandbox & yOSON.AppScript in appSandBox.js
 *
 */

/*
var loadElement = function(id, fnc, t){
    var t    = t?t:1;
    var oE   = document.getElementById(id);
    var time = window.setInterval(function(){
        if(oE){
            window.clearInterval(time);
            Console.log(oE); fnc();
        }else{ Console.log("nada"); }
    }, t);
    //window.load = function(){ window.clearInterval(time) };
};
loadElement('login-and-register',function(){alert('Exite!')},1);*/

yOSON.AppCore = (function(){
    /*"use strict";*/
    /*@member Core*/
    var oSandBox = new yOSON.AppSandbox(); /*@private Entorno de trabajo de todos los modulos (Caja de arena)*/
    var oModules = {};                      /*@private Almacena todos los modulos registrados*/
    var debug    = false;                   /*@private Habilitar-Deshabilitar modo de depuracion*/
    window.cont = 0;
    /*@private crea instancia del módulo al ejecutar AppCore.start */
    /*@return Instacia del modulo*/
    var doInstance = function(sModuleId){
        /*metodo creator se asigna a un modulo al llamar al registrar un modulo con el metodo register del AppCore*/ 
        var instance = oModules[sModuleId].definition( oSandBox );
        var name, method;
        if(!debug){
            for(name in instance){
                method = instance[name];
                if(typeof method == "function"){
                    instance[name] = function(name, method){
                        return function(){
                            try{return method.apply(this,arguments);}catch(ex){Console.log(name + "(): " + ex.message);}
                        };
                    }(name, method);
                }
            }
        } return instance; /*retornamos la Instancia del modulo*/
    };
	
    return {
        /*path.module*/
        addModule: function(sModuleId, fDefinition, aDep){  
            var aDep = (typeof(aDep)=='undefined') ? [] : aDep;
            if(typeof(oModules[sModuleId])=='undefined'){
                oModules[sModuleId]={definition:fDefinition, instance:null, dependency:aDep}; /*Console.log(oModules[sModuleId].definition);*/
            }else{ throw 'module "'+sModuleId+'" is already defined, Please set it again'; }
        },
		
        getModule: function(sModuleId){
            if (sModuleId && oModules[sModuleId]){ return oModules[sModuleId]; }
            else{ throw 'structureline58 param "sModuleId" is not defined or module not found'; }
        },
		
        runModule: function(sModuleId, oParams){
            console.log(sModuleId);
            if(oModules[sModuleId]!==undefined){
                
                if(oParams === undefined){ var oParams = {}; }
                oParams.moduleName = sModuleId;  /*Un primer valor de oParams*/
                var mod = this.getModule(sModuleId);
                var thisInstance = mod.instance = doInstance(sModuleId); 
                
                if(thisInstance.hasOwnProperty('init')){   /*if(sModuleId=='modal-images')Console.log('hay init()');*/  
                    Console.log('|runModule'+(++window.cont)+'|:---> '+sModuleId);
                    if(mod.dependency.length>0){  /*if(sModuleId=='modal-images')Console.log('core60---> length:'+mod.dependency.length);*/
                        yOSON.AppScript.charge([].copy(mod.dependency), function(){ thisInstance.init(oParams); }, sModuleId+window.cont, 1);
                    }else{ thisInstance.init(oParams); }
                    
                }else{ throw ' ---> init function is not defined in the module "'+oModules[sModuleId]+'"'; }
                
            }else{ throw 'module "'+sModuleId+'" is not defined or module not found'; }
        },
		
        runModules: function(aModuleIds){
            for(var id in aModuleIds){ this.runModule(aModuleIds[id]); }
        }
    }
})();