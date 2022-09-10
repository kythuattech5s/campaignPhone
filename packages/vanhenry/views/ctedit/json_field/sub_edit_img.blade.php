<?php 
	$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
	$type = $itemSubControl['type'];
	$rows = isset($itemSubControl['rows'])?$itemSubControl['rows']:'3';
	$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
	$value = isset($subValue[$name])?$subValue[$name]:$default;
?>
<div style="" class="switch">
	<input type="hidden" data-name="<?php echo $name; ?>" class="control" data-type="<?php echo $type; ?>" value='<?php echo $value; ?>' name="<?php echo $key.$name; ?>" id="<?php echo $key.$name; ?>">
	<?php 
		$tmp = json_decode($value,true);
		$img = isset($tmp) && is_array($tmp) ?$tmp["path"].$tmp["file_name"]:'admin/images/noimage.png';  
	?>
	<img style="width:100px;" src="<?php echo $img ?>" alt="" class="<?php echo $key.$name; ?>">
	<div class="btnadmin">
		<a href="{{$admincp}}/media/view?istiny=<?php echo $key.$name; ?>" class="btn iframe-btn" type="button">Browse File ...</a>
	</div>
	<div class="btnadmin">
		<a href="#" class="btn btnchange-<?php echo $name ?>" type="button">Sửa thông tin cơ bản</a>
	</div>
	<div class="btnadmin">
		<a href="#" class="btn btndelete-<?php echo $name ?>" type="button">Xóa ảnh</a>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$(".btnchange-<?php echo $name; ?>").click(function(event) {
			event.preventDefault();
			var file = $('input[name=<?php echo $name; ?>]').val();
			file = JSON.parse(file);
			var dialog = bootbox.dialog({
				title:"Chỉnh sửa thông tin ảnh",
				onEscape:true,
			    message: '<div class="row">'
						+'<div class="col-md-6 col-xs-12 form-group">'
						+'	<label for="">Title</label>'
						+'	<input name="title" type="text" class="form-control" value="'+(file.title==null?'':file.title)+'" placeholder="Title">'
						+'	</div>'
						+'	<div class="col-md-6 col-xs-12 form-group">'
						+'		<label for="">Caption</label>'
						+'		<input name="caption" type="text" class="form-control" value="'+(file.caption==null?'':file.caption)+'" placeholder="Caption">'
						+'	</div>'
						+'	<div class="col-md-6 col-xs-12 form-group">'
						+'		<label for="">Alt</label>'
						+'		<input name="alt" type="text" class="form-control" value="'+(file.alt==null?'':file.alt)+'" placeholder="Alt">'
						+'	</div>'
						+'	<div class="col-md-6 col-xs-12 form-group">'
						+'		<label for="">Description</label>'
						+'		<input name="description" type="text" class="form-control" value="'+(file.description==null?'':file.description)+'" placeholder="Description">'
						+'	</div>'
						+'</div>',
				buttons: {
			        confirm: {
			            label: 'Lưu',
			            className: 'btn-success',
			            callback: function() {
								file.title = dialog.find("input[name=title]").val();
								file.caption = dialog.find("input[name=caption]").val();
								file.alt = dialog.find("input[name=alt]").val();
								file.description = dialog.find("input[name=description]").val();
								$('input[name=<?php echo $name; ?>]').val(JSON.stringify(file));
							}
			        },
			        cancel: {
			            label: 'Thoát',
			            callback:function(){
			            	dialog.modal("hide");
			            }
			        }
			    },
			});
			dialog.modal("show");
		});
		$(".btndelete-<?php echo $name; ?>").click(function(event) {
			event.preventDefault();
			$("input[name='<?php echo $name; ?>']").val("");
			$("img.<?php echo $name; ?>").attr("src","");
		});
	});
</script>