/**
* Modulo principal de usuario
* @class usuario
* @module yOSON
*/

/**
* Registrar categoria
* @submodule register-category
* @main usuario
*/
yOSON.AppCore.addModule("register-category", function(Sb){ 
    function staticLoadCmb($id, $idDepend, $data, $clearOther, $callback) {
        $($id).bind('change', function(){
            var val = $(this).val(),
                text = $($id+' :selected').text(); 
                $($id).next().val(text);
                
                var dataFiltrada = $.grep($data.data, function(n, i){
                    return (n[$data.nameFatherId] == val);
                });
                $($idDepend).html('<option value="">Cargando...</option>');
                //var html = '<option value=""></option>';            
                var html = '';
                $(dataFiltrada).each(function(i){
                    html += '<option value="'+this[$data.nameId]+'" title="'+this[$data.nameValue]+'">'+this[$data.nameValue]+'</option>';
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
            $('#categoriaId2, #categoriaId3, #categoriaId4').css('height','400px');
            $('#categoriaId1 :nth-child(1)').attr('selected', 'selected');
            staticLoadCmb('#categoriaId1',
                          '#categoriaId2',
                          {'data':_categoria2,
                           'nameFatherId':'K_ID_PADRE',
                           'nameId':'K_ID_CATEGORIA',
                           'nameValue':'K_TIT'
                           }, 
                          '#categoriaId3, #categoriaId4',
                function() {   
                    $('#categoriaId2 :nth-child(1)').attr('selected', 'selected').trigger('change');
                }
            );
            staticLoadCmb('#categoriaId2',
                          '#categoriaId3', 
                         {'data':_categoria3,
                          'nameFatherId':'K_ID_PADRE',
                          'nameId':'K_ID_CATEGORIA',
                          'nameValue':'K_TIT'
                         }, 
                         '#categoriaId4',
                function() {   
                    $('#categoriaId3 :nth-child(1)').attr('selected', 'selected').trigger('change');
                }
            );
            staticLoadCmb('#categoriaId3',
                          '#categoriaId4', 
                          {'data':_categoria4,
                           'nameFatherId':'K_ID_PADRE',
                           'nameId':'K_ID_CATEGORIA', 
                           'nameValue':'K_TIT'
                          }, 
                '',
                function() {
                    $('#categoriaId4 :nth-child(1)').attr('selected', 'selected').trigger('change');
                }
            ); 
            


            $('#formulariocategoria').bind('submit',function(e){
               if($('#categoriaId1').val() == 1656){
                    e.preventDefault();
                    $.fancybox({
                        'content' : $('#adulto-msg-temp').html(),
                        'onComplete' : function(){
                            console.log('mam');
                            $('#adult-acept').live('click',function(){
                                console.log('click acept');
                                $('#formulariocategoria')[0].submit();
                            });
                            $('#adult-cancel').live('click',function(){
                                console.log('click calecel');
                                $.fancybox.close();
                            });
                        }
                    });

                } 
            });
        },
        /*Public method : Implementacion de alguna funcionalidad asociada a este modulo.*/
        other: function(){  },
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Mostrar la fotografia de como quedarian los destacados
* @submodule show-example-ads
* @main usuario
*/
yOSON.AppCore.addModule('show-example-ads', function(Sb){ 
    var dom = {showExampleLink :$('.show-example-img')};
    return {
        init: function(){
            dom.showExampleLink.fancybox({
                    'transitionIn'   : 'elastic',
                    'transitionOut' : 'elastic',
                    'titleShow'    : false
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Muestra video pago efectivo
* @submodule show-video
* @main usuario
*/
yOSON.AppCore.addModule('show-video', function(Sb){ 
    var dom = {linkVideo : $('#link-video')},
        showVideo = function(){
            dom.linkVideo.fancybox({
                'transitionIn'   : 'elastic',
                'transitionOut' : 'elastic',
                'titleShow'    : false
            });
        }
    return {
        init: function(){
            function checkVideo(){
                if (!!document.createElement('video').canPlayType) {
                    var vidTest = document.createElement("video");
                    var oggTest = vidTest.canPlayType('video/ogg; codecs="theora, vorbis"');

                    if (!oggTest) {
                        h264Test = vidTest.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');
                        if (!h264Test) {
                            return false;
                        } else {
                            if (h264Test == "probably") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    } else {
                        if (oggTest == "probably") {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }

            if (checkVideo() != true) {
                var params = {},
                flashvars = {},
                baseUrl_ = yOSON.baseHost ;/*escribir aqui la ruta estatica del video y/o la imagen tambien */
                params.allowscriptaccess = "always";
                params.allowfullscreen = "true";
                params.wmode = "opaque";
                params.flashvars = "file=" + baseUrl_ + "f/video/1.flv&repeat=no&stretching=fill&skin=" + baseUrl_ + "f/swf/md.swf&autostart=false&bufferlength=1&image=" + baseUrl_ + "f/video/1.jpg";
                swfobject.embedSWF("" + baseUrl_ + "f/swf/playertv.swf", "mediaplayer", "500", "300", "9", "" + baseUrl_ + "f/js/swfobject/expressInstall.swf", flashvars, params);
            }

            showVideo();
            
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js','swfobject/swfobject.js']);


/**
* Cargador de fotos
* @submodule uploader-photos
* @main usuario
*/
yOSON.AppCore.addModule('uploader-photos', function(Sb){ 
    var dom = {};
    return {
        init: function(){
            var productPath='',
            addImgUrl = yOSON.baseHost+'usuario/publicacion/image-upload',
            delImgUrl = yOSON.baseHost+'usuario/publicacion/image-delete/idfoto/',
            extension = '.jpg',
            dom = {
                frm: 'frm_add',
                parentRight: '.fielset-content-right',
                parentLeft: '.fielset-content-left',
                inputFile: '#photo_product',
                inputDesc: '#img-description',
                btnAdd: '#addProductImage'
            },
            options = {
                frm: dom.frm,
                onComplete: function (json){
                    var data = $.parseJSON(json);

                    $(dom.inputFile).val('');

                    $padre = $(dom.inputFile).parent();
                    $hijo = $(dom.inputFile);
                    $padre.html('');
                    $padre.append($hijo);

                    $('#'+dom.frm).attr('action', yOSON.baseHost+'usuario/publicacion/confirmar-publicacion');
                    $('#'+dom.frm).attr('target', '');

                    $(dom.inputDesc).val('');

                    if (data["status"]==1){
                        $(dom.parentRight).append('<div class="w120" id="'+data["id"]+'"><div class="options"><div class="item-del"><a title="eliminar" href="javascript:;"><img class="" alt="eliminar" src="'+yOSON.baseHost+'/f/img/remove.png"></a></div><div class="item-fav"><a title="default" href="javascript:;"><img class="" alt="default" src="'+yOSON.baseHost+'f/img/fav.png"></a></div></div><a title="imagen" href="javascript:;"><img class="main-class" alt="imagen" width="130" height="130" src="'+data["url"]+'"></a></div>');
                        $(dom.parentRight).trigger('change');

                    }else{
                    }
                }
            }

            var vfile,vext,cond=true;
            $(dom.btnAdd).click(function(){
                vfile=$(dom.inputFile).val().split('.');            
                vext=vfile[vfile.length-1];
                if(!/(jpg|gif|png|JPG|GIF|PNG)/gi.test($.trim(vext))){
                    cond=false;
                }
                
                if(cond){
                    $('#'+dom.frm).attr('action', addImgUrl);
                    $.fn.iframeUp('submit', options);
                }else{
                     $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>Solo puedes subir imagenes <strong style="font-size:16px">jpg</strong> , <strong style="font-size:16px">png</strong> o <strong style="font-size:16px">gif</strong></p>');
                }
                cond=true;
            });



            var options2 = {
                frm: '#'+dom.frm,
                parentRight: dom.parentRight,
                parentLeft: dom.parentLeft,
                url_del: delImgUrl,
                //defaultImage:AppWeb.statHost+"img-admin/img-principal.jpg",
                inputType:'hidden',
                separator:'-',
                limit: _cantImg,
                del: false,
                deleteImage: function(opts){

                    $(opts.itemDel+' a').live('click', function(event){

                        var $that = $(this);
                        opts.current=$that.parent().parent().parent().attr(opts.attributeRight);
                        var remove_link=$that.parent().parent().parent().attr(opts.attributeRight);

                        if (remove_link!='') {
                            
                            $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>¿Desea elimnar la imagen?</p><div class="control-group"><div class="control"><button type="button" id="delete-img-picture" class="btn-kotear" name="continuar"><span>Continuar</span></button><a value="Cancelar" class="btn-close" name="cancel" href="javascript:;" id="cancel-img-picture"><span>Cancelar</span></a></div></div></div>');

                            $('#cancel-img-picture').bind('click',function(){
                                $.fancybox.close();
                            });
                            
                            $('#delete-img-picture').bind('click',function(){

                                $.fancybox.close();
                            
                                $.ajax({
                                    url: opts.url_del+remove_link,
                                    success: function(json) {
                                        var data = $.parseJSON(json);

                                        if (data['code']==1) {
                                            if (opts.info==true && data['msg']!='' || data['msg']!=undefined) {
                                            }
                                            $that.parent().parent().parent().remove();
                                            $.fn.pictureManager('update', opts);
                                        }else{
                                            if (opts.info==true && data['msg']!='' || data['msg']!=undefined) {
                                            }
                                        }
                                    }
                                }); // ajax
                            
                            }); // $('#delete-img-picture').click()

                        }

                    });

                },onLimit: function(limit){                    
                    $('.options-image, .detail-max').slideUp('slow');
                },
                outLimit: function(limit){
                    $('.options-image, .detail-max').slideDown('slow');
                }
            };

            $.fn.pictureManager(options2);
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jqPictureManager.js','libs/plugins/min/jqSlider.js','libs/plugins/min/jqIframeUp.js','libs/plugins/min/ui.js']);

/**
* Slider para mostrar los productos en la parte inferior del detalle del producto
* @submodule carousel-products
* @main aviso
*/
yOSON.AppCore.addModule('carousel-products', function(Sb){ 
    var dom = {
        adCarouselProductsTop:$('#ad-carousel-products-top'),
        adCarouselProductsClasTop:$('.ad-carousel-products-top'),
        adCarouselProductsBottom:$('#ad-carousel-products-bottom'),
        adCarouselProductsClasBottom:$('.ad-carousel-products-bottom'),
        jcarouselClip :$(".jcarousel-clip")
    };
    function carouselInitCallbackTop(carousel){
        carousel.buttonNext.bind('click', function() {
            carousel.startAuto(0);
        });

        carousel.buttonPrev.bind('click', function() {
            carousel.startAuto(0);
        });

        carousel.clip.hover(function() {
            carousel.stopAuto();
        }, function() {
            carousel.startAuto();
        });
    }
    
    function carouselInitCallbackBottom(carousel){
        carousel.buttonNext.bind('click', function() {
            carousel.startAuto(0);
        });

        carousel.buttonPrev.bind('click', function() {
            carousel.startAuto(0);
        });

        carousel.clip.hover(function() {
            carousel.stopAuto();
        }, function() {
            carousel.startAuto();
        });
    }

    return {
        init: function(){
            //top
            
            if(dom.adCarouselProductsTop.find('.inner-wrapper-image').length > 5){
                dom.jcarouselClip.css("width","897px");
                dom.adCarouselProductsTop.jcarousel();
                dom.adCarouselProductsClasTop.jcarousel({
                    easing: 'easeInOutQuad',
                    animation: 1250,
                    auto: 2,
                    wrap: 'circular',
                    initCallback: carouselInitCallbackTop /*werik*/
                });
                $('.item-product').css('width','165px');
            }else{
                $('.item-product').css('width','158px');
                dom.adCarouselProductsTop.find('img.btn-arrow').hide();
                dom.adCarouselProductsTop.find('ul.jcarousel-list').css('list-style','none');
                
            }

            //bottom
            if(dom.adCarouselProductsBottom.find('.inner-wrapper-image').length > 5){
                dom.jcarouselClip.css("width","897px");
                dom.adCarouselProductsBottom.jcarousel();
                dom.adCarouselProductsClasBottom.jcarousel({
                    easing: 'easeInOutQuad',
                    animation: 1250,
                    auto: 2,
                    wrap: 'circular',
                    initCallback: carouselInitCallbackBottom
                });
                $('.item-product').css('width','165px');
            }else{
                $('.item-product').css('width','158px');
                dom.adCarouselProductsBottom.find('img.btn-arrow').hide();
                dom.adCarouselProductsBottom.find('ul.jcarousel-list').css('list-style','none');
            }
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.jcarousel.js','libs/plugins/min/jquery.easing.js']);


/*detail*/

/**
* Estilar buscador detalle producto y posiciona la busqueda avanzada
* @submodule remove-class-home
* @main default
*/
yOSON.AppCore.addModule('remove-class-home', function(Sb){ 
    var dom = {inputSearch:$('#q')};
    return {
        init: function(){
            dom.inputSearch.removeClass('home');
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});



/**
* Mostrar las fotos del producto
* @submodule show-photo-products
* @main usuario
*/
yOSON.AppCore.addModule('show-photo-products', function(Sb){ 
    var dom = {clickImgFull: $('#click-img-full') ,photoMain:$('#photo-main') , showMainPhoto :$('.show-photo[rel=groupGallery]'),showMainPhotoTrigger :$('.image-product .show-photo[rel=groupGallery]'),showGalleryAll : $('#show-gallery-all')};
    return {
        init: function(){
            dom.showMainPhoto.fancybox({
                    'padding'           : 0,
                    'transitionIn'      : 'elastic',
                    'transitionOut'     : 'elastic',
                    'titlePosition'     : 'over',
                    'titleFormat'       : function(title,currentArray,currentIndex,currentOpts){
                        return '<span id="fancybox-title-over">'+(currentIndex+1)+'/'+currentArray.length+(title.length?' &nbsp; '+title:'')+'</span>'
                    }
            });
            
            var $firstImage =    $('.image-mini-product li:first-child a'); 
            
            dom.showGalleryAll.bind('click',function(){
                 $firstImage.trigger('click');
            });
            
            dom.photoMain.bind('click',function(){
                 $firstImage.trigger('click');
            });
            
            dom.clickImgFull.bind('click',function(){
                $firstImage.trigger('click');
            });
           
            var numberFind = $('.show-photo[rel=groupGallery]').children('img');
            $.each(numberFind,function(i,elementDom){
              if($(elementDom).attr('src').indexOf('notFound') != -1){
                $(elementDom).parents('a').attr('href',yOSON.baseHost+'img/notFoundBig.jpg');
              }
           });
           
            
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);



/**
* Permite contar las palabras escritas
* @submodule count-word
* @main usuario
*/
yOSON.AppCore.addModule('count-word', function(Sb){ 
    var dom = {letterFree:$("#letter_free"),
               textImpValid:$("#text_imp_valid"),
               displayCountError:$(".display_count_error"),
               textImpWordValid:$("#text_imp_word_valid")
           };
    return {
        init: function(){
            //var nunMax= 10;
            var nunMax = _cantWordImp; 
            dom.letterFree.wordCount({
                maxWords: nunMax,
                onOverflow: function(){
                    var longitud = dom.textImpValid.val().length;
                    dom.letterFree.attr("maxlength",longitud);
                    dom.displayCountError.text("- Excediste el limite de "+ nunMax +" palabras permitidas.");
                    
                    dom.displayCountError.addClass("error"); 
                    dom.textImpWordValid.val("").addClass("error"); //gogo
                    window.EXCWORDS = true;
                },
                onRegular: function(){
                    window.EXCWORDS = false;
                    dom.displayCountError.text("");
                    dom.displayCountError.removeClass("error"); 
                    dom.textImpWordValid.val("wordEx").removeClass("error");

                }
            });     
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.wordCount.js']);


/**
* Edición de texto de ingreso para el registro de un anuncio
* @submodule tinymce-register
* @main usuario
*/
yOSON.AppCore.addModule('tinymce-register', function(Sb){ 
    var dom = {textAreaTinymce:$('textarea#informacion_adicional'),linkInline:$('a#_mce_item_31')};
    return {
        init: function(){
            dom.textAreaTinymce.attr('rows','15');
            dom.textAreaTinymce.attr('cols','80');
            dom.textAreaTinymce.css('width','80%');
            dom.textAreaTinymce.tinymce({
                // Location of TinyMCE script
                script_url : yOSON.baseHost+'f/js/libs/plugins/tiny_mce/tiny_mce.js',
                language : "es",
                // General options
                theme : "advanced",
                plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

                // Theme options
                theme_advanced_buttons1 : "bold,italic,underline,justifyleft,justifycenter,justifyright,|,bullist,numlist,|,outdent,indent,|,link,unlink,image,cleanup",
                theme_advanced_buttons2 : "fontselect,fontsizeselect,|,forecolor,backcolor,|,undo,redo,|,fullscreen,code",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,
                theme_advanced_resize_horizontal : false,


                // Replace values for the template plugin
                template_replace_values : {
                    username : "Kotear",
                    staffid : "991234"
                }
            });
            dom.linkInline.css('display','inline');
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/tiny_mce/tiny_mce.js','libs/plugins/tiny_mce/jquery.tinymce.js']);


/**
* Envia mensaje para validar la imagen
* @submodule validate-image
* @main usuario
*/
yOSON.AppCore.addModule('validate-image', function(Sb){ 
    var dom = {formRegisterData:$('.form-register-data'),idsHiddenAd:$('#ids_hidden_ad'),msgErrorImages:$('.msg-error-images'),adminImageContent:$('.admin-image-content')};
    return {
        init: function(){
            $('#ids_hidden_ad').addClass('ignore');
            $('.form-register-data').submit(function(event){
                if($('#ids_hidden_ad').val() == ''){
                    event.preventDefault();
                    if($('.msg-error-images').length == 0){
                        if($('.rbox').length == 0){
                            $('#register-user-image').before('<div class="rbox">Debe ingresar al menos una imagen.</div>');
                        }
                    }else{
                        $('.admin-image-content').fadeOut('slow').fadeIn('slow');
                    }
                    
                    return false;
                }else{
                    
                    return true;
                }
            });    
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});


/**
* Muestra la previsualizacion del aviso impreso
* @submodule preview-impress
* @main usuario
*/
yOSON.AppCore.addModule('preview-impress', function(Sb){ 
    var dom = {
        announcementTitle             :$("#announcement_title"),
        announcementTitleImpress      :$("#announcement_title_impress"),
        noPaste                       :$("#announcement_title_impress, #letter_free"),
        letterFree                    :$("#letter_free"),
        price                         :$("#price"),
        ubicationDepartement          :$("#ubication_departement"),
        ubicationDistrict             :$("#ubication_district"),
        ubicationProvince            :$('#ubication_province'),
        fono                          :$("#fono1"),
        impTit                        :$("#imp_tit"),
        impDesLinea1                  :$("#imp_des_linea1"),
        impDesLinea2                  :$("#imp_des_linea2"),
        impCity                       :$("#imp_city"),
        impCurrency                   :$("#imp_currency"),
        impMoney                      :$("#imp_money"),
        impTitHidden                  :$("#imp_tit_hidden"),
        impDesLinea1Hidden            :$("#impreso_des_linea1_hidden"),
        textImpValid                  :$("#text_imp_valid"),
        backPrint                     :$("#backPrint"),
        inputMonedaCheck              :$("input[name=currency]:checked"),
        inputMoneda                   :$("input[name=currency]"),
        impress                       :$(".impreso")
    };
    return {
        init: function(){
            /* Se le coloca la clase impreso para que el keyup actualize la vista previa */
            
            /*se desabilita la propiedad de copiar*/
            dom.noPaste.attr('onpaste','return false;');
            /*actualiza precio*/
            dom.price.bind('keyup',function(){
                
                dom.impMoney.html($(this).val());
            });
            /*actualiza tipo de moneda*/
            dom.inputMoneda.bind('click',function(){
                var typeMoneySimbol = ($(this).val() == 1)?'S/.':'US$';
                dom.impCurrency.html(typeMoneySimbol);
            });
        
            /* habilita los departamentos de lima y se selecciona el distrito */
            dom.ubicationDepartement.bind('change',function(){
                if($(this).val() == 15){    
                    dom.ubicationDistrict.parent().parent().slideDown('slow');
                    dom.ubicationDistrict.bind('change',function(){
                        dom.impCity.html($("option:selected",this).text());
                    });
                }else if($(this).val() == ""){
                        dom.impCity.html('');
                }else{
                    dom.ubicationDistrict.parent().parent().slideUp('slow');
                    dom.ubicationProvince.bind('change',function(){
                        //dom.ubicationDistrict.find('option').val();
                        //window.ubicn = dom.ubicationDistrict;
                        $("#ubication_district option:last-child").attr('selected','selected');
                        dom.impCity.html($("option:selected",this).text());
                    });
                }
                
            });
            
            dom.ubicationDistrict.trigger('change');
            /*no permite los carecteres especiales*/
            dom.announcementTitle.keyup(function(e){
                var regExp = /[\'\<\>\"]/gi;
                if(regExp.test(this.value)){
                  this.value=this.value.replace(regExp,'');   
                } 
            });
            for(var nombreDom in dom){
               if(nombreDom == 'announcementTitleImpress' || nombreDom == 'letterFree' ){
                   dom[nombreDom].keyup(function(e){
                        var regExp = /[\'\*\<\>\"\%\´\{\}\[\]]/gi;
                        if(regExp.test(this.value)){
                          this.value=this.value.replace(regExp,'');   
                        } 
                   });
               }
            }
            /*titulo del aviso*/
            dom.announcementTitleImpress.bind('keyup',function(){
               dom.impTit.html($(this).val()); 
            });
            /*palabras libres*/
            dom.letterFree.bind('keyup',function(){
               dom.impDesLinea1.html($(this).val()); 
               if(dom.textImpValid.length != 0){
                    if(dom.textImpValid.val().length < dom.letterFree.val().length){
                        dom.letterFree.attr("maxlength",100);
                    }
                }
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});


/**
* Mostrar formulario de olvidado de contraseña
* @submodule moduleName
* @main usuario
*/
yOSON.AppCore.addModule('show-forget-pass', function(Sb){ 
    var dom = {recoverPassw :$('.recover-passw')};
    return {
        init: function(){
            dom.recoverPassw.fancybox({
                    'transitionIn'   : 'elastic',
                    'transitionOut' : 'elastic',
                    'titleShow'    : false
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Mostrar registro mediante un trigger
* @submodule show-register-trigger
* @main usuario
*/
yOSON.AppCore.addModule('show-register-trigger', function(Sb){ 
    var dom = {showRegister:$('#show-register'),signIn:$('#sign-in')};
    return {
        init: function(){
            dom.showRegister.bind('click',function(){
                dom.signIn.trigger('click'); 
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});

/**
* Mostrar login mediante un trigger
* @submodule show-login-trigger
* @main usuario
*/
yOSON.AppCore.addModule('show-login-trigger', function(Sb){ 
    var dom = {showLogIn:$('#show-log-in'),logIn:$('#log-in')};
    return {
        init: function(){
            dom.showLogIn.bind('click',function(){
                if(dom.logIn == 1){
                    dom.logIn.trigger('click');
                }
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});



/**
* Reportar aviso
* @submodule report-ad
* @main usuario
*/
yOSON.AppCore.addModule('report-ad', function(Sb){ 
    var dom = {reportAdLink :$('.report-ad-link'),logIn : $('#log-in'),btnClose:$('.btn-close'),reportAdForm : $('#report-ad-form')};
    return {
        init: function(){
            dom.reportAdLink.bind('click',function(){         
               if(dom.logIn.length == 1){
                dom.logIn.trigger('click');
                }else if(dom.logIn.length == 0){
                    $.fancybox({
                        'transitionIn'   : 'fade',
                        'transitionOut' : 'fade',
                        'titleShow'    : false,
                        'type' : 'inline',
                        'content': '#report-ad',
                        'onComplete' : function(){
                            yOSON.AppCore.runModule('validate', {form:'#report-ad-form'});
                        },
                        'onClosed': function(){
                             dom.reportAdForm.find('select').val('');
                             dom.reportAdForm.find('textarea').val('');
                        }
                    });
                } 
            });
            
            dom.btnClose.bind('click',function(){
                $.fancybox.close();
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Enviar aviso a un amigo
* @submodule send-ad-friend
* @main usuario
*/
yOSON.AppCore.addModule('send-ad-friend', function(Sb){ 
    var dom = {sendFriendLink :$('.send-friend-link'),sendFriendForm : $('#send-friend-form'),btnSendForm: $('#btn-send-form') , btnClose:$('.btn-close')};
    return {
        init: function(){
            dom.sendFriendLink.fancybox({
                'transitionIn'   : 'elastic',
                'transitionOut' : 'elastic',
                'titleShow'    : false,
                'onComplete' : function(){
                    yOSON.AppCore.runModule('validate', {form:'#send-friend-form-ad'});
                }
            });
            dom.btnClose.bind('click',function(){ 
                $.fancybox.close();
            });

        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);

/**
* Añadir a mis favoritos
* @submodule add-favorites
* @main usuario
*/
yOSON.AppCore.addModule('add-favorites', function(Sb){ 
    var dom = {bookMarkAdd:$("a.book-mark-add")},
        fnFancybox = function(msg){
            $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+msg+'</p>');
        };
    return {
        init: function(){
            // add a "rel" attrib if Opera 7+
            if(window.opera) {
                if (dom.bookMarkAdd.attr("rel") != ""){ // don't overwrite the rel attrib if already set
                    dom.bookMarkAdd.attr("rel","sidebar");
                }
            }

            dom.bookMarkAdd.click(function(event){
                event.preventDefault(); // prevent the anchor tag from sending the user off to the link
                
                var url = $(this).attr('href'),
                    title = $(this).attr('title'),
                    messagesError = {
                        msg0:'Porfavor presione CTRL+D (o Comando+D para Mac) para añadir esta página.',
                        msg1:'Porfavor presione CTRL+B para añadir esta página.',
                        msg2:'Tu explorador no puede añadir esta pagina, porfavor añadela manualmente.'
                    };

                if (!url) {url = window.location}
                if (!title) {title = document.title}
                var browser=navigator.userAgent.toLowerCase();
                if (window.sidebar) { // Mozilla, Firefox, Netscape
                    window.sidebar.addPanel(title, url,"");
                } else if( window.external) { // IE or chrome
                    if (browser.indexOf('chrome')==-1){ // ie
                        window.external.AddFavorite( url, title); 
                    } else { // chrome
                        //alert('Porfavor presione CTRL+D (o Comando+D para Mac) para añadir esta página.');                      
                        fnFancybox(messagesError.msg0);
                    }
                }
                else if(window.opera && window.print) { // Opera - automatically adds to sidebar if rel=sidebar in the tag
                    return true;
                }
                else if (browser.indexOf('konqueror')!=-1) { // Konqueror
                    //alert('Porfavor presione CTRL+B para añadir esta página.');
                    fnFancybox(messagesError.msg1);
                }
                else if (browser.indexOf('safari')!=-1){ // safari
                    //alert('Porfavor presione CTRL+D (o Comando+D para Mac) para añadir esta página.');
                    fnFancybox(messagesError.msg0);
                } else {
                    //alert('Tu explorador no puede añadir esta pagina, porfavor añadela manualmente.')
                    fnFancybox(messagesError.msg2);
                }

            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Aviso-inactivos
* @submodule venta
* @main usuario
*/
yOSON.AppCore.addModule("ap-pagination-venta", function(Sb){
    return {
        init: function(oParams){
            
            var formSearch="#apFormSearch",
                btnNewAviso = "#btnNewAviso",
                btnSearchTitle = "#btnSearchTitle",
                btnRepublish = "#btnRepublish",
                cboMostrarAvisoDe = "#cboMostrarAvisoDe",
                cboFiltroPregunta = "#cboFiltroPregunta",
                linkOrder = ".linkOrder",
                hddOrder = "#hddOrder",
                txtTitle = "#txtTitle";
//                tablePagination="#tablePagination";
                
            var ApPagination = {
                init: function () {
                    ApPagination.afnPaginacion();
                    ApPagination.afnNewAViso();
                    ApPagination.afnMostrarAvisoDe();
                    ApPagination.afnFiltroPregunta();
                    ApPagination.afnTitle();
                    ApPagination.afnOrder();
                    ApPagination.afnRepublish();
                },
                afnPaginacion : function(){
                    $(".apPagination a").bind('click', function(event){
                        event.preventDefault();
                        var formValues = ApPagination._afnFormValues();
                        var $action = $(formSearch).attr("action")+formValues+'/page/'+$(this).attr("rel");
                        ApPagination._afnFormSubmit($action);
                        //$("#hpag").val($(this).attr("rel"));
                    });
                },
                afnNewAViso : function(){
                    $(btnNewAviso).bind('click', function(event){
                        event.preventDefault();
                        var $action = $(this).attr("action");
                        ApPagination._afnFormSubmit($action);
                    });
                },
                afnMostrarAvisoDe : function(){
                    $(cboMostrarAvisoDe).bind('change', function(){
                        var formValues = ApPagination._afnFormValues();
                        var $action = $(formSearch).attr("action")+formValues;//'/fechade/'+$(this).val();
                        ApPagination._afnFormSubmit($action);
                    });
                },
                afnFiltroPregunta : function(){
                    $(cboFiltroPregunta).bind('change', function(){
                        var formValues = ApPagination._afnFormValues();
                        var $action = $(formSearch).attr("action")+formValues;//'/fechade/'+$(this).val();
                        ApPagination._afnFormSubmit($action);
                    });
                },
                afnTitle : function(){
                    $(btnSearchTitle).bind('click', function(){
                        ApPagination._afnFormClear();
                        var formValues = ApPagination._afnFormValues();
//                        var textTitle = escape($(txtTitle).val());
                        var $action = $(formSearch).attr("action")+formValues;
                        ApPagination._afnFormSubmit($action);
                    });
                },
                afnOrder : function(){
                    $(linkOrder).bind('click', function(){
                        $(hddOrder).val($(this).attr("order"));
                        var formValues = ApPagination._afnFormValues();
                        var $action = $(formSearch).attr("action")+formValues;
                        ApPagination._afnFormSubmit($action);
                    });
                },
                afnRepublish : function(){
                    $(btnRepublish).bind('click', function(event){
                        event.preventDefault();
                        var $action = $(this).attr("href");
                       var $method = $(this).attr("method");
                        
                        ApPagination._afnFormSubmit($action, $method);
                    });
                },
                _afnFormClear : function(){
                    //$(cboMostrarAvisoDe).val('');
                    //$(txtTitle).val();
                    $(hddOrder).val('');
                },
                _afnFormValues : function(){
                    var $return = '',
                        mostrarAvisoDe = $(cboMostrarAvisoDe).val(),
                        filtroPregunta = $(cboFiltroPregunta).val(),
                        title = escape($(txtTitle).val()),
                        order = $(hddOrder).val();
                    $return = (mostrarAvisoDe!='0' &&  mostrarAvisoDe!=undefined)?('/fechade/'+mostrarAvisoDe):$return;
                    $return = (filtroPregunta!='0'  &&  filtroPregunta!=undefined)?('/pregunta/'+filtroPregunta):$return;
                    $return = (title!='' &&  title!='undefined')?($return+'/title/'+title):$return;
                    $return = (order!='' && order!='0' &&  order!=undefined)?($return+'/order/'+order):$return;
                    return $return;
                },
                _afnFormSubmit : function($action, $method){
                    $(formSearch).attr("action", $action);
                    if ($method != '') $(formSearch).attr("method", $method);
                    $(formSearch).submit();
                }
            }
            ApPagination.init();
        },
        /*Public method : Implementacion de alguna funcionalidad asociada a este modulo.*/
        other: function(){  },
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});




/**
* eliminar y desactivar para acciones
* @submodule ad-actions  
* @main usuario
*/
yOSON.AppCore.addModule('ad-actions-active', function(Sb){ 
    var dom = {
        adLink      : $('.ad-link-modal'),
        btnAceptar  : $('#message-ad .btn-kotear'),
        btnCancel   : $('#message-ad .btn-close'),
        btnOptions  : $('#message-ad .btn-options'),
        messageDiv  : $('#message-ad .message'),
        btnPaid     : $('.paid-btn'),
        typeActionMsg : $('#type-action')
    }; 
    return {
        init: function(){
                var urlProduct,titleAction;
                dom.adLink.bind('click',function(event){

                    event.preventDefault();
                    
                    urlProduct = $(this).attr('href');
                    titleAction = $(this).attr('title');
                    dom.typeActionMsg.html(titleAction);
                    
                    $.fancybox({
                            'content'  :  '#message-ad',
                            'type'     :  'inline',
                            'onClosed': function(){
                                if(dom.btnOptions.hasClass('done')){
                                    location.reload(true);
                                }else{}
                            }
                    });
                });

                dom.btnAceptar.bind('click',function(event){
                    event.preventDefault();
                    $.ajax({
                      url:  urlProduct,
                      success : function(value){
                        //if(value.code == 0){
                            dom.btnOptions.slideUp('down');
                            dom.messageDiv.html(value.msg);
                            dom.btnOptions.addClass('done');
                        //}
                      }
                    });

                });

                /* cerrar modal con cerrar */
                dom.btnCancel.bind('click',function(event){
                    event.preventDefault();
                    $.fancybox.close();
                });

                dom.btnPaid.bind('click',function(event){
                    event.preventDefault();
                    var urlType = $(this).attr('href');
                    $.ajax({
                        url : urlType,
                        success : function(value){
                            $.fancybox({
                                'content' : value,
                                'onComplete': function(){
                                    yOSON.AppCore.runModule('validate', {form:'#frm-seleccion-medio-pago'});
                                }
                            });
                        }
                    });
                });

        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Mostrar y constestar las preguntas recibidas
* @submodule show-questions 
* @main usuario
*/
yOSON.AppCore.addModule('show-questions', function(Sb){ 
    /*var dom = {
        showQuestionLink        : $('.show-question-link'),
        showRequestTextLink     : $('.show-request-text'),
        unansweredQuestions     : $('.unanswered-questions'),
        showRequestForm         : $('.show-request-form'),
        listResultProducts      : $('.list-result-products')
    }; */
    return {
        init: function(){

                $('.list-result-products').each(function(i,ul){
                    $('.show-question-link',ul).bind('click',function(){
                            if($('.unanswered-questions',ul).css("display")=="none"){
                                $('.arrow-uniq',ul).rotate({animateTo:+180});
                            }else{
                                $('.arrow-uniq',ul).rotate({animateTo:0});
                            }                            
                            $('.unanswered-questions',ul).slideToggle('down');
                    });
                    
                    $('.detail-content',ul).each(function(i,div){

                        $('.show-request-text',div).bind('click',function(){

                            if($('.show-request-form',div).css("display")=="none"){
                                $('.arrow-uniq-iner',div).rotate({animateTo:+180});
                            }else{
                                $('.arrow-uniq-iner',div).rotate({animateTo:0});
                            } 

                            $('.show-request-form',div).slideToggle('down');

                            /* Envio de ajax */

                            $('.btn-request-answer',div).bind('click',function(event){
                                event.preventDefault();
                                $that = $(this,div);
                                var href = $(this,div).attr('href'),
                                    comprador = $(this,div).attr('comprador'),
                                    idmensaje = $(this,div).attr('idmensaje'),
                                    comment = $('textarea',div).val();

                                $('.text-area-comment',div).validate({
                                    rules :{comment : {required :true}},
                                    messages: {comment : {required : 'Debe ingresar un comentario'}}
                                });
                                if($('.text-area-comment',div).valid()){
                                        $.ajax({
                                            url : href,
                                            type: 'post',
                                            data: 'comprador='+comprador+'&idmensaje='+idmensaje+'&comment='+comment,
                                            success : function(value){
                                                $('.show-request-text',div).slideUp('slow');
                                                $('.show-request-form',div).slideUp('slow');
                                                $that.parents('.unanswered-questions',div).after('<li class="unanswered-questions" style="display: list-item;"><div class="detail-content"><div class="answer-question"><div class="column-icon"><span class="icon-question-ok"></span></div><div class="column-text"><div>'+comment+'</div></div></div></div></li>');
                                            }
                                        });
                                }else{
                                    return false;
                                }
                                

                            });

                        });
                    }); 

                });


        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.rotate.js','libs/plugins/min/jqValidate.js']);



/**
* Hacer check a toda la lista
* @submodule all-checked-uncheked  
* @main usuario
*/
yOSON.AppCore.addModule('all-checked-uncheked', function(Sb){ 
    var dom = {
        checkParent     : $('.check-parent'),
        checkChildren   : $('.check-children'),
        listResultProducts  : $('.list-result-products')
    }; 
    return {
        init: function(){
            dom.checkParent.bind('click',function(){
                dom.listResultProducts.find(dom.checkChildren).attr('checked',this.checked);
            });

        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});


/**
* Mueve el scroll en un hash
* @submodule scroll-hash
* @main usuario
*/
yOSON.AppCore.addModule('scroll-hash', function(Sb){ 
    var dom = {questionSellerLink :$('#question-seller-scroll'),htmlDom:$('html'),windowDom:$(window)};
    return {
        init: function(){
           $('#question-seller-scroll').scrollWindow();
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});



/**
* Seguir un anuncio
* @submodule follow-ad
* @main usuario
*/
yOSON.AppCore.addModule('follow-ad', function(Sb){ 
    var dom = {followAdLink :$('#follow-ad-link'),logIn : $('#log-in')};
    return {
        init: function(){
           dom.followAdLink.bind('click',function(){
               var idRel = $(this).attr('rel'),
                   detailRel = $(this).attr('detail');
               if(dom.logIn.length == 1){
                dom.logIn.trigger('click');
                }else if(dom.logIn.length == 0){
                    if(detailRel == 'follow'){
                        var baseUrlFollow = yOSON.baseHost+'usuario/aviso/recibir-alerta';
                    }else{
                         baseUrlFollow = yOSON.baseHost+'usuario/aviso/cancelar-alerta';
                    }

                    $.ajax({
                        url:baseUrlFollow,
                        type : 'post',
                        data : 'id='+idRel,
                        success : function(value){
                            if(value.code == 0){
                               $.fancybox({
                                 'content' : '<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p>',
                                 'onClosed' : function(){
                                     location.reload(true);
                                 }
                               }); 
                               
                            }else{
                               $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p>');  
                            }
                            
                        }
                    });
                } 
            });
           
           
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);



/**
* Pregunta al vendedor
* @submodule question-seller
* @main usuario
*/
yOSON.AppCore.addModule('question-seller', function(Sb){ 
    var dom = {questionSellerLink :$('#question-seller-link'),logIn : $('#log-in'),btnCancel : $('.btn-close')};
    return {
        init: function(){
           dom.questionSellerLink.bind('click',function(event){
               event.preventDefault();
               $.fancybox({
                    'content'  :  '#question-seller-content',
                    'type'     :  'inline',
                    'onComplete': function(){
                        yOSON.AppCore.runModule('validate', {form:'#question-seller-form'});
                    },
                    'onClosed' : function(){
                        $('#change-question-msg').css('display','none');
                        $('#question-seller-form').show();
                        $('#question-seller-form')[0].reset();
                    }
               });
            });
            
            
            dom.btnCancel.bind('click',function(event){
                    event.preventDefault();
                    $.fancybox.close();
                });
           
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);

/**
* Cambiar Clave
* @submodule change-password
* @main usuario
*/
yOSON.AppCore.addModule('change-password', function(Sb){ 
    var dom = {
        changePasswordLink     : $('.change-password-link'),
        btnContinue : $('#change-password-form .btn-standar'),
        btnFinish : $('#change-password-form .btn-close'),
        changePasswordForm : $('#change-password-form'),
        changePassword : $('#change-password'),
        changePasswordMsg : $('#change-password-msg')
    }; 
    return {
        init: function(){
            dom.changePasswordLink.fancybox({
                'type': 'inline',
                'titleShow' : false,
                onComplete : function(){
                     $('#change-password-form')[0].reset();
                },
                onClosed :  function(){
                    dom.changePasswordMsg.html('').css('display','none');
                    dom.changePasswordForm.show();
                    $('#change-password-form')[0].reset();
                }
            });
            dom.btnFinish.bind('click',function(event){
                event.preventDefault();
                $.fancybox.close();
            });
            
            
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Solicitud de cambio de correo
* @submodule change-email 
* @main usuario
*/
yOSON.AppCore.addModule('change-email', function(Sb){ 
    var dom = {
        changeEmailLink     : $('.change-email-link'),
        btnContinue : $('#change-email-form .btn-standar'),
        btnFinish : $('#change-email-form .btn-close'),
        changeEmailForm : $('#change-email-form'),
        changeEmailMsg : $('#change-email-msg'),
        changeEmailMsgError : $('#change-email-msg-error')
    }; 
    return {
        init: function(){
            dom.changeEmailLink.fancybox({
                'titleShow'    : false,
                'type' : 'inline',
                'onClosed' :  function(){
                    dom.changeEmailMsg.html('').css('display','none');
                    dom.changeEmailForm.show();
                    dom.changeEmailMsgError.html('');
                    $('#change-email-form')[0].reset();
                } 
            });
            
            dom.btnFinish.bind('click',function(event){
                event.preventDefault();
                $.fancybox.close();
            });
            
            
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* Reenvio de confirmacion para aceptar la creacion del usaurio
* @submodule resend-confirmation
* @main usuario
*/
yOSON.AppCore.addModule('resend-confirmation', function(Sb){ 
    var dom = {resendEmailLink : $('#resend-email-link')};
    return {
        init: function(){
            dom.resendEmailLink.bind('click',function(){
                $.ajax({
                    url:yOSON.baseHost+'usuario/registro/enviar-correo', 
                    success : function(value){
                        $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p>'); 
                    }
                });
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);

/**
* Colocar botrder top en base a la cantidad de comentarios, en el caso que no haya coloca border-top si no no lo hace
* @submodule border-comment
* @main usuario
*/
yOSON.AppCore.addModule('border-comment', function(Sb){ 
    var dom = {textQuestiontoSeller:$('.text-question-to-seller'),maybeSesionStar : $('.maybe-sesion-star')};
    return {
            init: function(){
            // dar border superior
           var  numberChildren = dom.textQuestiontoSeller.find('.question-customer').length;
           if(numberChildren == 0){
              dom.maybeSesionStar.css('border-top','1px solid #ccc');
           }
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});


/**
* Toogle para poder mostrar datos de facturacion y boleta
* @submodule show-voucher
* @main usuario
*/
yOSON.AppCore.addModule('show-voucher', function(Sb){ 
    var dom = {voucher1:$('#voucher-1'),voucher2:$('#voucher-2'),voucherForm : $('#voucher-form')};
    return {
            init: function(){
                
            if(dom.voucher1.is(':checked')){
                dom.voucherForm.css('display','none');
            }
            
            dom.voucher1.bind('click',function(){
                    dom.voucherForm.slideUp('slow');
            });
            
            dom.voucher2.bind('click',function(){
                    dom.voucherForm.slideDown('slow');
            });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});


/**
* Validacion de documentos por diferentes tipos
* @submodule validate-document-dniRuc
* @main all-modules
*/
yOSON.AppCore.addModule('validate-document-dniRuc', function(Sb){ 
    var dom = {inputTipoDocumento:$('#document_type'),inputNroDoc:$('#document_number')};
     _cambioTipoDoc = function(){
        var _this=$(this);
        switch($.trim(_this.attr('value'))){
            case "2":
                dom.inputNroDoc.attr("maxlength", 8);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    dni: true,
                    required: true,
                    digits:true,
                    messages: {
                        required: 'Ingrese su Nro. de Documento.',
                        dni:'Ingrese su Nro. de Documento.',
                        digits:'Ingrese solo digitos.',
                        maxlength : 'Debe ingresar 8 numeros como máximo'
                    }
                });
            break;
            case "1":
                dom.inputNroDoc.attr("maxlength", 11);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    ruc: true,
                    required: true,                    
                    messages: {
                        required: 'Ingrese su RUC',
                        ruc:'Ingrese su RUC',
                        maxlength : 'Debe ingresar 11 numeros como máximo'
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
* Ocultar datos en el paso 3 para poder editar/editar-republicar/destacar
* @submodule display-none-dom
* @main usuario
*/
yOSON.AppCore.addModule('display-none-dom', function(Sb){ 
    var dom = {publicacionEstado:$('#publicacion_estado'),ubicationDepartement:$('#ubication_departement'),ubicationProvince:$('#ubication_province'),ubicationDistrict:$('#ubication_district')};
    return {
            init: function(){
             var varHidden =  dom.publicacionEstado.val();
              if(varHidden == 2 || varHidden == 3 || varHidden == 4 ){
                  if(dom.ubicationDepartement.val() != 15){
                      dom.ubicationDistrict.parents('.control-group').css('display','none');
                  }
                  if($('.ybox').length != 0){
                    $('.ybox').css('display','none');
                  }
              }
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});

/**
* Ocultar datos en el paso 3 para poder editar/editar-republicar/destacar
* @submodule display-none-dom
* @main usuario
*/
yOSON.AppCore.addModule('data-ubigeo-select', function(Sb){ 
    var dom = {ubicationDepartement:$('#ubication_departement'),ubicationProvince:$('#ubication_province'),ubicationDistrict:$('#ubication_district')};
    return {
            init: function(){
                if(dom.ubicationDepartement.val() != 15){
                    dom.ubicationDistrict.parents('.control-group').css('display','none');
                }else{
                    dom.ubicationDistrict.parents('.control-group').css('display','block');
                 }
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});


/**
* Reenviar email, cuando aparesca la pagina de error al inciar sesion
* @submodule resend-mail-error
* @main usuario
*/
yOSON.AppCore.addModule('resend-mail-error', function(Sb){ 
    var dom = {resendEmailLink : $('#resend-email-link')};
    return {
            init: function(){
                dom.resendEmailLink.bind('click',function(event){
                    event.preventDefault();
                    var urlAjax = $(this).attr('rel');
                    $.ajax({
                        url : urlAjax,
                        beforeSend : function(){
                            $.fancybox.showActivity();
                        },
                        success : function(value){
                            $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p>'); 
                            
                        }
                    });
                    
                });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);
