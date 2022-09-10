<?php 
	$data = $arrData;
	$config = $arrConfig;
    $field_table = $config['field'] ?? '';
    $select = explode(',',$data['select']);
    $table_name = $dataItem->$field_table;
    $main = $select[0];
    $sub = $select[1];
    $arrayValues = vanhenry\manager\helpers\DetailTableHelper::getDataTable($table_name,$select);
?>
@if((isset($active) && count($active) > 0) || $name !== 'act')
<div class="form-group ">
  <p class="form-title" for="">{{FCHelper::ep($table,'note')}}</p>
  <div class="form-control form-reset flex">
    <select {{FCHelper::ep($table,'require')==1?'required':''}} style="width:100%" placeholder="{{FCHelper::ep($table,'note')}}" class="select2" name="{{$name}}">
        @foreach($arrayValues as $value)
		<option value="{{ $value->$main ?? $value->id }}" {{ $value->$main == $dataItem->$name ? 'selected' : '' }}>
            {{ $value->$sub ?? $value->name }}
        </option>
        @endforeach
	</select>
  </div>
</div>
@else
	<input type="hidden" name="{{$name}}" value="0">
@endif
