var KotearImageDialog = {
	IMAGE_THUMB_SIZE : "150",
	IMAGE_ORIGINAL : "/original/",
	IMAGE_MEDIUM : "/original/",
	IMAGE_THUMB : "/original/",
	alt : "Foto de muestra",
	width : "250",
	height : "250",
	vspace : "5",
	hspace : "5",
	border : "0",

	resetImageData : function() {	
	},

	getImageData : function() {
		var f = document.forms[0];

		this.preloadImg = new Image();
		this.preloadImg.onerror = this.resetImageData;
		this.preloadImg.src = tinyMCEPopup.editor.documentBaseURI.toAbsolute(f.src.value);
	},
	
	updateImageData : function(id, url, trgt) {
		$("#src").val(url);
		$("#id").val(id);
		$(trgt).siblings(".imageinput").removeClass("selected").end().addClass("selected");
	},
	
	reFormatURL : function (type, src){ 
		return src;
	}

};