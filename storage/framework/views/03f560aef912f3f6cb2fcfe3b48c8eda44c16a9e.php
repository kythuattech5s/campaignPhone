<div id="folder<?php echo e($file->id); ?>" class="col-xxl-sp-12 col-md-sp-8 col-sm-2 col-xs-3 media-it fold fileitem" data-file='<?php echo e($file->extra); ?>'>
	<div class="media-item">
		<div class="dp-table">
			<?php if($trash==0): ?>
			<a class="mdi-img" title="" href="<?php echo e(\vanhenry\manager\helpers\MediaHelper::getLinkForDir($file->id)); ?>">
				<span class="folder"><span></span></span>
			</a>
			<?php else: ?>
			<a class="mdi-img" href="javascript:void(0)">
			<span class="folder"><span></span></span>
			</a>
			<?php endif; ?>
		</div>
		<div class="mdi-btn clearfix">
			<a class="name-edit" href="#" data-toggle="tooltip" title="Đổi tên"><i class="fa fa-pencil"></i></a>
			<?php if($trash==1): ?>
			<a onclick="MediaManager.restore(<?php echo e($file->id); ?>);return false;" dt-id="<?php echo e($file->id); ?>" href="#" data-toggle="tooltip" title="Restore"><i class="fa fa-arrow-up"></i></a>
			<?php endif; ?>
			<a href="#" dt-id="<?php echo e($file->id); ?>" onclick="<?php echo e($trash==1?'MediaManager.deleteFolderFull(this)':'MediaManager.deleteFolder(this)'); ?>;return false;" data-toggle="tooltip" title="Xóa"><i class="fa fa-trash-o"></i></a>
		</div>
		<div class="mdi-title"><?php echo e($file->name); ?></div>
		<span class="mdi-date"><?php echo e($file->created_at); ?></span>
		<span class="mdi-size"></span>
	</div>
</div><?php /**PATH H:\laragon\www\laravel-tech5s\packages\vanhenry\manager\src/views/media/folder.blade.php ENDPATH**/ ?>