@if (strtolower($search->type_show) == 'pivot')
    <input name="search-{{ $search->name }}" value="none" type="hidden">
    <input name="type-{{ $search->name }}" value="PIVOT" type="hidden">
@endif
@php
    $name = strtolower($search->type_show) == 'pivot' ? $search->name  : 'raw_' . $search->name;
    $dataDefault = json_decode($search->default_data, true);
    $isAjax = isset($dataDefault['isAjaxSearch']) && (int) $dataDefault['isAjaxSearch'] === 1; 
    $tableMap = $dataDefault['target_table'];
    $dataSelect = $dataDefault['target_select'];

    if (isset($dataDefault['target_table']) && !$isAjax) {
        $dataValues = DB::table($dataDefault['target_table'])->select($dataDefault['target_select'])->get();
    } else{
        $dataValues = [];
    }

    $value = isset($dataSearch[$name]) ? $dataSearch[$name] : false;
    if($value){
        $defaultValue = collect(DB::table($tableMap)->select($dataSelect)->where('id', $value)->first());
    }

@endphp
<div class="filter-group">
    <select name="{{ strtolower($search->type_show) == 'pivot' ? '' : 'raw_' }}{{ $search->name }}" id=""
        class="select2 @if($isAjax) ajx_search_single_{{ $name }} @endif" style="width:250px">
        <option value="">-- {{ $search->note }} --</option>
        @if(isset($defaultValue))
            <option value="{{$defaultValue[$dataSelect[0]]}}" selected>{{$defaultValue[$dataSelect[1]]}}</option>
        @endif
        @foreach (@$dataValues as $data)
            @php
                $type_show_value = strtolower($search->type_show) == 'pivot' ? $search->name : 'raw_' . $search->name;
                if (isset($dataSearch) && isset($dataSearch[$type_show_value]) && $dataSearch[$type_show_value] == ($data->id ?? $data['key'])) {
                    $selectedFilter = 'selected';
                } else {
                    $selectedFilter = '';
                }
            @endphp
            <option value="{{ $data->id ?? $data['key'] }}" {{ $selectedFilter }}>
                {{ FCHelper::ep($data, 'name') }}
            </option>
        @endforeach
    </select>
</div>
@if ($isAjax)
    <script type="text/javascript">
        $(function() {
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
                        },...data.results]
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