<?php 
	$name = FCHelper::er($table,'name');
	$default_code = FCHelper::er($table,'default_code');
	$default_code = json_decode($default_code,true);
	$default_code = @$default_code?$default_code:array();
	$value ="";
	if($actionType=='edit'||$actionType=='copy'){
		$value = FCHelper::er($dataItem,$name);
	}
?>
<div class="form-group">
  	<p class="form-title" for=""><?php echo e(FCHelper::er($table,'note')); ?><span class="count"></span></p>
 	<textarea  <?php echo e(FCHelper::ep($table,'require')==1?'required':''); ?>  name="<?php echo e($name); ?>" placeholder="<?php echo e(FCHelper::ep($table,'note')); ?>" dt-type="<?php echo e(FCHelper::ep($table,'type_show')); ?>" class="form-control" rows="2"><?php echo e($value); ?></textarea>
</div>
<script type="text/javascript">
	$(function() {
		<?php $__currentLoopData = $default_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<?php 
				$source = $dc['source'];
				$source = $source=='this'?"textarea[name=$name]":$source;
			?>
			$(document).on('input', "<?php echo e($source); ?>", function(event) {
				event.preventDefault();
				<?php if($dc['function']=='count'): ?>
					var input = $(this).val();
					$(this).parent().find('span.count').text(input.length+" Chars");
				<?php endif; ?>
				<?php if($dc['function']=='seo_title'): ?>
					$('textarea[name=<?php echo e($name); ?>]').val($(this).val());
				<?php endif; ?>
				<?php if($dc['function']=='seo_desc'): ?>
					$('textarea[name=<?php echo e($name); ?>]').val($(this).val());
				<?php endif; ?>
				<?php if($dc['function']=='seo_key'): ?>
					$('textarea[name=<?php echo e($name); ?>]').val($(this).val());
				<?php endif; ?>
			});
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	});
</script><?php /**PATH H:\laragon\www\laravel-tech5s\/packages/vanhenry/views/ctedit/textarea.blade.php ENDPATH**/ ?>