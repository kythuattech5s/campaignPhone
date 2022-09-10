<select name="{{$name}}" class="select2 @if ($isAjax) ajx_search_single_{{ $name }} @endif" style="width:250px">
	<option value="">-- {{ $search->note }} --</option>
    @if(isset($defaultValue) && $isAjax)
        <option value="{{$defaultValue[$dataSelect[0]]}}" selected>{{$defaultValue[$dataSelect[1]]}}</option>
    @endif
	@foreach (@$dataValues as $data)
		@php
			$type_show_value = strtolower($search->type_show) == 'pivot' ? $search->name : 'raw_' . $search->name;
            $selectedFilter = isset($dataSearch) && isset($dataSearch[$type_show_value]) && $dataSearch[$type_show_value] == ($data->id ?? $data['key']) ? 'selected' : '';
		@endphp
		<option value="{{ $data->id ?? $data['key'] }}" {{ $selectedFilter }}>
			{{ FCHelper::ep($data, 'name') }}
		</option>
	@endforeach
</select>

@if ($isAjax)
    <script type="text/javascript">
        $(function() {
            const listDatas = {!!$buildDataDefault!!};
            var timeout;
            $('.ajx_search_single_{{ $name }}').select2({
                ajax: {
                    transport: function(params, success, failure) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            var request = $.ajax({
                                url: "{{ $admincp }}/getData/{{ $tableMap }}",
                                data: "POST",
                                data: {
                                    q: params.data.term,
                                    target: '{{implode($dataSelect,',')}}',
                                    page: params.data.page
                                }
                            });
                            request.then(res => {
                                try{
                                    success(JSON.parse(res));
                                }catch(err){}
                            });
                        }, 350);
                    },
                    processResults: function(data, page) {
                        const newData = {};

                        newData['results'] = [{
                            id:"",
                            text: `-- {{ $search->note }} --`,
                        },...listDatas,...data.results]
                        return newData;
                    },
                    cache: true
                },
                minimumInputLength: 1,
                language: "{{ App::getLocale() }}",
            });
        });
    </script>
@endif



