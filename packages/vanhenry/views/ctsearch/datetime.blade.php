<input name="search-{{ $search->name }}" value="none" type="hidden">
<input name="type-{{ $search->name }}" value="DATETIME" type="hidden">
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
    <input type="text" class="datepicker-filter" name="from-{{ $search->name }}"
        placeholder="-- {{ $search->note }} từ --" value="{{ $valueFrom }}" autocomplete="off">
    <input type="text" class="datepicker-filter" name="to-{{ $search->name }}"
        placeholder="-- {{ $search->note }} đến --" value="{{ $valueTo }}" autocomplete="off">
</div>