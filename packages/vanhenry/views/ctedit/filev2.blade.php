<?php 
$name = FCHelper::er($table,'name');
$default_code = FCHelper::er($table,'default_code');
$default_code = json_decode($default_code,true);
$default_code = @$default_code?$default_code:array();
$value ="";
$img = 'admin/images/noimage.png';
if($actionType=='edit'||$actionType=='copy'){
	$value = FCHelper::ep($dataItem,$name);
	$tmp = json_decode($value,true);
	$filePath = isset($tmp) && is_array($tmp) ?$tmp["path"].$tmp["file_name"] : ''; 
}
?>
<div class="row">
	<p class="col-xs-12 form-title">{{FCHelper::ep(($tableMap=='configs'?$dataItem:$table),'note')}}</p>
	<div class="col-xs-12">
		<img src="{{$img}}" style="display: none" class="{{$name}} w100">
		<input placeholder="{{FCHelper::er($table,'note')}}" class="{{$name}}" type="hidden" value="{{$value}}" name="{{$name}}" id="{{$name}}">
		<input type="text" id="preview_{{$name}}" class="form-control" value="{{$filePath ?? ''}}">
		<div class="form-group textcenter">
			<a href="{{$admincp}}/media/view?istiny={{$name}}" class="browseimage bgmain btn btn-primary iframe-btn" type="button">Chọn File</a>
			<button style="margin-top: 15px;margin-left: 5px;" class="btndelete-{{$name}} bgmain btn btn-primary">{{trans('db::delete')}}</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		$('.btndelete-{{$name}}').click(function(event) {
			event.preventDefault();
			$("input[name='{{$name}}']").val("");
			$("input[name='{{$name}}']").prev().attr("src","");
			$('#preview_{{$name}}').val("");
		});
		$(document).on('change', 'input[name={{$name}}]', function(event) {
			var infoFile = JSON.parse($(this).val());
			$('#preview_{{$name}}').val(infoFile.path+infoFile.file_name);
		});
	});
</script>