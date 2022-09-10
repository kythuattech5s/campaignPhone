<?php 
$name = FCHelper::er($table,'name');
$default_code = FCHelper::er($table,'default_code');
$default_code = json_decode($default_code,true);
$default_code = @$default_code?$default_code:array();
$value ="";
$disabled = false;
if(isset($default_code['disabled']) && $default_code['disabled']){
    $disabled = $default_code['disabled'];
    $default_code = [];
}
if($actionType=='edit'||$actionType=='copy'){
	if($name != 'price' && $name != 'price_sale')
	$value = FCHelper::er($dataItem,$name);
	else
	$value = $dataItem->$name;
}
?>
<div class="form-group">
	<p class="form-title" for=""><?php echo e(FCHelper::er($table,'note')); ?> <span class="count"></span></p>
	<input <?php echo e(FCHelper::ep($table,'require')==1?'required':''); ?>  <?php if($disabled): ?> disabled <?php endif; ?>  type="text" name="<?php echo e($name); ?>" placeholder="<?php echo e(FCHelper::ep($table,'note')); ?>" id="<?php echo e($name); ?>"  class="form-control" dt-type="<?php echo e(FCHelper::ep($table,'type_show')); ?>" value="<?php echo e($value); ?>" />
</div>
<script type="text/javascript">
	$(function() {
		<?php $__currentLoopData = $default_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<?php 
            $source = $dc['source'];
            $source = $source=='this'?"input[name=$name]":$source;
		?>
		$(document).on('input', "<?php echo e($source); ?>", function(event) {
			event.preventDefault();
			<?php if($dc['function']=='count'): ?>
                var input = $(this).val();
                $(this).parent().find('span.count').text(input.length+" Chars");
			<?php endif; ?>
			<?php if($dc['function']=='seo_title'): ?>
			    $('input[name=<?php echo e($name); ?>]').val($(this).val());
			<?php endif; ?>
			<?php if($dc['function']=='seo_desc'): ?>
			    $('input[name=<?php echo e($name); ?>]').val($(this).val());
			<?php endif; ?>
			<?php if($dc['function']=='seo_key'): ?>
			    $('input[name=<?php echo e($name); ?>]').val($(this).val());
			<?php endif; ?>
		});
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	});
</script><?php /**PATH H:\laragon\www\laravel-tech5s\/packages/vanhenry/views/ctedit/text.blade.php ENDPATH**/ ?>