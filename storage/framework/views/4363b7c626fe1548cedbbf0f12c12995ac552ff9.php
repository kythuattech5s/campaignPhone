<?php 
$name = FCHelper::er($table,'name');
$default_code = FCHelper::er($table,'default_code');
$default_code = json_decode($default_code,true);
$default_code = @$default_code&& count($default_code)>0?$default_code[0]:array();
$height = FCHelper::er($default_code,'height');
$value ="";
if($actionType=='edit'||$actionType=='copy'){
	$value = FCHelper::er($dataItem,$name);
}
 ?>
<div class="form-group">
  <p class="form-title" for=""><?php echo e(FCHelper::er($table,'note')); ?></p>
  <textarea placeholder="<?php echo e(FCHelper::er($table,'note')); ?>" <?php echo e(FCHelper::ep($table,'require')==1?'required':''); ?>  dt-height="<?php echo e($height); ?>" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" class="form-control editor" rows="5" dt-type="<?php echo e(FCHelper::er($table,'type_show')); ?>"><?php echo e($value); ?></textarea>
</div><?php /**PATH H:\laragon\www\laravel-tech5s\/packages/vanhenry/views/ctedit/editor.blade.php ENDPATH**/ ?>