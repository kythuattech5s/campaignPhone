@php
$value = isset($dataSearch['raw_' . $search->name]) ? (int) $dataSearch['raw_' . $search->name] : null;
@endphp
<div class="filter-group">
	<select
		name="{{ strtolower($search->type_show) == 'pivot' ? '' : 'raw_' }}{{ $search->name }}"
		id=""
		class="select2" style="width:250px">
		<option value="">-- Trạng thái --</option>
		<option value="1" @if ($value === 1) selected @endif>Bật
		</option>
		<option value="0" @if ($value === 0) selected @endif>Tắt
		</option>
	</select>
</div>
