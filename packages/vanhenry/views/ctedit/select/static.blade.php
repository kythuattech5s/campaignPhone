<?php 
	$data= $arrData;
?>
@if($name !== 'act')
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
