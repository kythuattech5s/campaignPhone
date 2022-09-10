<div id="file<?php echo e($file->id); ?>" class="col-xxl-sp-12 col-md-sp-8 col-sm-2 col-xs-3 media-it file fileitem" data-file='<?php echo e(json_encode($file)); ?>'>
    <?php $extra = json_decode($file->extra, true); ?>
    <div class="media-item">
        <div class="dp-table">
            <a class="mdi-img" title="" href="<?php echo e($file->path . $file->file_name); ?>" rel="mdi">
                <img class="lazy" src="<?php echo e(file_exists(public_path($extra['thumb'])) ? $extra['thumb'] : $file->path . $file->file_name); ?>" alt="">
            </a>
        </div>
        <div class="mdi-check">
            <label><input class="selectfile" value="<?php echo e($file->id); ?>" type="checkbox"><i class="fa fa-check-square-o"></i></label>
        </div>
        <div class="mdi-btn clearfix">
            <a download href="<?php echo e($file->path . $file->file_name); ?>" data-toggle="tooltip" title="Tải về"><i class="fa fa-download"></i></a>
            <a href="<?php echo e($file->path . $file->file_name); ?>" rel="gallery-box" class="preview" title="Xem"><i class="fa fa-eye"></i></a>
            <a class="name-edit" dt-id="<?php echo e($file->id); ?>" href="#" data-toggle="tooltip" title="Đổi tên"><i class="fa fa-pencil"></i></a>
            <?php if($trash == 1): ?>
                <a onclick="MediaManager.restore(<?php echo e($file->id); ?>);return false;" dt-id="<?php echo e($file->id); ?>" href="#" data-toggle="tooltip" title="Khôi phục"><i class="fa fa-arrow-up"></i></a>
            <?php endif; ?>
            <a href="#" dt-id="<?php echo e($file->id); ?>" onclick="<?php echo e($trash == 1 ? 'MediaManager.deleteFileFull(this)' : 'MediaManager.deleteFile(this)'); ?>;return false;" data-toggle="tooltip" title="Xóa"><i class="fa fa-trash-o"></i></a>
        </div>
        <p class="mdi-title"><?php echo e(substr($file->name, 0, strrpos($file->name, '.'))); ?></p>
        <span class="mdi-date"><?php echo e($file->created_at); ?></span>
        <span class="mdi-size"><?php echo e($extra['size']); ?></span>
    </div>
</div>
<?php /**PATH H:\laragon\www\laravel-tech5s\packages\vanhenry\manager\src/views/media/file.blade.php ENDPATH**/ ?>