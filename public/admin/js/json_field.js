var ELEMENTOR_JSON_FIELD = function(clazz){
	this.currentClazz = clazz;
	this.baseurl = $('base').attr("href");
	this.admincp = 'Techsystem';
	var self1 = this;
	this.uniqueID = function () {
      return 'elementor_json_field_' + Math.random().toString(36).substr(2, 9);
    };
	this.initAddItem = function(){
		var self = this;
		$('.elementor_json_field .add-'+self.currentClazz).click(async function(event) {
			event.preventDefault();
			var html = $(this).closest('.elementor_json_field').find('.hidden-item').html();
			var fdivs = $(html).find('.elementor_json_field_control_content > div');
			for(var i = 0;i<fdivs.length;i++){
				var uniqueID = self.uniqueID();
                var div = $(fdivs[i]);
                var oldId = div.attr('class');
                var dataType = div.attr('data-type');
			    html = html.replace(new RegExp(oldId, 'g'), uniqueID);
                self.initNewGalleryControl(dataType,uniqueID);
            }
			$('.elementor_json_field .list-items-'+self.currentClazz).append(html);
			await self.initEditor();
		});
	}
	this.initNewGalleryControl = function(dataType,uniqueID){
		if(dataType == 'sub_edit_gallery'){
			window['uniqueID'] = new GALLERY_CONTROL_JSON_FIELD(uniqueID);
			window['uniqueID'].init();
        }
	}
	this.initCloseItem = function(){
		var self = this;
		$(document).on('click', '.elementor_json_field .list-items-'+self.currentClazz+' span.close', function(event) {
			event.preventDefault();
			$(this).parents('.item').remove();
			self.getDataValue();
		});
	}
	this.initChange = function(){
		var self = this;
		$(document).on('change', '.elementor_json_field .list-items-'+self.currentClazz+' .item .gallery,.elementor_json_field .list-items-'+self.currentClazz+' .item .text,.elementor_json_field .list-items-'+self.currentClazz+' .item .textarea,.elementor_json_field .list-items-'+self.currentClazz+' .item .number,.elementor_json_field .list-items-'+self.currentClazz+' .item .checkbox', function(event) {
			event.preventDefault();
			self.getDataValue();
		});
	}
	this.getDataValue = function (){
		var items = $('.list-items-'+this.currentClazz+' .item');
		var objs = [];
		for (var i = 0; i < items.length; i++) {
			var item = $(items[i]);
			var controls = item.find('.control');
			var tmp = {};
			for (var j = 0; j < controls.length; j++) {
				var control = $(controls[j]);
				var type = control.data('type');
				var name = control.data('name');
				var fnc = 'getDataValue'+type;
				if(typeof this[fnc] === "function"){
					tmp[name] = this[fnc](control);
				}
			}
			objs.push(tmp);
		}
		$('#'+this.currentClazz).text(JSON.stringify({...objs}));
	}
	this.callbackImage = function(items,field){
		if(items.length==0) return;
		var item = items[0];
		var url = item.path+item.file_name;
		$('img.'+field).attr('src',url);
		$('textarea.'+field).val(JSON.stringify(item));
		this.getDataValue();
	}
	this.callbackFile = function(items,field){
		if(items.length==0) return;
		var item = items[0];
		var url = item.path+item.file_name;
		$('input.'+field).val(url);
		$('textarea.'+field).val(JSON.stringify(item));
		this.getDataValue();
	}
	this.changeDetailImage = function (id){
			var tmpClazz = this.currentClazz;
	    	var file = $('textarea.'+id).val();
			var json = {title:"",caption:"",alt:"",description:""};
			try {
				json = JSON.parse(file);
			} catch(e) {

			}

			var str = `<input type="hidden" value="" name="id">
		<div class="row">
			<div class="col-md-6 col-xs-12 form-group">
				<label for="">Title</label>
				<input name="title" type="text" class="form-control" value="`+(json.title ==undefined?'':json.title)+`" placeholder="Title">
			</div>
			<div class="col-md-6 col-xs-12 form-group">
				<label for="">Caption</label>
				<input name="caption" type="text" class="form-control" value="`+(json.caption ==undefined?'':json.caption)+`" placeholder="Caption">
			</div>
			<div class="col-md-6 col-xs-12 form-group">
				<label for="">Alt</label>
				<input name="alt" type="text" class="form-control" value="`+(json.alt ==undefined?'':json.alt)+`" placeholder="Alt">
			</div>
			<div class="col-md-6 col-xs-12 form-group">
				<label for="">Description</label>
				<input name="description" type="text" class="form-control" value="`+(json.description ==undefined?'':json.description)+`" placeholder="Description">
			</div>
		</div>`;
		var dialog = bootbox.dialog({
	    	title: "Chỉnh sửa thông tin hình ảnh.",
	    	message: str,
	    	buttons: {
		        success: {
		        	label: "Save",
		        	className: "btn-success",
		        	callback: function () {
		            	json.title = dialog.find("input[name=title]").val();
						json.caption = dialog.find("input[name=caption]").val();
						json.alt = dialog.find("input[name=alt]").val();
						json.description = dialog.find("input[name=description]").val();
						$('textarea.'+id).val(JSON.stringify(json));
						window['elementor_json_field_'+tmpClazz].getDataValue();
		          	}
		        }
	      	}
	    });

	}
	this.deleteImage = function (id){
		$('img.'+id).attr('src','admin/images/noimage.png');
		$('textarea.'+id).val('');
		this.getDataValue();
	}
 	this.toCamel = function(str){
        return str.replace(
            /([-_][a-z])/g,function(group){
            	return group.toUpperCase()
                            .replace('-', '')
                            .replace('_', '');
            }
        );   
    }
    this.mergeArray = function (obj1,obj2){
        var obj3 = {};
        for (var attrname in obj1) { var new_attrname = (attrname); obj3[new_attrname] = obj1[attrname]; }
        for (var attrname in obj2) { var new_attrname = (attrname); obj3[new_attrname] = obj2[attrname]; }
        return obj3;
    }
    this.myCustomInitInstance = function(ins){
	    var height = $('textarea#'+ins.id).attr('dt-height');
	    if(height==undefined|| height==""){

	    }
	    else{
	        $('#'+ins.id+"_ifr").height(height);
	    }
	}
	this.fixTinymceFileManager = function (t, i, a, s) {
		     e=tinymce.activeEditor;
		        var r = $(window).innerWidth() - 30,
		            g = $(window).innerHeight() - 60;
		        if (r > 1800 && (r = 1800), g > 1200 && (g = 1200), r > 600) {
		            var d = (r - 20) % 138;
		            r = r - d + 10
		        }
		        urltype = 2, "image" == a && (urltype = 1), "media" == a && (urltype = 3);
		        var o = "Tech5s FileManager";
		        "undefined" != typeof e.settings.filemanager_title && e.settings.filemanager_title && (o = e.settings.filemanager_title);
		        var l = "key";
		        "undefined" != typeof e.settings.filemanager_sort_by && e.settings.filemanager_sort_by && (f = "&sort_by=" + e.settings.filemanager_sort_by);
		        var m = "false";
		        "undefined" != typeof e.settings.filemanager_descending && e.settings.filemanager_descending && (m = e.settings.filemanager_descending);
		        var v = "";
		        "undefined" != typeof e.settings.filemanager_crossdomain && e.settings.filemanager_crossdomain && (v = "&crossdomain=1", window.addEventListener ? window.addEventListener("message", n, !1) : window.attachEvent("onmessage", n)), tinymce.activeEditor.windowManager.open({
		            title: o,
		            file: e.settings.external_filemanager_path+"?istiny=2",
		            width: r,
		            height: g,
		            resizable: !0,
		            maximizable: !0,
		            inline: 1
		        }, {
		            setUrl: function(n) {
		                var i = s.document.getElementById(t);
		                if (i.value = e.convertURL(n), "createEvent" in document) {
		                    var a = document.createEvent("HTMLEvents");
		                    a.initEvent("change", !1, !0), i.dispatchEvent(a)
		                } else i.fireEvent("onchange");
		                 tinymce.activeEditor.windowManager.close();
		            }
		        })
		}
	this.initEditor = function(){
		console.log(this.currentClazz);
		var items = $('.list-items-'+this.currentClazz+' textarea.seditor');
		var self = this;
		for (var i = 0; i < items.length; i++) {
			var item = $(items[i]);
			var data = item.data();
			var options = this.mergeArray({
			    height: 300,
			    theme: 'modern',
			    plugins: [
			    "advlist autosave autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			    "save table contextmenu directionality emoticons template paste tech5sfilemanager textcolor colorpicker autoresize"
			    ],
			    toolbar: "image",
			    toolbar1:"bold italic underline hr | alignleft aligncenter alignright alignjustify | bullist numlist",
			    toolbar2:"formatselect fontselect fontsizeselect | link unlink table | forecolor backcolor | tech5sfilemanager | image | mathSymbols",
			    toolbar3:"",
			    sticky_offset: 45,
			    image_advtab: true ,
			    style_formats: [
				    {title: 'Bold text', inline: 'b'},
				    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
				    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
				    {title: 'Example 1', inline: 'span', classes: 'example1'},
				    {title: 'Example 2', inline: 'span', classes: 'example2'},
				    {title: 'Table styles'},
				    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
			    ],
			    fontsize_formats: '10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 30px 36px',
			    lineheight_formats: "10px 11px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px 42px 46px 50px",
			    inline_styles : true,
			    entity_encoding : "raw",
			    document_base_url:this.baseurl,
			    rel_list: [
			        {title: 'None', value: ''},
			        {title: 'No Follow', value: 'nofollow'},
			        {title: 'No Referrer', value: 'noreferrer'},
			        {title: 'External Link', value: 'external'},
			        {title: 'Paid links', value: 'sponsored'},
			        {title: 'UGC', value: 'ugc'}
			    ],
			    image_advtab: true,
			    init_instance_callback:this.myCustomInitInstance,
			    external_filemanager_path:this.baseurl+this.admincp+'/Media/media',
			    filemanager_title:"Tech5s File Manager" ,
			    external_plugins: { "filemanager" : this.baseurl+"admin/plug/tinymce/plugin.min.js",
							"mathSymbols": this.baseurl+"admin/plug/tinymce/plugins/mathsymbols-tinymce-plugin/plugin.js"},
			    file_browser_callback: function(field_name, url, type, win) {
			        func_value = win.document.getElementById(field_name).value;
			        fixTinymceFileManager(field_name, func_value, type, win); 
			    },
			    setup: function (editor) {
		            editor.on('change', function () {
		                editor.save();
		                self.getDataValue();
		            });
		        }

			  },data||{});
			item.tinymce(options);
		}
	}
	this.getDataValuegallery = function(item){
		return $(item).val();
	},
	this.getDataValuetext = function(item){
		return $(item).val();
	},
	this.getDataValuecolor=function(item){
		return this.getDataValuetext(item);
	},
	this.getDataValuecheckbox=function(item){
		return this.getDataValuetext(item);
	},
	this.getDataValuetextarea=function(item){
		return this.getDataValuetext(item);
	},
	this.getDataValuenumber=function(item){
		return this.getDataValuetext(item);
	},
	this.getDataValueimage=function(item){
		return this.getDataValuetext(item);
	},
	this.getDataValuefile=function(item){
		return this.getDataValuetext(item);
	},
	this.getDataValueseditor=function(item){
		return this.getDataValuetext(item);
	},
	this.init = function(){
		this.initAddItem();
		this.initCloseItem();
		this.initChange();
		this.initEditor();
	}
}
var ELEMENTOR_JSON_FIELD_PROVIDER = (function(){
	var callbackImage = function(items,field){
		var variable = $('textarea.'+field).data('variable');
		window[variable].callbackImage(items,field);
	}
	var callbackFile = function(items,field){
		var variable = $('textarea.'+field).data('variable');
		window[variable].callbackFile(items,field);
	}
	return {
		callbackImage:function(items,field){
			callbackImage(items,field);
		},
		callbackFile:function(items,field){
			callbackFile(items,field);
		}
	}
})();