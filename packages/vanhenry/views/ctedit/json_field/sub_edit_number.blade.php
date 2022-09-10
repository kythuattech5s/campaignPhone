<?php 
$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
$type = $itemSubControl['type'];
$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
$min = isset($itemSubControl['min'])?$itemSubControl['min']:'';
$max = isset($itemSubControl['max'])?$itemSubControl['max']:'';
$step = isset($itemSubControl['step'])?$itemSubControl['step']:'';
$value = isset($subValue[$name])?$subValue[$name]:$default;
 ?>
<input type="number" data-name="<?php echo $name ?>"  data-type="<?php echo $type ?>" min="<?php echo $min ?>"  max="<?php echo $max ?>" step="<?php echo $step ?>" class="control <?php echo $type ?> <?php echo $name ?>" value="<?php echo $value ?>">