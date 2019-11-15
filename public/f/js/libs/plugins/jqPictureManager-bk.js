/*  pictureManager v0.2 - jQuery pictureManager
    (c) 2012 Jan Sanchez - @jansanchez - Perucomsultores - yosÓn devs
    License: http://www.opensource.org/licenses/mit-license.php
*/
/*
requires:

    jquery.ui.draggable.js
    jquery.ui.sortable.js
*/

(function ($) {
    var methods = {
        defaults: function () {
            var defaults = {
                frm: '#frm_add',
                parentRight: '.fielset-content-right',
                parentLeft: '.fielset-content-left',
                itemFav: '.item-fav',
                itemDel: '.item-del',
                itemEdit: '.item-edit',
                sender: '#send',
                id: 'ids_hidden_ad',
                img: 'imgs_hidden_ad',
                attributeLeft: 'lang',
                attributeRight: 'id',
                info: true,
                current: 0,
                edit: true,
                zIndexEffect: '1000',
                timeEffect: 800,
                inputType:'hidden',
                separator:'_',
                arrowLeft: '.left',
                arrowRight: '.right'
            };
            
            defaults.isIE = !$.support.opacity && !$.support.style;
            defaults.isIE6 = defaults.isIE && !window.XMLHttpRequest;

            return defaults;
        },
        update : function(opts){

            var arr = $.fn.pictureManager('ids', opts).split(opts.separator);
            
            if (opts.current !=0 && opts.current == arr[0]) {
                /*si elimino una imagen main*/
                $.fn.pictureManager('setMainPicture' , opts ,arr[1], $('#'+arr[1]).children('a').children('img').attr('src'));
            }

            var childrens=$(opts.parentRight).children('div').length;

            if (childrens<1) {
                /*si no hay mas imagenes*/
                $(opts.parentLeft).children('a').children('img').attr('src', '');
                $(opts.parentLeft).fadeOut(250);
            }else{
                $.fn.pictureManager('setMainPicture', opts, arr[0], $('#'+arr[0]).children('a').children('img').attr('src'));
            }

            var ids=$.fn.pictureManager('ids', opts);
            var imgs = $.fn.pictureManager('imgs', opts);

            if ($('#'+opts.id).length==0) {
                $(opts.frm).append('<input class="'+opts.id+'" id="'+opts.id+'" name="'+opts.id+'" type="'+opts.inputType+'" value="'+ids+'"></input>');
                $(opts.frm).append('<input class="'+opts.img+'" id="'+opts.img+'" name="'+opts.img+'" type="'+opts.inputType+'" value="'+imgs+'"></input>');
            }else{
                $('#'+opts.id).val(ids);
                $('#'+opts.img).val(imgs);
            }

        },
        setMainPicture: function(opts, id, src){

            $(opts.parentLeft).children('a').children('img').attr('src', src);
            $(opts.parentLeft).children('a').attr(opts.attributeLeft, id);
            $(opts.parentLeft).children('p').html($('#'+id).children('p').html());
            $(opts.parentLeft).fadeIn(250);

        },
        ids: function (opts) {
            var ids = '';
            $.each($(opts.parentRight).children('div'), function(index, value) { 
                ids += $(value).attr(opts.attributeRight)+opts.separator;
            });
            ids=ids.substr(0,(ids.length-1));

            return ids;
        },
        imgs: function (opts) {
            var imgs = '';
            $.each($(opts.parentRight).children('div'), function(index, value) { 
                
                imgs += $(value).children('p').html()+opts.separator;

            });
            imgs=imgs.substr(0,(imgs.length-1));

            return imgs;
        },
        init: function (options) {

            var opts = $.extend({}, $.fn.pictureManager('defaults'), options);

            $(opts.parentRight).css('position','relative');

            $.fn.pictureManager('update', opts);

            $(opts.parentRight).change(function() {

                //Console.log('# Items: ' + $(this).children('div').length);

                $.fn.pictureManager('update', opts);

            });

            $(opts.itemFav+' a').live('click', function(event){

                var id = $(this).parent().parent().parent().attr(opts.attributeRight);
                var src = $(this).parent().parent().siblings('a').children('img').attr('src');
                
                var $father= $(this).parent().parent().parent().parent();
                var position = $(this).parent().parent().parent().position();

                var left = $father.css('paddingLeft')+$father.css('marginLeft');
                var right = $father.css('paddingRight')+$father.css('marginRight');
                
                var top = $father.css('paddingTop')+$father.css('marginTop');
                var bottom = $father.css('paddingBottom')+$father.css('marginBottom');

                $(this).parent().parent().parent().css({'z-index':opts.zIndexEffect}).animate({
                    top: -((position.top)-parseInt(top)),
                    left: -((position.left)-parseInt(left))
                    }, opts.timeEffect, function() {

                        $(opts.parentRight).prepend($('#'+id).css({'position':'','z-index':'',top:'',left:''}));

                        $.fn.pictureManager('setMainPicture' , opts , id, src);

                        $.fn.pictureManager('update', opts);
                });

            });

            $(opts.itemDel+' a').live('click', function(event){
                var $that = $(this);
                opts.current=$that.parent().parent().parent().attr(opts.attributeRight);               
                        //var remove_link=$that.attr(opts.attributeLeft);            
                 var remove_link=$that.parent().parent().parent().attr(opts.attributeRight);
                 if (remove_link!='') {
                     $.fancybox('<div class="message-modal"><div class="title-gen"><h3 class="title-step"><span class="icon-right"></span><p>Mensaje</p></h3></div><p>¿Desea elimnar la imagen?</p><div class="control-group"><p class="btn-options"><button type="button" id="delete-img-picture" class="btn-standar" name="continuar"><span>Continuar</span></button><a init-value="&lt;span&gt;Cancelar&lt;/span&gt;" value="&lt;span&gt;Cancelar&lt;/span&gt;" class="btn-finish" name="cancel" href="javascript:;" id="cancel-img-picture"><span>Cancelar</span></a></p></div></div>');
                     $('#cancel-img-picture').bind('click',function(){
                         $.fancybox.close();
                     }); 
                     $('#delete-img-picture').bind('click',function(){

                         $.fancybox.close();
                         
                         $.ajax({ //
                            url: opts.url_del+remove_link,
                            beforeSend : function(){
                                $('.options-image .third-columm').append('<img id="loader-photo" src="'+yOSON.baseHost+'f/img/loaderKotear.gif" style="width:20px;height:20px;float:right;margin-left:0px">');
                            },
                            success: function(json) {
                                var data = $.parseJSON(json);

                                //Console.log(data);

                                if (data['code']==1) {
                                    if (opts.info==true) {
                                       $('.options-image .third-columm #loader-photo').fadeOut('slow',function(){$(this).remove()});
                                    };
                                    $that.parent().parent().parent().fadeOut('slow').remove();
                                    $.fn.pictureManager('update', opts);

                                    var numberPhoto = $('.fielset-content-right').children().length;
                                    /* Remover el disable del añadir */
                                    if(numberPhoto <= _cantImg){
                                        $('#addProductImage').removeAttr('disabled');
                                    }
                                }else{
                                    if (opts.info==true) {
                                    }
                                }
                                deleteImg = 0;
                            }
                        });//
                     });
                }; //           
                
            });

            $(opts.parentRight).sortable({
                revert: false,
                stop: function(event, ui){
                    var arr = $.fn.pictureManager('ids', opts).split(opts.separator);
                    if ($(opts.parentLeft).children('a').attr(opts.attributeLeft) != arr[0]) {
                        $.fn.pictureManager('setMainPicture' , opts , arr[0], $('#'+arr[0]).children('a').children('img').attr('src'));
                    }
                    $(opts.parentRight).trigger('change');
                }
            });

            $( opts.parentRight ).disableSelection();

        /*implementacion de arrows*/

        
            $('a'+opts.arrowLeft).live('click', function(event){

                var $father= $(this).parent().parent().parent();

                var left = $father.css('paddingLeft')+$father.css('marginLeft');
                var right = $father.css('paddingRight')+$father.css('marginRight');
                
                var top = $father.css('paddingTop')+$father.css('marginTop');
                var bottom = $father.css('paddingBottom')+$father.css('marginBottom');

                var $iam = $(this).parent().parent();
                var id = $iam.attr(opts.attributeRight);
                var src = $iam.children('a').children('img').attr('src');
                var position = $iam.position();             

                var $prev = $iam.prev();
                var positionPre = $prev.position();

                $iam.prev().before($iam);

                /*
                $(this).parent().parent().parent().css({'z-index':opts.zIndexEffect}).animate({
                    top: -((position.top)-parseInt(top)),
                    left: -((position.left)-parseInt(left))
                    }, opts.timeEffect, function() {

                        $(opts.parentRight).prepend($('#'+id).css({'position':'','z-index':'',top:'',left:''}));

                        $.fn.pictureManager('setMainPicture' , opts , id, src);

                        $.fn.pictureManager('update', opts);
                });
                */
                $.fn.pictureManager('update', opts);

            });


            $('a'+opts.arrowRight).live('click', function(event){

                var $iam = $(this).parent().parent();
                $iam.next().after($iam);
                $.fn.pictureManager('update', opts);

            });


        },
        destroy: function (options) {

        }
    };
    $.fn.pictureManager = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            if (typeof method === "object" || !method) {
                return methods.init.apply(this, arguments);
            } else {
                $.error("Method " + method + " does not exist on jQuery.pictureManager");
            }
        }
    };
})(jQuery);
