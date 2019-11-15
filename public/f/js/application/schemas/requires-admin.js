var requires = {    
    "#frmConfis":{
        rules:{
            nombre:"required",
            delivery_day:"required",
            id_shop:"required",
            precio_proveedor:"required",
            precio_base:"required",
            porcentaje_ganancia:"required", //venta_sin_igv:"required",
            venta_con_igv:"required",
            descripcion_corta:"required",
            descripcion:"required",
            categoria_defecto:"required"
        },
        messages:{
            nombre:{required:"Ingrese nombre"},
            delivery_day:{required:"Ingrese dias de entrega"},
            id_shop:{required:"Ingrese tienda"},
            precio_proveedor:{required:"Ingrese precio de proveedor"},
            precio_base:{required:"Ingrese precio base"},
            porcentaje_ganancia:{required:"Ingrese % de ganacia"}, //venta_sin_igv:{required:"Ingrese venta sin IGV"},
            venta_con_igv:{required:"Ingrese venta con IGV"},
            descripcion_corta:{required:"Ingrese descripci&oacute;n corta"},
            descripcion:{required:"Ingrese descripci&oacute;n"},
            categoria_defecto:{required:"Ingrese sub-categoria por defecto"}
        },
        invalidHandler: function(form, validator){
            
            var errDat = false; $('#tab-dat label.error').each(function(i,e){
                
                if($(e).css('display')=='block'){errDat=true; return false;} 
            });
            
            var errCat = false; $('#tab-cat label.error').each(function(i,e){
                console.log(i+' - '+$(e).css('display'));
                if($(e).css('display')=='block'){errCat=true; return false;} 
            });
             
            Console.log('errDat:'+errDat+' - errCat:'+errCat);
            if( errDat && (errCat||!errCat) && $('#tab-dat').css('display')!='block' ){
                    $('#link-dat').trigger('click');
 
            }else if(!errDat && errCat){ $('#link-cat').trigger('click'); }
            
        }
    },
    "#mis-datos":{
        rules: {
            title: "required",
            message: "required",					
            'captcha[input]': "required"                    
        },
        messages: {
            title: "Escriba el título",
            message: "Escriba el mensaje",					
            'captcha[input]': "Escriba el código de seguridad"
        }
    },
    "#frm-step2":{
        rules:{
            date:"required",
            nombre:"required",
            apellido:"required",
            direccion:"required",
            stateUbigeo:"required",
            provinciaUbigeo:"required",
            districtUbigeo:"required",
            email:{email:true},
            phone:{required:true,number:true},
            signature:"required"
        },
        messages:{
            date:{required:"Ingrese la fecha de env&iacute;o"},
            nombre:{required:"Ingrese su Nombre"},
            apellido:{required:"Ingrese su Apellido"},
            direccion:{required:"Ingrese su Dirección"},
            stateUbigeo:{required:"Ingrese el Departamento"},
            provinciaUbigeo:{required:"Ingrese la Provincia"},
            districtUbigeo:{required:"Ingrese el Distrito"},
            email:{email:"Ingrese el email correctamente"},
            phone:{required:"Ingrese su tel&eacute;fono",number:"Ingrese su tel&eacute;fono correctamente"},
            signature:{required:"Ingrese su firma correctamente"}
        }
    }
};