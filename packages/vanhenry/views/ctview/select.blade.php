<td data-title="{{$show->note}}">
<?php $defaultData = FCHelper::ep($show,"default_data"); 
$arrKey = json_decode($defaultData,true);
$arrData = FCHelper::er($arrKey,'data');
$arrConfig = FCHelper::er($arrKey,'config');
$source = FCHelper::er($arrConfig,'source'); 
?>
@if(View::exists('tv::ctview.select.'.$source))
@include('tv::ctview.select.'.$source,array('arrData'=>$arrData,'arrConfig'=> $arrConfig))
@endif
</td>