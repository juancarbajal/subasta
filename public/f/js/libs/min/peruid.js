eval(function(h,b,i,d,g,f){g=function(a){return a.toString(b)};if(!"".replace(/^/,String)){while(i--){f[g(i)]=d[i]||g(i)}d=[function(a){return f[a]}];g=function(){return"\\w+"};i=1}while(i--){if(d[i]){h=h.replace(new RegExp("\\b"+g(i)+"\\b","g"),d[i])}}return h}("g n=p(){g 5,7;2(6.a&&6.k){5=6.j+6.o;7=6.a+6.k}4 2(0.1.i>0.1.h){5=0.1.m;7=0.1.i}4{5=0.1.l;7=0.1.h}g 3,9;2(e.a){2(0.8.b){3=0.8.b}4{3=e.j}9=e.a}4 2(0.8&&0.8.d){3=0.8.b;9=0.8.d}4 2(0.1){3=0.1.b;9=0.1.d}2(7<9){c=9}4{c=7}2(5<3){f=5}4{f=3}q[f,c]}",27,27,"document|body|if|windowWidth|else|xScroll|window|yScroll|documentElement|windowHeight|innerHeight|clientWidth|pageHeight|clientHeight|self|pageWidth|var|offsetHeight|scrollHeight|innerWidth|scrollMaxY|offsetWidth|scrollWidth|size|scrollMaxX|function|return".split("|"),0,{}));var peruid=function(d){var c=this;c.logued=null;c.param=d;$("#user-options").before('<div id="peruid_modal" style="display:none"><div class="pid_back"></div><div class="pid_frame" style="top:-800px"><a class="pid_close" href="#cerrar">Cerrar</a><iframe allowtransparency="true" id="pid_iframe" width="510px" height="284px" frame scrolling="no"></iframe></div></div> ');var a=$("div#peruid_modal").find("a.pid_close").click(function(f){f.preventDefault();a.hide().find("#pid_iframe").attr("src","")}).end();c.add_modal=function(f){if(typeof f!="object"){f=$(".go_peruid")}f.click(function(i){i.preventDefault();var g=size();a.find("div.pid_back").css({width:g[0]+"px",height:g[1]+"px"}).end().show();var h=$(this).attr("data-redirect");h=h?("&redirect="+encodeURIComponent(h)):"";if(this.href.indexOf("/registro/")>0){a.find("iframe").attr("src",d.path_base+"registros/registrate/"+d.path_portal+"?callback="+document.location.pathname+"&proxy="+encodeURIComponent(d.path_proxy)+h)}else{a.find("iframe").attr("src",d.path_base+"registros/loggin/"+d.path_portal+"?callback="+document.location.pathname+"&proxy="+encodeURIComponent(d.path_proxy)+h)}c.center()})};c.remove_modal=function(f){if(typeof f!="object"){f=$(".go_peruid")}f.unbind("click")};c.center=function(f){f=f||{};f.init=f.init||false;f.height=parseInt(f.height||0);f.width=parseInt(f.width||0);var i=$("div.pid_frame",a);var l=f.height>0?f.height:i.height();var h=f.width>0?f.width:i.width();var j=$(window).height();var k=j-l;k=k>0?(k/2):0;var g=$(window).width()-h;g=g>0?(g/2):0;i.css({left:g+"px",position:(f.height>j?"absolute":"fixed")}).show();i.find("#pid_iframe").css({height:l});i.animate({top:k-10,left:g,height:l,width:h})};c.proxy=function(f){var h={};if(f.href.split("?").length>1){var k=f.href.split("?")[1].split("&");var g=k.length;for(var j=0;j<g;j++){var i=k[j].split("=");h[i[0]]=i[1]}}if(h.type=="resize"){c.center({height:h.height,width:h.width})}else{if(h.type=="reload"){window.location.reload()}else{if(h.type=="redirect"){window.location.replace(h.to)}}}};var e=function(){var f=unescape(document.cookie).match(/pid_token=([0-9a-z]+)/);f=(f&&f[1])?f[1]:"";Console.log(d.path_base+"index.php/auth/token/"+d.path_portal+"/"+f+"?path="+document.location.pathname+"&callback=?");$.getJSON(d.path_base+"index.php/auth/token/"+d.path_portal+"/"+f+"?path="+document.location.pathname+"&callback=?",function(g){if(g.recursive){document.cookie="pid_token=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";e();return}c.param.token=g.token;$.post(d.path_receiver+"?v"+Math.ceil(Math.random()*10000),{token:g.token,version:g.version},function(j){var h,i;c.param.id_user=(unescape(document.cookie).match(/ecoid";(.*?);/i)||["",'"0'])[1].split('"')[1];c.param.avatar_user=(unescape(document.cookie).match(/avatar_url";(.*?);/i)||["",'"0'])[1].split('"')[1];if((j.usuario!==null)&&j.sesion){c.logued=true;h=1;i="Solo existe sesion en peruid, inicio forzado"}else{if((j.usuario!==null)&&j.sesion==false){c.logued=false;document.cookie="pid_token=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";h=3;i="Solo existe sesion en 2do servidor, logout forzado"}else{if((j.usuario==null)&&j.sesion==false){c.logued=false;document.cookie="pid_token=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";h=4;i="No existe sesion en ambos servidores"}else{c.logued=true;h=2;i="Existe sesion en ambos servidores"}}}if(typeof d.callback=="function"){d.callback({o:c,j:j,code:h,msg:i})}},"json")})};e();var b=document.createElement("link");b.type="text/css";b.rel="stylesheet";b.media="screen";b.href=d.path_base+"f/css/modal.css";document.getElementsByTagName("head")[0].appendChild(b);document.onkeydown=function(g){var f=g||window.event;if(f.keyCode==27){a.hide().find("#pid_iframe").attr("src","")}}};Console.log(yOSON.peruID);var _so={w:(window.top||window),n:"[go_comentarios]",t:1200000,l:document.location.href};var _sw=function(){var a=$("#go_comentar");if(a.hasClass("go_peruid")){a.click(function(){_so.w.name+=_so.n})}};if(_so.w.name.indexOf(_so.n)>=0){document.location.href="#comentarios";_so.w.name=_so.w.name.replace(_so.n,"")}if(typeof peruid=="function"){pid=new peruid({path_base:(typeof path_base=="string")?path_base:yOSON.peruID.urlBase,path_receiver:(typeof path_receiver=="string")?path_receiver:yOSON.peruID.urlReceiver,path_portal:(typeof path_portal=="string")?path_portal:yOSON.peruID.urlPortal,path_proxy:(typeof path_proxy=="string")?path_proxy:yOSON.peruID.urlProxy,callback:function(b){var a=this;Console.log(b);Console.log("--->"+b.code);var d=function(f){return'<a title="Carrito de Compras - PlazaTop" href="'+yOSON.baseHost+'carrito/paso1" style="display:none;">Carrito de compras<br><strong class="cart-items">'+f+"</strong>&nbsp;&nbsp;Productos</a>"};$('#frm-step2 button[type="submit"]').attr("disabled",false);if(b.code==1){var c='<div class="login-and-register">Bienvenido, '+b.j.usuario.nombre+' &nbsp;<a rel="bienvenido" href="'+yOSON.baseHost+'cuenta/inicio" title="bienvenido"><strong>Tu Cuenta</strong></a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a rel="Salir" title="Salir" href="'+a.path_base+"logout/"+a.path_portal+'?path=/logout.html">Salir</a><span>&nbsp;|&nbsp;</span><span class="follow">Síguenos en:</span><a target="_blank" href="http://www.facebook.com/ECPruebas" class="social facebook"></a><a target="_blank" href="https://twitter.com/portal_pruebas" class="social twitter"></a><a target="_blank" href="https://plus.google.com/u/0/b/115241876115305665552/115241876115305665552/" class="social google"></a><div>';$(".login-and-register").replaceWith(c);$(".alert-error").css("display","none");pid.remove_modal();$(".nav-cart .btn-cart a").fadeIn(450);var e=(yOSON.module=="yMvY0OPgoQ=="&&yOSON.controller=="x9Pl"&&yOSON.action=="2svk");if(e){pid.add_modal()}}if(b.code==3){var c='<div class="login-and-register"><a rel="login" title="Ingresar" class="go_peruid" href="'+a.path_base+"login/"+a.path_portal+'">Ingresar</a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span><a rel="register" title="Regístrate ahora y entérate de las últimas ofertas y dctos." target="_blank" class="go_peruid" href="'+a.path_base+"registro/"+a.path_portal+"?path="+encodeURIComponent(window.location.pathname)+'">Regístrate ahora y entérate de las últimas ofertas y dctos.</a><span>&nbsp;|&nbsp;</span><span class="follow">Síguenos en:</span><a target="_blank" href="http://www.facebook.com/ECPruebas" class="social facebook"></a><a target="_blank" href="https://twitter.com/portal_pruebas" class="social twitter"></a><a target="_blank" href="https://plus.google.com/u/0/b/115241876115305665552/115241876115305665552/" class="social google"></a><div>';$(".login-and-register").replaceWith(c);pid.add_modal();$(".btn-register").fadeIn(450);$.ajax({url:yOSON.baseHost+"closesession.html?"+new Date().getTime(),success:function(f){Console.log("====>"+f);if(f=="true"){$(".nav-cart .btn-cart").html(d("0")).find("a").fadeIn(450)}}})}if(b.code==4){pid.add_modal();$(".btn-register, .nav-cart .btn-cart a").fadeIn(450);$(".validate-login").fadeIn(450);$("#frm-step2").bind("submit",function(f){f.preventDefault();Console.log($('a[rel="login"]'));$('a[rel="login"]').trigger("click")})}$(".login-and-register").css("color","#fff").css("display","block")}})};