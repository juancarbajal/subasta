/*  iframeUp v0.2 - jQuery iframe Submit plugin
	(c) 2012 Jan Sanchez - @jansanchez - Perucomsultores - yosÓn devs
	License: http://www.opensource.org/licenses/mit-license.php
*/
/*
requires:
	
*/

(function( $ ){

	var methods = {
		init : function( opts ) {
			opts.id = 'frm_' + Math.floor(Math.random() * 99999);
			var d = $('<div/>').html('<iframe style="display:none" src="about:blank" id="'+opts.id+'" name="'+opts.id+'" onload="$.fn.iframeUp(\'load\',\''+opts.id+'\')" onunload="$.fn.iframeUp(\'unload\',\''+opts.id+'\')"></iframe>');
			$('body').append(d);

			var ifrm = document.getElementById(opts.id);			
			if (opts && typeof(opts.onSuccess) == 'function') {
				ifrm.onSuccess = opts.onSuccess;
			}
			if (opts && typeof(opts.onComplete) == 'function') {
				ifrm.onComplete = opts.onComplete;
			}
			return opts.id;
		},
		form : function(frm, name) {
			$('#'+frm).attr('target', name);
		},
		submit : function(options) {
			var defaults = {
				frm: 'frm_add',
				submit: true,
				loader: true,
				loading: function (opts){
					$('.options-image .third-columm').append('<img id="loader-photo" src="'+yOSON.baseHost+'f/img/loaderKotear.gif" style="width:20px;height:20px;float:right;margin-left:0px">');
					$('#addProductImage').attr('disabled','disabled');
				},
				afterSend: function (opts){
					// resetear input file
					//document.getElementById(opts.frm).reset();
				},
				onSuccess: function(html, ifrm, id){
					setTimeout(function(){
						$('#' + id).parent().remove();
						$.fn.iframeUp('unload', id, html);
						$('.options-image .third-columm #loader-photo').fadeOut('slow',function(){$(this).remove()});
						$('#addProductImage').removeAttr('disabled');
						if($('.msg-error-images').length != 0){
							$('.msg-error-images').fadeOut('slow',function(){$(this).remove()});
						}
					}, 50);
				}
			},
			opts = $.extend({}, defaults, options);

			opts.isIE = !$.support.opacity && !$.support.style;/*if ie*/
			opts.isIE6 = opts.isIE && !window.XMLHttpRequest;/*if ie6*/

			$.fn.iframeUp('form', opts.frm, $.fn.iframeUp('init', opts));

			if (opts && typeof(opts.beforeSend) == 'function') {
				opts.beforeSend(opts);
			}

                        if (opts.submit==true) {
                             var valuePhotos = $('#ids_hidden_ad').val(),
                             arrayPhotos = $.trim(valuePhotos)!==''?valuePhotos.split('-'):[],
                             numberPhoto = arrayPhotos.length;
                             /* Valida la cantidad de fotos */  
                             if(numberPhoto == _cantImg){
                                 alert('test _cant');
                                 $('#addProductImage').attr('disabled','disabled');
                                 $.fancybox({
                                     'content' : '<div class="message-modal"><div class="title-gen"><h3 class="title-step"><span class="icon-right"></span><p>Mensaje</p></h3></div><p>Solo puedes ingresar <strong style="font-size:16px">'+_cantImg+'</strong> imagenes.</p></div>',
                                     'onClosed' : function(){
                                         $('#photo_product').val('');
                                         //$('#frm_add').attr('action',yOSON.baseHost+'usuario/publicacion/confirmar-publicacion');
                                     }
                                 });
                                 return false; 
                             /* Valida la extencion de fotos */
                             }else if( /(\.gif|\.jpg|\.png)/gi.test()){
                                 $.fancybox('<div class="message-modal"><div class="title-gen"><h3 class="title-step"><span class="icon-right"></span><p>Mensaje</p></h3></div><p>Solo puedes subir imagenes <strong style="font-size:16px">jpg</strong> , <strong style="font-size:16px">png</strong> o <strong style="font-size:16px">gif</strong> </p></div>');
                                 return false;
                              }else{
                                 document.getElementById(opts.frm).submit();
                              }
                         }

                         if (opts && typeof(opts.afterSend) == 'function') {
                                 opts.afterSend(opts);
                         }

                         if (opts.loader==true) {
                                 if (opts && typeof(opts.loading) == 'function') {
                                         opts.loading(opts);
                                 }
                         }

		},
		load : function(id) {
			
			var ifrm = document.getElementById(id);

			if (ifrm.contentDocument) {
				var doc = ifrm.contentDocument;
			} else if (ifrm.contentWindow) {
				 doc = ifrm.contentWindow.document;
			} else {
			 	 doc = window.frames[id].document;
			}	
			if (doc.location.href == "about:blank") {
				return;
			}
			if (typeof(ifrm.onSuccess) == 'function') {
				ifrm.onSuccess(doc.body.innerHTML, ifrm, id);	
			}
			if (typeof(ifrm.onComplete) == 'function') {
				ifrm.onComplete(doc.body.innerHTML, ifrm);
			}
		},
		unload : function(id){

		}
	};
  
  $.fn.iframeUp = function( method ) {    
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.iframeUp' );
    }
  };

})(jQuery);