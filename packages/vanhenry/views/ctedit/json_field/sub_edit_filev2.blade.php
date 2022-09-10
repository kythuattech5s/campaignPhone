<?php 
	$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
	$type = $itemSubControl['type'];
	$rows = isset($itemSubControl['rows'])?$itemSubControl['rows']:'3';
	$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
	$value = isset($subValue[$name])?$subValue[$name]:$default;
?>
<div style="" class="switch">
	<input onchange="changeShow<?php echo $key.$name ?>(this)" type="hidden" data-name="<?php echo $name; ?>" class="control" data-type="<?php echo $type; ?>" value='<?php echo $value; ?>' name="<?php echo $key.$name; ?>" id="<?php echo $key.$name; ?>">
	<?php 
		$tmp = json_decode($value,true);
		$img = isset($tmp) && is_array($tmp) ?$tmp["path"].$tmp["file_name"]:'';  
	?>
	<input onchange="changeShow<?php echo $key.$name ?>(this)" type="file_video" value="<?php echo $img; ?>">
	<div class="btnadmin">
		<a href="{{$admincp}}/media/view?istiny=<?php echo $key.$name ?>" class="btn iframe-btn" type="button">Browse File ...</a>
	</div>
	</div>
<script type="text/javascript">
	function changeShow<?php echo $key.$name ?>(_this){
		var val = $(_this).val();
		try{
			var val = JSON.parse(val);
			$(_this).next(val.path+val.file_name);
		}
		catch{}
	}
	function changeHide<?php echo $key.$name ?>(_this){
		var val = $(_this).val();
		try{
			if(val.trim().length==0){
				$(_this).prev().val("");
			}
		}
		catch{}
	}
</script>