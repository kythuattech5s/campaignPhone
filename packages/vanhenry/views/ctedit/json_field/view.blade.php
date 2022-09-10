<?php 
	$results = landingpage\manager\models\LandingConfig::where('key_word',$key)->get();
	$configs = json_decode(count($results)>0?($results[0]->value ?? ''):'',true);
	$configs = @$configs?$configs:[];
	$configs = $configs['data'] ?? [];
	$dataViewItem = json_decode($dataView[$key] ?? '',true); 
	$dataViewItem = @$dataViewItem?$dataViewItem:[];
?>
<?php if(count($configs) > 0): ?>
<div class="content_block_element">
	<textarea class="hidden boss" data-name="<?php echo $key; ?>" name="<?php echo $key; ?>"><?php echo json_encode($dataViewItem); ?></textarea>
	<div class="list-items-elementor">
		<?php foreach($configs as $itemSubControl): ?>
			<?php 
				$typeSubControl = $itemSubControl['type']; 
				$nameSubControl = $itemSubControl['name'];
			?>
			<div class="item_elementor">
				<div class="controls">
					<div class="elementor_json_field_name">
						<label><?php echo isset($itemSubControl['text'])?$itemSubControl['text']:'' ?></label>
					</div>
					<div class="elementor_json_field_content col">
						@include('ldp::controls.sub_edit_'.$typeSubControl,['itemSubControl'=>$itemSubControl,'subValue'=>$dataViewItem ,'key'=>$key,'nameSubControl'=>$nameSubControl])

					</div>
				</div>
			</div>
			<?php if($typeSubControl == 'json_field'): ?>
			<script type="text/javascript">
				$(function() {
					window['elementor_json_field_<?php echo $key.$nameSubControl; ?>'] = new ELEMENTOR_JSON_FIELD('<?php echo $key.$nameSubControl; ?>');
					window['elementor_json_field_<?php echo $key.$nameSubControl; ?>'].init();
				});
			</script>
			<?php endif; ?>
		<?php endforeach ?>
	</div>	
	<button class="save_data_control btn">Cập nhật thông tin</button>	
</div>
<?php endif; ?>