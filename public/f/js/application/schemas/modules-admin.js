/*=========================================================================================
 *@ListModules: Listado de todos los Modulos asociados al portal
 **//*===================================================================================*/
yOSON.AppSchema.modules = {    
    'xcrf2Nw=':{ /* Perfil Cuenta */
           controllers:{
               '1Njh0+PXodQ=':{
                   actions:{
                       /* Listado productos*/
                       //'zdTW1OY=': function(){ yOSON.AppCore.runModule('list-product'); },
                       
                       /* Other Action */
                       'other-action': function(){ /**/ },
                       byDefault:function(){}
                   },
                   allActions: function(){}
               },
               '09jW1Nw=':{
                   actions:{
                       /* Listado de Ordenes */
                       //'zdTW1OY=': function(){ yOSON.AppCore.runModule('list-orders') ; }
                       byDefault:function(){}
                   },
                   allActions: function(){}
               },
               byDefault:function(){}
           },
           allControllers:function(){
               yOSON.AppCore.runModule('list-product');
               yOSON.AppCore.runModule('list-orders') ;
           }
    },
    /*-------------------------------------------------------------------------------------
     *@byDefault: De no haber @modules se ejecuta esta por defecto
     **//*-------------------------------------------------------------------------------*/
    byDefault : function(){ /*yOSON.AppCore.runModule('for-all-modules');*/ },
    
    /*-------------------------------------------------------------------------------------
     *@allModules: Modulos que se ejecutaran en todos los modulos
     *@param {Object} oMCA: Variable JSON con el modulo, controlador y action.
     **//*-------------------------------------------------------------------------------*/
    allModules : function(oMCA){
        //yOSON.AppCore.runModule('menu');       
        yOSON.AppCore.runModule('slide-toogle');
        yOSON.AppCore.runModule('nav-tree');
        yOSON.AppCore.runModule('product-manage-image');
        yOSON.AppCore.runModule('tabs-products');
        yOSON.AppCore.runModule('state-order');
        yOSON.AppCore.runModule('validation',{form:"#frmConfis"});
        yOSON.AppCore.runModule('select-information');
        yOSON.AppCore.runModule('date-products');
    }    
};