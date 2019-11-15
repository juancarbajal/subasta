
$(function(){
    //alert('http://' + window.location.hostname + '/admin/producto/get-sub-categorias');
    
    $('#category').change(function(){
        var idCat = $(this).val();
        var idShop = $('#id_shop').val();
        if(idCat!='-1' && idShop != '-1'){
            $.ajax({
                type: 'post',
                url: 'http://' + window.location.hostname + '/admin/producto/get-sub-categorias/id/'+idCat+'/idshop/'+idShop,
                //data: {id:idCat},
                success: function(response){
                    options = '';
                    for(i in response){
                        //Console.log( i + ' -> ' + response[i] );
                        options += '<option value="'+i+'">'+response[i]+'</option>';
                    }
                    $('#id_category_default').html(options);
                },
                dataType: 'json'
            });
        }
    });
    
    $('#id_shop').change(function(){
        var idshop = $(this).val();
        if(idshop!='-1'){
            $.ajax({
                type: 'post',
                url: 'http://' + window.location.hostname + '/admin/producto/get-categorias/idshop/'+idshop,
                //data: {id:idCat},
                success: function(response){
                    options = '';
                    options += '<option value="-1"></option>';
                    for(i in response){
                        //Console.log( i + ' -> ' + response[i] );
                        options += '<option value="'+i+'">'+response[i]+'</option>';
                    }
                    $('#category').html(options);
                },
                dataType: 'json'
            });
        }
    });
    
    $('#add_image').click(function(){
        
        var add = '<input name="userfile[]" type="file" /><br/>';
        //alert($('#div-images').html());
        //$('#div-images').childNodes.appendChild(add);
        $('#div-images').html($('#div-images').html() + add);
    });
});

