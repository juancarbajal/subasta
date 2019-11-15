/*!
 *
 * yOSON requires
 *
 * Copyright(c) 2011 yOSON <evangelizandolaweb@gmail.com>
 * yOSON developers <evangelizandolaweb@groups.facebook.com>
 *
 * MIT Licensed
 */
 
var requires = {
    "#aceptarAdultos":{
        rules:{
           aceptarContenidoAdulto:{
                required:true
            }
        },
        messages:{
            aceptarContenidoAdulto:{
                required:"Debe aceptar los términos y condiciones"
            }
        }
    },
    "#frm-seleccion-medio-pago":{
        rules:{
            medioPago:{
                required:true
            }
        },
        messages:{
            medioPago:{
                required:"Debe de seleccionar un medio de pago"
            }
        }
    },
    "#change-email-valid" :{
        rules:{
            apodo : {
                required:true,
                rangelength:[5,20]
            },
            confirma : {
                required:true,
                rangelength:[5,200]
            }
        },
        messages:{
            apodo : {
                required : 'Este campo es requerido',
                rangelength : 'El valor debe estar entre 5 y 20 digitos.'
            },
            confirma : {
                required : 'Este campo es requerido',
                rangelength : 'El valor debe estar entre 5 y 200 digitos.' 
            }
        }
    },
    "#register-user-validate" :{
        rules:{
            apodo : {
                required:true,
                rangelength:[5,20]
            },
            confirma : {
                required:true,
                rangelength:[5,200]
            },
            clave : {
                required:true
            }
        },
        messages:{
            apodo : {
                required : 'Este campo es requerido',
                rangelength : 'El valor debe estar entre 5 y 20 digitos.'
            },
            confirma : {
                required : 'Este campo es requerido',
                rangelength : 'El valor debe estar entre 5 y 200 digitos.' 
            },
            clave : {
                required:'Debe de ingresar una clave'
            }
        }
    },
    "#frm_confirm":{
        rules:{
            document_type : {
                required : true
            },
            document_number : {
                required : true,
                myMinLength: [11],
                myMaxLength: [11],
                digits : true
            },
            customer_name : {
                required : true,
                noinjection : true,
                razonsocial: true
            },
            address : {
                required : true,
                noinjection : true,
                razonsocial: true
            }
        },
        messages:{
            document_type : {
                required : 'Este campo es requerido'
            },
            document_number : {
                required : 'Este campo es requerido',
                digits :'Ingrese solo números'
            },
            customer_name : {
                required : 'Este campo es requerido'
            },
            address : {
                required : 'Este campo es requerido'
            }
        }
    },
    "#insert-password-form":{
        rules:{
            clave : {
                required : true
            },
            clave2 : {
                required : true
            }
        },
        messages:{
            clave :{
                required : 'Debe de ingresar el código'
            },
            clave2 :{
                required : 'Debe de ingresar el código'
            }
        }
    },
    "#code-recover-form":{
        rules:{
            vcod : {
                required : true
            }
        },
        messages:{
            vcod :{
                required : 'Debe de ingresar el código'
            }
        },
        submitHandler: function(form){
            var codeRecoverForm = $('#code-recover-form'),
                codeError = $('#code-error');
                
                $.ajax({
                    url : yOSON.baseHost+'usuario/clave/recuperar-confirmar',
                    type : 'post',
                    data : codeRecoverForm.serialize(),
                    success : function(value){
                        if(value.code == 0){
                            $.fancybox({
                                'content' : '<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p></div>',
                                'onClosed' : function(){
                                    location.href = yOSON.baseHost+"usuario/clave/recuperar-nueva"
                                }
                            });
                        }else{
                            codeError.html('<div class="rbox">'+value.msg+'</div>');
                        }
                    }
                });
        }
    },
    "#recover-passw-form":{
        rules:{
            captcha:{
                required : true
            },
            usercontact:{
                required : true
            }
        },
        messages :{
            captcha:{
                required : 'Este campo es requerido'
            },
            usercontact:{
                required : 'Este campo es requerido'
            }
        },
        submitHandler : function(form){
            var recoverPasswForm = $('#recover-passw-form'),
                recoverPasswError = $('#recover-passw-error') ;
            $.ajax({
                url : yOSON.baseHost+'usuario/clave/recuperar',
                type : 'post',
                data : recoverPasswForm.serialize(),
                success : function(value){
                    if(value.code == 0){
                        $.fancybox({
                            'href' : yOSON.baseHost+'usuario/clave/recuperar-codigo',
                            'onComplete' : function(){
                                 yOSON.AppCore.runModule('validate', {form:'#code-recover-form'});
                            }
                        });
                    }else{
                        recoverPasswError.html('<div class="rbox">'+value.msg+'</div>');  
                    }
                    
                }
            });
        }
    },
    "#question-seller-form":{
        rules:{
            mensage:{
                required : true,
                noinjection : true
            }
        },
        messages :{
            mensage:{
                required : 'Este campo es requerido'
            }
        },
        submitHandler : function(form){
                 var msgTextAreaDom = $('#mensage'),
                     msgTextArea = $('#mensage').val(),
                     questionSellerForm = $('#question-seller-form');
                     
                 $.ajax({
                    url :  yOSON.baseHost+'usuario/pregunta/comprador', 
                    type : 'post',
                    data : questionSellerForm.serialize(),
                    beforeSend : function(){
                        $.fancybox.showActivity();
                    },
                    success : function(value){                   
                        $.fancybox({
                            'content' : '<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p></div>',
                            'onClosed' : function(){
                                msgTextAreaDom.val('');
                            }
                        });                           
                        
                        if(value.code == 0){
                            $('.text-question-to-seller').append('<dt class="question-customer" style="border-bottom: 1px solid #CCCCCC;"><span class="icon-question"></span><p><strong>'+value.user+': </strong><span>'+msgTextArea+'</span></p><span class="date-hour">'+value.hora+'</span></dt>');
                        }
                    }
                })
        }
    },
    "#change-password-form":{
        rules:{
            newpassw:{
                required : true,
                equalTo : '#newpassw2'
            },
            newpassw2 :{
                required : true,
                equalTo : '#newpassw'
            }
        },
        messages :{
            newpassw:{
                required : 'Este campo es requerido',
                equalTo : 'La clave no coincide'
            },
            newpassw2:{
                required : 'Este campo es requerido',
                equalTo : 'La clave no coincide'
            }
        },
            submitHandler : function(form){
                 var changePasswordForm = $('#change-password-form'),
                     changePassword = $('#change-password'),
                     changePasswordMsg = $('#change-password-msg');
                 $.ajax({
                    url :  yOSON.baseHost+'usuario/edicion/clave',
                    type : 'post',
                    data : changePasswordForm.serialize(),
                    success : function(value){
                        $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p></div>');
                    }
                })
            }
    },
    "#change-email-form":{
        rules:{
             newmail:{
                required : true,
                email: true,
                equalTo : '#newmail2'
            },
            newmail2 :{
                required : true,
                email: true,
                equalTo : '#newmail'
            }
        },
        messages :{
            newmail:{
                required : 'Este campo es requerido',
                email : 'Ingrese un formato correcto',
                equalTo : 'El correo no coincide'
            },
            newmail2:{
                required : 'Este campo es requerido',
                email : 'Ingrese un formato correcto',
                equalTo : 'El correo no coincide'
            }
        },
            submitHandler : function(form){
                 var changeEmailForm = $('#change-email-form'), 
                     changeEmail = $('#change-email'),
                     changeEmailMsg = $('#change-email-msg'),
                     changeEmailMsgError = $('#change-email-msg-error')
                     rutaChange = $('#change-ruta');
                     if(rutaChange.length != 0){
                       var rutaServer =  "usuario/registro/cambio-correo";
                       console.log(rutaServer);
                     }else{
                         rutaServer = "usuario/edicion/correo";
                         console.log(rutaServer);
                     }
                     
                 $.ajax({
                    url :  yOSON.baseHost+rutaServer,
                    type : 'post',
                    data : changeEmailForm.serialize(),
                    success : function(value){
                        console.log(typeof value.code);
                        if(value.code == 0){
                            $.fancybox('<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p></div>');
                        }else if(value.code == 1){
                            changeEmailMsgError.html('<div class="rbox">'+value.msg.Newmail[0]+'</div>');
                            changeEmailMsg.show();
                           
                        }             
                    }
                });
                console.log(yOSON.baseHost+rutaServer);
            }
    },
    "#my-info":{
        rules:{
            nombre:{
               required :true,
               myMinLength: [2],
               nombre : true
            },
            apellido:{
               required :true,
               myMinLength: [2],
               nombre : true
           },
           ubication_departement:{
                required :true 
           },
           ubication_province:{
                required :true 
           },
           ubication_district:{
                required :true 
           },
           telefono:{
                required :true,
                digits: true,
                minlength : 7,
                maxlength : 9
           },
           telefono2:{
                required :false,
                digits: true,
                minlength : 7,
                maxlength : 9
           },
           conditions:{
                required :true 
           }
       },
       messages:{
            nombre:{
               required :'Este campo es requerido'
            },
            apellido:{
               required :'Este campo es requerido'
           },
           ubication_departement:{
                required :'Este campo es requerido'
           },
           ubication_province:{
                required :'Este campo es requerido'
           },
           ubication_district:{
                required :'Este campo es requerido'
           },
           telefono:{
                required :'Este campo es requerido',
                digits :'Ingrese solo números',
                minlength : "Mínimo 7 caracteres.",
                maxlength: "Máximo 9 caracteres."
           },
           telefono2:{
                required :'Este campo es requerido',
                digits :'Ingrese solo números',
                minlength : "Mínimo 7 caracteres.",
                maxlength: "Máximo 9 caracteres."
           },
           conditions:{
                required :'Este campo es requerido'
           }
       }
    },
    "#send-friend-form-ad":{
       rules:{
            de:{
               required :true,
               email : true
            },
            para:{
               required :true,
               email:true 
           },
           mensaje:{
                required :true,
                minlength:40
           }
       },
       messages:{
            de:{
               required :'Este campo es requerido',
               email: 'El e-mail es invalido'
            },
            para:{
               required :'Este campo es requerido',
               email: 'El e-mail es invalido' 
           },
           mensaje:{
                required :'Este campo es requerido',
                minlength : 'Debe de ingresar un minimo de 40 caracteres'
                
           }
       },
       'submitHandler' : function(){
           var sendFriendForm = $('#send-friend-form-ad');
           $.ajax({
                    url : yOSON.baseHost+'usuario/aviso/envio-correo-amigo',
                    type : 'post',
                    data : sendFriendForm.serialize(),
                    beforeSend : function(){
                        $.fancybox.showActivity();
                    },
                    success : function(value){
                        $.fancybox({
                          'transitionIn' : 'none',
                          'transitionOut' : 'none',
                          'content': '<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p>',
                          'onClosed': function(){
                              sendFriendForm.find('input').val('');
                              sendFriendForm.find('textarea').val('');
                          }
                        }); 
                    }
                });
       }
    },
    "#send-friend-form-search":{
       rules:{
            email:{
               required :true,
               email : true
            },
            email2:{
               required :true,
               email:true 
           },
           message:{
                required :true,
                minlength:40
           }
       },
       messages:{
            email:{
               required :'Este campo es requerido',
               email: 'El e-mail es invalido'
            },
            email2:{
               required :'Este campo es requerido',
               email: 'El e-mail es invalido' 
           },
           message:{
                required :'Este campo es requerido',
                minlength : 'Debe de ingresar un minimo de 40 caracteres'
                
           }
       },
       'submitHandler' : function(){
           var sendFriendForm = $('#send-friend-form-search');
           $.ajax({
                    url : yOSON.baseHost+'busqueda/envio-correo',
                    type : 'post',
                    data : sendFriendForm.serialize(),
                    beforeSend : function(){
                        $.fancybox.showActivity();
                    },
                    success : function(value){
                        $.fancybox({
                        'transitionIn' : 'none',
                        'transitionOut' : 'none',
                          'content': '<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p>',
                          'onClosed': function(){
                              sendFriendForm.find('input').val('');
                              sendFriendForm.find('textarea').val('');
                              $('#send-friend-form-search')[0].reset();
                          }
                        }); 
                    }
                });
       }
    },
    "#report-ad-form":{
        rules:{
            nombres:{
                required :true,
                nombre: true
            },
            apellidos:{
                required :true,
                nombre: true
            },
            email:{
                required :true,
                email :true
            },
            telefono:{
                required :true,
                digits: true,
                minlength : 7,
                maxlength : 9
            },
            slcmotivo:{
                required :true,
                digits: true
            },
            comentario:{
                required :true,
                myMaxLength: [300]
            }
        },
        messages:{
            nombres:{
                required :'Este campo es requerido'
            },
            apellidos:{
                required :'Este campo es requerido'
            },
            email:{
                required :'Este campo es requerido',
                email: 'El e-mail es invalido'
            },
            telefono:{
                required :'Este campo es requerido',
                digits :'Ingrese solo números',
                minlength : "Mínimo 7 caracteres.",
                maxlength: "Máximo 9 caracteres."
            },
            slcmotivo:{
                required :'Este campo es requerido',
                digits :'Ingrese solo numeros'
            },
            comentario:{
                required :'Este campo es requerido'
            }
        },
        'submitHandler': function(form){
            var reportAdForm = $('#report-ad-form');
            $.ajax({
                url: yOSON.baseHost+'usuario/aviso/denunciar',
                data : reportAdForm.serialize(),
                type:'post',
                success: function(value){                    
                    $.fancybox({
                        'transitionIn' : 'none',
                        'transitionOut' : 'none',
                          'content': '<div class="message-modal"><h3 class="k-title"><span></span>Mensaje</h3><p>'+value.msg+'</p>',
                          'onClosed': function(){
                              reportAdForm.find('select').val('');
                              reportAdForm.find('textarea').val('');
                          }
                    }); 
                       
                }
            });//-- ajax
        }
    },
    "#formulariocategoria":{
        rules:{
            categoriaId1:{
                required :true
            },
            categoriaId2 : {
                required :true
            }
        },
        messages:{
            categoriaId1:{
                required :'Debe seleccionar un rubro'
            },
            categoriaId2 : {
                required :'Debe seleccionar un subrubro'
            }
        }
    },
    "#frm_add":{
        rules:{
            announcement_title:{
                required: true,
                myMinLength: [3],
                myMaxLength: [100]
            },
            price:{
                required :true,
                number :true,
                decimalKotear: true
            },
            ubication_departement:{
                required :true
            },
            ubication_province:{
                required :true
            },
            ubication_district:{
                required :true
            },
            ids_hidden_ad:{
                required :true
            },
            announcement_title_impress:{
                required :true
            },
            "letter_free":{
                required :true, "excWords":true
            }
        },
        messages:{
            announcement_title:{
                required :'Este campo es requerido'
            },
            price:{
                required :'Este campo es requerido',
                number :'Solo ingrese numeros'
            },
            ubication_departement:{
                required :'Este campo es requerido'
            },
            ubication_province:{
                required :'Este campo es requerido'
            },
            ubication_district:{
                required :'Este campo es requerido'
            },
            ids_hidden_ad:{
                required :'Debe cargar una fotografía'
            },
            announcement_title_impress:{
                required :'Este campo es requerido'
            },
            letter_free:{
                required :'Este campo es requerido'
            }
        },
        ignore : ".ignore" 
        
    },
    "#form-advanced-search":{
        rules:{
            pmax:{
                number :true,
                decimalKotear: true,
                morethandepends : $('#price-min')
            },
            pmin:{
                number :true,
                decimalKotear: true,
                lessthandepends : $('#price-max')
            } 
        },
        messages:{
            pmax:{
                number :'Ingrese solo numeros',
                morethandepends : 'El valor ingresado deber ser mayor'
            },
            pmin:{
                number :'Ingrese solo numeros',
                lessthandepends : 'El valor ingresado deber ser menor'
            }
        }
    },
    "#form-log-in":{
        rules: {
            user:{
                required : true
            },
            password:{
                required : true
            }                   
        },
        messages: {
            user:{
                required : "Por favor, ingrese su email o apodo"
            },
            password:{
                required : "Por favor, ingrese su clave"
            }
        }
    },
    "#form-sign-in":{
        rules: {
            apodo:{
                required: true,
                myMinLength: 6,
                myMaxLength: 20,
                alphanumspecial : true,
                noguiones: true
            },
            clave:{
                required: true,
                myMinLength: 6,
                myMaxLength: 20
            },
            claveRep:{
                required: true,
                myMinLength: 6,
                myMaxLength: 20,
                equalTo : '#txt-password'
            },
            nombre:{
                required: true,
                nombre: true,
                myMinLength: 2,
                myMaxLength: 40
            },
            apellido:{
                required: true,
                nombre: true,
                myMinLength: 2,
                myMaxLength: 50
            },
            email:{
                required: true,
                email: true,
                nopunto: true,
                myMinLength: 8,
                myMaxLength: 50
            },
            tipodocumento:{
                required : true
            },
            telefono:{
                required: true,
                digits: true,
                myMaxLength: 9,
                myMinLength: 7
            },
            telefono2:{
                required: false,
                digits: true,
                minlength : 7,
                maxlength: 9
            },
            ubication_departement:{
                required: true 
            },
            ubication_province:{
                required: true 
            },
            ubication_district:{
                required: true 
            },
            terms:{
                required : true
            }                  
        },
        messages: {
            apodo:{
                required: "Ingrese su apodo"
            },
            clave:{
                required: "Ingrese la clave"
            },
            claveRep:{
                required: "Ingrese nuevamente la clave",
                equalTo: "No coincide con el campo Clave"
            },
            nombre:{
                required: "Ingrese sus Nombres"
            },
            apellido:{
                required: "Ingrese sus Apellidos"
            },
            email:{
                required: "Ingrese su e-mail",
                email: "El e-mail es invalido"
            },
            tipodocumento:{
                required: "Seleccione un tipo de documento"
            },
            telefono:{
                required: "Ingrese su número de télefono",
                digits : "Ingrese solo digitos"
            },
            telefono2:{
                required: "Ingrese su número de télefono",
                digits : "Ingrese solo digitos",
                minlength : "Mínimo 7 caracteres",
                maxlength: "Máximo 9 caracteres"
            },
            ubication_departement:{
                required: "Debe seleccionar un departamento"
            },
            ubication_province:{
                required: "Debe seleccionar una provincia"
            },
            ubication_district:{
                required: "Debe de seleccionar un distrito"
            },
            terms:{
                required: "No ha aceptado los Términos y Condiciones de Uso"
            }
        },
        'submitHandler': function(form){
            var formSignIn = $('#form-sign-in');
            $.ajax({
                url: yOSON.baseHost+'usuario/registro',
                data : formSignIn.serialize(),
                type:'post',
                success: function(value){                    
                    if(value.code == 0){                        
                        location.href=yOSON.baseHost+"usuario/registro/confirmar-correo";
                        
                    }else{
                        $('#form-sign-in .rbox').remove();
                        
                        var mensajeError = value.msg;
                        if(mensajeError != ""){
                            var mensajeErrorArray = mensajeError.split(';');
                            var msgError ="<ul>";
                            $.each(mensajeErrorArray,function(i,val){
                               if(val != ""){
                                    msgError += "<li>"+val+"</li>";
                               }
                            });
                            msgError += "</ul>";
                            $('#form-sign-in .k-title').after('<div id="errorServer" class="rbox">'+msgError+'</div>');
                            $('window, html').animate({scrollTop:$('#errorServer').offset().top-100});
                            }// mensaje white

                            $('#captcha-label-control').html(value.captcha);
                             var errorCaptcha = $('#captcha-label-control').find('ul.errors').length;
                             if(errorCaptcha != 0){
                                 $('ul.errors').remove();
                                 $('#captcha-input').after('<div class="rbox" style="margin-top:10px;width:87%">Captcha invalido</div>');
                             }
                           
                        }
                       
                }
            });//-- ajax
        }
    },
    "#loginAdmin":{
        rules :{
            user:{
                required:true
            },
            pass:{
                required:true
            }
        },
        messages :{
            user:{
                required:"Debe de ingresar su usuario"
            },
            pass:{
                required:"Debe de ingresar su clave"
            }
        }
    },
    "#apfbFormSearch":{
        rules:{
            a_email :{
                email : true,
                required:false
            },
            a_tipDoc : {
                required:false
            },
            a_tag:{
                required:false
            },
            a_estado:{
                required:false,
                digits:true
            },
            a_prioridad:{
                required:false,
                digits:true
            },
            a_orden:{
                required:false,
                digits:true
            }
        },
        messages:{
            a_email :{
                email:"Ingrese un formato correcto."
            },
            a_tag:{
                required:false
            },
            a_estado:{
                required:false,
                digits : "Ingrese solo digitos"
            },
            a_prioridad:{
                required:false,
                digits : "Ingrese solo digitos"
            },
            a_orden:{
                required:false,
                digits : "Ingrese solo digitos"
            }
        },
        submitHandler : function(){
            yOSON.AppCore.runModule('ap-filtro-busqueda');
        }
    }, // apfbFormSearch
    "#apfbForm":{
        rules:{
           f_apodo:{
               required:true
           },
           f_email:{
                required: true,
                email: true,
                nopunto: true,
                myMinLength: 8,
                myMaxLength: 50
           },
           f_nombre:{
                required: true,
                nombre: true,
                myMinLength: 2,
                myMaxLength: 40
           },
           f_tipDoc:{
              required:true 
           },
           f_tel1:{
                required: true,
                digits: true,
                myMaxLength: 9,
                myMinLength: 7 
           },
           f_departamento:{
              required:true
           },
           f_provincia:{
              required:true 
           },
           f_distrito:{
              required:true 
           },
           f_clave:{
               required:true
           },
           f_apellidos:{
            required: true,
            nombre: true,
            myMinLength: 2,
            myMaxLength: 50
           },
           f_nDoc:{
               required:true
           },
           f_tel2:{
                required: false,
                digits: true,
                minlength : 7,
                maxlength: 9
           },
           f_tipoUsuario:{
               required:true
           },
           f_tag:{
               required:true
           },
           f_url:{
               required:true,
               url:true
           },
           f_orden:{
               required:true,
               digits : true
           },
           f_prioridad:{
               required:true,
               digits : true
           }
        },
        messages:{
           f_apodo:{
               required:"Este campo es requerido"
           },
           f_email:{
                required: "Ingrese su e-mail",
                email: "El e-mail es invalido"
           },
           f_nombre:{
               required: "Ingrese sus Nombres"
           },
           f_tipDoc:{
              required: "Seleccione un tipo de documento"
           },
           f_tel1:{
                required: "Ingrese su número de télefono",
                digits : "Ingrese solo digitos"
           },
           f_departamento:{
             required: "Debe seleccionar un departamento"
           },
           f_provincia:{
              required: "Debe seleccionar una provincia"
           },
           f_distrito:{
              required: "Debe seleccionar un distrito" 
           },
           f_clave:{
               required:"Este campo es requerido"
           },
           f_apellidos:{
                required: "Ingrese sus apellidos"
           },
           f_nDoc:{
               required:"Ingrese el numero de documento"
           },
           f_tel2:{
                required: "Ingrese su número de télefono",
                digits : "Ingrese solo digitos",
                minlength : "Mínimo 7 caracteres.",
                maxlength: "Máximo 9 caracteres."
           },
           f_tipoUsuario:{
               required:"Este campo es requerido"
           },
           f_tag:{
               required:"Este campo es requerido"
           },
           f_url:{
               required:"Este campo es requerido",
               url:"Ingrese un formato correcto"
           },
           f_orden:{
               required:"Este campo es requerido",
               digits : "Ingrese solo digitos"
           },
           f_prioridad:{
               required:"Este campo es requerido",
               digits : "Ingrese solo digitos"
           }
        },
        submitHandler : function(){
           var formSearch = '#apfbFormSearch',
                formEdit = '#apfbForm';
                
            $.ajax({
                url : yOSON.baseHost+yOSON.module+'/'+$(formSearch).attr("form"),
                type : 'post',
                data: $(formEdit).serialize(),
                beforeSend : function(){
                    $.fancybox.showActivity();
                    $("#f_cerrar, #apbtnGuardar").attr("disabled","disabled");
                },
                success: function(msg){
                    $.fancybox.hideActivity();
                    var $isData = $(msg).attr('isData'),
                    $msg = $(msg).attr('msg');       
                    if ($isData == '0') {
                      $alert = 'info';
                      $msg = "Éxito: Datos guardados correctamente.";
                    }else{
                        $alert = 'error';
                        if($msg==''){
                            $msg = "Error: No se pudo guardar.";
                        }
                    }
                    var $html ='<div class="mbottom0 alert alert-'+$alert+'">';
                    $html +='<p class="mbottom0">'+$msg+'</p>';
                    $html +='</div>';
                      
                    $.fancybox({
                         content : $html,
                         onClosed : function(){
                             $("#apfbBtnSearch").trigger('click');
                         }
                    });
                     
                }
            });
        }
    },//apfbform
    "#formSearchItem":{
        rules:{
          a_tipoModulo:{
              required:true
          },
          a_modulo:{
              required:false
          },
          a_nombreItem:{
              required:false,
              nombre:true
          },
          a_link:{
              required:false,
              url:true
          },
          a_activo:{
              required:false
          }
        },
        messages:{
          a_tipoModulo:{
              required:"Seleccione un modulo"
          },
          a_link:{
              url:"El formato del link es incorrecto"
          }
        },
        submitHandler : function(){
            $("#apfbBtnSearch").attr("disabled","disabled");
            var urlAction=$("#formSearchItem").attr("action");
             $.ajax({
                url : yOSON.baseHost+yOSON.module+urlAction,
                type : 'GET',
                dataType : 'html',
                data : $("#formSearchItem").serialize(),
                beforeSend : function(){
                    $("#afbResultAjax").html('').addClass("loading");
                },
                success : function (value,status) {
                    $("#afbResultAjax").removeClass("loading").html(value);
                    $("#apfbBtnSearch").removeAttr("disabled");
                }
              });//ajax
        }
    },
    "#addLink":{
        rules:{
          f_name:{
              required:true
          },
          f_id:{
              required:true
          },
          f_ord:{
                required:true
          },
          f_modulo:{
                required:true
          },
          f_link:{
                required:true
          }
        },
        messages:{
           f_name:{
              required:"Esta campo es requerido"
          },
          f_id:{
               required:"Esta campo es requerido"
          },
          f_ord:{
               required:"Esta campo es requerido"             
          },
          f_modulo:{
               required:"Esta campo es requerido"              
          },
          f_link:{
               required:"Esta campo es requerido"              
          }
        }
    },
    "#formSearchEspecial":{
        rules:{
           a_codigoAviso:{
               required:false
           },
           a_apodo:{
               required:false
           },
           a_tipoUsuario:{
               required:false
           },
           a_tipoDestaque:{
               required:false
           },
           a_fecIni:{
               required:false
           },
           a_fecFin:{
               required:false
           }
        },
        messages:{
           a_codigoAviso:{},
           a_apodo:{},
           a_tipoUsuario:{},
           a_tipoDestaque:{},
           a_fecIni:{},
           a_fecFin:{}
        },
        submitHandler: function(){
            $("#apfbBtnSearch").attr("disabled","disabled");
            var urlAction=$("#formSearchEspecial").attr("action");
             $.ajax({
                     url : yOSON.baseHost+yOSON.module+urlAction,
                     type : 'GET',
                     dataType : 'html',
                     data : $("#formSearchEspecial").serialize(),
                     beforeSend : function(){
                         $("#afbResultAjax").html('').addClass("loading");
                     },
                     success : function (value,status) {
                             $("#afbResultAjax").removeClass("loading").html(value);
                             $("#apfbBtnSearch").removeAttr("disabled");
                             yOSON.AppCore.runModule('validate',{form:'#formAddEspecialSave'});
                     }
              });
              
              
        }
    },
    "#apfbFormEnlace":{
        rules:{
            f_nombre:{
                required:true,
                nombre:true
            },
            f_orden:{
                required:true
            },
            f_modulo:{
                required:true
            },
            f_link:{
                required:true,
                url:true
            }
        },
        messages:{
            f_nombre:{
                 required:"Esta campo es requerido" 
            },
            f_orden:{
                 required:"Esta campo es requerido" 
            },
            f_modulo:{
                 required:"Esta campo es requerido" 
            },
            f_link:{
                 required:"Esta campo es requerido", 
                  url:"El formato del link es incorrecto"
            }
        },
        submitHandler: function(){
            var formSearch = '#formSearchItem',
                formEdit = '#apfbFormEnlace';
            $.ajax({
                url : yOSON.baseHost+yOSON.module+$(formSearch).attr("form"),
                type : 'post',
                data: $(formEdit).serialize(),
                beforeSend : function(){
                    $.fancybox.showActivity();
                },
                success: function(msg){
                    $.fancybox.hideActivity();
                    var $isData = $(msg).attr('isData'),
                    $msg = $(msg).attr('msg');       
                    if ($isData == '0') {
                      $alert = 'info';
                      $msg = "Éxito: Datos guardados correctamente.";
                    }else{
                        $alert = 'error';
                        if($msg==''){
                            $msg = "Error: No se pudo guardar.";
                        }
                    }
                    var $html ='<div class="mbottom0 alert alert-'+$alert+'">';
                    $html +='<p class="mbottom0">'+$msg+'</p>';
                    $html +='</div>';
                      
                    $.fancybox({
                         content : $html,
                         onClosed : function(){
                             $("#apfbBtnSearch").trigger('click');
                         }
                    });
                     
                }
            });
        }
    },
    "#apfbFormEspecial":{
        rules:{
            f_modulo:{
                required:true
            }
        },
        messages:{
            f_modulo:{
                required:"Este campo es requerido"
            }
        },
        submitHandler:function(){
            var formSearch = '#formSearchItem',
                formEdit = '#apfbFormEspecial';
            $.ajax({
                url : yOSON.baseHost+yOSON.module+$(formSearch).attr("form"),
                type : 'post',
                data: $(formEdit).serialize(),
                beforeSend : function(){
                    $.fancybox.showActivity();
                },
                success: function(msg){
                    $.fancybox.hideActivity();
                    var $isData = $(msg).attr('isData'),
                    $msg = $(msg).attr('msg');       
                    if ($isData == '0') {
                      $alert = 'info';
                      $msg = "Éxito: Datos guardados correctamente.";
                    }else{
                        $alert = 'error';
                        if($msg==''){
                            $msg = "Error: No se pudo guardar.";
                        }
                    }
                    var $html ='<div class="mbottom0 alert alert-'+$alert+'">';
                    $html +='<p class="mbottom0">'+$msg+'</p>';
                    $html +='</div>';
                      
                    $.fancybox({
                         content : $html,
                         onClosed : function(){
                             $("#apfbBtnSearch").trigger('click');
                         }
                    });
                     
                }
            });
        }
    },
    "#formAddEspecialSave":{
        rules:{
            f_modulo:{
                required:true
            },
            f_idAviso:{
                required:true
            }
        },
        messages:{
            f_modulo:{
                 required:"Esta campo es requerido" 
            },
            f_idAviso:{
                 required:"Esta campo es requerido" 
            }
        },
        submitHandler:function(){
            var urlAction=$("#formAddEspecialSave").attr("form");
             $.ajax({
                     url : yOSON.baseHost+yOSON.module+urlAction,
                     type : 'POST',
                     dataType : 'json',
                     data : $("#formAddEspecialSave").serialize(),
                     beforeSend : function(){
                         $("#afbResultAjax").html('').addClass("loading");
                     },
                     success : function (value) {
                             $("#afbResultAjax").removeClass("loading");
                             if(value.isError == 0){
                                 classStyle = "info"
                             }else{
                                 classStyle = "error"
                             }
                             var $html ='<div class="mbottom0 alert alert-'+classStyle+'">';
                                 $html +='<p class="mbottom0">'+value.msg+'</p>';
                                 $html +='</div>';

                             $.fancybox({
                                 content : $html
                             })
                     }
            });
        }
    }
};