/************************************************************************************************\
 * index.js - Modulos                                                                           *
 * Module    : default                                                                          *
 * Controller: index                                                                            *
 *                                                                                              *
 * Company: Perucomsultores.pe                                                                  *
 * Authors: Jaime Rodriguez                                                                     *
 *          Jan Sanchez A.                                                                      *
 *          Andres Muñoz                                                                        *
 *          Erik Flores                                                                         *
 *          Brayan Borda                                                                        *
 ************************************************************************************************/

/*-----------------------------------------------------------------------------------------------
 * @Module: Modulo-Template 
 * @Description: Descripcion de este modulo aca.
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("some-module", function(Sb){ 
    var sParam  = 'String';            /*Private Property : Pra uso solo dentro del modulo*/
    var fPrivate = function(){/*...*/};/*Private Method   : Para uso solo dentro del modulo*/
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){ },
        /*Public method : Implementacion de alguna funcionalidad asociada a este modulo.*/
        other: function(){  },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});


/*-------------------------------------------------------------------------------------------------------------
 * @Module: add-product
 * @Description: Adiciona un producto al carrito
 *//*---------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("add-product", function(Sb){ 
    var staturl=yOSON.baseHost;/* Base de la url */   
    var url,data,id,extra,cant; /* Parametros post*/ 
    return {    
        init: function(oParams){

           function ajaxPost(url,data){
                $.ajax({
                    'url':url+"carrito/additem",
                    'type':'get',
                    'data': data,
                    'dataType' : 'jsonp',
                    'success' : function(response){
                        if(response.success) {
                            location.href=staturl+"carrito/paso1";
                        }else {alert(response.errormessage);}                       
                    }
                });
           };
           //#add-item-cart
           $(".add-cart, .item-add .btn-peruplaza").click(function(event){/*, .description-shop-middle .btn-peruplaza*/

               if($(this).attr('class').indexOf('disabled')!=-1){
                   $(this).css({'cursor':'default', 'text-decoration':'none'});
                   return false;
               }
               
               event.preventDefault();
               id=$(this).attr("rel");  
               var inp = $('.vnumeric');
               cant=inp.exist()?( ($.trim(inp.val())!='')?inp.val():0 ):1; 
               var idPaper = ($('.gitf-check input').attr('checked'))?0:$('.item-title input:checked').val();

               data={'idProduct':id, cantidad: cant, idExtraProduct:idPaper};

               //ajaxPost(staturl, data);
               Console.log('889:'+document.domain);
               ajaxPost(yOSON.baseHost, data);
           });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});


/*-----------------------------------------------------------------------------------------------
 * @Module: num-items
 * @Description: Numero de items del carrito de compras
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("num-items", function(Sb){ 
    
    var sParam  = 'String';            /*Private Property : Pra uso solo dentro del modulo*/
    var fPrivate = function(){/*...*/};/*Private Method   : Para uso solo dentro del modulo*/
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            window.pet = $.ajax({
                url      :yOSON.baseHost+"carrito/nitems",
                type     :'get',
                data     : {a:""},
                dataType : 'jsonp',
                success  : function(response){ /*alert(response);*/$('.btn-cart a .cart-items').html(response.nitems);}
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };

});

/*-------------------------------------------------------------------------------------------------
 * @Module: show-popup
 * @Description: modulo para mostrar popup.
 *//*---------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("show-popup", function(Sb){ 
    /*
     * @private
     */
    var dom = {btnVisa:$('a.verified-by-visa-ico, .radio .verified')};  /*[ .radio .verified ] se encuebtra eb carrito paso3*/
    Console.log(dom.btnVisa);
    $('.radio .verified').css('cursor', 'pointer');  /*solo para "verified" del paso 3*/
    var popup = function(pagina, ancho, alto, barras){
        izquierda = (screen.width) ? (screen.width - ancho) / 2 : 100;
        arriba = (screen.height) ? (screen.height - alto) / 2 : 100;
        opciones ='toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars='+barras+',resizable=0,width='+ancho+',height='+alto+',left='+izquierda+',top='+arriba+'';
        window.open(pagina,"nombre"+"-"+ancho+"x"+alto,opciones);
    };
    /*
     * @public
     */
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(){
            dom.btnVisa.bind('click', function(){popup('http://www.visanet.com.pe/visa.htm', 606, 405, 'no');});            
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };

});

/*-------------------------------------------------------------------------------------------------------
 * @Module     : search-autocomplete
 * @Description: autocomplete de palabras al escribir, el cual se mostrara en todas la paginas
 *//*---------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('search-autocomplete', function(Sb){
    var defaults = {
        monosilabos : ['de','el','la','los','un','una','tres','las','unos'],
        selector : '#search-keyword',
        /*ajaxUrl:baseDir+'search.php',*/
        limitRows: 10,
        arrayName: 'products',
        erase : 'inputerase',
        eraseText: '',
        SelectorAuto:'ul.ui-autocomplete li a',
        SelectorAuto2:'ul.ui-autocomplete',
        SelectorImprovised:'.header',
        SelectorMainMenu:'ul#main-menu li a',
        length: 2,
        multipleWords : 0,
        requests:[],
        noresults : {"msg" : [{"id":"0","name":"No se encontraron resultados para su busqueda.","value":"No se encontraron resultados para su busqueda.","url":""}]},
        nofound : false
    },
    st = $.extend(defaults, st),
    dom = {},
    catchDom = function(){dom.inputSearch = $(st.selector);},
    bindEvents = function(){
        dom.inputSearch.bind("keyup", function(event){
            //if(/[a-zA-Z0-9áéíóúñÁÉÍÓÚÑ\&\'\"]/g.test(dom.inputSearch.val()))return false;([a-z-]+)\/([a-zA-Z0-9-áéíóúñÁÉÍÓÚÑ\&\'\"\s]+)
            dom.inputSearch.val(dom.inputSearch.val().replace(/([^\*\-#@\sa-zA-Z0-9-áéíóúñÁÉÍÓÚÑ\&\'\"])/g,''));//([^\*\-#@\s0-9])
            if(dom.inputSearch.val()==''){$('a.'+st.erase).fadeOut();}//return false;
        }),
        dom.inputSearch.bind("keydown", function(event){
            dom.inputSearch.val(dom.inputSearch.val().replace(/([^\*\-#@\sa-zA-Z0-9-áéíóúñÁÉÍÓÚÑ\&\'\"])/g,''));
            if(event.keyCode===$.ui.keyCode.TAB && $(this).data("autocomplete").menu.active){event.preventDefault();}
        }).autocomplete({
            delay: 400,
            source: function(request, response){
                var elem=this.element, eTerm='';
                if(st.multipleWords==0){eTerm = request.term.toLowerCase();}
                else{eTerm = extractLast(request.term.toLowerCase());}
                if(eTerm!='' && $.inArray($.trim(dom.inputSearch.val()),st.monosilabos)==-1){
                    $.ajax({
                        url: yOSON.baseHost+ 'buscar/autocompletar/'+eTerm+'/'+$('#search-category option:selected').val(),
                        dataType: 'jsonp',
                        success: function(data){ 
                            
                            $(st.SelectorImprovised).css('zIndex',85);
                            var state=0, inp=$('#search-keyword').val(), sel=$('#search-category option:selected').val();
                            var arr = $.grep(data[st.arrayName], function(item){state=1;return {id:item.id, label:item.name, value:item.name, url:item.url}});
                            var length = data[st.arrayName].length;
                            if(length>=10)arr.push({id:'ver_mas', label:'ver mas...', value:'ver mas...', url:yOSON.baseHost+'buscar/'+sel+'/'+inp});
                            response(arr);
                            if (state==0 && st.nofound) {
                                response(                      
                                    $.grep(st.noresults.msg, function(item){state=0;return {id:item.id, label:item.name, value:item.name, url:item.url}})
                                );
                            }
                            
                        },
                        beforeSend: function(xhr){ },
                        complete: function(event){

                            if(dom.inputSearch.parent().find('a.'+st.erase).attr('rel')==undefined){
                                dom.inputSearch.after($('<a title="'+st.eraseText+'" class="'+st.erase+'" style="display:none;" rel="'+elem[0].id+'" ></a>'));
                                $('a.'+st.erase).fadeIn();
                                $(st.SelectorAuto2).css('zIndex', 9999);                      
                                $('a.'+st.erase).bind("click", function(event){
                                    dom.inputSearch.val('').focus();$(this).fadeOut();
                                }).autocomplete('close');						
                                $(st.SelectorMainMenu).bind("mouseover", function(event){
                                    $('#header').css('zIndex', 50);$(st.SelectorAuto2).fadeOut();								
                                }).autocomplete('close');
                            }else{
                                $('a.'+st.erase).fadeIn();$(st.SelectorAuto2).css('zIndex', 9999);
                            }
                        }
                    });                
                }
            },
            focus: function() {return false;},
                minLength: st.length,
            select: function(event, ui){   
                if(st.multipleWords==0){
                    if(ui.item.url!=""){location.href=ui.item.url;$(this).val(ui.item.value);}
                    else{ /*acciones de cuando no se encuentre nada*/ }

                }else if(st.multipleWords==1){
                    var result2=dom.inputSearch.val().search(ui.item.value);
                    if(result2!=0){if(result2!=-1){return false;}}
                    else{return false;}        
                    var terms = split(this.value);        
                    terms.pop();terms.push(ui.item.value);terms.push( "" );
                    this.value = terms.join(", ");
                    return false;        
                }      
            },
            open: function(event){			
                var texto=this.value.toLowerCase();
                $(st.SelectorAuto).each(function(){
                    result=$(this).html().search(new RegExp(texto, "i"));
                    if(result>0){
                        prev=$(this).html();
                        $(this).html($(this).html().replace(texto,'<strong>'+texto+'</strong>'));
                        if(prev==$(this).html()){
                            $(this).html($(this).html().replace(texto.capitalize(),'<strong>'+texto.capitalize()+'</strong>'));
                        }
                    } else {
                        $(this).html($(this).html().replace(texto.capitalize(),'<strong>'+texto.capitalize()+'</strong>'));
                    }
                });
            },
            close: function(){$('#header').css('zIndex', 50);}
        });    

    },
    selectorFunctions = function(){ },
    split = function(val){return val.split( /,\s*/ );},
    extractLast = function(term){return split(term).pop();},    
    load = function(){       
        $(function() { 
            catchDom();bindEvents();selectorFunctions();
            /*sb.browserDetect(); se ejecuta para obtener las variables del navegador actual como: name, version, valid
            startList();*/
        });       
    },
    
    /*---------------------------------------------------------------------
     * Redireccion para formulario de busqueda.
     *//*-----------------------------------------------------------------*/
    searchForm = function(){
        $('.form-search').bind('submit', function(e){
            e.preventDefault();
            var inp = $.trim($('#search-keyword').val());
            var sel = $('#search-category option:selected').val();
            var isNum = $.isNumeric(inp);
            var isMon = ($.inArray(inp,['de','el','la','los','un','una','tres','las','unos'])>-1);
            if(inp.length>=2 && !(isNum || isMon)){
                $('#search-keyword').css('border','1px solid #CCC');
                window.location.href = yOSON.baseHost+'buscar/'+sel+'/'+inp.replace(/[*%$#"']/,'|');

            }else{ //$('#search-keyword').css('border','1px solid #E52A00');
                $('.ui-autocomplete').css('display','none'); /*Cerramos el autocomplete*/
                $('#search-keyword').focus();
                $('.messages-bar .w1000').slideDown(400, function(){
                    var that=this;
                    var msj = (isNum||isMon)?'Ingresa la palabra o nombre de producto para realizar una búsqueda':'Ingresa la palabra o nombre de producto para realizar una búsqueda';
                    $('div',this).html(msj);
                    $(this).find('a').unbind('click').bind('click', function(){$(that).slideUp(400);});/*boton cerrar*/
                });
            }
            
        });
    },
    
    init = function(){load();searchForm();},
    destroy = function(args){ };
    return {init : init, destroy : destroy}
},['libs/plugins/'+yOSON.min+'uiAutoComplete.js']);


/*-----------------------------------------------------------------------------------------------------------
 * @Module     : main-menu
 * @Description: menu principal se mostrará en todas las paginas
 *//*-------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('main-menu', function(Sb){
    
    //alert(yOSON.module+'-'+yOSON.controller+'-'+yOSON.action);
    var isINDEX=(yOSON.module=='yMvY0OPgoQ=='&&yOSON.controller=='zdTW1OY='&&yOSON.action=='zdTW1OY='); //Si estamos en la pagina pricipal
    
    init = function(){
        var menu=$(".nav-menu"),h,h2;
         $.each(menu, function(index, value) { 
            h=$(menu[index]).height(); 
            var submenu=$("ul",menu[index]);
            $.each(submenu,function(index,value){
                h2=$(submenu[index]).height();
                if(h>h2){
                    $(submenu[index]).css("height",h);
                }
            });
        });
        if(isINDEX){$('.search-bar .float-menu').remove();}
        else{
            if($(".float-menu").length){
                var nav=$(".nav-bar .top-nav-categories"),nmenu=$(".float-menu");
                nav.mouseenter(function(){ 
                    nmenu.css("visibility","visible");
                    nav.css("background","#2C2C2C");
                }).mouseleave(function(){ 
                    nav.css("background","none");
                    nmenu.css("visibility","hidden");
                });
                nmenu.mouseenter(function(){ 
                    nav.trigger('mouseenter');
                }).mouseleave(function(){ 
                    nav.trigger('mouseleave');
                });
            }
            if($(".menu-store").length){ 
                var nav2=$(".menu-store"),nmenu2=$(".nav-store");
                nav2.mouseenter(function(){ 
                    nav2.addClass("store-hover");
                    nmenu2.css("visibility","visible");
                }).mouseleave(function(){ 
                    nav2.removeClass("store-hover");
                    nmenu2.css("visibility","hidden");
                });
                nmenu2.mouseenter(function(){ 
                    nav2.trigger('mouseenter');
                }).mouseleave(function(){ 
                    nav2.trigger('mouseleave');
                });
            }
        }
    },
    destroy = function(args){ };

    return {init:init, destroy:destroy}
});


/*-------------------------------------------------------------------------------------------------------
 * @Module     : tiendas-destacadas
 * @Description: Efecto mostral ocultar despelgable de descripcion de tiendas destacadas
 *//*---------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('tiendas-destacadas', function(Sb){
    var tEnter = null;
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            /*$('.featured-stores .f-store').bind('mouseenter', function(){$(this).css('cursor','pointer');});*/
            $('.featured-stores .f-store').bind('click', function(){window.location.href=$('h2 a', this).attr('href');});
            
            $('.featured-stores .f-store').mouseenter(function(){
                console.log("Hola");
                $(this).data('TIMER', setTimeout(function(){
                    $('.first-content', this).slideUp(300);
                    $('.second-content', this).slideDown(300);
                }.bnd(this), 400));
            });
            
            $('.featured-stores .f-store').mouseleave(function(){
                clearTimeout($(this).data('TIMER'));
                $('.first-content', this).slideDown(300);
                $('.second-content', this).slideUp(300);
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
});


/*--------------------------------------------------------------------------------------------------------
 * @Module     : modal-images
 * @Description: menu principal se mostrará en todas las paginas
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('modal-images', function(Sb){
    var imgDe = $('.detail-image .tac img');
    var zoomH = $('.detail-image .tac .hidden-zoom');
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){ /*alert('init!!!');  */
            Console.log('520: init - .carrusel-items');
            Console.log($(".carrusel-items"));
            $(".carrusel-items").colorbox({rel:'carrusel-items'});
            //zoomH.bind('click',function(){ $('.carrusel-item').trigger('click'); });
            imgDe.mouseenter(function(){zoomH.css('visibility','visible')});
            zoomH.mouseleave(function(){zoomH.css('visibility','hidden')})
            $('.carrusel-item').bind('click', function(e){e.preventDefault();$('.carrusel-items:first').trigger('click');});
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqColorBox.js', 'plugins/jqColorBox.css']);


/*--------------------------------------------------------------------------------------------------------
 * @Module     : paper-gift
 * @Description: Papel de regalo
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('paper-gift', function(Sb){
    
    var paperContent   = $('.paper-gift .paper-gift-content');
    var paperCloseOpen = $('.paper-gift .gitf-toggle a');
    var paperSelected  = $('.paper-select img, .gitf-image img');
    var paperItem      = '.paper-item';
    var paperItemCheck = '.item-title input[type="checkbox"], .item-img img';
    var paperItemImg   = function(context){return $('.item-img img',context);};
    var chkNotPaper    = $('.paper-gift .gitf-check input[type=checkbox]');
    var paperCloseOpenContent = $('.paper-gift .gitf-toggle');
    //var dataPapers     = {a:'a'};
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        dataPapers: {},  /*Guardara la data de todos los papers.*/
        
        init: function(oParams){
            /*Click en el link "ver mas papeles"*/
            paperCloseOpen.click(function(e, fnc){  
                var that = this;
                if($("#toogle").hasClass("toggle-down")){
                    $("#toogle").removeClass("toggle-down").addClass("toggle-up");
                } else {
                    $("#toogle").removeClass("toggle-up").addClass("toggle-down");
                }
                paperContent.slideToggle(500,function(){
                    $(that).html( ($.trim($(that).text())=='Cerrar')?'Ver m&aacute;s papeles':'Cerrar' );
                    if(fnc)fnc();
                });
            });
            /*Click en un papel de regalo para seleccionarlo.*/
            $(paperItemCheck).live('click', function(){ 
                var thisPaperItem = $(this).parent().parent();
                $(paperItem).css('background', '#F5F5FB');
                paperSelected.attr('src', paperItemImg(thisPaperItem).attr('src') );
                $(paperItemCheck).attr('checked', false);                   /*all checked item false*/
                $('.item-title input[type="checkbox"]', thisPaperItem).attr('checked', true); /*Checked en este item seleccionado*/
                $('.paper-select span:eq(0)').html( $('span:eq(0)', thisPaperItem).html() ); /*Actualizar titulo*/
                $('.paper-select span:eq(1)').html( $('span:eq(1)', thisPaperItem).html() ); /*Actualizar precio*/
                thisPaperItem.css('background', '#ACECF7');  /*Item selecionado*/
            });
            /*Llenando de data los papeles existentes y posibles */
            /*$.ajax({    
                type:"POST", url:yOSON.baseHost+'default/aviso/get-papeles-regalo', dataType:'json',
                success:function(data){this.dataPapers=data;$('#gift-reason').attr('readonly',false);}.bnd(this)
            });*/
            /*Asociamos funcionalidad al selector de motivos*/
            this.selectReason();
            this.selectNotPaper();
        },
        /*No deseo papel de regalo*/
        selectNotPaper: function(){            
           chkNotPaper.bind('click', function(){  
               if(chkNotPaper.is(':checked')){
                   if( $.trim(paperCloseOpen.text())=='Cerrar'){ paperCloseOpen.trigger('click'); }
                   paperCloseOpenContent.fadeOut(300);
                   Console.log($('.paper-list .paper-item input:checked', paperContent));
                   $('.paper-list .paper-item input:checked', paperContent).attr('checked', false);
               }else{
                   paperCloseOpenContent.fadeIn(300);
                   $('.paper-list .paper-item input:first', paperContent).attr('checked', true);
               }  
            });
        },
        /*Implementacion del seleccionador de papeles segun el motivo*/
        selectReason: function(){
            var THAT = this;
            $('#gift-reason').bind('change', function(){
                var dataHtml='', id=$('option:selected',this).val(); 
                /*Console.log(THAT.dataPapers);*/
                if(THAT.dataPapers[id])$.each(THAT.dataPapers[id], function(i,val){
                    dataHtml+=THAT.tplPaper(val['tit'],val['src'],val['prec'],val['id']);
                });
                $('.paper-gift-content .paper-list').html(dataHtml);
            }); /*Console.log(THAT);*/
        },
        /*Template para un papel*/
        tplPaper: function(tit,src,prec,id){
            return '<div class="paper-item" style="background: none repeat scroll 0% 0% rgb(245, 245, 251);">'+
                        '<div class="item-title"><input type="checkbox"><span>&nbsp;'+tit+'</span></div>'+
                        '<div class="item-img"><img width="121"  src="'+src+'" alt="Cortesía" /></div>'+
                        '<span>'+prec+'</span><span style="display:none;">'+id+'</span>'+
                    '</div>';
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
});


/*-------------------------------------------------------------------------------------------------------------
 * @Module     : result-search
 * @Description: Acciones que se ejecutan al cargar la pagina resultados de busqueda
 *//*---------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('result-search', function(Sb){
    
    var txtsPrec = $('#precio_de, #precio_a');      /*Rangos de precio: en los filtros*/
    var linkSend = $('a',$('#precio_de').parent()); /*Link send, envia los datos de precio*/
    var btnsView = $('.view-paginate .view a');     /*Botones para cambiar las vistas (galeria o lista)*/
    return { 
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            txtsPrec.numeric({negative:false, decimal:false, length:4});
            this.findByPrec();
            this.typeView();
            this.checkLinks();
            //$('div.result-list a img, ul.result-list a img').resize({maxWidth:180, maxHeight:120});
        },
        /*
         *@public: Buscar por precio, filtro que recibe 2 parametros cantidad inicial y final
         **/
        findByPrec: function(){
            linkSend.unbind().bind('click', function(e){
                e.preventDefault();
                var correct = ( parseInt($('#precio_de').val()) < parseInt($('#precio_a').val()) );
                if($('#precio_de').val()!=''&&$('#precio_a').val()!=''){
                    if(correct){
                        txtsPrec.css('border','1px solid #CBCBCB');
                        window.location.href=$(this).attr('href')+'precio-de-'+$('#precio_de').val()+'-a-'+$('#precio_a').val();                        
                    }else{
                        $('.messages-bar .w1000').slideDown(400, function(){
                            location.href = "#close-messages";
                            var that=this;$('div',this).html('Valor inicial(<b>De</b>) debe ser menor que valor final(<b>a</b>).');
                            $('#precio_de, #precio_a').css('border','1px solid #f00');
                            $(this).find('a').unbind('click').bind('click', function(){$(that).slideUp(400);});/*boton cerrar*/
                        });
                    }
                }else{
                    if($('#precio_de').val()=='')$('#precio_de').css('border','1px solid #f00'); else $('#precio_de').css('border','1px solid #CBCBCB');
                    if($('#precio_a').val() =='')$('#precio_a' ).css('border','1px solid #f00'); else $('#precio_a').css('border','1px solid #CBCBCB');
                }
            });            
        },
        /*
         *@public: Tipo de vistas en el resultado de busqueda (como galeria o como lista)
         **/
        typeView: function(){
            btnsView.bind('click', function(e){
                e.preventDefault();
                var type = $.trim($(this).attr('title'));/*Console.log(type);*/ /*lista o galeria*/
                Cookie.del('searchState').create('searchState', (type== 'lista')?'cell':'grid');
                Console.log('Cookie:673 - ');Console.log(Cookie.read('searchState'));
                if($.browser.msie && $.browser.version=='7.0'){
                    if(type== 'lista'){$('ul.result-list').css('display','block');$('div.result-list').css('display','none');return;}
                    if(type=='galeria'){$('div.result-list').css('display','block');$('ul.result-list').css('display','none');return;}                    
                }else{
                    if(type== 'lista'){$('ul.result-list').slideDown(500);$('div.result-list').slideUp(500);return;}
                    if(type=='galeria'){$('div.result-list').slideDown(500);$('ul.result-list').slideUp(500);return;}                    
                }
            });
        },
        
        /*
         * Check en los items de categoria que redireccionan al mismo lugar que los links de su costado.
         * http://local.peruplaza.pe/flores-y-peluches/arreglos-y-plantas/inauguracion--funebres
         **/
        checkLinks : function(){
            $('.item-options li h3 input[type="checkbox"]').bind('click', function(){
                location.href = $(this).siblings('a').attr('href');
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqNumeric.js', 'libs/class/classCookie.js']);


/*--------------------------------------------------------------------------------------------------------
 * @Module     : modal-shopping
 * @Description: Modal de pedidos
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('modal-shopping', function(Sb){
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){            
            $(".actions-compras").colorbox();
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqColorBox.js', 'plugins/jqColorBox.css']);


/*--------------------------------------------------------------------------------------------------------
 * @Module     : Validate Form
 * @Description: Valida el formulario
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('validation', function(Sb){
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){ 
            $(oParams.form).validate({
                rules:requires[oParams.form].rules,
                messages:requires[oParams.form].messages
            }); 
            
            $('#btn-back').bind('click', function(){history.back();});
            
            $('input#email').bind('keyup blur', function(e){$(this).val($.trim($(this).val()));}); /*Validacion del Email*/
            $('input#phone').bind('keyup blur', function(e){$(this).val( $(this).val().replace(/([^\*\-#@\s0-9])/gi,'') );}); /*Validacion del Email*/
            $('input#phone').bind('blur', function(e){$(this).val( $.trim($(this).val()) );});
            //$('#delivery_instructions, #your_message').bind('keydown', function(e){ Console.log('--->'+$(this).val().length); if($(this).val().length>=350){return false;} });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqValidate.js']);


/*--------------------------------------------------------------------------------------------------------
 * @Module     : Carousel
 * @Description: Carousel de formularios
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('slide-carousel', function(Sb){
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){            
          if($("#product-carousel article").length > 5){
            $("#product-carousel").bxSlider({
                displaySlideQty:5, moveSlideQty:5, infiniteLoop:false, hideControlOnEnd: true,
                onLastSlide: function(){$('a.bx-next').fadeOut();}
            });
          }else{$("#product-carousel").css("margin-left","26px");}
          
          if($("#product-carousel2 article").length > 5){
            $("#product-carousel2").bxSlider({
                displaySlideQty:5, moveSlideQty:5, infiniteLoop:false, hideControlOnEnd: true,
                onLastSlide: function(){$('a.bx-next').fadeOut();}
            });
          }else{$("#product-carousel2").css("margin-left","26px");}
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqBxSlider.js']);


/*--------------------------------------------------------------------------------------------------------
 * @Module     : Validate Numeric
 * @Description: Valida campos enteros
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('valid-numeric', function(Sb){
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            $(".vnumeric").numeric({negative:false, decimal:false, length:2});
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqNumeric.js']);


/*-------------------------------------------------------------------------------------------------------------
 * @Module: bubble-efect
 * @Description: Efecto del globo de oferta parpadeante en los productos.
 *//*---------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("bubble-efect", function(Sb){ 
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            $(".product").topHover({topHover:'-15px', topOut:'-9px', selectors:['.new','.offer']});
            $(".result-list li .item-image").topHover({topHover:'-15px', topOut:'-9px', selectors:['.new','.offer']});
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});

/*-------------------------------------------------------------------------------------------------------------
 * @Module: menu-vitrina
 * @Description: Menu desplegalbe a la derecha, semejante al home.
 *//*---------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("menu-vitrina", function(Sb){ 
    var isTIENDA=(yOSON.module=='yMvY0OPgoQ=='&&yOSON.controller=='2M/X3dLV'&&yOSON.action=='zdTW1OY='); //Si estamos en la pagina pricipal
    var menu = $('#main-menu2');
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            $('li', menu).mouseenter(function(){
                $('.sub', this).animate({opacity:1},500).css({'display':'block','left':'191px','top':'28px','border-top':'1px solid #B0B0AF','height':'295px'});
            }).mouseleave(function(){
                $('.sub', this).css('opacity','0').css('display','none');
                //$('.sub', this).animate({opacity:0},50,function(){$(this).css('display','none');});
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});

/*-------------------------------------------------------------------------------------------------------------
 * @Module: delete-product
 * @Description: Elimina un producto del carrito en el paso 1 via ajax
 *//*---------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("delete-product", function(Sb){ 
    var staturl=yOSON.baseHost;/* Base de la url */    
    var url,data,id,gift; /* Parametros post*/ 
    return {	
        init: function(oParams){
           function ajaxPost(url,data){
                $.ajax({
                    'url':url,
                    'type':'post',
                    'data': data,
                    'dataType' : 'json',
                    'success' : function(response) { 
                        if(response) {
                            if(url.indexOf('removeitem')!=-1){Cookie.del('cartProduct'+data.idProduct);}                 	   
                            location.href=staturl+"carrito/paso1";
                        } else {
                            alert(response.errormessage);
                        }

                    }
                });  
           }
           $(".delete a").click(function(event){
               event.preventDefault();
               url=$(this).attr("rel");     
               if($(this).hasClass("gift")){
                  id=$(this).attr("dataid");
                  gift=$(this).parents(".gift-item").attr("id").split("gid-").join("");
                  data={'idProduct': id,'idExtraProduct': gift}; 
               }
               else{
                 id=$.trim($(this).parent().siblings(".id").text());
                 data={'idProduct': id};
               }
               ajaxPost(staturl+url,data);
           });
        },       
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
}, ['libs/class/classCookie.js']);



/*-------------------------------------------------------------------------------------------------------------
 * @Module: auto-suma
 * @Description: Auto sumado de los campos de los items del carrito.
 *//*---------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("auto-suma", function(Sb){ 
    //var oSums = $('.total-price');
    var oSubT = $('.subtotal').siblings('td').find('.sprice').add('.content .total'); /*Objeto subtotal de subtotales*/
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            var THAT = this;
            $("input[name*='quantity']").numeric({negative:false, min:0, decimal:false, length:2, enter:false}).bind('keyup click',function(){
                var idItem = $.trim( $('td.id', $(this).parent().parent()).text() );
                Cookie.del('cartProduct'+idItem).create('cartProduct'+idItem, $.trim($(this).val()) );
                THAT.subTotal( $(this).parent().parent() );
            });
        },
        
        subTotal:function(oRow){
            var precUpaper = 0;
            var precTpaper = 0;
            var oRowNext   = $(oRow).next('tr');
            var havePaper  = (($.trim(oRowNext.attr('class'))=='gift-item'));
            
            var precU = parseFloat($.trim( $('.unit-price', oRow).text().replace(',','') ));        /*Precio unitario de un row*/
            var quant = parseFloat($.trim( $('input[id="quantity"]', oRow).val().replace(',','') ));/*Cantidad unitaria de un row*/
            var precT = parseFloat($.trim( $('.total-price', oRow).text().replace(',','') ));       /*subtotal unitaria anterior de un row*/
            var subTo = parseFloat($.trim(oSubT.text().replace(',','')));                           /*subtotal de subtotales*/
            quant = isNaN(quant)?0:quant;

            
            if(havePaper){ /*Si tiene papel de regalo*/
                precUpaper = parseFloat($.trim( $('.unit-price', oRowNext).text().replace(',','') ));/*Precio unitario papel*/
                precTpaper = parseFloat($.trim( $('.total-price',oRowNext).text().replace(',','') ));/*subtotal unitaria anterior de papel*/
                $('input[id="quantitygift"]', oRowNext).val(quant);                /*Igualando cantidad de item con cantidad de papeles de regalo*/
                var result=Math.round(precUpaper*quant*Math.pow(10,2))/Math.pow(10,2);
                $('.total-price', oRowNext).text( result.toFixed(2) );  /*Actualizar subtotal unitario de papel*/
            }
            
            $('.total-price', oRow).text( (Math.round(precU*quant*Math.pow(10,2))/Math.pow(10,2)).toFixed(2) );                                  /*Actualizar subtotal unitarios*/
            /*Actualizar subtotal de subtotales*/
            Console.log('precU:'+precU+' quant:'+quant+' precT:'+precT+' subTo:'+subTo+'');
            oSubT.text( (Math.round( (( subTo-precT-(precTpaper) )+( precU*quant + (precUpaper*quant) ) )*Math.pow(10,2))/Math.pow(10,2)).toFixed(2) );
            $('.content .total').text('S/. '+$('.content .total').text());
        }
    }
}, ['libs/plugins/jqNumeric.js', 'libs/class/classCookie.js']);

/*-------------------------------------------------------------------------------------------------------------
 * @Module: change-cardgift
 * @Description: Tarjetas de Regalo
 *//*---------------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("change-cardgift", function(Sb){ 
    var chckcard=$("#check-giftcard");/* Checkbox que almacena el id del regalo */  
    var popup=$(".giftcard"); /* Link que inicializa el popup */
    var nm=$("span",chckcard.parent()); /* Nombre del regalo*/
    var pr=$(".add-gift",chckcard.parents("tr")); /* Precio del regalo*/
    var cnt; /* Paper select */
    var price,name,srcimg,giftval; /* Parametros */    
    var comaDecimal = function(){
        /*SEPARADOR DE MILES*/
    };
    return {	
        init: function(oParams){
            var THAT = this;
            function cardgift(){
                cnt=$(".paper-select");              
                $(".paper-item").live('click',function(){ 
                    $(".paper-item").removeClass("active");
                    $(this).addClass("active");
                    $(".paper-item input[type='checkbox']").attr('checked',false);
                    $('.item-title input[type="checkbox"]',$(this)).attr('checked', true); 
                    giftval=$('.item-title input[type="checkbox"]',$(this)).attr("id");
                    srcimg=$(".item-img img",$(this)).attr("src");                
                    name=$(".item-title span",$(this)).text();                 
                    price=$(".paper-price",$(this)).text();
                    $(".select-img img",cnt).attr("src",srcimg); 
                    $('.paper-gift-top .gitf-image img').attr("src",srcimg);  /*<------- 24185*/
                    $("span:eq(0)",cnt).html(name);                 
                    $("span:eq(1)",cnt).html(price);                 
                });
                $(".confirmcard").live('click',function(){
                    $('a.giftcard').attr('href', yOSON.baseHost+'carrito/tarjetas/id/'+giftval);
                    THAT.sumRest($('#check-giftcard').attr('checked'), $.trim(price.replace('S/.', '')).replace(' ', ''), true);
                    chckcard.val(giftval);nm.html(name);pr.html(price);$.colorbox.close();
                });
            };  /*alert("change-cardgift");*/
            popup.colorbox({onComplete:function(){cardgift();}});
            $('#check-giftcard').bind('click', function(){
                THAT.sumRest($(this).attr('checked'));
            });
           
        },
        
        sumRest : function(checked, nNeoPrec, outer){
            var nNeoPrec  = parseFloat(nNeoPrec);nNeoPrec=(isNaN(nNeoPrec))?0:nNeoPrec;
            
            var nPrecPaper= parseFloat( $(".add-gift").text().replace('S/.','') );nPrecPaper=(isNaN(nPrecPaper))?0:nPrecPaper;

            var nPedTotal = parseFloat( $.trim($.trim($('.total span').text()).replace('S/.', '')).replace(' ', '').replace(',', '') );

            var delta;
            if(checked){
                if(nNeoPrec==0){Console.log('999 - AQQUI!!');delta = outer?(nPedTotal-nPrecPaper):(nPedTotal+nPrecPaper);}
                else{delta = (nPedTotal-nPrecPaper+nNeoPrec);}
            }else{delta = outer?nPedTotal:(nPedTotal-nPrecPaper);}
            
            if(nNeoPrec!=0){$(".add-gift").text('S/. ' + nNeoPrec);}           

            $('.total span').text( 'S/. ' + (Math.round((delta)*Math.pow(10,2))/Math.pow(10,2)).toFixed(2) );
        },

        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
}, ['libs/plugins/jqColorBox.js', 'plugins/jqColorBox.css']);


/*-----------------------------------------------------------------------------------------------
 * @Module: print-button
 * @Description: Funcionalidad boton imprimir
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("print-button", function(Sb){
    return {
        init: function(oParams){
            $('.detail-print button[type="submit"]').bind('click', function(){window.print();});
        }
    };
});


/*-----------------------------------------------------------------------------------------------
 * @Module: date-picker
 * @Description: Calendario UI date-picker
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("date-picker", function(Sb){     
    /*Private Property : Para uso solo dentro del modulo*/
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            var that = this; 
            var allData = {input:'#date',anotherDate:'.another-date',maxDate:'+1m +1w +1w',minDate:0/*,diasBloqueados:{0:[1]}*/,diasGlobalesBloqueados:[]};
            var inpDate = $(allData.input);
            
            $.ajax({
                url     : yOSON.baseHost+"carrito/get-json-date-active/b/"+( $.isNumeric( $.trim($('#order').val()) ) ? 0 : 1 ),
                type    : 'get',
                dataType: 'json',
                success : function(resp){
                    Console.log('1030:success');Console.log(resp);
                    allData.minDate = parseInt(resp.data);
                    inpDate.datepicker({/*defaultDate: +3,*/minDate: "+"+allData.minDate+"d", maxDate: allData.maxDate, blockedDays: allData.diasBloqueados, blockedGlobalDays : allData.diasGlobalesBloqueados});
                    that.anotherDatePicker(inpDate, allData.anotherDate); 
                    $('.another-date').removeClass("hide");  
                    
                    //$('#date').datepicker('show'); 
                    //Console.log($('.ui-datepicker-calendar td:not(.ui-state-disabled)')[0].innerHTML);
                    //$($('.ui-datepicker-calendar td:not(.ui-state-disabled)')[0]).trigger('click');
                    //$('#ui-datepicker-div').css('display','none');
                    //document.getElementById("ui-datepicker-div").style.display = "none";
                    //Console.log('=====> element');
                    //Console.log($('.ui-datepicker-calendar td:not(.ui-state-disabled)')[0]);
                    //$('#date').val($($('.ui-datepicker-calendar td:not(.ui-state-disabled) a')[0]).text());

                },
                error : function(resp){
                    Console.log('1037:error');Console.log(resp);
                    inpDate.datepicker({minDate: allData.minDate, maxDate: allData.maxDate, blockedDays: allData.diasBloqueados, blockedGlobalDays : allData.diasGlobalesBloqueados});
                    that.anotherDatePicker(inpDate, allData.anotherDate);
                    $('.another-date').removeClass("hide");
                }
            });

            

            this.textBloker('#delivery_instructions','keyup');
            this.textBloker('#your_message','keyup');

            this.textBloker('#delivery_instructions','blur');
            this.textBloker('#your_message','blur');
            

        },
        textBloker: function(a, b){

            $(a)[b](function(e){
                var currentText=$(this).val(),
                totalChars= 350,
                inputTextChars= currentText.length,
                remainingChars=(totalChars-inputTextChars),
                outputContent='#'+ $(this).attr('id')+'-error',
                youAre= 'Tienes',
                charAvailable= 'caracteres disponibles',
                charAllowed= 'caracteres Permitidos',
                andUseThe= 'Ya estas usando';

                if(remainingChars>=0){
                    remainingChars=totalChars-inputTextChars;            
                    $(outputContent).fadeIn();
                    message=youAre+' '+remainingChars+' '+charAvailable+'.';
                    if(remainingChars<=0){
                        $(outputContent).fadeIn();
                        message=andUseThe+' '+totalChars+' '+charAllowed+'.';
                    }
                }else{
                    if(remainingChars<=0){
                        $(outputContent).fadeIn();
                        message=andUseThe+' '+totalChars+' '+charAllowed+'.';
                    }
                $(this).val(currentText.substring(0,totalChars));
                }
                
               if($('#'+$(this).attr('id')+'-error').length){                    
                    $('#'+$(this).attr('id')+'-error').children('li').html(message);
                }else{                    
                    $(this).parent().append('<ul id="'+$(this).attr('id')+'-error" class="errors"><li>'+message+'</li></ul>');
                }                
            });

        },
        anotherDatePicker: function(inpDate, anotherDate){
            $(anotherDate).click(function(){
                inpDate.trigger('focus');
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
}, ['plugins/start/jquery-ui-1.8.22.custom.css', 'libs/plugins/jqDatepicker.js']);


/*-----------------------------------------------------------------------------------------------
 * @Module: select-dependent
 * @Description: Select dependent
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('select-dependent', function(Sb){
    
    var domainServer = 'http://'+document.domain;
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            
            /*Console.log($('#stateUbigeo option:selected'));*/
            /*if(!$('#stateUbigeo option:selected').text()){
                $('#stateUbigeo option[label="LIMA"]').attr('selected',true).trigger('change',['150100', '150101']);
            }*/
            
            $('#regaloDireccion').change(function() { //Console.log('dir: '); Console.log(this);
                if($.trim($(this).val())!='' && $.trim($(this).val())!='0'){   
                    $.ajax({
                        url     : domainServer+'/carrito/get-json-address/id/'+$(this).val(),
                        type    :'get',		        
                        dataType:'json',
                        success :function(res){
                            Console.log('success-1090');
                            if(res.status=='ok'){                                    
                                /*Selecciona estado segun #regaloDireccion*/
                                $("#stateUbigeo option").each(function(){                                                     
                                    if($(this).attr('value')==res.data.id_state){$(this).attr('selected',true);}
                                });
                                $("#stateUbigeo").trigger("change", [res.data.id_provinces, res.data.id_districts]);

                                $('#nombre').val(res.data.firstname);
                                $('#apellido').val(res.data.lastname);
                                $('#direccion').val(res.data.address1);
                                $('#phone').val(res.data.phone);
                                $('#email').val(res.data.email);

                            } else {$("#districtUbigeo").append('<option value="0">No hay distritos disponibles</option>');}                                
                        }
                    }); 
                }else{
                    Console.log($('#stateUbigeo option[label="LIMA"]'));
                    $('#stateUbigeo option[label="LIMA"]').attr('selected',true).trigger('change',['150100', '150101']);
                    $('#nombre').val('');
                    $('#apellido').val('');
                    $('#direccion').val('');
                    $('#phone').val('');
                    $('#email').val('');
                } 
            });
            
            $('#stateUbigeo').change(function(e, idProv, idDist){    
                /*Console.log('dep: ');Console.log('idProv:'+idProv+' - idDist:'+idDist);*/
                $.ajax({
                    url: domainServer+'/carrito/get-json-provinces/id/'+$(this).val(),
                    type:'get', dataType:'json',
                    success: function(res){
                        /*Console.log('dep:succ');*/
                        $("#provinciaUbigeo, #districtUbigeo").html("");
                        $("#districtUbigeo").append('<option value="0">seleccionar</option>');
                        if(res.status=='ok'){                                    
                            $.each(res.data, function(i,e){
                                $("#provinciaUbigeo").append( $('<option value="'+i+'">'+e+'</option>').attr('selected',(i==idProv)) );
                            });
                            $("#provinciaUbigeo").trigger('change', [idDist]);
                        }else{$("#provinciaUbigeo").append('<option value="0">No hay provincias disponibles</option>');}                                
                    }
                });                           
            });

            $('#provinciaUbigeo').change(function(e, idDist) {
                /*Console.log('prov: ');Console.log(this);*/
                $.ajax({
                    url: domainServer+'/carrito/get-json-district/id/'+$('#provinciaUbigeo option:selected').val(),
                    type:'get',		        
                    dataType: 'json',
                    success: function(res){
                        /*Console.log('prov:succ');*/
                        $("#districtUbigeo").html("");
                        if(res.status=='ok'){                                    
                            $.each(res.data, function(i,e){ 
                                $("#districtUbigeo").append( $('<option value="'+i+'">'+e+'</option>').attr('selected',(i==idDist)) );
                            });
                        }else{                                    
                            $("#districtUbigeo").append('<option value="0">No hay distritos disponibles</option>');
                        }                                
                    }
                });
                var pu_sucess = 1;
            });                
            /*get data by address*/
            if($('#regaloDireccion').val() != 0){$("#regaloDireccion").trigger('change');}

        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };

});

/*-----------------------------------------------------------------------------------------------
 * @Module: Popup
 * @Description: Inicializa un popup
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('popup', function(Sb){    
    var popup = $(".popup");    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){//alert('popup-init');
            popup.colorbox();
            this.submitPay();
        },
                
        submitPay: function(){ //alert('submitPay');
            var TIMER1 = null,TIMER2 = null;
            $('#submit-pay').bind('click', function(){  
                clearTimeout(TIMER1);clearTimeout(TIMER2);
                TIMER1 = setTimeout( function(){$(this).attr('disabled', true);}.bnd(this), 150);
                TIMER2 = setTimeout( function(){$(this).attr('disabled', false);}.bnd(this), 20000);
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
}, ['libs/plugins/jqColorBox.js', 'plugins/jqColorBox.css']);
/*-----------------------------------------------------------------------------------------------
 * @Module: Accordion
 * @Description: Inicializa un efecto acordeon
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('accordion', function(Sb){    
    var slip = $(".accordion"); 
    var id;
    return {        
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){ 
            slip.click(function(){			
                id=$(this).attr("data-id");
                if($(this).hasClass("active")){
                    $(this).removeClass("active");
                }else{
                    $(this).addClass("active");
                }                
                $(id).slideToggle();
            });		
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});