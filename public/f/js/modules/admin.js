/**
* Modulo principal de admin
* @class admin
* @module yOSON
*/

/**
* Validador general para todos los formularios, es un metodo general
* @submodule validate
* @main admin
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
        destroy: function(){}
    };
},['libs/plugins/min/jqValidate.js']);


/**
* Muestra modal para agregar nuevo
* @submodule show-modal
* @main admin
*/
yOSON.AppCore.addModule('show-modal-add', function(Sb){ 
    return {
        init: function(oParams){
            $("#addRegister").bind('click',function(){
                 $.fancybox({
                     href : yOSON.baseHost+yOSON.module+'/'+$("#apfbFormSearch").attr("form"),
                     onComplete : function(){
                         //validacion
                         yOSON.AppCore.runModule('validate',{form:'#apfbForm'});
                         $("#apbtnGuardar").parent().addClass("aFormLine-center");
                         $("#f_orden").attr('placeholder','Ingrese un numero de dos digitos');
                         $("#f_cerrar").bind('click',function(){
                             $.fancybox.close();
                         });
                     }
                 });
             });
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jqValidate.js','libs/plugins/min/jquery.fancybox.js']);



/**
* paginacion
* @submodule 
* @main admin
*/
yOSON.AppCore.addModule('ap-filtro-busqueda', function(Sb){
    
    return {
        init: function(){
            var formSearch = '#apfbFormSearch',//->Form Search
                btnSearch = '#apfbBtnSearch',
                divResultSearch = '#apfbResultAjax',
                btnOpenModal = '.apfbBtnOpenModal',
                btnCloseModal = '#f_cerrar',   
                btnSave='#apbtnGuardar',
                pag = '.pag';
                
            var apFiltroBusqueda = {
                fntSearchForm : function(){
                    apFiltroBusqueda.fntSearchCleanEvents();
                    var data = $(formSearch).serialize();
                    apFiltroBusqueda._fntSearchFind(data);
                },
                fntSearchCleanEvents : function () {
                    $(pag).unbind();
                    $(btnOpenModal).unbind();
                },
                _fntSearchFind: function(data) {
                    $(btnSearch).attr("disabled","disabled");
                    apFiltroBusqueda.fntSearchCleanEvents();
                    $(divResultSearch).html('&nbsp;');
                    $(divResultSearch).addClass("loading");
                    var urlAction=$(formSearch).attr("action");
                    $.ajax({
                        url : yOSON.baseHost+yOSON.module+urlAction,
                        type : 'GET',
                        dataType : 'html',
                        data : data,
                        success : function (html,status) {
                            apFiltroBusqueda.fntSearchCleanEvents();
                            $(divResultSearch).removeClass("loading").append(html);
                            $(pag).bind('click', apFiltroBusqueda._fntSearchPagination);
                            apFiltroBusqueda._fntModalAccion(btnOpenModal);
                            $(btnSearch).removeAttr("disabled");
                        }
                    });
                    
                    $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                        if(jqXHR.status == 401)
                        window.location.href = 'auth';
                    });
                },
                _fntSearchPagination : function(){
                    apFiltroBusqueda.fntSearchCleanEvents();
                    var data = $(formSearch).serialize();
                    data = data + '&page=' + $(this).attr('rel');
                    apFiltroBusqueda._fntSearchFind(data);
                    return false;
                },
                _fntModalAccion : function(A){
                    $(A).bind('click',function(){
                        var idHidden = $(this).attr("data-id");
                        $.fancybox({
                            //llamado de formulario
                            href : yOSON.baseHost+yOSON.module+'/'+$(formSearch).attr("form")+'?f_id='+idHidden,
                            onComplete : function(){
                                //add class
                                $("#apbtnGuardar").parent().addClass("aFormLine-center");
                                //validacion
                                yOSON.AppCore.runModule('validate',{form:'#apfbForm'});
                                yOSON.AppCore.runModule('validate-document');
                                yOSON.AppCore.runModule('data-ubigeo');
                                apFiltroBusqueda._fntModalClose();
                                
                            }
                        });
                    })//bind   
                },
                _fntModalCleanEvents : function () {
                    $(btnCloseModal).unbind();
                    $(btnSave).unbind();
                },
                _fntModalCloseTime: function (){
                    setTimeout(
                        function(){
                            $.fancybox.close()
                        },
                        1500
                    );
                },
                _fntModalClose: function (){
                    $(btnCloseModal).bind('click',function(){
                        $.fancybox.close()
                    })
                }
            }
            
            //inicio de funciones
            apFiltroBusqueda.fntSearchCleanEvents();
            apFiltroBusqueda.fntSearchForm();
            
        },
        destroy: function(){ /*destruir la instancia de este modulo aqui*/ }
    };
},['libs/plugins/min/jqValidate.js','libs/plugins/min/jquery.fancybox.js']);


/**
* Funcionalidades en general
* @submodule funcionality
* @main admin
*/
yOSON.AppCore.addModule('funcionality', function(Sb){
    var dom = { starDate : '#a_fecIni',endDate : '#a_fecFin'},
        datePicker = function(){
            var vigencia = $(dom.starDate+','+dom.endDate).datepicker({
               changeMonth: true,
               dateFormat: "dd/mm/y",
               changeYear: true,
               maxDate: -0,
               showMonthAfterYear: false,
               onSelect: function( selectedDate ) {
                   var option = ('#'+this.id == dom.starDate) ? "minDate" : "maxDate",
                   instance = $(this).data("datepicker"),
                   dateIF = $.datepicker.parseDate(
                       instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                       selectedDate, instance.settings );
                   vigencia.not( this ).datepicker( "option", option, dateIF );
               }
           });
        },
        placeholderDetail = function(){
            $("#a_orden").attr('placeholder','Ingrese un digito de dos cifras');
        }
           
    return {
        init: function(oParams){
            datePicker();
            placeholderDetail();
        },
        destroy: function(){}
    };
},['libs/ui/min/jquery.ui.core.js','libs/ui/min/jquery.ui.datepicker.js','libs/ui/i18n/min/jquery.ui.datepicker-es.js']);

/**
* Validacion de documentos por diferentes tipos
* @submodule validate-document
* @main admin
*/
yOSON.AppCore.addModule('validate-document', function(Sb){ 
    var dom = {inputTipoDocumento:$('#a_tipDoc'),inputNroDoc:$('#a_nDoc')};
     _cambioTipoDoc = function(){
        var _this=$(this);
        switch($.trim(_this.attr('value'))){
            case "05":
                dom.inputNroDoc.attr("maxlength", 8);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    required:true,
                    dni: true,
                    digits:true,
                    messages: {
                        required: 'Ingrese su Nro. de Documento.',
                        dni:'Ingrese su Nro. de Documento.',
                        digits:'Ingrese solo digitos.'
                    }
                });
            break;
            case "06":
                dom.inputNroDoc.removeAttr("maxlength");
                dom.inputNroDoc.attr("minlength", 3);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    required:true,
                    minlength:3,
                    messages: {
                        required:'Ingrese su Pasaporte',
                        minlength:'Mínimo 3 caractéres'
                    }
                });
            break;
            case "07":
                dom.inputNroDoc.attr("maxlength", 11);
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.rules("add",{
                    required:true,
                    ruc: true,                   
                    messages: {
                        required: 'Ingrese su RUC',
                        ruc:'Ingrese su RUC'
                    }
                });
            break;
            case "":
                dom.inputNroDoc.removeAttr("maxlength");
                _this.rules("remove");
                dom.inputNroDoc.rules("remove");
                dom.inputNroDoc.val("");
            break;
        }
         $("#apfbFormSearch").valid();
    },
    _keyValidate = function(){
        dom.inputNroDoc.bind('keyup',function(e){
           if(dom.inputTipoDocumento.val() == ""){
                 dom.inputTipoDocumento.rules("add",{
                    required:true,                  
                    messages: {
                        required: 'Seleccione un tipo'
                    }
                });
                $("#apfbFormSearch").valid();
           }
        });
    },
    bindEvents = function(){
        dom.inputTipoDocumento.bind("change", _cambioTipoDoc).trigger("change");
        _keyValidate();
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
* @main admin
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
            staticLoadCmb('#f_departamento',
                          '#f_provincia', '',
                          {'data':_ubiProv,
                           'nameFatherId':'ID_DPTO',
                           'nameId':'ID_PROV', 
                           'nameValue':'NOM'
                           }, 
                          '#f_distrito',
                function() {}
            );
            staticLoadCmb('#f_provincia',
                          '#f_distrito',
                          '#f_departamento',
                          {'data':_ubiDist,
                           'nameFatherId':'ID_PROV',
                           'nameFatherOtherId':'ID_DPTO',
                           'nameId':'ID_UBIGEO',
                           'nameValue':'NOM'},
                function() {
                       $('#categoriaId3 :nth-child(1)').attr('selected', 'selected').trigger('change');
                }
            );

            /*validate selector lima */
            $('#ubication_departement').bind('change',function(){
                if($(this).val() == 15){
                    $("#ubication_district").parent().parent().slideDown('slow');
                }else{
                    $("#ubication_district").parent().parent().slideUp('slow');
                    $('#ubication_province').bind('change',function(){
                        $('#ubication_district').children(2).attr('selected',true);
                    });
                }
            });

        },
        other: function(){  },
        destroy: function(){}
    };
});

/**
* Funcionalidades para la administracion del item
* @submodule admin-item
* @main admin
*/
yOSON.AppCore.addModule("admin-item", function(Sb){
    

    return {
        init: function(oParams){
            var dom = {
                selectModel : $("#a_tipoModulo"),
                especial:$(".especial"),
                enlace:$(".enlace"),
                hiddenDefault:$(".hidden-default"),
                submitButton:$("#apfbBtnSearch"),
                addRegisterContent:$(".add-register-content"),
                addRegister:$(".add-register"),
                formSearchItem : $("#formSearchItem"),
                afbResultAjax:$("#afbResultAjax"),
                pag:$(".pag"),
                aheroUnit:$(".ahero-unit")
            },
            showOptionsForm = function(){
                //ocultar po default
                dom.selectModel.trigger('change');
                //cambio de propiedades al cambiar el tipo
                dom.selectModel.bind('change',function(){
                    $("#a_modulo").html("");
                    // si es enlace
                    if($(this).val() == 1){
                        dom.addRegisterContent.fadeIn("slow");
                        dom.hiddenDefault.fadeIn('slow');
                        dom.enlace.fadeIn("slow");
                        dom.especial.fadeOut("slow");
                        dom.addRegister.bind('click');
                        dom.formSearchItem.attr('form','/item/form/tipo/1');
                        dom.afbResultAjax.html('');
                         fillDataSelect(1);
                    // si es especial
                    }else if($(this).val() == 2){
                        dom.addRegisterContent.fadeIn("slow");
                        dom.hiddenDefault.fadeIn('slow');
                        dom.especial.fadeIn("slow");
                         dom.enlace.fadeOut("slow");
                         dom.addRegister.unbind('click');
                         dom.addRegister.attr("href",yOSON.baseHost+"admin/item/especial");
                         dom.formSearchItem.attr('form','/item/form/tipo/2');
                         dom.afbResultAjax.html('');
                          fillDataSelect(2);
                    }else{
                        dom.aheroUnit.fadeOut('slow');
                    }
                });
            },
            searchItem = function(dataForm,urlAction){
                $("#apfbBtnSearch").attr("disabled","disabled");
                 $.ajax({
                    url : yOSON.baseHost+yOSON.module+urlAction,
                    type : 'GET',
                    dataType : 'html',
                    data : dataForm,
                    beforeSend : function(){
                        $("#afbResultAjax").html('').addClass("loading");
                    },
                    success : function (value,status) {
                        $("#afbResultAjax").removeClass("loading").html(value);
                        $("#apfbBtnSearch").removeAttr("disabled");

                    }
                  });//ajax
            },
            editModal = function(){
              $(".apfbBtnOpenModal").live('click',function(event){
                event.preventDefault();
                var formUrl = $('#formSearchItem').attr('form');
                $.fancybox({
                  href : yOSON.baseHost+yOSON.module+formUrl+'?f_id='+$(this).attr('data-id'),
                  onComplete :function(){
                      yOSON.AppCore.runModule('validate',{form:'#apfbFormEnlace'});
                      yOSON.AppCore.runModule('validate',{form:'#apfbFormEspecial'});
                      $("#apfbFormItem").parent().addClass("aFormLine-center");
                           $("#f_cerrar").bind('click',function(){
                             $.fancybox.close();
                      });
                  }
                });
              });


            },
            addModal = function(){
                $("#addRegister").bind('click',function(event){
                    event.preventDefault();
                    var formUrl = $('#formSearchItem').attr('form');
                    $.fancybox({
                        href : yOSON.baseHost+yOSON.module+formUrl,
                        onComplete:function(){
                            var idFormValidate = "#apfbFormEnlace";
                            yOSON.AppCore.runModule('validate',{form:idFormValidate});
                             $("#apfbFormItem").parent().addClass("aFormLine-center");
                             $("#f_link").attr('placeholder','ejem: http://www.ejemplo.com');
                             $("#f_cerrar").bind('click',function(){
                                 $.fancybox.close();
                             });
                        }
                    });
                });
            },
            pagination = function(){
               dom.pag.live('click',function(){
                   var urlAction=$("#formSearchItem").attr("action");
                   var data = $('#formSearchItem').serialize();
                   dataPage = data + '&page=' + $(this).attr('rel');
                   searchItem(dataPage,urlAction);
               });
            },
            paginationEspecial = function(){
               $(".pag").live('click',function(){
                   var urlAction =$("#formSearchEspecial").attr("action");
                   var data = $("#formSearchEspecial").serialize();
                   dataPage = data + '&page=' + $(this).attr('rel');
                   searchItem(dataPage,urlAction);
               });
            },
            fillDataSelect = function(typeItem){
                var dataSelect = _dataModulo,
                    htmlOptionResult;
                for(var i=0;i<dataSelect.length;i++){   
                    if(dataSelect[i].K_ID_TIPO_MODULO == typeItem){
                            htmlOptionResult = htmlOptionResult + "<option value='"+dataSelect[i].K_ID_MODULO+"'>"+dataSelect[i].K_TITULO+"</option>";
                            //$("#a_modulo").append("<option value='"+dataSelect[i].K_ID_MODULO+"'>"+dataSelect[i].K_TITULO+"</option>")   
                    }
                }
                htmlOptionResult = "<option value=''>Seleccione una opción</option>"+htmlOptionResult
                $("#a_modulo").append(htmlOptionResult);
            },
            placeHolder = function(){
                $("#a_link").attr('placeholder','ejem:http://www.ejemplo.com');
            }
            // run method
            showOptionsForm();
            editModal();
            addModal();
            placeHolder();
            if(yOSON.action == "especial"){
               paginationEspecial(); 
            }else{
               pagination();
            }
        },
        other: function(){},
        destroy: function(){}
    };
},['libs/plugins/min/jquery.fancybox.js']);





