<?php 
	$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
	$type = $itemSubControl['type'];
	$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
	$value = isset($subValue[$name])?$subValue[$name]:$default;
?>
<input type="checkbox" style="width: 18px!important;height: 18px;" data-name="<?php echo $name ?>"  data-type="<?php echo $type ?>" class="control <?php echo $type ?> <?php echo $name ?>" value="<?php echo $value ?>" <?php echo $value == 1?'checked':'' ?>>
<script type="text/javascript">
	$(function(){
		$('input[data-name="<?php echo $name ?>"]').click(function(){
			$(this).val($(this).is(':checked')?"1":"0");
		})
	});
</script>
