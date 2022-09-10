<?php 
    $table = FCHelper::ep($arrData,'table');
    $relationship = FCHelper::ep($arrData,'relationship');
    $field_show = FCHelper::ep($arrData,'field_show');
    $isAjax = FCHelper::ep($arrConfig, 'ajax');
    $tableMain = $show->parent_name;
    $fieldsSelect = explode(',',$arrData['select']);
    $fieldMain = $fieldsSelect[0];
    $defaultData = collect(FCHelper::ep($arrData,'default'));

    $dataFilter = $defaultData->first(function($q, $key) use($dataItem,$show){
        return $key == $dataItem[$show->name];
    });
?>
@if($show->editable == 1)
    @php
        $dataList = collect();
        if($isAjax == 0){
            $dataList =  Collect(FCHelper::getDataList($table, $tableMain, $show->name , $fieldsSelect));
        }
    @endphp
    <select @if($isAjax == 1) data-lang="{{App::getLocale()}}" admin-cp="{{$admincp}}" data-table="{{$table}}" data-default="{{json_encode($defaultData,JSON_UNESCAPED_UNICODE)}}" data-field-select="{{implode(',',$fieldsSelect)}}" editable-field @endif name="{{$show->name}}" dt-prop-id="{{$show->id}}" dt-prop="{{$show->is_prop ?? 0}}"  class="select2 editable" table="{{$show->parent_name}}" table="{{$tableMain}}" style="width: 150px">
    @if($isAjax == 1 && $dataItem->$relationship)
        <option value="{{$dataItem->$relationship->id}}" selected>{{$dataItem->$relationship->$field_show}}</option>
    @elseif($dataFilter)
        <option value="{{$dataItem[$show->name]}}" selected>{{$dataFilter[App::getLocale().'_value']}}</option>
    @endif
    
	@foreach($dataList as $item)
        <option value="{{FCHelper::ep($item, $fieldMain)}}" @if(!is_null($dataItem->$relationship) && $dataItem->$relationship->id  == $item->$fieldMain) selected @endif>{{FCHelper::ep($item, $field_show)}}</option>
    @endforeach
</select>
@elseif($dataItem->$relationship)
	<p class="select static-select" dt-value="{{$dataItem->$relationship->id}}">{{$dataItem->$relationship->$field_show}}</p>
@elseif($dataFilter)
	<p class="select static-select" dt-value="{{$dataItem[$show->name]}}">{{$dataFilter[App::getLocale().'_value']}}</p>
@endif