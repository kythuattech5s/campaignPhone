<?php
$defaultData = 	json_decode(FCHelper::ep($show,"default_data"), true);
$listData = [];
if (is_array($defaultData)) {
	$relationship = $defaultData['relationship'];
	$field_show = $defaultData['field_show'];
}
?>
<td>
	@foreach($dataItem->$relationship as $item)
        <p class="select" data-id="{{FCHelper::ep($item,'id')}}" data-table="{{FCHelper::ep($show,'parent_name')}}" data-item-id="{{FCHelper::ep($dataItem,'id')}}">{{FCHelper::ep($item,$field_show)}}</p>
    @endforeach
</td>