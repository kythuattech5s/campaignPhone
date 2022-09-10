<?php 
	$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
	$type = $itemSubControl['type'];
	$rows = isset($itemSubControl['rows'])?$itemSubControl['rows']:'3';
	$default = isset($itemSubControl['default'])?$itemSubControl['default']:'';
	$value = isset($subValue[$name])?$subValue[$name]:$default;
?>
<?php  
	$idfile = $name."_".\Str::random(10);
?>
<div class="<?php echo $idfile; ?>" data-type="sub_edit_gallery">
	<div class="box-gallery" data-variable ='{{$idfile}}'>
		<?php 
			$galleriesJson = $value;
           	$galleries = json_decode($galleriesJson,true);
           	$galleries = is_array($galleries)?$galleries:[];
        ?>
		<textarea class="hidden gallery control" id="{{$idfile}}" data-variable="elementor_json_field_<?php echo $nameSubControl; ?>" data-type="<?php echo $type; ?>" data-name="<?php echo $type; ?>"><?php echo $galleriesJson ?></textarea>
		<div class="gallery_control_admin_json_field_list" >
           <ul class="gallery_ul gallery_ul_{{$idfile}}">
           	
            <?php foreach($galleries as $gallery): ?>

            	<?php 
            		$path = $gallery["path"] ?? '';
            		$fileName = $gallery["file_name"] ?? '';
            		$file =$path.$fileName; 
            		$file= file_exists($file)?$file:'admin/gallery_control/theme/images/no-image.svg'; 
            	?>
            	<?php 
            		$idfileItem = \Str::random(10);
            	?>
	            <li class="col-sm-2 col-xs-12 gallery-item">
	                 <div>
	                    <span tagname="gallery"></span> <img class="img-responsive " id="{{$idfileItem}}" rel="lib_img" dt-file='<?php echo json_encode($gallery) ?>' src="<?php echo $file ?>" alt="<?php echo $gallery['file_name'] ?? '' ?>"> 
	                    <p><?php echo $gallery['file_name'] ?? '' ?></p>
	                    <i class="fa fa-times icon-remove gallery-close" aria-hidden="true"></i> <a href="esystem/media/view?istiny={{$idfileItem}}&callback=GALLERY_CONTROL_ADMIN_PROVIDER_JSON_FIELD.callback" class="iframe-btn button" type="button">Chọn hình</a> 
	                 </div>
	            </li>
            <?php endforeach ?>
           </ul>
      	</div>
      	<div class="btnadmin">
	    	<a href="javascript:void(0);" class="btn gallery_ul_{{$idfile}}_add " type="button">Add 1 Image</a>
	    </div>
	    <div class="btnadmin">
	    	<a href="esystem/media/view?istiny={{$idfile}}&callback=GALLERY_CONTROL_ADMIN_PROVIDER_JSON_FIELD.callback_multi" class="btn gallery_ul_{{$idfile}}_add_multi iframe-btn" type="button">Add Images</a>
	    </div>
	</div>
</div>
<style type="text/css">
	.gallery_control_admin_json_field_list{
	    width: 100%;
		max-height: 250px;
		border: 1px solid #E0E0E0;
		overflow-y: scroll;
	}
	.gallery_control_admin_json_field_list .gallery_ul{
		padding: 0;
	}
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item >div{
		position: relative;
		background: #ececec;
		height: 102px;
	}
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item img{
		margin:0 auto;
		max-height: 102px;
	}
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item{
		position: relative;
		    padding-top: 15px;
	}
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item p {
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
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item .gallery-close {
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
	    background: #ff1515!important;
	    line-height: 27px;
	}
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item .button {
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
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item:hover .button {
	    visibility: visible;
	}
	.gallery_control_admin_json_field_list .gallery_ul .gallery-item.selected {
	    opacity: 0.4;
	}
</style>
<script type="text/javascript">
	$(function() {
		window['{{$idfile}}'] = new GALLERY_CONTROL_JSON_FIELD('{{$idfile}}');
		window['{{$idfile}}'].init();
	});
</script>