yOSON.AppCore.addModule("validate",function(a){return{init:function(b){var c=b.form.split(",");$.each(c,function(d,f){var e={};for(var g in requires[f]){e[g]=requires[f][g]}$(f).validate(e)})},destroy:function(){}}},["libs/plugins/min/jqValidate.js"]);yOSON.AppCore.addModule("show-modal-add",function(a){return{init:function(b){$("#addRegister").bind("click",function(){$.fancybox({href:yOSON.baseHost+yOSON.module+"/"+$("#apfbFormSearch").attr("form"),onComplete:function(){yOSON.AppCore.runModule("validate",{form:"#apfbForm"});$("#apbtnGuardar").parent().addClass("aFormLine-center");$("#f_orden").attr("placeholder","Ingrese un numero de dos digitos");$("#f_cerrar").bind("click",function(){$.fancybox.close()})}})})},destroy:function(){}}},["libs/plugins/min/jqValidate.js","libs/plugins/min/jquery.fancybox.js"]);yOSON.AppCore.addModule("ap-filtro-busqueda",function(a){return{init:function(){var i="#apfbFormSearch",f="#apfbBtnSearch",h="#apfbResultAjax",e=".apfbBtnOpenModal",g="#f_cerrar",b="#apbtnGuardar",d=".pag";var c={fntSearchForm:function(){c.fntSearchCleanEvents();var j=$(i).serialize();c._fntSearchFind(j)},fntSearchCleanEvents:function(){$(d).unbind();$(e).unbind()},_fntSearchFind:function(k){$(f).attr("disabled","disabled");c.fntSearchCleanEvents();$(h).html("&nbsp;");$(h).addClass("loading");var j=$(i).attr("action");$.ajax({url:yOSON.baseHost+yOSON.module+j,type:"GET",dataType:"html",data:k,success:function(m,l){c.fntSearchCleanEvents();$(h).removeClass("loading").append(m);$(d).bind("click",c._fntSearchPagination);c._fntModalAccion(e);$(f).removeAttr("disabled")}});$(document).ajaxError(function(n,m,o,l){if(m.status==401){window.location.href="auth"}})},_fntSearchPagination:function(){c.fntSearchCleanEvents();var j=$(i).serialize();j=j+"&page="+$(this).attr("rel");c._fntSearchFind(j);return false},_fntModalAccion:function(j){$(j).bind("click",function(){var k=$(this).attr("data-id");$.fancybox({href:yOSON.baseHost+yOSON.module+"/"+$(i).attr("form")+"?f_id="+k,onComplete:function(){$("#apbtnGuardar").parent().addClass("aFormLine-center");yOSON.AppCore.runModule("validate",{form:"#apfbForm"});yOSON.AppCore.runModule("validate-document");yOSON.AppCore.runModule("data-ubigeo");c._fntModalClose()}})})},_fntModalCleanEvents:function(){$(g).unbind();$(b).unbind()},_fntModalCloseTime:function(){setTimeout(function(){$.fancybox.close()},1500)},_fntModalClose:function(){$(g).bind("click",function(){$.fancybox.close()})}};c.fntSearchCleanEvents();c.fntSearchForm()},destroy:function(){}}},["libs/plugins/min/jqValidate.js","libs/plugins/min/jquery.fancybox.js"]);yOSON.AppCore.addModule("funcionality",function(b){var d={starDate:"#a_fecIni",endDate:"#a_fecFin"},c=function(){var e=$(d.starDate+","+d.endDate).datepicker({changeMonth:true,dateFormat:"dd/mm/y",changeYear:true,maxDate:-0,showMonthAfterYear:false,onSelect:function(g){var i=("#"+this.id==d.starDate)?"minDate":"maxDate",f=$(this).data("datepicker"),h=$.datepicker.parseDate(f.settings.dateFormat||$.datepicker._defaults.dateFormat,g,f.settings);e.not(this).datepicker("option",i,h)}})},a=function(){$("#a_orden").attr("placeholder","Ingrese un digito de dos cifras")};return{init:function(e){c();a()},destroy:function(){}}},["libs/ui/min/jquery.ui.core.js","libs/ui/min/jquery.ui.datepicker.js","libs/ui/i18n/min/jquery.ui.datepicker-es.js"]);yOSON.AppCore.addModule("validate-document",function(a){var b={inputTipoDocumento:$("#a_tipDoc"),inputNroDoc:$("#a_nDoc")};_cambioTipoDoc=function(){var c=$(this);switch($.trim(c.attr("value"))){case"05":b.inputNroDoc.attr("maxlength",8);b.inputNroDoc.rules("remove");b.inputNroDoc.rules("add",{required:true,dni:true,digits:true,messages:{required:"Ingrese su Nro. de Documento.",dni:"Ingrese su Nro. de Documento.",digits:"Ingrese solo digitos."}});break;case"06":b.inputNroDoc.removeAttr("maxlength");b.inputNroDoc.attr("minlength",3);b.inputNroDoc.rules("remove");b.inputNroDoc.rules("add",{required:true,minlength:3,messages:{required:"Ingrese su Pasaporte",minlength:"Mínimo 3 caractéres"}});break;case"07":b.inputNroDoc.attr("maxlength",11);b.inputNroDoc.rules("remove");b.inputNroDoc.rules("add",{required:true,ruc:true,messages:{required:"Ingrese su RUC",ruc:"Ingrese su RUC"}});break;case"":b.inputNroDoc.removeAttr("maxlength");c.rules("remove");b.inputNroDoc.rules("remove");b.inputNroDoc.val("");break}$("#apfbFormSearch").valid()},_keyValidate=function(){b.inputNroDoc.bind("keyup",function(c){if(b.inputTipoDocumento.val()==""){b.inputTipoDocumento.rules("add",{required:true,messages:{required:"Seleccione un tipo"}});$("#apfbFormSearch").valid()}})},bindEvents=function(){b.inputTipoDocumento.bind("change",_cambioTipoDoc).trigger("change");_keyValidate()};return{init:function(c){bindEvents()},destroy:function(){}}},["libs/plugins/min/jqValidate.js"]);yOSON.AppCore.addModule("data-ubigeo",function(a){function b(h,e,f,c,d,g){$(h).bind("change",function(){var l=$(this).val(),i=$(f).val();var k=$.grep(c.data,function(o,m){if(i==undefined){return(o[c.nameFatherId]==l)}else{return(o[c.nameFatherId]==l&&o[c.nameFatherOtherId]==i)}});$(e).html('<option value="">Cargando...</option>');var j='<option value=""></option>';$(k).each(function(m){j+='<option value="'+this[c.nameId]+'">'+this[c.nameValue]+"</option>"});$(e).html(j);$(d).html("");$(d).next().val("");if(g&&typeof(g)==="function"){g()}})}return{init:function(c){b("#f_departamento","#f_provincia","",{data:_ubiProv,nameFatherId:"ID_DPTO",nameId:"ID_PROV",nameValue:"NOM"},"#f_distrito",function(){});b("#f_provincia","#f_distrito","#f_departamento",{data:_ubiDist,nameFatherId:"ID_PROV",nameFatherOtherId:"ID_DPTO",nameId:"ID_UBIGEO",nameValue:"NOM"},function(){$("#categoriaId3 :nth-child(1)").attr("selected","selected").trigger("change")});$("#ubication_departement").bind("change",function(){if($(this).val()==15){$("#ubication_district").parent().parent().slideDown("slow")}else{$("#ubication_district").parent().parent().slideUp("slow");$("#ubication_province").bind("change",function(){$("#ubication_district").children(2).attr("selected",true)})}})},other:function(){},destroy:function(){}}});