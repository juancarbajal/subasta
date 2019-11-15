/**
* pictureManager v0.2.1 - jQuery pictureManager
* (c) 2012 Jan Sanchez - @jansanchez - Web evangelists
* MIT Licensed
*/

/**
* requires:
*     jqConsola.js
*     jquery.ui.draggable.js
*     jquery.ui.sortable.js
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
                favorite: true,
                del: true,
                zIndexEffect: '1000',
                timeEffect: 800,
                inputType:'hidden',
                separator:',',
                childrens: 0,
                limit:0,
                isLimit:false,
                defaultImage:'',
                onLimit: function(limit){

                },
                outLimit: function(limit){

                },
                favoriteImage: function(){

                },
                editImage: function(){

                },
                deleteImage: function(){

                }
            };
            
            defaults.isIE = !$.support.opacity && !$.support.style;
            defaults.isIE6 = defaults.isIE && !window.XMLHttpRequest;

            return defaults;
        },
        childrensLength: function(opts){
            if (opts.isLimit==false){
                if(opts.childrens==opts.limit){
                    opts.isLimit=true;
                    opts.onLimit(opts.childrens);
                }
            }else{
                if(opts.childrens==(opts.limit-1)){
                    opts.isLimit=false;
                    opts.outLimit(opts.childrens);
                }
            }
        },
        update : function(opts){

            var arr = $.fn.pictureManager('ids', opts).split(opts.separator);
            
            if (opts.current !=0 && opts.current == arr[0]) {
                /*si elimino una imagen main*/
                $.fn.pictureManager('setMainPicture' , opts ,arr[1], $('#'+arr[1]).children('a').children('img').attr('src'));
            }
            opts.childrens=$(opts.parentRight).children('div').length;

            if (opts.childrens<1) {
                /*si no hay mas imagenes*/
                $(opts.parentLeft).children('a').children('img').attr('src', opts.defaultImage);
                $(opts.parentLeft).fadeOut(250);

                $.fn.pictureManager('childrensLength', opts);
            }else{
                $.fn.pictureManager('setMainPicture', opts, arr[0], $('#'+arr[0]).children('a').children('img').attr('src'));
                $.fn.pictureManager('childrensLength', opts);
            }

            var ids=$.fn.pictureManager('ids', opts);
            var imgs = $.fn.pictureManager('imgs', opts);

            if ($('#'+opts.id).length==0) {
                $(opts.frm).append('<input id="'+opts.id+'" name="'+opts.id+'" type="'+opts.inputType+'" value="'+ids+'"></input>');
                $(opts.frm).append('<input id="'+opts.img+'" name="'+opts.img+'" type="'+opts.inputType+'" value="'+imgs+'"></input>');
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



            if (opts.favorite==true) {
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
            }else{
                opts.favoriteImage(opts);
            };

            if (opts.del==true) {
                $(opts.itemDel+' a').live('click', function(event){
                    var $that = $(this);
                    opts.current=$that.parent().parent().parent().attr(opts.attributeRight);

                    var remove_link=$that.parent().parent().parent().attr(opts.attributeRight);


                    if (remove_link!='') {
                        $.ajax({
                            url: opts.url_del+remove_link,
                            success: function(json) {
                                var data = $.parseJSON(json);

                                if (data['code']==1) {
                                    if (opts.info==true) {
                                        if (data['msg']!='') {
                                            //$.fn.consola({msg:data['msg'], mode:'info',lifetime: 1500});
                                        }
                                    }
                                    $that.parent().parent().parent().remove();
                                    $.fn.pictureManager('update', opts);
                                }else{
                                    if (opts.info==true) {
                                        if (data['msg']!='') {
                                            //$.fn.consola({msg:data['msg'], mode:'info',lifetime: 2500});
                                        }
                                    }
                                }
                            }
                        });
                    };
                });
            }else{
                opts.deleteImage(opts);
            }

            if (opts.edit==true) {
                $(opts.itemEdit+' a').live('click', function(event){

                });
            }else{
                opts.editImage(opts);
            }

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