$(function(){
    var aFormCategoria = '#aFormCategoria',
//        aSelectCatn1 = '#a_catn1',
//        aSelectCatn2 = '#a_catn2',
//        aSelectCatn3 = '#a_catn3',
//        aSelectCatn4 = '#a_catn4',aSelectCat
        aSelectCat = '.aSelectCat',
        aBtnNew = '.abtnNC';
    var myModal = '#myModal',
        classBtnSave = 'abtnGuardar';
//    var afbformSearch = '#afbFormSearch',
//        afbBtnSearch = '#afbBtnSearch',
//        afbResultAjax = '#afbResultAjax';
//         
    var afbform = '#afbForm',        
        afbBtnGuardar='#abtnGuardar';
//        afbBtnModal = '.afbBtnModal',
//        myModal = '#myModal',
//        afbId = '#f_id',        
//        afbBtnGuardar='#abtnGuardar';
//    var afb_fecIni = '#a_fecIni',
//        afb_fecFin = '#a_fecFin';
    //->>paginacion
    var pag = '.pag';
    var aCategoria = {
        init: function () {
            aCategoria.afntCargarCategoriaNivel(aSelectCat);
            aCategoria.afntFormUpdate(aSelectCat);
            aCategoria.afntFormNuevo(aBtnNew);
            aCategoria.afntFormSave(afbBtnGuardar);
        },
        afntCargarCategoriaNivel : function(a){
            $(a).live('click', function(){
                var T = $(this),
                    v = $(this).val(),
                    b = $(this).attr('dependencia'),
                    n = $(this).attr('nivel');
                var clearPadreBtn = '#a_btnCaten'+(parseFloat(n)+2)+',#a_btnCaten'+(parseFloat(n)+3),
                    clearPadreSel = '#a_catn'+(parseFloat(n)+2)+',#a_catn'+(parseFloat(n)+3);
                $(clearPadreBtn).removeAttr('padre_id');
                $(clearPadreSel).html("");
                if(b != undefined){
                    var btnNC = (parseFloat(n)+1);
                    $('#a_btnCaten'+btnNC).attr('padre_id',v);
                    var B = $(b),
                        options = '<option value="">Cargando...</option>';
                    B.attr('padre_id', v).html(options);
                    $.post( urls.admin + '/categoria/json-cargar-niveles', {f_id: v, f_nivel: n}, function(data){
                        options = '';
                        $.each(data, function(index, item) {
                            options += '<option value="'+index+'">' +item +'</option>'
                        });
                        B.html(options);
                    }, "json");
                }                
            })
        },
        afntFormUpdate : function(a){
            $(a).live('dblclick', function(){
                var T = $(this),
                    b_v = 'f_id='+$(this).val();
                aCategoria._afntModalAccion(T, b_v);
                
            });
        },
        afntFormNuevo : function(a){
            $(a).live('click', function(){
                var T = $(this),
                    T_n = $(this).attr("nivel"),
                    T_padre_id = $(this).attr("padre_id");
                if(T_padre_id==undefined){
                    alert('Seleccione Categoria nivel '+( parseFloat(T_n)-1))
                } else {
                    var data = 'f_nivel='+ T_n+'&f_padre_id='+T_padre_id;
                    aCategoria._afntModalAccion(T, data);
                }
            });
        },
        afntFormSave : function(a){
            $(a).live('click', function(){
                var T = $(this);
                aCategoria._afntModalAccion(T, '');                
            });
        },
        _afntModalAccion : function(T, b_v){
//            $(a).live('click', function(){
//                if ($(this).attr("disabled")!='disabled'){
                    var contenido=$(myModal + ' #contenModal'),
//                        T = $(this),
                        tipo = 'GET',
                        data = '',
                        form = '',
                        url = urls.admin+$(aFormCategoria).attr("action");
//                    var b;
                    if(b_v!=''){
                        data = b_v;
                    }
                    
                    if(T.hasClass(classBtnSave)) {
                        tipo = 'POST';
                        form = $(afbform).serialize();
                        data = form;
                    }
                    
                    contenido.html("&nbsp;");
                    $(myModal).modal({show:true}).css({
                        'width': '600px',
                        'margin-left': function () {
                            return -($(this).width() / 2);
                        }
                        ,'top': '50%'
                    });
                    contenido.addClass("loading");

                    $.ajax({
                        'url' : url,
                        'type' : tipo,
                        'dataType' : 'html',
                        'data' : data,
                        'success' : function(msg) {
                            contenido.removeClass("loading");
                            try
                            {
                                var $isData = $(msg).attr('isData'),
                                    $msg = $(msg).attr('msg');
                                if ($isData == '-1') { //->NORMAL
                                    contenido.html(msg);
                                } else if ($isData == '0') { //->OK
                                    contenido.html(aCategoria._afntMsgAjax(0, $msg));
                                    aCategoria.closeWindowTime();
                                    aCategoria._fntClearSelect();
                                } else { //->ERROR
                                    contenido.html(aCategoria._afntMsgAjax(1, $msg));
                                    aCategoria.closeWindowTime();
                                }
                            }
                            catch(err)
                            {
                                contenido.html(aCategoria._afntMsgAjax(1, ''));
                                aCategoria.closeWindowTime();
                            }
                        }
                    });
//                }
//            })
        },
        _fntClearSelect: function (){
//            $(aSelectCat).html("");
            location.href=urls.admin+'/categoria';
        },
        closeWindowTime: function (){
            setTimeout(
                function(){
                    $(myModal).modal('hide');
                },
                1500
            );
        },
        _afntMsgAjax: function (tipo, msg){
            var $alert = 'error', $msg = "Error: No se pudo guardar.";
            if(tipo==0){
                $alert = 'info';
                $msg = "Éxito: Datos guardados correctamente.";
            }
            $msg = (msg!='')?msg:$msg;
            var $html ='<div class="alert alert-'+$alert+'">';
            $html +='<p class="maring0">'+$msg+'</p>';
            $html +='</div>';
            return $html;            
        }
    }
    aCategoria.init();
});

