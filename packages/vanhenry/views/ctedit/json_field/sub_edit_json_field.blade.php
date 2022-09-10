<?php 
	$name = $itemSubControl['name'] ?? '';
	$subitems = $itemSubControl['data'] ?? [];
	$jsons = json_decode($subValue[$name] ?? '',true);
	$jsons = @$jsons?$jsons:[];
	$widthItem = (isset($itemSubControl['width']) && $itemSubControl['width']==1)?'col-100':'col-50';
?>
<div class="elementor_json_field">
	<textarea class="hidden control" data-name="<?php echo $name;  ?>" name="<?php echo $key.$name; ?>" id="<?php echo $key.$name; ?>" data-type="ELEMENTOR_JSON_FIELD.VIEW"><?php echo $subValue[$name] ?? '';  ?></textarea>
	<div class="hidden hidden-item">
		<div class="item <?php echo $widthItem ?> ">
			<?php foreach($subitems as $itemSubControl): ?>
				<?php 
					$typeSubControl = $itemSubControl['type']; 
					$field =  $itemSubControl['name'];
				?>
				<div class="elementor_json_field_control <?php echo (isset($itemSubControl['width'])&&$itemSubControl['width']==1)?'col-100':'col-50' ?> ">
					<div class="elementor_json_field_control_name">
						<label><?php echo isset($itemSubControl['text'])?$itemSubControl['text']:'' ?></label>
					</div>
					<div class="elementor_json_field_control_content col">
						@include('ldp::controls.sub_edit_'.$typeSubControl,['itemSubControl'=>$itemSubControl,'field'=>$field,'key'=>$key,'subValue'=>$jsons,'nameSubControl'=>$nameSubControl])
					</div>
				</div>
			<?php endforeach; ?>
			<span class="close">
				<i class="fa fa-times" aria-hidden="true"></i>
			</span>
		</div>
	</div>
	<div class="list-items list-items-<?php echo $key.$name; ?>">
		<?php foreach($jsons as $subValue): ?>
			<div class="item <?php echo $widthItem ?> ">
			<?php foreach($subitems as $itemSubControl): ?>
				<?php 
					$typeSubControl = $itemSubControl['type'];
					$field =  $itemSubControl['name'];
				?>
					<div class="elementor_json_field_control <?php echo (isset($itemSubControl['width'])&&$itemSubControl['width']==1)?'col-100':'col-50' ?> ">
						<div class="elementor_json_field_control_name">
							<label><?php echo isset($itemSubControl['text'])?$itemSubControl['text']:'' ?></label>
						</div>
						<div class="elementor_json_field_control_content col">
							@include('ldp::controls.sub_edit_'.$typeSubControl,['itemSubControl'=>$itemSubControl,'field'=>$field,'key'=>$key,'subValue'=>$subValue,'nameSubControl'=>$nameSubControl])
						</div>
					</div>
			<?php endforeach; ?>
			<span class="close">
				<i class="fa fa-times" aria-hidden="true"></i>
			</span>
			</div>
		<?php endforeach ?>
	</div>
	<div class="text-center" style="width: 80%">
		<div class="btnadmin">
			<button type="button" class="btn add-<?php echo $key.$name; ?>"><?php echo 'Thêm mới'; ?></button>
		</div>
	</div>		
</div>
<style type="text/css">
	.elementor_json_field .list-items{
	    border: 1px solid #00923f;
	    width: 80%;
	    display: flex;
	    flex-wrap: wrap;
	    padding: 5px;
	}
	.elementor_json_field .elementor_json_field_control{
		display: flex;
		margin: 2px 0px;

	}
	.elementor_json_field .list-items .item{
		padding:0;
		position: relative;
		z-index: 9;
		padding: 10px;
	}
	.elementor_json_field .list-items .item span.close{
		    position: absolute;
    top: 0px;
    right: 0;
    background: red !important;
    opacity: 1;
    color: #fff;
    font-size: 12px;
    border-radius: 50%;
    padding: 3px;
    z-index: 13;
    width: 20px;
    height: 20px;
    text-align: center;
	}
	.elementor_json_field .list-items .item:before{
		background: rgba(192, 192, 192, 0.18);
    border: 1px solid rgba(0, 146, 63, 0.73);
    content: '';
    display: block;
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: -1;
	}
	.elementor_json_field .col-50{
		-ms-flex: 0 0 50%;
	    flex: 0 0 50%;
	    max-width: 50%;
	}
	.elementor_json_field .col-30{
		-ms-flex: 0 0 30%;
	    flex: 0 0 30%;
	    max-width: 30%;
	}
	.elementor_json_field .col-50:nth-child(even){
		padding-left: 30px;
	}
	.elementor_json_field .col-100{
		-ms-flex: 0 0 100%;
	    flex: 0 0 100%;
	    max-width: 100%;
	}
	.elementor_json_field .item{
		padding: 3px;
    	border: 3px solid transparent;
    	display: flex;
    flex-wrap: wrap;
	}
	.elementor_json_field .elementor_json_field_control .elementor_json_field_control_name{
		width: 100px;
	}
	.elementor_json_field .elementor_json_field_control  .col{
	    -ms-flex-preferred-size: 0;
	    flex-basis: 0;
	    -ms-flex-positive: 1;
	    flex-grow: 1;
	    max-width: 100%;
	}
	.elementor_json_field input,
	.elementor_json_field textarea{
		width:100% !important;
	}
</style>
