<?php 
$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
$type = $itemSubControl['type'];
$rows = isset($itemSubControl['rows'])?$itemSubControl['rows']:'3';
$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
$value = isset($subValue[$name])?$subValue[$name]:$default;
 ?>
<textarea cols="30" data-name="<?php echo $name ?>"  data-type="<?php echo $type ?>" rows="<?php echo $rows ?>" class="control <?php echo $type ?> <?php echo $name ?>" value="<?php echo $default ?>"><?php echo $value ?></textarea>