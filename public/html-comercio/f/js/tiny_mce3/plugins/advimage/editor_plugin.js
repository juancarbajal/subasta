/**
 * editor_plugin_src.js
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */

(function() {
	function getUploadedImages(ed){
		var tmpCurrentImages = [];
		$("#gall_photo_upload .ui-state-default").each(function(i){
			//console.log($(this));
			var imgInput = $(this).find("input:hidden[name^=pics]");
			var imgElement = $(this).find("img");
			//console.log(imgElement);
			if(imgInput.val() != "0" && imgElement.length>0){
				tmpCurrentImages.push({id:imgInput.val(), src:imgElement.attr("src")});
			}
		});
		ed.currentImages = tmpCurrentImages;
	}
	
	tinymce.create('tinymce.plugins.AdvancedImagePlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceAdvImage', function() {
				getUploadedImages(ed);
				
				// Internal image object like a flash placeholder
				if (ed.dom.getAttrib(ed.selection.getNode(), 'class').indexOf('mceItem') != -1)
					return;

				ed.windowManager.open({
					file : url + '/image.htm',
					width : 530 + parseInt(ed.getLang('advimage.delta_width', 0)),
					height : 420 + parseInt(ed.getLang('advimage.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('image', {
				title : 'advimage.image_desc',
				cmd : 'mceAdvImage'
			});
			
			ed.addCommand('mceAdvImageEraseById', function(ui, v) {
				ed.dom.remove(v);
			});
		},

		getInfo : function() {
			return {
				longname : 'Advanced image',
				author : 'Moxiecode Systems AB & Erik Porroa',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/advimage',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('advimage', tinymce.plugins.AdvancedImagePlugin);
})();