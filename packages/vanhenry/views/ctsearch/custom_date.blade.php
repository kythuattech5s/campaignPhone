@php
    $field_name = $search->name;
    $detail_field = $tableDetailData->first(function($q) use($field_name){
        return $q->name == $field_name;
    });
    $jsonFieldData = json_decode($detail_field->default_data,true);
@endphp
<input name="search-{{ $search->name }}" value="none" type="hidden">
<input name="type-{{ $search->name }}" value="CUSTOM_DATE" type="hidden">
<div class="filter-group">
    @php
        if (isset($dataSearch['from-' . $search->name])) {
            $valueFrom = $dataSearch['from-' . $search->name];
        } else {
            $valueFrom = '';
        }

        if (isset($dataSearch['to-' . $search->name])) {
            $valueTo = $dataSearch['to-' . $search->name];
        } else {
            $valueTo = '';
        }
    @endphp
    <input type="text" class="datepicker-filter {{Str::random(10)}}" name="from-{{ $search->name }}"
        placeholder="-- {{ $search->note }} từ --" value="{{ $valueFrom }}" autocomplete="off" data-format="{{$jsonFieldData['format'] ?? 'H:i:s d/m/Y'}}">
    <input type="text" class="datepicker-filter {{Str::random(10)}}" name="to-{{ $search->name }}"
        placeholder="-- {{ $search->note }} đến --" value="{{ $valueTo }}" autocomplete="off" data-format="{{$jsonFieldData['format'] ?? 'H:i:s d/m/Y'}}">
</div>