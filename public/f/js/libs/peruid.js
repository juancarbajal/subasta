/*----------------------------------------------------------------------------------------------------
 *@PERUID :Codigo para poder generar lightbox (tener cuidado con las rutas de no funcionar
 *o no ser correcta las url que se llaman por ajax no se levantaran los lightbos)
 *->@NOTA : Se cargara este archivo desde la url: http://pre.peruid.pe/f/scripts/peruid.js
 *          devido a que este archivo esta en constante actualizacion.
 **///------------------------------------------------------------------------------------------------
eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('g n=p(){g 5,7;2(6.a&&6.k){5=6.j+6.o;7=6.a+6.k}4 2(0.1.i>0.1.h){5=0.1.m;7=0.1.i}4{5=0.1.l;7=0.1.h}g 3,9;2(e.a){2(0.8.b){3=0.8.b}4{3=e.j}9=e.a}4 2(0.8&&0.8.d){3=0.8.b;9=0.8.d}4 2(0.1){3=0.1.b;9=0.1.d}2(7<9){c=9}4{c=7}2(5<3){f=5}4{f=3}q[f,c]}',27,27,'document|body|if|windowWidth|else|xScroll|window|yScroll|documentElement|windowHeight|innerHeight|clientWidth|pageHeight|clientHeight|self|pageWidth|var|offsetHeight|scrollHeight|innerWidth|scrollMaxY|offsetWidth|scrollWidth|size|scrollMaxX|function|return'.split('|'),0,{}))
var peruid = function(par){
	/* callback, path_base, path_receiver, path_portal, path_proxy */
	var t = this;
	t.logued = null;
	t.param = par;
	/*$('body').append*/$('#user-options').before('<div id="peruid_modal" style="display:none"><div class="pid_back"></div><div class="pid_frame" style="top:-800px"><a class="pid_close" href="#cerrar">Cerrar</a><iframe allowtransparency="true" id="pid_iframe" width="510px" height="284px" frame scrolling="no"></iframe></div></div> ');
	var m = $('div#peruid_modal').find('a.pid_close').click(function(e){ e.preventDefault(); m.hide().find('#pid_iframe').attr('src','') }).end();	
	t.add_modal = function(o){

		if (typeof o != 'object') o = $('.go_peruid');
		o.click(function(e){
			e.preventDefault();
			var s = size();

			//back frame
			m.find('div.pid_back').css({ width: s[0] + 'px', height: s[1] + 'px' } ).end().show();
			var rd = $(this).attr('data-redirect'); rd = rd?('&redirect='+encodeURIComponent(rd)):'';
			
			//server
			if (this.href.indexOf('/registro/')>0) m.find('iframe').attr('src',par.path_base+'registros/registrate/'+par.path_portal+'?callback='+document.location.pathname+'&proxy='+encodeURIComponent(par.path_proxy)+rd);
			else m.find('iframe').attr('src',par.path_base+'registros/loggin/'+par.path_portal+'?callback='+document.location.pathname+'&proxy='+encodeURIComponent(par.path_proxy)+rd);
			t.center();
		});		
	}
	t.remove_modal = function(o){
		if (typeof o != 'object') o = $('.go_peruid');
		o.unbind('click');
	}
	t.center = function(_p){
		_p = _p || {};
		_p.init = _p.init || false;
		_p.height = parseInt(_p.height||0);
		_p.width = parseInt(_p.width||0);
		var _pf = $('div.pid_frame',m);
		var _h = _p.height>0?_p.height:_pf.height();
		var _w = _p.width>0?_p.width:_pf.width();
		var _wh = $(window).height();
		var _t = _wh - _h; _t = _t>0?(_t/2):0;
		var _l = $(window).width()  - _w; _l = _l>0?(_l/2):0;
		_pf.css( { left: _l + 'px', position:(_p.height>_wh?'absolute':'fixed') } ).show();
		_pf.find('#pid_iframe').css({height:_h});
		_pf.animate({top: _t-10, left:_l, height:_h, width: _w});
	}
	t.proxy = function (_p){
		var _a = {};
		if (_p.href.split("?").length>1){
			var _h = _p.href.split("?")[1].split("&");
			var _l = _h.length
			for (var _i = 0; _i<_l; _i++){
					var _j = _h[_i].split('='); 
					_a[_j[0]] = _j[1]
			}			
		}
		if (_a["type"] == "resize") t.center( {height: _a["height"], width: _a["width"]  });		
		else if(_a["type"] == "reload") window.location.reload();
		else if(_a["type"] == "redirect") window.location.replace(_a["to"]);
		/*
		var h = _p.hash.replace('#','');
		if (h=='reload') window.location.reload();
		else if (h.substring(0,8)=='redirect') window.location.replace(h.replace('redirect=',''));
		else {
			h = h.split('x');
			t.center( {height: h[0], width: (h.length>1?h[1]:0) });		
		}
		*/
	}
	// proceso de sincronizacion de servidores
	var sync = function(){
		var tk = unescape(document.cookie).match(/pid_token=([0-9a-z]+)/);
		tk = (tk&&tk[1])?tk[1]:'';
	        Console.log(par.path_base+'index.php/auth/token/'+par.path_portal+'/'+tk+'?path='+document.location.pathname+'&callback=?');
		//$.getJSON(par.path_base+'index.php/auth/token/'+par.path_portal+'/'+tk+'?callback=?',
		$.getJSON(par.path_base+'index.php/auth/token/'+par.path_portal+'/'+tk+'?path='+document.location.pathname+'&callback=?',
			function (js){
				if (js.recursive){
					document.cookie = 'pid_token=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
					sync();
					return;
				}
				t.param.token = js.token;
				$.post(par.path_receiver+'?v'+Math.ceil(Math.random()*10000), {token: js.token, version:js.version }, 
					function(js){
						var _c, _cc;
						t.param.id_user = (unescape(document.cookie).match( /ecoid";(.*?);/i )||['','"0'])[1].split('"')[1];
						t.param.avatar_user = (unescape(document.cookie).match( /avatar_url";(.*?);/i )||['','"0'])[1].split('"')[1];
						if ((js.usuario !== null) && js.sesion){
							t.logued = true;
							_c = 1; _cc = 'Solo existe sesion en peruid, inicio forzado';
						}else if ((js.usuario !== null) && js.sesion==false){
							t.logued = false;
							document.cookie = 'pid_token=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
							_c = 3; _cc = 'Solo existe sesion en 2do servidor, logout forzado';
						}else if ((js.usuario == null) && js.sesion==false){
							t.logued = false;
							document.cookie = 'pid_token=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
							_c = 4; _cc = 'No existe sesion en ambos servidores';
						}else {
							t.logued = true;
							_c = 2; _cc = 'Existe sesion en ambos servidores';
						}
						if (typeof par.callback == 'function') par.callback({o:t,j:js,code:_c,msg:_cc});
					}, "json"
				)
			}
		)
	}
	sync();
	var css = document.createElement('link'); 
	css.type = 'text/css';
	css.rel = 'stylesheet';
	css.media = 'screen';
	css.href = par.path_base+"f/css/modal.css";
	document.getElementsByTagName('head')[0].appendChild(css);
	document.onkeydown = function(e){
		var evt = e || window.event;
		if (evt.keyCode == 27 ){
			m.hide().find('#pid_iframe').attr('src','');
		}
	}
}

/*----------------------------------------------------------------------------------------------------------
 * @Session      : Peru-Id
 * @Dependencias : http://pre.peruid.pe/f/scripts/peruid.js
 * @Description  : Verifica la la veracidad de inicio de session en Peru-Id
 *//*------------------------------------------------------------------------------------------------------*/
Console.log(yOSON.peruID);

var _so = {w:(window.top||window), n:'[go_comentarios]', t:1200000, l:document.location.href}
var _sw = function(){ var _t=$('#go_comentar'); if(_t.hasClass('go_peruid')) _t.click(function(){_so.w.name+=_so.n;}); }
if (_so.w.name.indexOf(_so.n)>=0){document.location.href = '#comentarios';_so.w.name = _so.w.name.replace(_so.n,'');}

if (typeof peruid=='function') {pid=new peruid({/* Nueva Version */
        path_base:    (typeof path_base     == 'string') ? path_base     : yOSON.peruID.urlBase ,
        path_receiver:(typeof path_receiver == 'string') ? path_receiver : yOSON.peruID.urlReceiver,
        path_portal:  (typeof path_portal   == 'string') ? path_portal   : yOSON.peruID.urlPortal,
        path_proxy:   (typeof path_proxy    == 'string') ? path_proxy    : yOSON.peruID.urlProxy,
        callback: function(_p){
            var THAT = this;
            Console.log(_p);Console.log('--->'+_p.code);
            var cartHtml = function(cant){ return '<a title="Carrito de Compras - PlazaTop" href="'+yOSON.baseHost+'carrito/paso1" style="display:none;">Carrito de compras<br><strong class="cart-items">'+cant+'</strong>&nbsp;&nbsp;Productos</a>'; };
            $('#frm-step2 button[type="submit"]').attr('disabled',false);
            if (_p.code==1){ /*En sesion*/
                var _h = '<div class="login-and-register">Bienvenido, '+_p.j.usuario.nombre+' &nbsp;'+
                            '<a rel="bienvenido" href="'+yOSON.baseHost+'cuenta/inicio" title="bienvenido">'+
                            '<strong>Tu Cuenta</strong></a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>'+
                            '<a rel="Salir" title="Salir" href="'+THAT.path_base+'logout/'+THAT.path_portal+'?path=/logout.html">Salir</a>'+
                            '<span>&nbsp;|&nbsp;</span><span class="follow">Síguenos en:</span><a target="_blank" href="http://www.facebook.com/ECPruebas" class="social facebook"></a><a target="_blank" href="https://twitter.com/portal_pruebas" class="social twitter"></a><a target="_blank" href="https://plus.google.com/u/0/b/115241876115305665552/115241876115305665552/" class="social google"></a><div>';
                $('.login-and-register').replaceWith(_h);  
                $('.alert-error').css('display','none');/* Extra: Ocultar mensaje error si es que estoy en session.*/
                pid.remove_modal(); /*window.location.href=window.location.href;*/    
                $('.nav-cart .btn-cart a').fadeIn(450);
                /*EXEPCION*/
                var isComoCompr=(yOSON.module=='yMvY0OPgoQ=='&&yOSON.controller=='x9Pl'&&yOSON.action=='2svk');
                if(isComoCompr)pid.add_modal();

            }
            if(_p.code==3){ /* Cerrando session*/
                var _h = '<div class="login-and-register">'+
                            '<a rel="login" title="Ingresar" class="go_peruid" href="'+THAT.path_base+'login/'+THAT.path_portal+'">Ingresar</a>'+
                            '<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>'+
                            '<a rel="register" title="Regístrate ahora y entérate de las últimas ofertas y dctos." target="_blank" class="go_peruid" href="'+THAT.path_base+'registro/'+THAT.path_portal+'?path='+encodeURIComponent(window.location.pathname)+'">Regístrate ahora y entérate de las últimas ofertas y dctos.</a>'+
                            '<span>&nbsp;|&nbsp;</span><span class="follow">Síguenos en:</span><a target="_blank" href="http://www.facebook.com/ECPruebas" class="social facebook"></a><a target="_blank" href="https://twitter.com/portal_pruebas" class="social twitter"></a><a target="_blank" href="https://plus.google.com/u/0/b/115241876115305665552/115241876115305665552/" class="social google"></a><div>';
                $('.login-and-register').replaceWith(_h); pid.add_modal(); $('.btn-register').fadeIn(450);
                $.ajax({
                    url    :yOSON.baseHost+"closesession.html?"+new Date().getTime(), 
                    success:function(rpta){ Console.log("====>"+rpta); if(rpta=="true"){$('.nav-cart .btn-cart').html(cartHtml("0")).find('a').fadeIn(450);} } 
                }); /*window.location.href=window.location.href;*/
            }
            if(_p.code==4){ /*Sin session*/
                pid.add_modal();  /*Creando modal*/
                $('.btn-register, .nav-cart .btn-cart a').fadeIn(450); /*Ocultando boton de registro*/
                $('.validate-login').fadeIn(450);
                $('#frm-step2').bind('submit', function(e){/*Mostar login PeruId*/
                    e.preventDefault(); Console.log($('a[rel="login"]')); $('a[rel="login"]').trigger('click');
                });
            }
            $('.login-and-register').css('color','#fff').css('display','block'); /*Mostrar panel de registro y login superior*/
        }
    }); /*$( function(){  pid.add_modal(); });*/
}
