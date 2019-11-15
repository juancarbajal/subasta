var intervalID, KotearSettings = null;

(function($){
	// Global settings
	KotearSettings = {
		item_class : '#search-result .item',
		results_panel_id : '#search-result',
		loading_box_id : '#itemsloading',
		images_url : 'http://img.kotear.pe',
		image_max_upload : 6
	};
	
	// Public Access
	$.Kotear = {
		init : function(_s){
			KotearSettings = $.extend(KotearSettings, _s);
		},
		validarRegistro : function(){
			$("#register").validate({
				rules: {
					apodo: {required : true, minlength: 2},
					email: {required : true, email: true},
					emailRep: {required : true,  equalTo: "#email"},
					clave: {required : true, minlength: 5},
					claveRep: {required : true, minlength: 5, equalTo: "#claveRep"},
					nombre: {required : true},
					apellido: {required : true},
					departamento: {required : true},
					ciudad: {required : true},
					codigoArea: {required : true},
					telefono: {required : true},
					palabraSeguridad: {required : true},
					condiciones: "required"
				},
				messages: {
					apodo: {required : "Ingrese su apodo", minlength: ""},
					email: {required : "Ingrese su e-mail", email: "El e-mail es invalido"},
					emailRep: {required : "Repita su e-mail",  equalTo: "El e-mail no es igual al email"},
					clave: {required : "Ingrese la clave", minlength: ""},
					claveRep: {required : "Repita la clave", minlength: "", equalTo: "La clave repetida no es igual que la clave"},
					nombre: {required : "Ingrese su Nombre"},
					apellido: {required : "Ingrese su Apellido"},
					departamento: {required : "Selecciones la zona/provincia"},
					ciudad: {required : "Ingrese su localidad"},
					codigoArea: {required : "Ingrese el código télefonica de la ciudad"},
					telefono: {required : "Ingrese su número de télefono"},
					palabraSeguridad: {required : "Ingrese el valor de la izquierda"},
					condiciones: {required : "No ha aceptado los Términos y Condiciones de Uso" }
				}
			});
		}
	};
	
	$.extend( $.validator.defaults, { errorElement: "em" } );
	
	/* Form cambio de datos */
	var triggers = $("a.cmd").overlay({ 
		expose: { 
			color: '#333', 
			loadSpeed: 200, 
			opacity: 0.9 
		},
		top: 'center',
		closeOnClick: false 
	});
	
	$("#change-passw").validate({
		submitHandler: function(form) {
			$('a[rel=#change-passw]').overlay().close();
			//form.submit();
		}
	});
	$("#change-email").validate({
		submitHandler: function(form) {
			$('a[rel=#change-email]').overlay().close();
			//form.submit();
		}
	});	
	
	$('#login').validate({
		submitHandler: function(form){
			//form.submit();
		}
	});
	
	$('#frmSuspended').validate({
		submitHandler: function(form){
			$('a[rel=#frmSuspended]').overlay().close();
			//form.submit();
		}
	});
	$('#frmsendtoafriend').validate({
		submitHandler: function(form){
			$('a[rel=#frmsendtoafriend]').overlay().close();
			//form.submit();
		}
	});
	
	var $recover_passw = $('#recover-passw');
	$recover_passw.validate({
		submitHandler: function(form){
			//$.post( recover_passw.get(0).action , $recover_passw.serialize(), function(data){ alert("Data Loaded: " + data); } );
			$('a[rel=#recover-passw]').overlay().close();
			return form.preventDefault();
			//form.submit();
		}
	});
	
	
	
	$('#pub_step1').validate({
		submitHandler: function(form){
			//form.submit();
		}
	});
	
	
	
	function prodscrolls1_initCallback(carousel) {
		$('#prodscrolls1 .next').bind('click', function() {	carousel.next();	return false; });
		$('#prodscrolls1 .prev').bind('click', function() {	carousel.prev();	return false; });
	};
	function prodscrolls2_initCallback(carousel) {
		$('#prodscrolls2 .next').bind('click', function() {	carousel.next();	return false; });
		$('#prodscrolls2 .prev').bind('click', function() {	carousel.prev();	return false; });
	};

	$('#prodscrolls1').jcarousel({
		scroll: 1,
		initCallback: prodscrolls1_initCallback,
		buttonNextHTML: null,
		buttonPrevHTML: null
    });
	$('#prodscrolls2').jcarousel({
		scroll: 1,
		initCallback: prodscrolls2_initCallback,
		buttonNextHTML: null,
		buttonPrevHTML: null
    });
	
	
	
	
})(jQuery);