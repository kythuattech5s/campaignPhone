@php
$name = FCHelper::er($table, 'name');
$dataDefault = FCHelper::er($table, 'default_data');
$folder = null;
if (!empty($dataDefault) && count($dataDefault = json_decode($dataDefault, true)) > 0 && isset($dataDefault['folder'])) {
    $folder = $dataDefault['folder'];
}
$value = '';
if ($actionType == 'edit' || $actionType == 'copy') {
    $value = FCHelper::ep($dataItem, $name);
}
@endphp
<div class="row margin0">
    <div class="col-xs-12">
        <p class="form-title">{{ FCHelper::er($table, 'note') }}</p>
    </div>
    <div class="col-xs-12 box-gallery" data-variable='gallery_control_admin_{{ $name }}'>
        @php
            $galleriesJson = $value;
            $galleries = json_decode($galleriesJson, true);
            $galleries = is_array($galleries) ? $galleries : [];
        @endphp
        <textarea class="hidden" name="{{ $name }}" id="{{ $name }}" data-type="GALLERY_CONTROL_ADMIN.VIEW"><?php echo $galleriesJson; ?></textarea>
        <div class="gallery_control_admin_list">
            <ul class="gallery_ul gallery_ul_{{ $name }}">
                @foreach ($galleries as $gallery)
                    @php
                        $path = $gallery['path'] ?? '';
                        $fileName = $gallery['file_name'] ?? '';
                        $file = $path . $fileName;
                        $file = file_exists(public_path($file)) ? $file : 'public/admin/images/noimage.png';
                        $idfile = \Str::random(10);
                    @endphp
                    <li class="col-sm-2 col-xs-12 gallery-item">
                        <div>
                            <span tagname="gallery"></span> <img class="img-responsive" name="gallery_control_admin_list_<?php echo $idfile; ?>" id="gallery_control_admin_list_<?php echo $idfile; ?>" rel="lib_img" dt-file='<?php echo json_encode($gallery); ?>' src="<?php echo $file; ?>" alt="<?php echo $gallery['file_name'] ?? ''; ?>">
                            <p><?php echo $gallery['file_name'] ?? ''; ?></p>
                            <i class="fa fa-times icon-remove gallery-close" aria-hidden="true"></i> <a href="esystem/media/view?istiny=gallery_control_admin_list_<?php echo $idfile; ?>&callback=GALLERY_CONTROL_ADMIN_PROVIDER.callback" class="iframe-btn button" type="button">Chọn hình</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="btnadmin">
            <a href="javascript:void(0);" class="btn gallery_ul_{{ $name }}_add" type="button">Add 1 Image</a>
        </div>
        <div class="btnadmin">
            <a href="esystem/media/view?istiny=gallery_control_admin_{{ $name }}&callback=GALLERY_CONTROL_ADMIN_PROVIDER.callback_multi" class="btn gallery_ul_{{ $name }}_add_multi iframe-btn" type="button">Add Images</a>
        </div>
        @if (!is_null($folder))
            <div class="btnadmin">
                <a href="javascript:void(0)" class="btn add-zip" data-name="{{ $name }}">Add Zip</a>
            </div>
        @endif
    </div>
</div>
<style type="text/css">
    .box-gallery {
        margin-bottom: 15px;
    }

    .gallery_control_admin_list {
        width: 100%;
        max-height: 250px;
        border: 1px solid #E0E0E0;
        overflow-y: scroll;
    }

    .gallery_control_admin_list .gallery_ul {
        padding: 0;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item>div {
        position: relative;
        background: #ececec;
        height: 102px;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item img {
        margin: 0 auto;
        max-height: 102px;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item {
        position: relative;
        padding-top: 15px;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item p {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.55);
        color: #fff;
        padding: 3px;
        text-align: center;
        margin: 0;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item .gallery-close {
        position: absolute;
        top: -13px;
        right: -13px;
        font-size: 20px;
        z-index: 1;
        border-radius: 50%;
        width: 27px;
        height: 27px;
        text-align: center;
        cursor: pointer;
        color: #fff;
        background: #ff1515 !important;
        line-height: 27px;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item .button {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        width: 100px;
        height: 30px;
        background: #00923f;
        color: #fff;
        border: none;
        text-transform: uppercase;
        visibility: hidden;
        display: block;
        text-align: center;
        padding-top: 7px;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item:hover .button {
        visibility: visible;
    }

    .gallery_control_admin_list .gallery_ul .gallery-item.selected {
        opacity: 0.4;
    }
</style>
<script type="text/javascript">
    $(function() {
        window['gallery_control_admin_{{ $name }}'] = new GALLERY_CONTROL('{{ $name }}');
        window['gallery_control_admin_{{ $name }}'].init();
        @if (!is_null($folder))
            const addZip = document.querySelectorAll('.add-zip');
            addZip.forEach((button) => {
                button.onclick = () => {
                    const name = button.dataset.name;
                    const fileUpload = document.createElement('input');

                    fileUpload.name = "gallery-input-" + name;
                    fileUpload.type = "file";
                    const form = document.querySelector('form');
                    form.appendChild(fileUpload);
                    fileUpload.click();

                    fileUpload.onchange = (e) => {
                        const files = e.target.files;
                        if (files.length == 0) {
                            return false;
                        }
                        const formData = new FormData();
                        Array.from(files).forEach(file => {
                            formData.append('file', file);
                        });

                        formData.append('folder', "{{ $folder }}");
                        formData.append('_token', document.querySelector('[name="_token"]').getAttribute('content'));
                        $.ajax({
                            url: "esystem/media/upload-file-zip",
                            data: formData,
                            method: "POST",
                            cache: false,
                            contentType: false,
                            processData: false,
                        }).then(res => {
                            res.media.forEach(media => {
                                const id_unique = name + media.id + Math.random().toString(36).substr(2, 9);
                                const html = `
                        <li class="col-sm-2 col-xs-12 gallery-item ui-sortable-handle">
                            <div>
                                <span tagname="gallery"></span> <img class="img-responsive" name="gallery_control_admin_list_${id_unique}" id="gallery_control_admin_list_${id_unique}" rel="lib_img" dt-file='${JSON.stringify(media)}' src="${media.path + media.file_name}" alt="${media.alt}">
                                <p>${media.file_name}</p>
                                <i class="fa fa-times icon-remove gallery-close" aria-hidden="true"></i> <a href="esystem/media/view?istiny=gallery_control_admin_list_${id_unique}&callback=GALLERY_CONTROL_ADMIN_PROVIDER.callback" class="iframe-btn button" type="button">Chọn hình</a>
                            </div>
                        </li>
                        `
                                window['gallery_control_admin_' + name].currentGallery.append(html);
                            })
                            window['gallery_control_admin_' + name].changeValueGallery();
                            fileUpload.remove();
                        });
                    }
                }
            });
        @endif
    });
</script>
