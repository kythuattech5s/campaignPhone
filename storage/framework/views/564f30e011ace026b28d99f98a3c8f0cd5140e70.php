<?php
$name = FCHelper::er($table, 'name');
$default_code = FCHelper::er($table, 'default_code');
$default_code = json_decode($default_code, true);
$default_code = @$default_code ? $default_code : [];
$value = '';
$img = 'admin/images/noimage.png';
if ($actionType == 'edit' || $actionType == 'copy') {
    $value = FCHelper::ep($dataItem, $name);
    $tmp = json_decode($value, true);
    $img = isset($tmp) && is_array($tmp) && array_key_exists('path', $tmp) && file_exists(public_path($tmp['path'] . $tmp['file_name'])) ? $tmp['path'] . $tmp['file_name'] : $img;
    if ($img == 'admin/images/noimage.png') {
        $img = FCHelper::eimg2($dataItem, $name);
    }
}
?>
<div class="row">
    <p class="des col-xs-12"><?php echo e(trans('db::choose_img')); ?> <?php echo e(FCHelper::ep($tableMap == 'configs' ? $dataItem : $table, 'note')); ?></p>
    <div class="col-xs-12">
        <img style="    margin: 0 auto;max-width: 30%;" src="<?php echo e($img); ?>" alt="" class="img-responsive">
        <input placeholder="<?php echo e(FCHelper::er($table, 'note')); ?>" type="hidden" value="<?php echo e($value); ?>" name="<?php echo e($name); ?>" id="<?php echo e($name); ?>">
        <div class="form-group textcenter">
            <a href="<?php echo e($admincp); ?>/media/view?istiny=<?php echo e($name); ?>" class="browseimage bgmain btn btn-primary iframe-btn" type="button"><?php echo e(trans('db::choose_img')); ?></a>
            <button style="margin-top: 15px;margin-left: 5px;" class="btnchange-<?php echo e($name); ?> bgmain btn btn-primary"><?php echo e(trans('db::edit')); ?></button>
            <button style="margin-top: 15px;margin-left: 5px;" class="btndelete-<?php echo e($name); ?> bgmain btn btn-primary"><?php echo e(trans('db::delete')); ?></button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        $('.btndelete-<?php echo e($name); ?>').click(function(event) {
            event.preventDefault();
            $("input[name='<?php echo e($name); ?>']").val("");
            $("input[name='<?php echo e($name); ?>']").prev().attr("src", "");
        });
        $(".btnchange-<?php echo e($name); ?>").click(function(event) {
            event.preventDefault();
            var file = $('input[name=<?php echo e($name); ?>]').val();
            try {
                file = JSON.parse(file);
            } catch (ex) {
                file = {};
                file.title = "";
                file.caption = "";
                file.alt = "";
                file.description = "";
            }
            var dialog = bootbox.dialog({
                title: "Chỉnh sửa thông tin ảnh",
                onEscape: true,
                message: '<div class="row">' +
                    '<div class="col-md-6 col-xs-12 form-group">' +
                    '	<label for="">Title</label>' +
                    '	<input name="title" type="text" class="form-control" value="' + (file.title == null ? '' : file.title) + '" placeholder="Title">' +
                    '	</div>' +
                    '	<div class="col-md-6 col-xs-12 form-group">' +
                    '		<label for="">Caption</label>' +
                    '		<input name="caption" type="text" class="form-control" value="' + (file.caption == null ? '' : file.caption) + '" placeholder="Caption">' +
                    '	</div>' +
                    '	<div class="col-md-6 col-xs-12 form-group">' +
                    '		<label for="">Alt</label>' +
                    '		<input name="alt" type="text" class="form-control" value="' + (file.alt == null ? '' : file.alt) + '" placeholder="Alt">' +
                    '	</div>' +
                    '	<div class="col-md-6 col-xs-12 form-group">' +
                    '		<label for="">Description</label>' +
                    '		<input name="description" type="text" class="form-control" value="' + (file.description == null ? '' : file.description) + '" placeholder="Description">' +
                    '	</div>' +
                    '</div>',
                buttons: {
                    confirm: {
                        label: '<?php echo e(trans('db::save')); ?>',
                        className: 'btn-success',
                        callback: function() {
                            file.title = dialog.find("input[name=title]").val();
                            file.caption = dialog.find("input[name=caption]").val();
                            file.alt = dialog.find("input[name=alt]").val();
                            file.description = dialog.find("input[name=description]").val();
                            $('input[name=<?php echo e($name); ?>]').val(JSON.stringify(file));
                        }
                    },
                    cancel: {
                        label: '<?php echo e(trans('db::close')); ?>',
                        callback: function() {
                            dialog.modal("hide");
                        }
                    }
                },
            });
            dialog.modal("show");
        });
    });
</script>
<?php /**PATH H:\laragon\www\laravel-tech5s\/packages/vanhenry/views/ctedit/imagev2.blade.php ENDPATH**/ ?>