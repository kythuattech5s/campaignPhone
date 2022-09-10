<td data-title="{{$show->note}}">
    @if(!is_null(FCHelper::ep($dataItem,$show->name)))
    <p>{{date('H:i:s d-m-Y',strtotime(FCHelper::ep($dataItem,$show->name)))}}</p>
    @endif
</td>