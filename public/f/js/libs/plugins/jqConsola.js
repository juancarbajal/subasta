/*  Consola v0.3 - jQuery Console log
    (c) 2012 Jan Sanchez - @jansanchez - Perucomsultores - yosÓn devs
    License: http://www.opensource.org/licenses/mit-license.php
*/
(function ($) {
    var methods = {
        defaults: function () {
                var defaults = {
                id: '',
                width: '30%',
                height: '300px',
                bottom: '50px',
                bgcolor:'#F9EDBE',
                bcolor:'#e5ad43',
                color:'#222222',
                opacity: '0.80',
                ffamily:'tahoma,arial',
                fsize:'11px',
                lheight:'15px',
                zindex:9000,
                colorLink:'#bf2217',
                elementClose: 'consola-close',
                textClose: 'x',
                elementMax: 'consola-max',
                textMax: '+',
                elementMin: 'consola-min',
                textMin: '-',
                spedd: 350,
                ie6ChildBG: 'transparent',
                ie6ChildBGHover: 'transparent',
                msg: '',
                separator: '<br><br>',
                lifetime: 0,
                mode: 'default',
                maxlevel: 2
            };
            
            defaults.isIE = !$.support.opacity && !$.support.style;
            defaults.isIE6 = defaults.isIE && !window.XMLHttpRequest;



            return defaults;
        },
        evaluate: function(msg, level, maxlevel){

            if(msg.toString()=='[object Object]'){
            var txtEdited = '',
            spaces = '';

            for (i=0; i<level; i++){
                spaces += '&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            for(ele in msg){

            if(msg[ele].toString().indexOf("return new e.fn.init(a, b, h);")>=0 || msg[ele].toString().indexOf("function()")>=0 || msg[ele].toString().indexOf("jquery")>=0 || msg[ele].toString().indexOf("constructor")>=0){
                txtEdited= txtEdited.replace(spaces+'<strong>context</strong>:<br>','');
                txtEdited= txtEdited.replace('<br>'+spaces+spaces+'[object HTMLDocument]','');
                return msg = msg+txtEdited;
            }/*jquery*/

            txtEdited += '<br>'+spaces+'<strong>'+ ele+ '</strong>:<br>'+spaces;

                if(msg[ele].toString().indexOf("function")>=0){
                    txtEdited += spaces+'function()';
                }else{
                    if(msg[ele].toString()=='[object Object]'){
                        level = level + 1;
                        if (level<=maxlevel) {
                            txtEdited += $.fn.consola('evaluate', msg[ele], level, maxlevel);    
                        };
                    }else{
                        txtEdited += spaces+msg[ele].toString();
                    }                
                }

            }
            msg = msg+txtEdited; 
            }
            return msg;
        },
        init: function (options) {
            var opts = $.extend({}, $.fn.consola('defaults'), options);
            opts.id = 'Console-'+opts.mode;

            if (opts.mode=='info') {
                opts.id = opts.id+'-'+Math.floor(Math.random()*999);
                opts.zindex = opts.zindex++;
            };

            if(!$('#'+opts.id).length){
                $.fn.consola('create', opts);
                opts.separator='';
            }

            opts.msg = $.fn.consola('evaluate', opts.msg, 1, opts.maxlevel);

            $.fn.consola('update', opts);

        },
        create: function (opts) {
            var closeDiv='<div id="'+opts.id+'-title" style="background-color: '+opts.bgcolor+';border-color: '+opts.bcolor+';border-style: solid;border-width: 1px 1px 0;display: block;float: right;height: 17px;position: absolute;right: -1px;text-align: center;top: -19px;padding: 0 5px"><a href="javascript:;" class="'+opts.elementMin+'" title="'+opts.textMin+'" style="color:'+opts.colorLink+';cursor: pointer; text-decoration: none; padding: 0 4px 0 0;">'+opts.textMin+'</a><a href="javascript:;" class="'+opts.elementMax+'" title="'+opts.textMax+'" style="color:'+opts.colorLink+';cursor: pointer; text-decoration: none; padding: 0 4px">'+opts.textMax+'</a><a href="javascript:;" class="'+opts.elementClose+'" title="'+opts.textClose+'" style="color:'+opts.colorLink+';cursor: pointer; text-decoration: none; padding: 0 0 0 4px;">'+opts.textClose+'</a></div>',
            visibility='block';
            switch(opts.mode) {
                case 'info':
                case 'load':
                    closeDiv='';
                    visibility='block';
                break;
            }
            $('body').append('<div id="'+opts.id+'" style="opacity: '+opts.opacity+'; word-wrap: break-word; width:'+opts.width+'; display:none; background-color: '+opts.bgcolor+';border: 1px solid '+opts.bcolor+';bottom: '+opts.bottom+';color: '+opts.color+';   direction: ltr;    font-family: '+opts.ffamily+';    font-size: '+opts.fsize+';    left: 0;    line-height: '+opts.lheight+';padding: 8px;    position: fixed;    right: 0;    z-index: '+opts.zindex+';">'+closeDiv+'<div id="'+opts.id+'-body" style="width:100%; max-height: '+opts.height+'; overflow-y: auto; display:'+visibility+'; "></div></div>');
        },
        update: function (opts) {

            var element=$('#'+opts.id),
            body=$('#'+opts.id+'-body');
            body.append(opts.separator+opts.msg);

            $('.'+opts.elementMin).bind('click',function(){
                body.fadeOut(250);
            });
            
            $('.'+opts.elementMax).bind('click',function(){
                body.fadeIn(250);
            });

            $('.'+opts.elementClose).bind('click',function(){
                $.fn.consola('destroy', opts);
            });

            element.fadeIn(opts.speed);
            
            if(opts.lifetime!=0){
                setTimeout(function(){
                    $.fn.consola('destroy', opts);
                }, opts.lifetime);
            }

        },
        destroy: function (options) {
            var opts = $.extend({}, $.fn.consola('defaults'), options);

            if (opts.mode!='info') {
                opts.id = 'Console-'+opts.mode;
            };

            var element=$('#'+opts.id);
            element.fadeOut(opts.speed,function(){
                element.remove();              
            });
        }
    };
    $.fn.consola = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            if (typeof method === "object" || !method) {
                return methods.init.apply(this, arguments);
            } else {
                $.error("Method " + method + " does not exist on jQuery.consola");
            }
        }
    };
})(jQuery);

function echo(message, lifetime) {
    if (!lifetime) {
        lifetime = 5000;
    };
    $.fn.consola({msg:message,mode:'info',lifetime:lifetime});
};