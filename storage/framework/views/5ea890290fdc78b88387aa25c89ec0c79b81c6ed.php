<?php $__currentLoopData = $listItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<?php if($file->is_file): ?>
		<?php echo $__env->make("vh::media.file", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<?php else: ?>
		<?php echo $__env->make("vh::media.folder", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH H:\laragon\www\laravel-tech5s\packages\vanhenry\manager\src/views/media/media-manager.blade.php ENDPATH**/ ?>