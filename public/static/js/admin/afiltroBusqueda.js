$(function(){
    var afbformSearch = '#afbFormSearch',
        afbBtnSearch = '#afbBtnSearch',
        afbResultAjax = '#afbResultAjax';
    var afbBtnModal = '.afbBtnModal',
        myModal = '#myModal',
        afbform = '#afbForm',
        afbId = '#f_id',        
        afbBtnGuardar='#abtnGuardar';
    var afb_fecIni = '#a_fecIni',
        afb_fecFin = '#a_fecFin';
    //->>paginacion
    var pag = '.pag';
    var afiltroBusqueda = {
        init: function () {
            afiltroBusqueda.fntBusqueda();
            afiltroBusqueda.fntLoadFecIniFin();
            afiltroBusqueda.fntModalAccion(afbBtnModal);
            afiltroBusqueda.fntModalAccion(afbBtnGuardar);
        },
        fntBusqueda : function(){
            $(afbBtnSearch).bind('click', function(){
                afiltroBusqueda.cleanEvents();
                var data = $(afbformSearch).serialize();
                afiltroBusqueda.find(data);
            });
        },
        paginacion : function(){
            afiltroBusqueda.cleanEvents();
            var data = $(afbformSearch).serialize();
            data = data + '&page=' + $(this).attr('rel');
            afiltroBusqueda.find(data);
            return false;
        },
        find: function(data) {
            afiltroBusqueda.cleanEvents();
            $(afbResultAjax).html('&nbsp;');
            $(afbResultAjax).addClass("loading");
            var urlAction=$(afbformSearch).attr("action");
            $.ajax({
                url : urls.admin+urlAction,
                type : 'GET',
                dataType : 'html',
                data : data,
                success : function (html) {
                    afiltroBusqueda.cleanEvents();
                    $(afbResultAjax).removeClass("loading").append(html);
                    $(pag).bind('click', afiltroBusqueda.paginacion);
                }
            })
        },
        cleanEvents : function () {
            $(pag).unbind();
        },
        fntLoadFecIniFin : function () {
            var vigencia = $(afb_fecIni+','+afb_fecFin).datepicker({
                changeMonth: true,
                changeYear: true,
//                minDate: -0,
                maxDate: -0,
                showMonthAfterYear: false,
                onSelect: function( selectedDate ) {
                    var option = ('#'+this.id == afb_fecIni) ? "minDate" : "maxDate",
                    instance = $(this).data("datepicker"),
                    dateIF = $.datepicker.parseDate(
                        instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                        selectedDate, instance.settings );
                    vigencia.not( this ).datepicker( "option", option, dateIF );
                }
            });
        },
        fntModalAccion : function(A){
            $(A).live('click', function(){
                if ($(this).attr("disabled")!='disabled'){
                    var contenido=$(myModal + ' #contenModal'),
                        T = $(this),
                        $html = "",
                        tipo = "",
                        data = "",
//                        csrf = $(myModal + "#csrf").text(),
                        form = '',
                        url = urls.admin+$(afbformSearch).attr("form-edit");
                    var id=T.attr("data-id");

                    if('#'+T.attr('id')==afbBtnGuardar) {
                        tipo = 'POST';
                        form = $(afbform).serialize();
//                        id = $(afbId).val();
                        data = form;
                        //$(form).serialize() + '&csrf=' + valcsrf,
                    } else {
                        tipo = 'GET';
                        data = 'f_id='+id;
                    }

                    contenido.html("&nbsp;");
//                    $(myModal).modal('show');
                    $(myModal).modal({show:true}).css({
                        'width': '800px',
                        'margin-left': function () {
                            return -($(this).width() / 2);
                        }
                        ,'top': '40%'
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
                                    contenido.html(afiltroBusqueda._afntMsgAjax(0, $msg));
                                    afiltroBusqueda.closeWindowTime();
                                } else { //->ERROR
                                    contenido.html(afiltroBusqueda._afntMsgAjax(1, $msg));
                                    afiltroBusqueda.closeWindowTime();
                                }
                            }
                            catch(err)
                            {
                                contenido.html(afiltroBusqueda._afntMsgAjax(1, ''));
                                afiltroBusqueda.closeWindowTime();
                            }
                        }
                    });
                }
            })
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
    afiltroBusqueda.cleanEvents();
    afiltroBusqueda.init();    
    $(afbBtnSearch).trigger('click');
});

