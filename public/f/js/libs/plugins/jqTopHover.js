/**
* topHover - efecto de slideup parametrizado para globitos de nuevo/oferta de un producto
*/
$.fn.topHover = function(options){
var defaults = {
    topHover: '',
    topOut: '0px',
    speed: 250,
    selectors: []
}
var opts = $.extend({}, defaults, options);
var up = function(that, action){
    for(id in opts.selectors){
        if(typeof opts.selectors[id] != 'function'){
            if($(that).find(opts.selectors[id]).size()==1){
            if(opts.topOut==''){
                opts.topOut = $(that).find(opts.selectors[id]).css('top');
            }
            $(that).find(opts.selectors[id]).animate({
                top: action
            }, opts.speed);
            }
        }
    }
};
$(this).hover(
    function (){
        up(this,opts.topHover);
    },
    function () {
        up(this,opts.topOut);
    }
);
};