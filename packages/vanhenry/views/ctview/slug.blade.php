<td data-title="{{$show->note}}">
	<div class="edit-slug">
		<input dt-prop="{{$show->is_prop ?? 0}}" dt-prop-id="{{$show->id}}" class="{{$show->editable==1?'editable':''}}"  name="{{$show->name}}" title="{{$show->note}}" value="{{FCHelper::ep($dataItem,$show->name)}}" type="text" disabled />
		<button type="button" class="get-link tooltipx ">
			<i class="fa fa-link" aria-hidden="true"></i>
			<span class="tooltiptext">Lấy link</span>
		</button>
	</div>
</td>