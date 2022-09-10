var TECH5SYOAST = (function(){
	var blockSeo = null;
	var globalApp = null;
	var _initHiddenOldField = function(){
		var items = ['[name=seo_title]','[name=seo_des]','[name=seo_key]'];
		for (var i = 0; i < items.length; i++) {
			var item = $(items[i]);
			item.closest('.form-group').hide();
		}
	}
	var _updateYoastSlug = function(){
		$(document).on('input', '[name="name"]', function(event) {
			event.preventDefault();
			$('#snippet-editor-slug').val($('input[name="slug"]').val());
		});
		$(document).on('input', '[name="slug"]', function(event) {
			event.preventDefault();
			$('#snippet-editor-slug').val($(this).val());
		});

	}
	var _initYoastSeoHtml = function(){
		blockSeo = $('textarea[name="yoast_score"]').parent();
		blockSeo.removeClass('none');
		blockSeo.append('<div class="row margin0"><div id="wpseosnippet" class="wpseosnippet col-xs-12"></div></div>\
			<div class="row margin0">\
			<div class="col-xs-12 col-md-6"><label for="keyword_focus">Từ khóa Focus</label><input id="keyword_focus" placeholder="Keyword Focus" /></div><div class="col-xs-12 col-md-6"></div>\
			</div>\
			<div class="row margin0">\
			<div class="col-xs-12 col-md-6"><p class="yoast_seo_custom_title">ĐỘ CHUẨN SEO<span class="wpseo-score-icon seo"></span></p><div id="wpseo-pageanalysis"></div></div>\
			<div class="col-xs-12 col-md-6"><p class="yoast_seo_custom_title">KHẢ NĂNG ĐỌC<span class="wpseo-score-icon read"></span></p><div id="yoast-seo-content-analysis"></div></div>');
		$('.header-top .breadc').append('\
			<div align="center">\
				<p class="toolbar-score">Điểm Đọc: <span class="wpseo-score-icon read "></span></p>\
				<p class="toolbar-score">Điểm SEO: <span class="wpseo-score-icon seo "></span></p>\
			</div>\
			')
		$('input#keyword_focus').val($('[name=seo_key]').val());
		$(document).on('input', 'input#keyword_focus', function(event) {
			event.preventDefault();
			$('[name=seo_key]').val($(this).val());
		});
	}
	var _initTranslator = function  () {
	   	fetch('admin/tech5s_yoast_seo/theme/json/vi.json').then(function(response){
	   		return response.json();
	   	}).then(function(json){
	   		_initYoastSeoJs({
			"js-text-analysis": {
		"": {}}});
	   	}) .catch(function(error)  {
        	console.log("OMG! Error:");
	        console.log(error);
	    });
	}
	var getTitle = function (){
		var name = $("input[name=name]").val();
		var s_title = $("input[name=seo_title]").val();
		return s_title.trim().length==0?name:s_title;
	}
	var getSlug = function (){
		var slug = $("[name=slug]").val();
		return slug;
	}
	var getDescSeo = function (){
		var s_des = $("[name=seo_des]").val();
		return s_des;
	}
	var getKeySeo = function (){
		var s_key = $("[name=seo_key]").val();
		return s_key;
	}
	var getText = function (){
		var content = $("[name=content]");
		var id = content.attr("id");
		var content = "";
		try {
			content = tinyMCE.get(id).getContent();
		} catch(e) {}
		return content;
	}
	var getExcerpt = function (){
		var short_content = $("[name=short_content]");
		if(short_content.length==0) return '';
		var id = short_content.attr("id");
		var content = "";
		try {
			content = tinyMCE.get(id).getContent();
		} catch(e) {}
		return content;
	}
	var setDataSeo = function (data){
		var title = data.title;
		var metaDesc = data.metaDesc;
		var urlPath = data.urlPath;
		$("input[name=seo_title]").val(title);
		$("[name=seo_des]").val(metaDesc);
		$("[name=slug]").val(urlPath);
	}
	var saveScores = function  (score){
		if(globalApp==undefined){
			return '';
		}
		score /= 10;
        var seoAssessorPresenter= globalApp.seoAssessorPresenter;
        var textScore =  0 === score ? "feedback" : score <= 4 ? "bad" : score > 4 && score <= 7 ? "ok" : score > 7 ? "good" : "";
        var result= seoAssessorPresenter.getIndicator(textScore);
        $('.wpseo-score-icon.read').attr('class','wpseo-score-icon read '+result.className).attr('title',result.fullText +" - " + score);
        var current = $('textarea[name=yoast_score]').val();
        var objcurrent = {};
        try {
        	var objcurrent = JSON.parse(current);
        } catch(e) {}
        objcurrent.read = {class:result.className,score:score};
        $('textarea[name=yoast_score]').val(JSON.stringify(objcurrent));
	}
	var saveContentScore = function (score){
		if(globalApp==undefined){
			return '';
		}
		score /= 10;
        var contentAssessorPresenter= globalApp.contentAssessorPresenter;
        var textScore =  0 === score ? "feedback" : score <= 4 ? "bad" : score > 4 && score <= 7 ? "ok" : score > 7 ? "good" : "";
        var result= contentAssessorPresenter.getIndicator(textScore);
        $('.wpseo-score-icon.seo').attr('class','wpseo-score-icon seo '+result.className).attr('title',result.fullText + " - "+ score );
        var current = $('textarea[name=yoast_score]').val();
        var objcurrent = {};
        try {
        	var objcurrent = JSON.parse(current);
        } catch(e) {}
        objcurrent.seo = {class:result.className,score:score};
        $('textarea[name=yoast_score]').val(JSON.stringify(objcurrent));
	}
	var _initYoastSeoJs = function(json){
		var trans  = {
			domain: "js-text-analysis",
			locale_data: json
		};
		var jed = YOASTSEO.AnalysisWebWorker.createI18n(trans);
		var snippetPreview = new YOASTSEO.SnippetPreview({
				targetElement:document.getElementById('wpseosnippet'),
				data: {
					title: getTitle(),
					metaDesc: getDescSeo(),
					urlPath: getSlug(),
					titleWidth: 299,
					metaHeight: 18
				},
				placeholder: {
					title: "Tiêu đề SEO mặc định, nhấn vào để sửa",
					metaDesc: "Mô tả SEO mặc định, nhấn vào để sửa nội dung",
					urlPath: ""
				},
				defaultValue: {
					title: '',
					metaDesc: ""
				},
				baseURL: $('base').attr('href'),
				callbacks: {
					saveSnippetData: function saveSnippetData(data) {
						setDataSeo(data);
					}
				},
				i18n: jed,
				addTrailingSlash: true,
				metaDescriptionDate: "",
				previewMode: "desktop"
			});
		
		globalApp = new YOASTSEO.App({
			elementTarget:["content","name","keyword_focus"],
			callbacks:{
				getData: function(){
					return {
							keyword: getKeySeo(),
							meta: getDescSeo(),
							text: getText(),
							title: getTitle(),
							url: getSlug(),
							excerpt: getExcerpt(),
							snippetTitle: getTitle(),
							snippetMeta: getDescSeo(),
							snippetCite: "",
							primaryCategory: "",
						};
				},
				/*bindElementEvents: function() {
					console.log('bindElementEvents');
					console.log(arguments);
				},
				updateSnippetValues: function() {
					console.log('updateSnippetValues');
					console.log(arguments);
				},*/
				saveScores: function() {
					console.log('saveScores');
					// console.log(arguments);
					saveContentScore(arguments[0]);
				},
				saveContentScore: function() {
					console.log('saveContentScore');
					saveScores(arguments[0]);
				},
			/*	updatedContentResults: function() {
					console.log('updatedContentResults');
					console.log(arguments);
				},
				updatedKeywordsResults: function() {
					console.log('updatedKeywordsResults');
					console.log(arguments);
				}*/
			},
			locale: "vi_VN",
			translations:trans,
			targets:
			{
				output: "wpseo-pageanalysis",
				contentOutput: "yoast-seo-content-analysis"
			}
			,
			snippetPreview: snippetPreview,
			contentAnalysisActive:1,
			keywordAnalysisActive:1
		});
		globalApp.bindInputEvent();
		tinyMCE.get('content').on("change", function (a) {
            globalApp.refresh();
        });
	}
	_initHiddenOldField();
	_updateYoastSlug();
	_initYoastSeoHtml();
	var interval = setInterval(function(){
		if ( typeof( tinyMCE) != "undefined" ) {
		    if ( tinyMCE.activeEditor != null || !tinyMCE.activeEditor.isHidden() ) {
		        _initTranslator();
		        clearInterval(interval);
		    }
		}
		else{
			debugger;
		}
	}, 100);
	var intervalApp = setInterval(function(){
		if(globalApp!=null){
			clearInterval(intervalApp);
			globalApp._pureRefresh();
		}
	}, 300);
	
})();