<?php 
$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
$type = $itemSubControl['type'];
$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
 ?>

<input type="date" data-name="<?php echo $name ?>" data-type="<?php echo $type ?>" class="control <?php echo $type ?> <?php echo $name ?>" value="<?php echo $default ?>">