<?php
$user = Auth::guard('h_users')
    ->user()
    ->with('hGroupUser')
    ->find(Auth::guard('h_users')->id());
$value = FCHelper::ep($dataItem, $show->name);

if ($value == 1) {
    $className = 'no-editable';
} elseif ($show->editable == 1) {
    $className = 'editable';
} else {
    $className = '';
}
?>
<td data-title="{{ $show->note }}">
    <input dt-prop="{{ $show->is_prop ?? 0 }}" dt-prop-id="{{ $show->id }}" type="checkbox" data-off-label="false"
        data-on-label="false" data-off-icon-cls="glyphicon-remove" name="{{ $show->name }}"
        {{ $value == 1 ? 'checked' : '' }} data-on-icon-cls="glyphicon-ok" class="ccb {{ $className }}" onchange="setTimeout(()=>{
   this.className = 'ccb no-editable hidden'},1)" />
</td>
