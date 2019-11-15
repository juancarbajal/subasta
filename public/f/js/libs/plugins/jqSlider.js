/*  Consola v0.1 - jQuery Slider
    (c) 2012 Jan Sanchez - @jansanchez - Perucomsultores - yosÓn devs
    License: http://www.opensource.org/licenses/mit-license.php
*/
(function ($) {
    var methods = {
        defaults: function () {
                var defaults = {
                id: 'slider',
                height: '138px',
                width: '',
                arrowRight: '.arrow-right',
                arrowLeft: '.arrow-left',
                overflow: 'hidden',
                animationTime: 1000
            };
            
            defaults.isIE = !$.support.opacity && !$.support.style;
            defaults.isIE6 = defaults.isIE && !window.XMLHttpRequest;

            return defaults;
        },
        calculate: function (options) {

        },
        init: function (options) {
            var opts = $.extend({}, $.fn.slider('defaults'), options);

            return this.each(function () {
                var $this = $(this),
                    data = $this.data(opts.id),
                    slider = function () {

                        var html = $this.html();
                        $this.parent().prepend('<div id="'+opts.id+'-father"></div>');

                        $('#'+opts.id+'-father').css({overflow:opts.overflow, height:opts.height, width:(opts.width==''?$this.css('width'):opts.width), position: 'relative'}).prepend($this);

                        var ancho = 0,
                        anchoUnit=0,
                        distance=0,
                        wait=true;

                        $.each($this.children(), function(index, value) {
                            anchoUnit = $(value).outerWidth(true);
                            ancho += $(value).outerWidth(true);
                        });

                        var currentWidth=$this.css('width');

                        distance=parseInt(anchoUnit)*parseInt(parseInt(currentWidth)/parseInt(anchoUnit));

                        $this.css({width: ancho, position: 'absolute', left: '0px'});

                        

                        $(opts.arrowLeft).click(function(){
                            if(parseInt($this.css('left'))<=(-(ancho/2))){
                                
                            }else{
                                if(wait){
                                    wait=false;
                                    $.fn.consola({msg:'fin de desplazamiento - left',mode: 'info'});
                                    $this.animate({
                                        left: '-='+distance
                                    }, opts.animationTime, function() {
                                        $.fn.consola('destroy',{mode:'info'});
                                        wait=true;
                                    });
                                }
                            }                         

                        });

                        
                        $(opts.arrowRight).click(function(){
                            if(parseInt($this.css('left'))>=0){
                                
                            }else{    
                                $.fn.consola({msg:'fin de desplazamiento - right',mode: 'info'});
                                if(wait){
                                    wait=false;
                                    $this.animate({
                                        left: '+='+distance
                                    }, opts.animationTime, function() {
                                        $.fn.consola('destroy',{mode:'info'});
                                        wait=true;
                                    });
                                }
                            }
                        });


                        $('body').keypress(function(event) {
                            
                            switch(event.which){
                                case 13:
                                    console.log('enter');
                                break;
                                case 49:
                                    console.log('1');
                                break;
                                case 50:
                                    console.log('2');
                                break;
                                case 51:
                                    console.log('3');
                                break;
                                case 52:
                                    console.log('4');
                                break;
                                case 53:
                                    console.log('5');
                                break;
                                break;
                                default:
                                    switch(event.keyCode){
                                        case 37:
                                            $(opts.arrowLeft).trigger('click');
                                        break;
                                        case 39:
                                            $(opts.arrowRight).trigger('click');
                                        break;
                                        default:
                                           
                                                console.log('default');
                                          
                                        break;
                                    }
                                break;
                            }

                        });


                        
                    };
                if (!data) {
                    $(this).data(opts.id, {
                        target: $this,
                        slider: slider
                    });
                }
                $(this).data(opts.id).slider();
            });

        },
        destroy: function (options) {

        }
    };
    $.fn.slider = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            if (typeof method === "object" || !method) {
                return methods.init.apply(this, arguments);
            } else {
                $.error("Method " + method + " does not exist on jQuery.slider");
            }
        }
    };
})(jQuery);




$(function(){
    
    $('.slider').slider({id:'slider2'});

});