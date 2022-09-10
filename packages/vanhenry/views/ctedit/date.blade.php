<?php
$name = FCHelper::er($table, 'name');
$valueShow = date('d/m/Y H:i:s');
$valueHidden = date('Y-m-d H:i:s');
if ($actionType == 'edit' || $actionType == 'copy') {
    $value = FCHelper::ep($dataItem, $name);
    if (!is_null($value) && strtotime($value) > 0){
        $valueShow = $value;
        $valueHidden = $value;
    }
}
?>
<div class="form-group">
	<p class="form-title" for="">{{ FCHelper::ep($table, 'note', 1) }} <p/>
    <input value="{{ $valueHidden }}" class="form-control" type="date" placeholder="{{ FCHelper::er($table, 'note') }}" {{ FCHelper::ep($table, 'require') == 1 ? 'required' : '' }} name="{{ $name }}">
</div>