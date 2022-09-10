<?php 
	$name = isset($itemSubControl['name'])?$itemSubControl['name']:'';
	$defaultData = $itemSubControl['data'] ?? [];
	$source = $defaultData['source'];
	$valueDb = isset($subValue[$name])?$subValue[$name]:'';
?>
<input value="<?php echo $valueDb; ?>" name="<?php echo $key.$name; ?>" data-name="<?php echo $name; ?>" data-type="<?php echo $key; ?>" type="hidden" class="control"/>
<input type="text" class="search<?php echo $key.$name; ?>" style="max-width: 500px" placeholder="Gõ để tìm kiếm">
<ul class="listitem multiselect padding0 scrollbar listitem<?php echo $key.$name; ?>">
	<?php 
		$valueDb = explode(',', $valueDb);
		if($source==="static"){
			$values = $defaultData['data']['value'];
			foreach ($values as $value) {
				foreach ($value as $k =>$v) {
					echo "<li><label><input type='checkbox' ".(in_array($k, $valueDb)?'checked':'')."  value='".$k."'/>".$v."</label></li>";
				}
				
			}
		}
		else if($source==="database"){
			$values = $defaultData['value'];
			$input = array_key_exists('select', $values) ?$values['select']:"";
			$table = array_key_exists('table', $values) ?$values['table']:"";
			$fieldjson = array_key_exists('field', $values) ?$values['field']:"";
			$basefield = array_key_exists('base_field', $values) ?$values['base_field']:"";
			$where = array_key_exists('where', $values) ?$values['where']:"";
			$fieldValue =array_key_exists('field_value', $values) ?$values['field_value']:"";
			$w = array();
			foreach ($where as $itemwhere) {
				foreach ($itemwhere as $swhere =>$svalue) {
					$w[$swhere]= $svalue;
				}
			}
			$arr = $this->Admindao->recursiveTable($input,$table,$fieldjson,$basefield,$fieldValue,$w);
			$valueput = $valueDb;
			printRecursiveMultiSelect(0,$arr,$valueput);
		}
	 ?>
</ul>
<script type="text/javascript">
	$(function() {
		$('body').on('click', '.listitem<?php echo $key.$name; ?> li input', function(event) {
			var arr = $('.listitem<?php echo $key.$name; ?> li input:checked');
			var str = "";
			for (var i = 0; i < arr.length; i++) {
				var item = arr[i];
				str += $(item).val();
				if(i<arr.length-1){
					str+=",";
				}
			};
			$('input[name=<?php echo $key.$name; ?>]').val(str);
		});
		$('body').on('input', '.search<?php echo $key.$name; ?>', function(event) {
			event.preventDefault();
			var val = $(this).val().toLowerCase();
			if(val ==""){
				$(this).next().find("li").show();	
			}
			else{
				var lis = $(this).next().find("li");
				for (var i = 0; i < lis.length; i++) {
					var li = $(lis[i]);
					var text = li.text().toLowerCase();
					if(text.indexOf(val)!=-1){
						li.show();
					}
					else{
						li.hide();
					}
				}
			}
			
		});
	});
</script>

