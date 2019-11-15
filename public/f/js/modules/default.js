/**
* Modulo principal de default
* @class default
* @module yOSON
*/



/*-----------------------------------------------------------------------------------------------
 * Galeria 3D para la pagina de inicio
 * @submodule gallery3D
 * @main default
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('gallery3D', function(Sb){
    var dom = {
        carousel:$("#carousel"),
        btnLeft:$("#carousel-left"),
        btnRight: $("#carousel-right"),
        btns:$("#carousel-left,#carousel-right,.tracker-individual-blip"),
        btnMedia:$("#btn-media")
    },
    gallery3D = function(){
         var optionsCarousel = {
            trackerSummation: false,
            stopOnHover: false,
            pauseOnHover: false,
            largeFeatureWidth: 400,
            largeFeatureHeight:	300,
            smallFeatureWidth: 180,
            smallFeatureHeight: 180,
            movedToCenter: function(obj){
                    $(obj).addClass('in-center');
                    $(obj).removeClass('in-thumb');
            },
            leavingCenter: function(obj){
                    $(obj).removeClass('in-center');
                    $(obj).addClass('in-thumb');
            }
        };
        dom.carousel.featureCarousel(optionsCarousel);

        dom.btns.live('click',function(){
            //dom.carousel.next();
            var btnmedia=$("#btn-media");
            btnmedia.animate({backgroundPosition:'-1px -1px'});
            btnmedia.removeClass('btn-media-play');
            btnmedia.addClass('btn-media-pause');
        });        
        dom.btnMedia.live('click',function(){
            var $this = $(this);
            if($this.hasClass('btn-media-pause')){
                $this.animate({backgroundPosition:'-1px -20px'});
                $this.removeClass('btn-media-pause');
                $this.addClass('btn-media-play');
                dom.carousel.pause();
            }else if($this.hasClass('btn-media-play')){
                $this.animate({backgroundPosition:'-1px -1px'});
                $this.removeClass('btn-media-play');
                $this.addClass('btn-media-pause');
                dom.carousel.start();
            }  
        });    
        
    };
    return {
    /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/               
        init: function(oParams){

                    gallery3D();
           
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
}, ['libs/plugins/min/jquery.featureCarousel.js']);



/**
* Mostrar mas categorias
* @submodule showMoreCategories
* @main default
*/
yOSON.AppCore.addModule('showMoreCategories', function(Sb){
    var dom = {contentMenu:$('.contentShowMore'),btnShowMore:$('.contentShowMore .show-all')},
    showMoreCategories = function(){
        
    var lista=$(".list-menu");
    $.each(lista,function(index,value){
        $(value).data("h", $(value).height());
        $(value).find("li").hide();
        $(value).find("li:lt(5)").show();

        var height=$(value).height();
        $(value).data("h2", height);
        $(value).css('height',height);
        $(value).find("li").show();
    });

    $('.show-all').bind('click',function(){

        var pt=null;
        if($(this).data("instp")){
          pt=$(this).data("instp");
        }else{
          pt=$(".list-menu",$(this).parent()); 
          $(this).data("instp",pt);

        }
        if(!$(this).hasClass("desplegable")){
            pt.animate({
                height:pt.data("h")
            },1000, 'easeOutBounce');
            $(this).addClass("desplegable");
        }else{

             pt.animate({
                height:pt.data("h2")
            },1000, 'easeOutBounce');
            $(this).removeClass("desplegable");
        }    
        return false;
    });
       
        
    };
    return {
        init: function(){
            showMoreCategories();
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});

/**
* Enviar lista de aviso por email
* @submodule send-list-ad-email
* @main default
*/
yOSON.AppCore.addModule('send-list-ad-email', function(Sb){ 
    var dom = {sendListAdEmailLink:$('.send-list-ad-email-link'),btnClose:$('.btn-close')};
    return {
        init: function(){
          dom.sendListAdEmailLink.bind('click',function(){
              
                var $avisos = $('.item-checked .chk-item:checked');
                
                var $extra = $('.send-friend').find('.extra');
                var $titulos = $avisos.parent().find('h3');
                var $lista = $('.send-friend').find('.avisos-seleccionados');
                var listaAvisos = '';
                $titulos.each(function(){
                  var $this = $(this);
                  listaAvisos += '<li>'+ $this.text() +'</li>';
                });
                $lista.html( listaAvisos );
                $extra.html('');
                $extra.append( $avisos.clone() );

                if( $avisos.length > 0 ){
                    setTimeout(function(){
                        $.fancybox({
                             'transitionIn' : 'none',
                             'transitionOut' : 'none',
                             'type' : 'inline',
                             'content' :   '#enviar-mail',
                             'onComplete' : function(){
                                 yOSON.AppCore.runModule('validate', {form:'#send-friend-form-search'});
                             }
                         });
                        },200);
                                       
                    
                }else{
                    if( $('.description-product input:checked').length <= 0 ){
                      $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>Debes seleccionar al menos un aviso de los resultados.</p></div>');
                     return false;
                    }
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
* Valida avisos y manda lista para el impreso
* @submodule impress-ad-list
* @main default
*/
yOSON.AppCore.addModule('impress-ad-list', function(Sb){ 
    var dom = {printListLink:$('.print-list'),btnClose:$('.btn-close')};
    return {
        init: function(){
           var $avisos;
          dom.printListLink.attr("lhref",dom.printListLink.attr("href")).mouseenter(function(){

              $avisos = $('.item-checked .chk-item:checked');
              $this = $(this);
              
              var newUrl = $this.attr("lhref");
              
              $avisos.each(function(i){
                newUrl += (i>0?",":"")+$(this).val();
              });
              
              if( $avisos.length > 0 ){
                $(this).attr("href", newUrl);
              }
              
          }).bind('click',function(){
              if( $avisos.length <= 0 ){
                $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>Debes seleccionar al menos un aviso de los resultados</p></div>');
               return false;
              }
          }); 
          
          /*dom.printListLink.attr("lhref",dom.printListLink.attr("href"));
          
          dom.printListLink.bind({
              mouseenter:function(){},
              click: function(){}
              
          });*/
            

          dom.btnClose.bind('click',function(){
              $.fancybox.close();
          });

        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jquery.fancybox.js']);


/**
* PlaceHolder
* @submodule placeholder
* @main default
*/
yOSON.AppCore.addModule('placeholder', function(Sb){ 
    var dom = {withPlaceHolder:$('input[placeholder], textarea[placeholder]')};
    placeholder = function(){
        dom.withPlaceHolder.placeholder();    
    };
    return {
        init: function(){
            placeholder();
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});

/**
* Agregar estilo medio para mostrar la galeria del productos
* @submodule add-class-middle
* @main default
*/
yOSON.AppCore.addModule('add-class-middle', function(Sb){ 
    var dom = {squareResultMiddle:$('ul.square-result-products li:nth-child(3n+2)')};
    return {
            init: function(){
            dom.squareResultMiddle.addClass('middle');
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
});


















