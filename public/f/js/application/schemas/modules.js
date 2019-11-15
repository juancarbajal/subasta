/*!
 *
 * yOSON modules
 *
 * Copyright(c) 2011 yOSON <evangelizandolaweb@gmail.com>
 * yOSON developers <evangelizandolaweb@groups.facebook.com>
 *
 * MIT Licensed
 */

/**
 * List of router modules, router controllers and router actions.
 */

yOSON.AppSchema.modules = {
    'default': {
        controllers: {
            'index': {
                /**
                 * router actions
                 */
                actions: {
                    'index': function(){
                            yOSON.AppCore.runModule('gallery3D');
                    },
                    /**
                     * the instructions in byDefault function are executed 
                     * when the router controller 'index' hasn't actions defined
                     */
                    byDefault: function(){ }
                },
                /**
                 * the instructions in allActions function are executed automatically
                 * for the router controller 'index', in this case: 'index'
                 */
                allActions: function(){ }
            },
            'busqueda': {
                actions: {
                    'index': function(){ 
                        yOSON.AppCore.runModule('showMoreCategories');
                        yOSON.AppCore.runModule('send-list-ad-email');
                        yOSON.AppCore.runModule('impress-ad-list');
                        yOSON.AppCore.runModule('scroll-top');
                    },
                    'categoria' : function(){
                        yOSON.AppCore.runModule('search-categorie-complete');
                    },
                    byDefault: function(){
                    }
                },

                allActions: function(){
                    yOSON.AppCore.runModule('add-class-middle');
                }
            },
            'categoria': {
                actions: {
                    'index': function(){ 
                        yOSON.AppCore.runModule('gallery3D');
                        yOSON.AppCore.runModule('search-categorie-complete');
                    },
                    byDefault: function(){
                    }
                },
                allActions: function(){
                    
                }
            },
            'error': {
                actions: {
                    byDefault: function(){
                         yOSON.AppCore.runModule('search-categorie-complete');
                    }
                },
                allActions: function(){
                    
                }
            },
            'adultos': {
                actions: {
                    byDefault: function(){
                         yOSON.AppCore.runModule('search-categorie-complete');
                         yOSON.AppCore.runModule('validate',{form:'#aceptarAdultos'});
                    }
                },
                allActions: function(){
                    
                }
            },
            byDefault: function(){ }
        },
        allControllers : function(){
                yOSON.AppCore.runModule('show-advanced-search');
                yOSON.AppCore.runModule('geotags');
                yOSON.AppCore.runModule('search-categories',{tipo:'global'});
                yOSON.AppCore.runModule('search-categories',{tipo:'advanced'});                
                yOSON.AppCore.runModule('show-popup');
                yOSON.AppCore.runModule('lightbox-logIn-signUp');
                yOSON.AppCore.runModule('forget-password');
                yOSON.AppCore.runModule('banner-top-classified');
                yOSON.AppCore.runModule('lazy-load-img');
                
        }
    },
    'usuario': {
        controllers: {
            'publicacion': {
                actions: {
                    'registro-categoria': function(){
                        yOSON.AppCore.runModule('register-category');
                        yOSON.AppCore.runModule('validate',{form:'#formulariocategoria'});
                    },
                    'registro-destaque': function(){
                        yOSON.AppCore.runModule('show-example-ads');
                        yOSON.AppCore.runModule('forget-password');
                        
                    },
                    'confirmar-publicacion': function(){
                        yOSON.AppCore.runModule('show-video');
                        yOSON.AppCore.runModule('show-voucher');
                        yOSON.AppCore.runModule('validate',{form:'#frm_confirm'});
                        yOSON.AppCore.runModule('validate-document-dniRuc');
                    },
                    'registro-datos': function(){
                        yOSON.AppCore.runModule('validate',{form:'#frm_add'});
                        yOSON.AppCore.runModule('uploader-photos');
                        yOSON.AppCore.runModule('data-ubigeo');
                        yOSON.AppCore.runModule('tinymce-register');
                        yOSON.AppCore.runModule('validate-image');
                        yOSON.AppCore.runModule('count-word');
                        yOSON.AppCore.runModule('preview-impress');
                        yOSON.AppCore.runModule('display-none-dom');

                    },
                    byDefault: function(){ }
                },
                allActions: function(){ }
            },
            'aviso':{
                actions: {
                    byDefault: function(){ }
                },
                allActions: function(){
                    yOSON.AppCore.runModule('carousel-products');
                    yOSON.AppCore.runModule('remove-class-home');
                    yOSON.AppCore.runModule('show-photo-products');
                    yOSON.AppCore.runModule('report-ad');
                    yOSON.AppCore.runModule('send-ad-friend');
                    yOSON.AppCore.runModule('show-login-trigger');
                    yOSON.AppCore.runModule('add-favorites');
                    yOSON.AppCore.runModule('scroll-hash');
                    yOSON.AppCore.runModule('border-comment');
                    yOSON.AppCore.runModule('follow-ad');
                    yOSON.AppCore.runModule('question-seller');
                    yOSON.AppCore.runModule('show-advanced-search');
                    yOSON.AppCore.runModule('geotags');
                    yOSON.AppCore.runModule('search-categories',{tipo:'global'});
                    yOSON.AppCore.runModule('search-categories',{tipo:'advanced'});
                    yOSON.AppCore.runModule('scroll-top');
                }
            },
            'acceso':{
                actions: {
                    byDefault: function(){
                        yOSON.AppCore.runModule('show-forget-pass');
                        yOSON.AppCore.runModule('show-register-trigger');
                        yOSON.AppCore.runModule('validate','#form-log-in');
                        yOSON.AppCore.runModule('resend-mail-error');
                    }
                },
                allActions: function(){
                }
            },
            'venta':{
                actions: {
//                    'activas': function(){
//                        yOSON.AppCore.runModule('aviso-activos');
//                    },
//                    'inactivas': function(){
//                        yOSON.AppCore.runModule('inaviso-activos');
//                    },
                    byDefault: function(){
                        yOSON.AppCore.runModule('ap-pagination-venta');
                        yOSON.AppCore.runModule('ad-actions-active');
                        yOSON.AppCore.runModule('show-questions');
                        yOSON.AppCore.runModule('all-checked-uncheked');
                    }
                },
                allActions: function(){
                    
                }
            },
            'edicion':{
                actions: {
                    'validar-correo': function(){
                        yOSON.AppCore.runModule('validate',{form:'#change-email-valid'}); 
                       
                    },
                    byDefault: function(){
                        yOSON.AppCore.runModule('change-password');
                        yOSON.AppCore.runModule('change-email');
                        yOSON.AppCore.runModule('data-ubigeo');
                        yOSON.AppCore.runModule('data-ubigeo-select');
                        yOSON.AppCore.runModule('validate',{form:'#my-info'});
                        yOSON.AppCore.runModule('validate',{form:'#change-email-form'});
                        yOSON.AppCore.runModule('validate',{form:'#change-password-form'}); 
                    }
                },
                allActions: function(){
                    yOSON.AppCore.runModule('search-categorie-complete');
                    yOSON.AppCore.runModule('show-advanced-search');
                    yOSON.AppCore.runModule('geotags');
                    yOSON.AppCore.runModule('search-categories',{tipo:'global'});
                    yOSON.AppCore.runModule('search-categories',{tipo:'advanced'});
                }
            },
            'compra':{
                actions: {
                    'seguimiento': function(){
                        yOSON.AppCore.runModule('ap-pagination-venta');
//                        yOSON.AppCore.runModule('ad-actions-active');
//                        yOSON.AppCore.runModule('show-questions');
//                        yOSON.AppCore.runModule('all-checked-uncheked');
                    },
                    'preguntas-realizadas': function(){
                        yOSON.AppCore.runModule('ap-pagination-venta');
                        yOSON.AppCore.runModule('show-questions');
                    },
                    byDefault: function(){
                        
                    }
                },
                allActions: function(){
                    
                }
            },
            'registro':{
                actions: {
                    'bienvenido': function(){
                        yOSON.AppCore.runModule('search-categorie-complete');
                        yOSON.AppCore.runModule('show-advanced-search');
                        yOSON.AppCore.runModule('geotags');
                        yOSON.AppCore.runModule('search-categories',{tipo:'global'});
                        yOSON.AppCore.runModule('search-categories',{tipo:'advanced'}); 
                    },
                    'validar': function(){
                        yOSON.AppCore.runModule('search-categorie-complete');
                        yOSON.AppCore.runModule('show-advanced-search');
                        yOSON.AppCore.runModule('geotags');
                        yOSON.AppCore.runModule('search-categories',{tipo:'global'});
                        yOSON.AppCore.runModule('search-categories',{tipo:'advanced'}); 
                        yOSON.AppCore.runModule('validate',{form:'#register-user-validate'});
                    },
                    byDefault: function(){
                        yOSON.AppCore.runModule('change-email');
                        yOSON.AppCore.runModule('resend-confirmation');
                        yOSON.AppCore.runModule('validate',{form:'#change-email-form'});
                    }
                },
                allActions: function(){
                }
            },
            'clave':{
                actions: {
                    byDefault: function(){
                        yOSON.AppCore.runModule('validate',{form:'#change-password-form'});
                    }
                },
                allActions: function(){
                }
            },
            'facturacion':{
                actions: {
                    byDefault: function(){
                        yOSON.AppCore.runModule('validate',{form:'#frm_confirm'});
                        yOSON.AppCore.runModule('validate-document-dniRuc');
                    }
                },
                allActions: function(){
                }
            },
            byDefault: function(){ }
        },
        allControllers : function(){
            yOSON.AppCore.runModule('show-popup');
            yOSON.AppCore.runModule('lightbox-logIn-signUp');
            yOSON.AppCore.runModule('forget-password');
            yOSON.AppCore.runModule('banner-top-classified');       
            yOSON.AppCore.runModule('lazy-load-img');
        }
    },
    'admin': {
        controllers: {
            'auth':{
                actions: {
                    byDefault: function(){
                        yOSON.AppCore.runModule('validate',{form:'#loginAdmin'});
                    }
                },
                allActions: function(){
                }
            },
             'item':{
                actions: {
                    'especial':function(){
                       yOSON.AppCore.runModule('validate',{form:'#formSearchEspecial'});
                       yOSON.AppCore.runModule('funcionality');
                    },
                    byDefault: function(){
                        yOSON.AppCore.runModule('validate',{form:'#formSearchItem'});
                    }
                },
                allActions: function(){
                     yOSON.AppCore.runModule('admin-item');
                }
            },
            byDefault: function(){
               yOSON.AppCore.runModule('validate',{form:'#apfbFormSearch'});
               yOSON.AppCore.runModule('validate-document');
               yOSON.AppCore.runModule('funcionality');
               yOSON.AppCore.runModule('show-modal-add');
               
            }
        },
        allControllers : function(){
        }
    },
    byDefault : function(){ /*yOSON.AppCore.runModule('for-all-modules');*/ },
    
    /*-------------------------------------------------------------------------------------
     *@allModules: Modulos que se ejecutaran en todos los modulos
     *@param {Object} oMCA: Variable JSON con el modulo, controlador y action.
     **//*-------------------------------------------------------------------------------*/
    allModules : function(oMCA){
        
    }
    
};