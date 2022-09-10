var GALLERY_CONTROL_JSON_FIELD = function(clazz){
	this.targetClazz = clazz;
	console.log(clazz);
	this.currrentClazz = '.gallery_ul_'+clazz;
	this.currentGallery = $(this.currrentClazz);
	this.uniqueID = function () {
      return 'gallery_contron_admin_json_field' + Math.random().toString(36).substr(2, 9);
    };
    this.createContextMenu = function() {
    	var tmpClazz = this.targetClazz;
        $.contextMenu({
            selector: this.currrentClazz+' .gallery-item',
            callback: function(key, options) {
                switch (key) {
                    case 'delete':
                        $(this).remove();
                        window[tmpClazz].changeValueGallery();
                        break;
                    case 'duplicate':
                        var img = $(this).find('img');
                        var id = window[tmpClazz].uniqueID();
                        var src = img.attr('src');
                        var name = img.attr('name');
                        var item = JSON.parse(img.attr('dt-file'));
                        var html = window[tmpClazz].getHtmlItem(id,src,name,item);
        				$(html).insertAfter(this);
        				window[tmpClazz].changeValueGallery();
                        break;
                    case 'edit':
                        window[tmpClazz].showPopupDetail(this);
                        break;
                }
            },
            items: {
                "edit": {
                    name: "Edit"
                },
                "duplicate": {
                    name: "Duplicate"
                },
                "delete": {
                    name: "Delete"
                },
                "sep1": "---------",
                "close": {
                    name: "Close"
                }
            }
        });
    }
    this.getHtmlItem = function(id,src,name,item){
    	var json = JSON.stringify(item);
    	var html = `<li class="col-sm-2 col-xs-12 gallery-item">
                 <div>
                    <span tagname="gallery"></span> <img class="img-responsive " dt-file='`+json+`' name="`+id+`" id="`+id+`" rel="lib_img" dt-value="" src="`+src+`" alt="`+name+`"> 
                    <p>`+name+`</p>
                    <i class="fa fa-times icon-remove gallery-close" aria-hidden="true"></i> <a href="esystem/media/view?istiny=`+id+`&callback=GALLERY_CONTROL_ADMIN_PROVIDER_JSON_FIELD.callback" class="iframe-btn button" type="button">Chọn hình</a> 
                 </div>
              </li>`;
        return html;
    }
	this.initAddFile =function(){
		var self = this;
        $(this.currrentClazz+'_add').click(function(event) {
        	event.preventDefault();
        	var id = self.uniqueID();
			var html = self.getHtmlItem(id,'theme/frontend/plugins/gallery_control_admin/theme/images/no-image.svg','New Image',{});
        	self.currentGallery.append(html);
        });
	}
	this.initDelete = function(){
		var self = this;
		$(document).on('click', this.currrentClazz+' .gallery-close', function(event) {
			event.preventDefault();
        	$(this).parents('.gallery-item').remove();
        	self.changeValueGallery();
		});
	}
	this.initSortable = function (){
		var tmpClazz = this.targetClazz;
		console.log(this.currentGallery);
		this.currentGallery.sortable({
		    tolerance: 'pointer',
		    items: "> li",
		    opacity: 0.5,
		    start: function(event, ui) {
		        clone = $(ui.item[0].outerHTML).clone();
		    },
		    helper: function(event, ui) {
		        var $clone = $(ui).clone();
		        $clone.css('position', 'absolute');
		        return $clone.get(0);
		    },
		    cursor: "move",
		    placeholder: {
		        element: function(clone, ui) {
		            return $('<li class="selected col-sm-3 col-xs-12 gallery-item gallery-item ">' + clone[0].innerHTML + '</li>');
		        },
		        update: function() {
		            return;
		        }
		    },
		    stop: function(event, ui) {
		    	window[tmpClazz].changeValueGallery();
		    }
		});
	}
	this.changeValueGallery=function (){
		var tmpClazz = this.targetClazz;
	    var arr = this.currentGallery.find('img');
	    var json =[];
	    for (var i = 0; i < arr.length; i++) {
	      	var item = arr[i];
	      	var tmp = JSON.parse($(item).attr('dt-file'));
	      	json.push(tmp);
	  	};
	  	str = JSON.stringify(json);
	  	$('textarea#'+tmpClazz).text(str);
	  	$('textarea#'+tmpClazz).trigger('change');
	}
	this.showPopupDetail = function (_this){
		var tmpClazz = this.targetClazz;
    	var file = $(_this).find('img').attr('dt-file');
		var json = {title:"",caption:"",alt:"",description:""};
		try {
			json = JSON.parse(file);
		} catch(e) {
		}
		var str = `<input type="hidden" value="" name="id">
			<div class="row">
				<div class="col-md-6 col-xs-12 form-group">
					<label for="">Title</label>
					<input name="title" type="text" class="form-control" value="`+json.title+`" placeholder="Title">
				</div>
				<div class="col-md-6 col-xs-12 form-group">
					<label for="">Caption</label>
					<input name="caption" type="text" class="form-control" value="`+json.caption+`" placeholder="Caption">
				</div>
				<div class="col-md-6 col-xs-12 form-group">
					<label for="">Alt</label>
					<input name="alt" type="text" class="form-control" value="`+json.alt+`" placeholder="Alt">
				</div>
				<div class="col-md-6 col-xs-12 form-group">
					<label for="">Description</label>
					<input name="description" type="text" class="form-control" value="`+json.description+`" placeholder="Description">
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
						$(_this).find('img').attr("dt-file",JSON.stringify(json));
						window[tmpClazz].changeValueGallery();
		          	}
		        }
	      	}
	    });
	}
	this.init = function(){
		this.initSortable();
		this.createContextMenu();
		this.initAddFile();
		this.initDelete();
	}
	this.callback = function(items,field,idMain){
		if(items.length==0)return;
		var item = items[0];
        var url = item.path+item.file_name;
        var html = this.getHtmlItem(field,url,item.file_name,item);
        var currentItem = $('#'+field).parents('.gallery-item');
        if(currentItem.length==0)return;
        currentItem[0].outerHTML = html;
        this.changeValueGallery();
	}
	this.callback_multi = function(items, field){
		for (var i = 0; i < items.length; i++) {
			var item = items[i];
        	var url = item.path+item.file_name;
        	var id = this.uniqueID();
        	var html = this.getHtmlItem(id,url,item.file_name,item);
        	this.currentGallery.append(html);
		}
    	this.changeValueGallery();
	}
}
var GALLERY_CONTROL_ADMIN_PROVIDER_JSON_FIELD = (function(){
	var callback = function(items,field){
		var box = $('#'+field).parents('.box-gallery');
		var variable = box.data('variable');
		window[variable].callback(items,field);
	}
	var callback_multi = function(items,field){
		window[field].callback_multi(items,field);
	}
	return {
		callback:function (items,field,idMain){
			callback(items,field);
		},
		callback_multi:function (items,field){
			callback_multi(items,field);
		}
	}
})();