var intervalID, KotearSettings = null;

(function($){
	// Global settings
	KotearSettings = {
		item_class : '#search-result .item',
		results_panel_id : '#search-result',
		loading_box_id : '#itemsloading',
		images_url : 'http://img.kotear.pe',
		image_max_upload : 6
	};
	
	// Public Access
	$.Kotear = {
		init : function(_s){
			KotearSettings = $.extend(KotearSettings, _s);
		}
	};
	
})(jQuery);