<div class="filter-group">
    @php
        if (isset($dataSearch['raw_' . $search->name])) {
            $value = $dataSearch['raw_' . $search->name];
        } else {
            $value = '';
        }
    @endphp
    <input type="text" name="raw_{{ $search->name }}" placeholder="{{ $search->note }}"
        value="{{ $value }}">
</div>