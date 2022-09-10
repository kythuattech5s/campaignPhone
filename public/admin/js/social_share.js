var SHARE_SOCIAL = {
	init:function(){
		if ($('#share-facebook-preview').length == 0) return;
		if ($('#share_image_facebook').length == 0) return;
		if ($('#share_title_facebook').length == 0) return;
		if ($('textarea[name=share_description_facebook]').length == 0) return;
		SHARE_SOCIAL.initView();
		SHARE_SOCIAL.changeImage();
		SHARE_SOCIAL.focusText();
		SHARE_SOCIAL._initImageBg();
		SHARE_SOCIAL._initTitle();
		SHARE_SOCIAL._initContent();
	},
	initView:function(){
		var parent = $('#share-facebook-preview').closest('.none');
		parent.removeClass('none');
		$('#share_image_facebook').closest('.row').addClass('none');
		$('#share-facebook-preview').closest('.col-xs-12').addClass('share-facebook-preview-wrapper');
	},
	changeImage:function(){
		$(document).on('click', '#share-facebook-preview .image-container', function(event) {
			event.preventDefault();
			$('#share_image_facebook').closest('.col-xs-12').find('a.browseimage').click();
		});
		$(document).on('change', '#share_image_facebook', function(event) {
			setTimeout(function(){ 
				SHARE_SOCIAL._initImageBg();
			},100);
		});
	},
	_initImageBg:function(){
		var imageContainer = $('#share-facebook-preview .image-container');
		if ($('#share_image_facebook').val() != '') {
			var imgSourse = $('#share_image_facebook').closest('.col-xs-12').find('img').attr('src');
			imageContainer.css('background-image', 'url(' + imgSourse + ')');
			imageContainer.addClass('has-image');
		}else {
			imageContainer.removeClass('has-image');
		}
	},
	focusText:function(){
		$(document).on('click', '#share-facebook-preview .title-container', function(event) {
			event.preventDefault();
			$('#share_title_facebook').focus();
		});
		$(document).on('click', '#share-facebook-preview .description-container', function(event) {
			event.preventDefault();
			$('textarea[name=share_description_facebook]').focus();
		});
		$(document).on('input', '#share_title_facebook', function(event) {
			event.preventDefault();
			SHARE_SOCIAL._initTitle();
		});
		$('textarea[name=share_description_facebook]').bind('input propertychange', function() {
			SHARE_SOCIAL._initContent();
		});
	},
	_initTitle:function(){
		var val = $('#share_title_facebook').val();
		if (val != '') {
			$('#share-facebook-preview .title-container').html(val);
		}else {
			$('#share-facebook-preview .title-container').html('Tiêu đề bài viết');
		}
	},
	_initContent:function(){
		var val = $('textarea[name=share_description_facebook]').val();
		if (val != '') {
			$('#share-facebook-preview .description-container').html(val);
		}else {
			$('#share-facebook-preview .description-container').html('Mô tả ngắn của bài viết');
		}
	}
}
$(document).ready(function($) {
	SHARE_SOCIAL.init();
});