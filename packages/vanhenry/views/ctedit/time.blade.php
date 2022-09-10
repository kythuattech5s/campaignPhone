<?php 
    $name = FCHelper::er($table,'name');
    $valueShow =date("d/m/Y H:i:s");
    $valueHidden = date("Y-m-d H:i:s");
    if($actionType=='edit'||$actionType=='copy'){
        $value = FCHelper::ep($dataItem,$name);
    }else{
        if( strtotime($value) >= 0 ){
            $valueShow = $value;
            $valueHidden = $value;  
        }
    }	
?>
<div class="form-group ">
  <p class="form-title" for="">{{FCHelper::ep($table,'note',1)}}<p/>
  <div class="form-control flex">
    <i class="fa fa-calendar"></i>
    <input value="{{$value}}" dt-type="{{FCHelper::er($table,'type_show')}}" class="date-control datepicker-type-time" type="text" name="{{$name}}"/>
  </div>
</div>
<script type="text/javascript">
	$(function() {
        $('.datepicker-type-time').datetimepicker({
            format:"H:i:s",
            datepicker:false,
            step:15
        })
		$.datetimepicker.setLocale('{{App::getLocale()}}');
	});
</script>