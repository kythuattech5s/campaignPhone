<td data-title="{{$show->note}}">

<?php 

	$value = FCHelper::ep($dataItem,$show->name);

	$value = json_decode($value,true);

	$value = @$value?$value:[];

	$read = isset($value['read'])?$value['read']:['class'=>'none','score'=>'0'];

	$seo = isset($value['seo'])?$value['seo']:['class'=>'none','score'=>'0'];

	$color = ($read['class']=='bad'?'#dc3232':($read['class']=='good'?'#7ad03a':($read['class']=='ok'?'#ee7c1b':'#999')));

	$colorseo = ($seo['class']=='bad'?'#dc3232':($seo['class']=='good'?'#7ad03a':($seo['class']=='ok'?'#ee7c1b':'#999')));

 ?>

	<span style="width: 10px;height: 10px;display: inline-block;border-radius: 100%;margin: 0px 2px;background: <?php echo $color ?>" class="<?php echo $read['class'] ?>" title="Score Read"></span>

	<span style="width: 10px;height: 10px;display: inline-block;border-radius: 100%;margin: 0px 2px;background: <?php echo $colorseo ?>" class="<?php echo $seo['class'] ?>" title="Score SEO"></span>

</td>