<?php 
	$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
	$type = $itemSubControl['type'];
	$rows = isset($itemSubControl['rows'])?$itemSubControl['rows']:'3';
	$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
	$value = isset($subValue[$name])?$subValue[$name]:$default;
?>
<textarea class="editor control" data-type="<?php echo $type; ?>" data-name="<?php echo $name; ?>" name="<?php echo $key.$name ?>" id="<?php echo $key.$name ?>" style="visibility:visible !important" rows="4"><?php echo $value; ?></textarea>