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
 * @Module: slide-toogle
 * @Description: Toogle
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("slide-toogle", function(Sb){
    var slide  = $(".slide-toogle");
    var id;
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            slide.click(function(obj){
                if(!$(obj.target).hasClass("notbind")){
                    id=$(this).attr("data-id");
                    if($(this).hasClass("active")){
                        $(this).removeClass("active");
                    }else{
                        $(this).addClass("active");
                    }               
                    $(id).slideToggle();
                }
            });            
        },
        /*Public method : Implementacion de alguna funcionalidad asociada a este modulo.*/
        other: function(){  },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };

});
/*-----------------------------------------------------------------------------------------------
 * @Module: menu
 * @Description: menu
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("menu", function(Sb){
    var menu  = $("#menu").children('li');
    var submenu=$("#menu ul");
    var menuactive=$("#menu .active").siblings("ul");
    var position,left;
    return {
    /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        currentMenu:function(){
            submenu.hide();
            menuactive.show();
        },
        init: function(oParams){
            var that=this;
            that.currentMenu();
            $.each(menu,function(index,element){
                position=$(element).position();
                left=644-position.left;
                $("ul",$(element)).css("width",left);
            });
            menu.mouseenter(function(){
                if($("ul",this).length){
                    submenu.hide();
                    $("ul",this).show();
                }
            }).mouseleave(function(){
                that.currentMenu();
            });
        },       
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});

/*-----------------------------------------------------------------------------------------------
 * @Module: nav-tree
 * @Description: menu
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("nav-tree", function(Sb){
    var tree=$(".category h2, .subcat h3");
    return {
    /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            
            tree.live('click', function(evt){
                if($(evt.target).attr('type')==undefined){
                    if($(this).hasClass("active")){
                        $(this).removeClass("active");
                    } else {
                        $(this).addClass("active");
                    }
                    $(this).siblings("ul").slideToggle();
                }
            });
            
        },       
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
});


/*-----------------------------------------------------------------------------------------------
 * @Module: State Order
 * @Description: menu
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('state-order', function(Sb){
    var select=$(".select-state"),popup;
    return {
    /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
         init: function(oParams){
                select.bind("change",function(){
                    popup=$(this).val();
                    if($(popup).length){
                        $.colorbox({inline:true,href:popup});
                    }
                });
        },       
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqColorBox.js', 'plugins/jqColorBox.css']);

/*-----------------------------------------------------------------------------------------------
 * @Module: product-manage-image
 * @Description: menu
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("product-manage-image", function(Sb){
    var tree=$(".category h2, .subcat h3");
    var inst;
    return {
    /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){

            var productPath='product/tmp/',
            addImgUrl = yOSON.baseHost+'admin/producto/agregar-imagen/id/1',
            delImgUrl = yOSON.baseHost+'admin/producto/delete-img-tmp/img/',
            extension = '.jpg',
            dom = {
                frm: 'frmConfis',
                parentRight: '.fielset-content-right',
                parentLeft: '.fielset-content-left',
                inputFile: '#image-uploader',
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

                    $('#'+dom.frm).attr('action','');
                    $('#'+dom.frm).attr('target', '');

                    $(dom.inputDesc).val('');

                    if (data["status"]==1){
                        $(dom.parentRight).append('<div class="w170" id="'+data["url"]+'"><div class="options"><div class="item-del"><a title="eliminar" href="javascript:;"><img class="" alt="eliminar" src="'+yOSON.statHost+'img-admin/remove.png"></a></div><div class="item-fav"><a title="default" href="javascript:;"><img class="" alt="default" src="'+yOSON.statHost+'img-admin/fav.png"></a></div></div><a title="imagen" href="javascript:;"><img class="main-class" alt="imagen" src="'+yOSON.eHost+productPath+data["url"]+extension+'"></a><p>'+data["description"]+'</p></div>');
                        $(dom.parentRight).trigger('change');
                    }else{
                        Console.log(data["status"]);
                    }
                }
            }

            $(dom.btnAdd).click(function(){
                $('#'+dom.frm).attr('action', addImgUrl);               
                $.fn.iframeUp('submit', options);
            });

            var options2 = {
                frm: '#'+dom.frm,
                parentRight: dom.parentRight,
                parentLeft: dom.parentLeft,
                url_del: delImgUrl
            };

            $.fn.pictureManager(options2);

        },       
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };

}, ['libs/plugins/jqUploader.js', 'libs/plugins/jqPictureManager.js', 'libs/plugins/ui.js']);

/*--------------------------------------------------------------------------------------------------------
 * @Module     : Validate Form
 * @Description: Validacion de formularios 
 * para mas detalle consultar 
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('validation', function(Sb){
    
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            $(oParams.form).validate({
                ignore  : '',
                rules   :requires[oParams.form].rules,
                messages:requires[oParams.form].messages, /*,
                errorPlacement: function(error, element){
                    //alert('ERROr!!');
                }*/
                errorPlacement: function(error, element){
                    $(element).parent().append(error);
                },
                invalidHandler:(requires[oParams.form].hasOwnProperty('invalidHandler')) ? requires[oParams.form].invalidHandler : function(){}
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
}, ['libs/plugins/jqValidate.js']);

/*-----------------------------------------------------------------------------------------------
 * @Module: Editor WYSIWYG
 * @Description: menu
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("tabs-products", function(Sb){
    var tabs  = $('#frmConfis .content-tabs');
    var links = $('.nav-options li a');
    var fields = $('#precio_proveedor, #precio_base, #porcentaje_ganancia'); /*, #venta_sin_igv, #venta_con_igv*/
    var oShop = $('#id_shop');
    return {
        /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            /*Funcionalidad de los link tipo tabs*/
            //Console.log(tabs);
            links.each(function(i,lnk){
                $(lnk).bind('click', (function(index){
                    return function(evt){
                        evt.preventDefault();Console.log(tabs.get(index));
                        tabs.fadeOut(400);
                        $(tabs.get(index)).fadeIn(400);
                    };
                })(i));
            });
            this.doWysiwyg();
            /*Campos que seran enteros*/
            fields.add('#venta_con_igv').numeric({negative:false, decimal:'.', length:10});
            fields.add('#venta_con_igv').bind('keyup', this.doCalcule );this.doCalcule(); /*Calculando al hacer load*/
            this.doTree();
        },
        
        doCalcule : function(){
            var pB  = $.trim($('#precio_base').val());
            var vPB = (pB=="") ? 0 : parseFloat(pB);$('#venta_con_igv').attr('readonly',(vPB<=0)); //PV    = PB*(1+%G)   //PVIgv = PV*(1.18)
            Console.log(this);
            var inpVCI = ($(this).attr('id')=='venta_con_igv'); /*Input en el que se esta escribiendo*/
            if(!inpVCI){
                var pG  = $.trim($('#porcentaje_ganancia').val());
                var vPG = (pG=="") ? 0 : parseFloat(pG);
                $('#venta_sin_igv').val( Math.round( (vPB*(1+(vPG/100)))*Math.pow(10,2) )/Math.pow(10,2) );
                $('#venta_con_igv').val( Math.round( parseFloat( $('#venta_sin_igv').val() )*1.18*Math.pow(10,2) )/Math.pow(10,2) );
            }else{
                var pVCI  = $.trim($('#venta_con_igv').val());
                var vPVCI = (pVCI=="")? 0 : parseFloat(pVCI);
                $('#porcentaje_ganancia').val( Math.round( ( parseFloat(vPVCI)/(1.18*vPB)-1 )*100*Math.pow(10,2) )/Math.pow(10,2) );
                var vPG = ($.trim($('#porcentaje_ganancia').val())=="") ? 0 : parseFloat($('#porcentaje_ganancia').val());
                $('#venta_sin_igv').val( Math.round( (vPB*(1+(vPG/100)))*Math.pow(10,2) )/Math.pow(10,2) );
            }
            $('#precio_final_con_oferta').val( Math.round( parseFloat($('#venta_sin_igv').val())*0.8*1.18*100 )/100 );
        },
        
        doWysiwyg : function(){
            new nicEditor({
                iconsPath  : yOSON.statHost+'img/nicEditorIcons.gif', 
                buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul'],
                maxHeight : 100
            }).panelInstance('descripcion');
            
            $('.nicEdit-main[contentEditable="true"]').bind('keyup', function(){
                /* /patron/.test(cadena):  se busca coincidencia del patron detro de la cadena xaya <><>*/
                /*var desc = /^(<br>|<br \/>|\s|&nbsp;|<([^\/])+>(&nbsp;|\s|)+<\/(.)+>|)+$/gi.test( $.trim($(this).html()) ) ? '' : $(this).html();*/
                /*var desc = /^([a-zA-Z\s]|<([a-z^\/])+>(.)+<\/[a-z]+>)+$/gi.test( $.trim($(this).html()) ) ? $(this).html() : '';*/
                var desc = />?[^>^<]+<?/gi.test( $.trim($(this).html()) ) ? $(this).html() : ''; //Console.log('desc: '+desc);
                $('#descripcion').val(desc);
                $('#descripcion').trigger('keyup');
            }).bind('keydown', function(e){
                var key = e.charCode ? e.charCode : e.keyCode?e.keyCode:0; //Console.log("keycode : =====>"+key);
                $("#id-error-desc").css('display','block').html( "Le queda "+(250-$(this).text().length)+" caracteres por escribir." );
                if($(this).text().length>=250 && !/^(37|38|39|40|13|8)$/.test(key)){return false;}                
            });
        },
        
        doTree: function(){
            oShop.bind('change', function(){
                var id = $.trim($('option:selected', this).val());Console.log(id);
                var idp= $.trim($(this).attr('id-prod'));Console.log(id);
                var srtUrl = idp!=''?'/product/'+idp:'';
                $.getJSON(yOSON.baseHost+'admin/producto/get-json-category-filter/shop/'+id+srtUrl, function(json){
                    var htmlTree = ""; //"<ul>";  
                    $.each(json.d, function(i, v){ /*Console.log('---------->'+i+"-"+v);*/
                        htmlTree += '<li class="category">';
                            htmlTree += '<h2>'+v.n+'<span class="slip"></span><span class="icon"></span></h2>';  /*v.name*/
                            htmlTree += '<ul class="content-subcat">';
                            $.each(v.c, function(i2, v2){ /*Console.log('    ---------->'+i2+"-"+v2);*/
                                htmlTree += '<li class="subcat">';
                                    htmlTree += '<h3>'+v2.n[0]+'<span class="slip"></span><span class="icon"></span><input name="subcategoria[]" type="checkbox" value="'+v2.i+'" name="subcatweg" '+(v2.n[1]!='0'?'checked':'')+' /></h3>';
                                    htmlTree += '<ul class="content-attr">';
                                    $.each(v2.c, function(i3, v3){ /*Console.log('        ---------->'+i3+"-"+v3);*/
                                        htmlTree += '<li class="attr">';
                                            htmlTree += '<h4>'+v3.n+'<span class="icon"></span></h4>';
                                            htmlTree += '<ul>';
                                            $.each(v3.c, function(i4, v4){ /*Console.log('            ---------->'+i4+"-"+v4);*/
                                                htmlTree += '<li><label class="checkbox"><input name="filtro[]" type="checkbox" value="'+i4+'" '+(v4[1]!='0'?'checked':'')+' />'+v4[0]+'</label></li>';
                                            });
                                            htmlTree += '</ul>';
                                        htmlTree += '</li>';
                                    });
                                    htmlTree += '</ul>';
                                htmlTree += '</li>';
                            });
                            htmlTree += '</ul>';
                        htmlTree += '</li>';
                    }); //htmlTree += '</ul>';
                    /*Cargando listado de del arbol*/
                    $('.content-tree #main-tree').html(htmlTree); 
                    /*Asignado funcionalidad a checkbox*/
                    $('li.subcat h3 input[type="checkbox"]').each(function(i,e){ 
                        var ul = $($(e).parent()).siblings("ul.content-attr");
                        var thisUlChks = $('input[type="checkbox"]', ul);
                        thisUlChks.bind('click', (function(parentChk){
                            return function(){
                                var checked = $(this).prop('checked');
                                if(!checked)thisUlChks.each(function(i,e){
                                    if($(e).attr('checked')){checked=true;return false;}
                                });
                                $(parentChk).attr('checked', checked);
                            };
                        })(e)); 
                    });
                });
 
                $.getJSON(yOSON.baseHost+'admin/producto/get-json-sub-category/shop/'+id+srtUrl, function(json){
                    var selCat = '<select name="categoria_defecto" id="categoria_defecto" class="valid">';
                    selCat += '<option value="">Seleccione</option>';
                    console.log('|get-json-sub-category---------------------------|');
                    console.log(json.d);
                    console.log('|get-json-sub-category---------------------------|');
                    $.each(json.d, function(i, v){
                        selCat += '<optgroup label="'+v.np+'">';
                        //Console.log("341:");Console.log(v.c);
                        if(v.hasOwnProperty("c"))$.each(v.c, function(i, v){
                            if(v.hasOwnProperty("n")){
                                selCat += '<option value="'+i+'" '+(v.n[1]!='0'?'selected':'')+'>'+v.n[0]+'</option>';
                            }
                        }); 
                        selCat += '</optgroup>';
                    });
                    selCat += '</select>';
                    $('#categoria_defecto').parent().html(selCat);
                });

            }).trigger('change');
        },
        
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
}, ['libs/simpleNicEdit.js','libs/plugins/jqNumeric.js']);

/*-----------------------------------------------------------------------------------------------
 * @Module: Listado de productos
 * @Description: Muestra una grillas con todos los productos y los respectivos botones de accion.
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("list-product", function(Sb){
    var frmlistP = $('#frm');
    var inpState = $('#h_act');
    var chkState = $('.t-show input[type="checkbox"]');
    var selPagin = $('#gridNumPage');
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            chkState.bind('click' , this.changeStateReg );  //Asignado funcionalidad
            Sb.events(['submit-paginate'], function(sel, frm){  /*Evento a nivel de modulo de paginacion por submit*/
                sel.bind('change', function(){ frm.submit() } );
            }, this).trigger('submit-paginate',[selPagin, frmlistP]); /*Disparando evento*/
        },
        /*Public method : Implementacion de alguna funcionalidad asociada a este modulo.*/
        changeStateReg: function(){
            var id = $(this).attr('id').split('active')[1];
            var chk= $(this).attr('checked')?'1':'0';
            chkState.attr('disabled', true);Console.log(id+"-"+chk);
            inpState.val(id+'|'+chk);frmlistP.submit();
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };

});

/*-----------------------------------------------------------------------------------------------
 * @Module: Listado de productos
 * @Description: Muestra una grillas con todos los productos y los respectivos botones de accion.
 *//*-------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule("list-orders", function(Sb){
    var frmlistP = $('#frm');
    var inpState = $('#h_act');
    var chkState = $('.t-show input[type="checkbox"]');
    var selPagin = $('#gridNumPage');
    return {
	/*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            chkState.bind('click' , this.changeStateReg );  //Asignado funcionalidad
            Sb.trigger('submit-paginate', [selPagin, frmlistP]); /*Disparando evento*/
        },
        /*Public method : Implementacion de alguna funcionalidad asociada a este modulo.*/
        changeStateReg: function(){
            var id = $(this).attr('id').split('active')[1];
            var chk= $(this).attr('checked')?'1':'0';
            chkState.attr('disabled', true);Console.log(id+"-"+chk);
            inpState.val(id+'|'+chk);frmlistP.submit();
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };

});

/*--------------------------------------------------------------------------------------------------------
 * @Module     : Select Information
 * @Description: Muestra los divs segun la opción elegida
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('select-information', function(Sb){    
    return {
    /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
                var select=$("#destaque"),selected=$("#destaque option:selected"),selects=$(".select-option"),value;
                value="#"+selected.attr("label");
                if($(value).length){ 
                    $(value).show();
                }
                select.change(function(){ 
                    selected=$("#destaque option:selected");
                    value="#"+selected.attr("label");
                    selects.hide();
                    if($(value).length){ 
                        $(value).fadeIn(500);
                    }
                });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
});
/*--------------------------------------------------------------------------------------------------------
 * @Module     : Datepicker Products
 * @Description: Muestra Datepicker en la sección de productos
 *//*----------------------------------------------------------------------------------------------------*/
yOSON.AppCore.addModule('date-products', function(Sb){    
    return {
    /*Public Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules)*/
        init: function(oParams){
            
            var fechaFin = $("#destaque_fecha");      /* Datetimepicker from */
            var datefrom = $("#discount_start_date"); /* Datetimepicker from */
            var dateto   = $("#discount_end_date");   /* Datetimepicker to   */
            
            fechaFin.datepicker({
                dateFormat:"dd-mm-yy", showOn:"button", minDate:0,
                buttonImage: yOSON.statHost+"img-admin/datepicker.png",
                buttonImageOnly: true
            });
            
            datefrom.datepicker({
                dateFormat:"dd-mm-yy", showOn:"button", minDate:0,
                buttonImage: yOSON.statHost+"img-admin/datepicker.png",
                buttonImageOnly: true,
                onSelect: function(selectedDate){dateto.datepicker("option", "minDate", selectedDate);}
            });
            
            dateto.datepicker({
                dateFormat:"dd-mm-yy", showOn:"button", minDate:0,
                buttonImage: yOSON.statHost+"img-admin/datepicker.png",
                buttonImageOnly: true
            });
        },
        /*Constructor : Inicializa el modulo (Es el que se ejecuta en el schema @modules).*/
        destroy: function(){ /*Como destruir instacia de este modulo aqui*/ }
    };
    
},['plugins/start/jquery-ui-1.8.22.custom.css', 'libs/plugins/min/jqDatepicker.js']);
