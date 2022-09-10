<div class="filter-group">
    @php
        $value = isset($dataSearch['raw_' . $search->name]) ? $dataSearch['raw_' . $search->name] : '';
    @endphp
    <input type="text" name="raw_{{ $search->name }}" placeholder="{{ $search->note }}" value="{{ $value }}">
</div>