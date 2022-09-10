<td data-title="{{$show->note}}">
	<div  dt-prop="{{$show->is_prop ?? 0}}" dt-prop-id="{{$show->id}}"  name="{{$show->name}}" title="{{$show->note}}">
		{!!strip_tags(FCHelper::ep($dataItem,$show->name),'<p><br>')!!}
	</div>
</td>