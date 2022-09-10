<?php
    $name = FCHelper::er($table, 'name');
    $default_data = FCHelper::er($table, 'default_data');
    $default_data = json_decode($default_data, true);

    $default_code = FCHelper::er($table, 'default_code');
    $default_code = json_decode($default_code, true);

    $arrData = FCHelper::er($default_data, 'data'); 
    $arrConfig = FCHelper::er($default_data, 'config');
    $source = FCHelper::er($arrConfig, 'source');
?>

@if (View::exists('tv::ctedit.select.' . $source))
	@include('tv::ctedit.select.' . $source, ['arrData' => $arrData, 'arrConfig' => $arrConfig, 'default_data' => $default_data])
@endif
