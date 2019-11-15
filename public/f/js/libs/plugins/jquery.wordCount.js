(function($){
    String.prototype.trim=function(){a=this.replace(/^\s+/,'');return a.replace(/\s+$/,'');};
    String.prototype.ltrim=function(){return this.replace(/^\s+/,'');}
    String.prototype.rtrim=function(){return this.replace(/\s+$/,'');}
    String.prototype.fulltrim=function(){return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');}
    String.prototype.getWords = function(){ return (this.trim())? this.trim().split(/[\s\+\,\-\_\|\.\?]+/):[]; }
    $.fn.extend({
        wordCount: function(options){
        if(this && this.length){
        var wcounts = $.data(this[0], 'WCounts');
        if(wcounts){
        return wcounts;
        }
        }
        return this.each(function(){
        var wcounts = new $.WCounts(this, options);
        $.data(this, 'WCounts', wcounts);
        });
        }
    })
    $.WCounts = function($this, options){
        this.s = $.extend(true, {}, $.WCounts.defaults, options);
        this.inputbox = $this;
        this.total_words = 0;
        this.init();
    };
    $.extend($.WCounts, {
        defaults:{
            counterElement:"display_count",
            minWords:1,
            maxWords: 9,
            onOverflow:false,
            onRegular:false
        },
        prototype: {
            setSettings: function(obj){
                this.s = $.extend(this.s, obj);
                this.chekStatus();
            },
            init: function(){
                var this_ = this;
                $(this.inputbox).bind('keypress.wcount, keyup.wcount, blur.wcount', function(e){
                this_.chekStatus();
                });
                this.putTextCount();
            },
            isInValid: function(){
                return (this.inputbox.value.getWords().length > this.s.maxWords);
            },
            chekStatus: function(){
                if(this.isInValid()){
                this.s.onOverflow && this.s.onOverflow.apply(this, [this.inputbox]);
                }else{
                this.s.onRegular && this.s.onRegular.apply(this, [this.inputbox]);
                }
                this.putTextCount();
            },
            putTextCount: function(){
                var wordValid = this.s.maxWords - this.inputbox.value.getWords().length;
                /* permite no mostrar el valor de -1 */
                if(wordValid == -1){
                    return false;
                }else{
                    $('#' + this.s.counterElement).html(wordValid);
                }
            }
        }
    });
})(jQuery);