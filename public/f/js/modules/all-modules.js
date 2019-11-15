/**
* Modulo principal de all-modules
* @class all-modules
* @module yOSON
*/


/**
* Popup para visa ubicado en el footer
* @submodule show-popup
* @main default
*/
yOSON.AppCore.addModule("show-popup", function(Sb){ 
    var dom = {btnVisa:$('a.verified-by-visa-ico, .verify-visa-window')};
    $('.radio .verified').css('cursor', 'pointer');
    var popup = function(pagina, ancho, alto, barras){
        izquierda = (screen.width) ? (screen.width - ancho) / 2 : 100;
        arriba = (screen.height) ? (screen.height - alto) / 2 : 100;
        opciones ='toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars='+barras+',resizable=0,width='+ancho+',height='+alto+',left='+izquierda+',top='+arriba+'';
        window.open(pagina,"nombre"+"-"+ancho+"x"+alto,opciones);
    };
    return {
        init: function(){
            dom.btnVisa.bind('click', function(){popup('http://www.visanet.com.pe/visa.htm', 606, 405, 'no');});            
        },
        destroy: function(){}
    };
});

/**
* Validador general para todos los formularios
* @submodule validate
* @main default
*/
yOSON.AppCore.addModule('validate', function(Sb){ 
    return {
        init: function(oParams){
            var forms = oParams.form.split(",");
           $.each(forms,function(index,value){
                var settings = {};
                for(var prop in requires[value]) settings[prop]=requires[value][prop];
                $(value).validate(settings);
           });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jqValidate.js']);

/**
* LightBox para mostrar ingreso y registro
* @submodule lightbox-logIn-signUp
* @main default
*/
yOSON.AppCore.addModule('lightbox-logIn-signUp', function(Sb){  
    var dom = {logInInner:$('#log-in-inner'),signInInner:$('#sign-in-inner'),logIn  : $('#log-in'),signIn : $('#sign-in'),btnContinue :$('.btn-continuar-highlight'),btnPrice :$('.btn-price-highlight')},
        chargeLightBoxSession = function(oTrigger, sltrId, numberForm, sltrIdSecond){
            oTrigger.bind('click',function(event){
                event.preventDefault();
                var destaqueId = $(this).attr('destaqueId'),
                    pasoId = $(this).attr('paso'),
                    actionPaso2 = $('#next-form-destaque').attr('paso2'),
                    actionPaso3 = $('#next-form-destaque').attr('paso3');
                $.ajax({
                    url : yOSON.baseHost+'usuario/acceso/ingresar-registrar',
                    beforeSend : function(){
                        $.fancybox.showActivity();
                    },
                    success : function(element){
                        $.fancybox.hideActivity();
                        var logeoFlag = $(element).attr('logeo');
                        if(logeoFlag == 0){
                            $.fancybox(element,{
                                'transitionIn'   : 'elastic',
                                'transitionOut' : 'elastic',
                                'titleShow'    : false,
                                 onComplete : function(){
                                    if(numberForm == 1){
                                        yOSON.AppCore.runModule('validate',{form:sltrId});
                                    }else if(numberForm == 2){
                                        yOSON.AppCore.runModule('validate',{form:sltrId});
                                        yOSON.AppCore.runModule('validate',{form:sltrIdSecond});
                                        yOSON.AppCore.runModule('data-ubigeo');
                                         yOSON.AppCore.runModule('validate-document');

                                    }
                                    $('.password-strong').pstrength();
                                    $('#show-tip').live('click',function($e){
                                        $e.preventDefault();
                                        $this = $(this);
                                        if($this.hasClass('expand')){
                                          $('.kotear-tip').slideUp({
                                            easing: 'easeOutBounce',
                                            duration: 500,
                                            complete : function(){
                                              $this.removeClass('expand');
                                            }
                                          });
                                        }else{
                                          $('.kotear-tip').slideDown({
                                            easing: 'easeOutBounce',
                                            duration: 500,
                                            complete : function(){
                                              $this.addClass('expand');
                                            }
                                          });
                                        }
                                    });
                                },
                                onClosed : function(){
                                    // eliminar mensaje de error
                                    if($('.rbox').length != 0){
                                        $(this).remove();
                                     }
                                }
                            });
                        }else{

                            $('#idDestaqueHidden').val(destaqueId);
                            if(pasoId == 2){
                                 $('#next-form-destaque').attr('action',yOSON.baseHost+actionPaso2);
                            }else if(pasoId == 3){
                                $('#next-form-destaque').attr('action',yOSON.baseHost+actionPaso3);
                            }
                           $('#next-form-destaque').submit();
                        }
                    }
                });
            });
        };
        chargeLightBox = function(oTrigger, sltrId, numberForm, sltrIdSecond){
            var flag = 0;
            oTrigger.fancybox({
                    'transitionIn'   : 'elastic',
                    'transitionOut' : 'elastic',
                    'titleShow'    : false,
                     onComplete : function(){
                        if(numberForm == 1){
                            yOSON.AppCore.runModule('validate',{form:sltrId});
                            yOSON.AppCore.runModule('data-ubigeo');
                            yOSON.AppCore.runModule('validate-document');
                        }else if(numberForm == 2){
                            yOSON.AppCore.runModule('validate',{form:sltrId});
                            yOSON.AppCore.runModule('validate',{form:sltrIdSecond});
                            yOSON.AppCore.runModule('data-ubigeo');
                        }
                        /* para pasar a la pantalla de login desde registro */
                            dom.signInInner.live('click',function(){
                                $.fancybox.close();
                                flag = 1; /* abrir login */
                            });
                        /* para pasar a la pantalla de login desde login */
                            dom.logInInner.live('click',function(){
                                $.fancybox.close();
                                flag = 2; /* abrir registro */
                            });
                            flag = 0; /* resetea el flag  */
                            $('.password-strong').pstrength();
                            $('#show-tip').live('click',function($e){
                                $e.preventDefault();
                                $this = $(this);
                                if($this.hasClass('expand')){
                                  $('.kotear-tip').slideUp({
                                    easing: 'easeOutBounce',
                                    duration: 500,
                                    complete : function(){
                                      $this.removeClass('expand');
                                    }
                                  });
                                }else{
                                  $('.kotear-tip').slideDown({
                                    easing: 'easeOutBounce',
                                    duration: 500,
                                    complete : function(){
                                      $this.addClass('expand');
                                    }
                                  });
                                }
                            });
                    },
                    onClosed : function(currentArray, currentIndex, currentOpts){
                        if(flag == 1){
                           setTimeout(function(){dom.logIn.trigger('click');},currentOpts.speedOut);
                        }else if(flag == 2){
                           setTimeout(function(){dom.signIn.trigger('click');},currentOpts.speedOut);
                        }
                        // eliminar mensaje de error
                        if($('.rbox').length != 0){
                            $(this).remove();
                        }
                        
                    }
            });
        };

    return {
        init: function(){
            chargeLightBox(dom.logIn, '#form-log-in',1);
            chargeLightBox(dom.signIn,'#form-sign-in',1);
            chargeLightBoxSession(dom.btnContinue,'#form-sign-in',2,'#form-log-in');
            chargeLightBoxSession(dom.btnPrice,'#form-sign-in',2,'#form-log-in');
            
            dom.btnContinue.each(function(){
                var habilitarBtn = $(this).attr('habilitado');
                if(habilitarBtn == 0){
                    $(this).unbind('click');
                }
            });
            
            dom.btnPrice.each(function(){
                var habilitarBtn = $(this).attr('habilitado');
                if(habilitarBtn == 0){
                    $(this).unbind('click');
                    $(this).css('cursor','not-allowed');
                }
            });
        },
        destroy: function(){}
    };
},['libs/plugins/min/jquery.fancybox.js','libs/plugins/min/jquery.pstrength.min.js']);


/**
* Proceso para olvidado de clave
* @submodule forget-password
* @main all-modules
*/
yOSON.AppCore.addModule('forget-password', function(Sb){ 
    var dom = {forgetPasswordLink : $('#forget-password-link'),
        recoverPasswForm : $('#recover-passw-form'),
        logInForm : $('#form-log-in'),
        btnFinish : $('#recover-passw-form .btn-close, #code-recover-form .btn-close'),
        recoverPasswError : $('#recover-passw-error')},
        forgetPasswordFn = function(){
           dom.forgetPasswordLink.live('click',function(){
                $.fancybox({
                    'href' : yOSON.baseHost+'usuario/acceso/recuperar-clave',
                    'onComplete' : function(){
                        yOSON.AppCore.runModule('validate', {form:'#recover-passw-form'});
                    },
                    'onClosed' : function(){
                        dom.recoverPasswError.html('');
                    }
                });
           });
           
           
           dom.btnFinish.live('click',function(){
               $.fancybox.close();
           });
           
        };
    return {
        init: function(){
           forgetPasswordFn();
        },
        destroy: function(){}
    };
},['libs/plugins/min/jquery.fancybox.js']);





/**
* Mostrar listado de clasificados en la cabecera de toda la web
* @submodule banner-top classified
* @main all-modules
*/
yOSON.AppCore.addModule('banner-top-classified', function(Sb){ 
    var dom = {linkShow : $('a.link-gec')};
    bannerTopClassified = function(){
         dom.linkShow.clasificados({
          slideElement: '#slide-gec',
          ie6ChildBG: '#FFFFFF',
          ie6ChildBGHover: '#F3F3F3'
         });   
    }
    return {
        init: function(){
            bannerTopClassified();
        },
        destroy: function(){}
    };
});



/**
* Combos dependientes para buscar por la diferentes categorias
* @submodule search-categories
* @main all-modules
*/
yOSON.AppCore.addModule('search-categories', function(Sb){
   function staticLoadCmb($id, $idDepend, $data, $clearOther, $callback) {
        $($id).bind('change', function(){

            var val = $(this).val(),
                text = $($id+' :selected').text();
                $($id).next().val(text);

                var dataFiltrada = $.grep($data.data, function(n, i){
                    return (n[$data.nameFatherId] == val);
                });

                $($idDepend).html('<option value="">Cargando...</option>');
                var html = '<option value="">Seleccione un item</option>';            
                html = '';

                $(dataFiltrada).each(function(i){
                    html += '<option value="'+this[$data.nameId]+'">'+this[$data.nameValue]+'</option>';
                });

                $($clearOther).html('');
                $($idDepend).html(html);
                $($clearOther).next().val('');

                if ($callback && typeof($callback) === "function") {
                    $callback();
                }
        }); 
    }
    return {
        init: function(oParams){
            /*validacion de primer selector */
            $('#search-option1-'+oParams.tipo).attr('disabled','disabled');
            $('#search-option2-'+oParams.tipo).attr('disabled','disabled');
            
            $('#search-category-'+oParams.tipo+' :nth-child(1)').attr('selected', 'selected');
            staticLoadCmb('#search-category-'+oParams.tipo,
                          '#search-option1-'+oParams.tipo,
                         {'data':_categoria2,
                          'nameFatherId':'K_ID_PADRE',
                          'nameId':'K_ID_CATEGORIA',
                          'nameValue':'K_TIT'
                          }, 
                         '#search-option2-'+oParams.tipo,
                function() {
                    $('#search-option1-'+oParams.tipo+' :nth-child(1)').attr('selected', 'selected').trigger('change');
                    if($('#search-option1-'+oParams.tipo+' option').length == 0 && $('#search-option2-'+oParams.tipo+' option').length == 0){
                        $('#search-option1-'+oParams.tipo).attr('disabled','disabled');
                        $('#search-option2-'+oParams.tipo).attr('disabled','disabled');
                    }else{
                        $('#search-option1-'+oParams.tipo).removeAttr('disabled');
                    }
                }
            );
            staticLoadCmb('#search-option1-'+oParams.tipo,
                          '#search-option2-'+oParams.tipo, 
                          {'data':_categoria3,
                           'nameFatherId':'K_ID_PADRE',
                           'nameId':'K_ID_CATEGORIA',
                           'nameValue':'K_TIT'},
                function() {
                    $('#search-option2-'+oParams.tipo+' :nth-child(1)').attr('selected', 'selected').trigger('change');
                    if($('#search-option2-'+oParams.tipo+' option').length == 0){
                        $('#search-option2-'+oParams.tipo).attr('disabled','disabled');
                    }else{
                        $('#search-option2-'+oParams.tipo).removeAttr('disabled');
                    }
                }
            );
        },
        other: function(){  },
        destroy: function(){}
    }; 
});


/**
* Mostrar formulario de busquedas avanzadas
* @submodule show-advanced-search
* @main default
*/
yOSON.AppCore.addModule('show-advanced-search', function(Sb){ 
    var dom = {showSearchLink:$('.show-advanced-search'),searchContent:$('#advanced-search'),closeSearchLink :$('#advanced-search .close-search-advanced')};
    showAdvancedSearch = function(){
        dom.showSearchLink.bind('click',function(e){
            e.preventDefault();
            dom.searchContent.toggle('drop',{direction:'up'},500);
            yOSON.AppCore.runModule('validate',{form:'#form-advanced-search'});
        });
        dom.closeSearchLink.bind('click',function(e){
            e.preventDefault();
            dom.searchContent.hide('drop',{direction:'up'},500);
        });
    }
    return {
        init: function(){
            showAdvancedSearch();
        },
        destroy: function(){}
    };
},['libs/plugins/min/jqValidate.js']);


/**
* Muestra los tags de localizacion
* @submodule geotags
* @main default
*/
yOSON.AppCore.addModule('geotags', function(Sb){
    var dom = {tagSelectorLink : $('.tag-selector')};
    
    $.fn.tagSelector = function(_options){
            var options = $.extend({ firstSelect:true, valAllitems : '-1', varArrayName:'ubic', optIndexDef:0, delText: 'Remover ubicación', initDef :'' }, _options);
            return $(this).each(function(){
            $.fn.tagSelector.create($(this), options);
            });
            };
            $.fn.tagSelector.create = function(elem, options){
                var targets = elem.attr('alt').split(':'),
                srcTarget = $(targets[0]),
                destTarget = $(targets[1]),
                acButton = elem;
                elem.click(function(){
                if($(srcTarget[0].options[srcTarget[0].selectedIndex]).val() == options.initDef) return;
                createTag(srcTarget.val(), $(srcTarget[0].options[srcTarget[0].selectedIndex]));
                });

                function createTag(val, option){
                    if(existTagValidate(val, option)){
                        var li = $('<li/>')
                                 .hide()
                                 .text(option.text())
                                 .append($('<a href="#" title="'+options.delText+'"/>')
                                 .text('x')
                                 .click(function(event){
                                    event.preventDefault(); 
                                    $(this).parents('li').hide('blind',{direction:'left'},500).remove();
                                     isEmpty(); 
                                 }))
                        .append('<input type="hidden" value="'+ val +'" name="'+ options.varArrayName +'[]" />');
                        destTarget.append(li); 
                        li.show('blind',{direction:'left'},500);
                        option.addClass('bold gray');
                    }
                }
                function existTagValidate(val, option){
                    if(options.valAllitems == val)
                        resetItems();
                        var hds = destTarget.find('input:hidden');
                        for(var i=0; i < hds.length; i+=1){
                            if(hds[i].value == options.valAllitems){
                                $(hds[i]).parents('li').hide('blind',{direction:'left'},500).remove();
                                $(srcTarget[0].options).removeAttr('class');
                            }
                            if(hds[i].value == val){
                                $(hds[i]).parents('li').animate({opacity: 0.3 },500).animate({opacity: 1},200);
                                return false;
                            }
                        }
                    return true;
                }

                function resetItems(){
                    destTarget.empty();
                    $(srcTarget[0].options).removeAttr('class');
                }

                function isEmpty(){
                    if(destTarget.find('input:hidden').length < 1){
                        init();
                    }
                }

                function init(){
                    srcTarget.get(0).selectedIndex = 0;
                    if(options.firstSelect)
                    createTag(srcTarget.val(), $(srcTarget[0].options[0]));
                }

                init();

            };
    return {
        init: function(){
            dom.tagSelectorLink.tagSelector();
        },
        destroy: function(){}
    };
});


/**
* Validacion de documentos por diferentes tipos
* @submodule validate-document
* @main all-modules
*/
yOSON.AppCore.addModule('validate-document', function(Sb){ 
    var dom = {inputTipoDocumento:$('#slt-document'),inputNroDoc:$('#txt-document')};
     _cambioTipoDoc = function(){
        var _this=$(this);
        switch($.trim(_this.attr('value'))){
            case "05":
                dom.inputNroDoc.attr("maxlength", 8);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    dni: true,
                    required: true,
                    digits:true,
                    messages: {
                        required: 'Ingrese su Nro. de Documento.',
                        dni:'Ingrese su Nro. de Documento.',
                        digits:'Ingrese solo digitos.'
                    }
                });
            break;
            case "06":
                dom.inputNroDoc.attr("maxlength", 12);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    required: true,
                    digits:true,
                    messages: {
                        required: 'Ingrese su Pasaporte',
                        digits:'Ingrese solo digitos.'
                    }
                });
            break;
            case "07":
                dom.inputNroDoc.attr("maxlength", 11);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    ruc: true,
                    required: true,                    
                    messages: {
                        required: 'Ingrese su RUC',
                        ruc:'Ingrese su RUC'
                    }
                });
            break;
            default:
                dom.inputNroDoc.attr("maxlength", 12);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    alphanumsimple: true,
                    required: true,
                    messages: {
                        required: 'Ingrese su Nro. de Documento.'
                    }
                });
            break;
        }
    },
    bindEvents = function(){
        dom.inputTipoDocumento.bind("change", _cambioTipoDoc).trigger("change");
    };
    return {
        init: function(oParams){
            bindEvents();
        },
        destroy: function(){  }
    };
},['libs/plugins/min/jqValidate.js']);



/**
* Mostrar la data del ubigeo
* @submodule data-ubigeo
* @main all-usuario
*/
yOSON.AppCore.addModule("data-ubigeo", function(Sb){ 
    function staticLoadCmb($id, $idDepend, $otherDepend, $data, $clearOther, $callback) {
        $($id).bind('change', function(){
            var val = $(this).val(),
                valOtherDepend = $($otherDepend).val();
                var dataFiltrada = $.grep($data.data, function(n, i){
                    if(valOtherDepend == undefined) return (n[$data.nameFatherId] == val);
                    else return (n[$data.nameFatherId] == val && n[$data.nameFatherOtherId] == valOtherDepend);
                });
                $($idDepend).html('<option value="">Cargando...</option>');
                var html = '<option value=""></option>';            
                $(dataFiltrada).each(function(i){
                    html += '<option value="'+this[$data.nameId]+'">'+this[$data.nameValue]+'</option>';
                });
                $($idDepend).html(html);
                $($clearOther).html('');
                $($clearOther).next().val('');
                if ($callback && typeof($callback) === "function") {
                    $callback();
                }
        });
    }
    return {
        init: function(oParams){
            var dom = {
                    ubicationDepartement:$("#ubication_departement"),
                    ubicationDistrict:$("#ubication_district"),
                    ubicationProvince:$('#ubication_province'),
                    impCity :$("#imp_city")
                };
            staticLoadCmb('#ubication_departement',
                          '#ubication_province', '',
                          {'data':_ubiProv,
                           'nameFatherId':'ID_DPTO',
                           'nameId':'ID_PROV', 
                           'nameValue':'NOM'
                           }, 
                          '#ubication_district',
                function() {}
            );
            staticLoadCmb('#ubication_province',
                          '#ubication_district',
                          '#ubication_departement',
                          {'data':_ubiDist,
                           'nameFatherId':'ID_PROV',
                           'nameFatherOtherId':'ID_DPTO',
                           'nameId':'ID_UBIGEO',
                           'nameValue':'NOM'},
                function() {
                       $('#categoriaId3 :nth-child(1)').attr('selected', 'selected').trigger('change');
                }
            );

            /* habilita los departamentos de lima y se selecciona el distrito */
            dom.ubicationDepartement.bind('change',function(){
                if($(this).val() == 15){    
                    dom.ubicationDistrict.parent().parent().slideDown('slow');
                    dom.ubicationDistrict.bind('change',function(){
                        dom.impCity.html($("option:selected",this).text());
                    });
                }else{
                    dom.ubicationDistrict.parent().parent().slideUp('slow');
                    dom.ubicationProvince.bind('change',function(){
                        //dom.ubicationDistrict.attr('selected',true);
                        dom.impCity.html($("option:selected",this).text());
                    });
                }
                
            });

        },
        other: function(){  },
        destroy: function(){}
    };
});



/**
* Carge de imagen con fadeIn
* @submodule lazy-load-img
* @main all-modules
*/
yOSON.AppCore.addModule('lazy-load-img', function(Sb){ 
    var dom = {imgLazy:$('img.lazy')};
    return {
            init: function(){
                dom.imgLazy.lazyload({ 
                    effect : "fadeIn"
                });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.lazyload.js']);


/**
* Da un tamaño de 26% al input para que se cuadre por 100%
* @submodule search-categorie-complete
* @main all-modules
*/
yOSON.AppCore.addModule('search-categorie-complete', function(Sb){ 
    var dom = {searchGeneral:$('#q')};
    return {
            init: function(){
            dom.searchGeneral.removeClass('home');
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});

/**
* Envia a la cabecera cuando se tiene una lista muy larga 
* @submodule scrollTop
* @main default
*/
yOSON.AppCore.addModule('scroll-top', function(Sb){ 
    var dom = {scrollTopLink :$('.scroll-top-link'),htmlDom:$('html'),windowDom:$(window)};
    return {
        init: function(){
            
            $.fn.UItoTop = function(options) {
                var defaults = {
                    text: 'To Top',
                    min: 200,
                    inDelay:600,
                    outDelay:400,
                    containerID: 'toTop',
                    containerHoverID: 'toTopHover',
                    scrollSpeed: 1900,
                    easingType: 'linear'
                };
                
                var settings = $.extend(defaults, options);
                var containerIDhash = '#' + settings.containerID;
                var containerHoverIDHash = '#'+settings.containerHoverID;
                $('body').append('<a href="#" id="'+settings.containerID+'">'+settings.text+'</a>');
                
                var pos;
                $(containerIDhash).hide().click(function(){
                    if($.browser.msie){
                        $('html, body').animate({
                        scrollTop:0}, 
                        settings.scrollSpeed, 
                        settings.easingType);
                        $('#'+settings.containerHoverID, this).stop().animate({'opacity': 0 }, settings.inDelay, settings.easingType);
                        return false;
                    }else{
                    pos= $(window).scrollTop();
                    $("body").css({
                        "margin-top": -pos/4+"px",
                        "overflow-y": "scroll"
                    });
                    $(window).scrollTop(0);

                    $("body").css("transition", "all 1s "+"cubic-bezier(0.175, 0.885, 0.320, 1.275)");
    
                    $("body").css("margin-top", "0");

                    $("body").on("webkitTransitionEnd transitionend msTransitionEnd oTransitionEnd", function(){
                      $("body").css("transition", "none");
                    });
                    }

                })
                .prepend('<span id="'+settings.containerHoverID+'"></span>')
                .hover(function() {
                    $(containerHoverIDHash, this).stop().animate({
                        'opacity': 1
                    }, 600, 'linear');
                }, function() {
                    $(containerHoverIDHash, this).stop().animate({
                    'opacity': 0
                    }, 700, 'linear');
                });
                
                $(window).scroll(function() {
                var sd = $(window).scrollTop();
                if(typeof document.body.style.maxHeight === "undefined") {
                
                    $(containerIDhash).css({
                        'position': 'absolute',
                        'top': $(window).scrollTop() + $(window).height() - 50
                    });
                
                }
                
                if ( sd > settings.min )
                    $(containerIDhash).fadeIn(settings.inDelay);
                else
                    $(containerIDhash).fadeOut(settings.Outdelay);
                });
            }; 
            
            
            $().UItoTop({ easingType: 'easeOutQuart' });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});