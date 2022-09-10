<?php 
    $name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
    $type = $itemSubControl['type'];
    $default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
    $value = isset($subValue[$name])?$subValue[$name]:$default;
?>
<input type="text" data-name="<?php echo $name ?>"  data-type="<?php echo $type ?>" class="control <?php echo $type ?> <?php echo $name ?>" value="<?php echo $value ?>">