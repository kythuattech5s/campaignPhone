<?php
	$data = $arrData;
    $config= $arrConfig;
    $select = $config['map']['select'];
    $field = $config['map']['field'];
?>
@if((isset($active) && count($active) > 0) || $name !== 'act')
<div class="form-group ">
  <p class="form-title" for="">{{FCHelper::ep($table,'note')}}<p/>
  <div class="form-control form-reset flex">
    <select {{FCHelper::ep($table,'require')==1?'required':''}} style="width:100%" placeholder="{{FCHelper::ep($table,'note')}}" class="select2" name="{{$name}}">
	@foreach($arrData as $key => $value)
		<?php
            $tmpValue = is_array($value) ? FCHelper::ep($value,'key',1): $value ;
            $currentID = FCHelper::ep($dataItem,$name);
		?>
		<option {{$tmpValue==$currentID ?"selected":""}} value="{{$tmpValue}}">{{ is_array($value) ? FCHelper::ep($value,'value',1): $key}}</option>
	@endforeach
	</select>
  </div>
</div>
@else
	<input type="hidden" name="{{$name}}" value="0">
@endif

<script>
    $(document).on('change','[name="{{$name}}"]',function(){
        $.ajax({
            url:'/esystem/getDataTableSelect',
            method:"POST",
            data:{
                select: "{{ $select }}",
                table: $(this).val()
            }
        }).done(res=>{
            $('[name="{{ $field }}"]').html(res.data.map(item => `<option value="${item.id}">${item.name}</option>`).join(''));
        })
    })
</script>
{{--

MAP_TABLE
{
    "data": {
        "1": {
            "key": "products",
            "en_value": "Sản phẩm",
            "vi_value": "Sản phẩm"
        }
    },
    "config": {
				"map":{
					"field":"map_id",
					"select":"id,name"
				},
        "source": "trigger",
        "has_search": "1",
        "multiselect": "0"
    }
}

//MAP_ID
{
    "data": {
       "select":"id,name"
    },
    "config": {
		"field":"map_table",
        "source": "map_id",
        "has_search": "1",
        "multiselect": "0"
    }
}

--}}
