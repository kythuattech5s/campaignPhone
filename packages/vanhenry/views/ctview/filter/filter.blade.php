@php
    $has_history = $tableData->get('has_history', '') == 1;
    $arrayIncludesHistory = ['news']
@endphp
<div class="filter-table">
    {%FILTER.advanceSearchs.filterAdvanceSearch.tableDetailData%}
    {%FILTER.simpleSearch.filterSimpleSearch.tableDetailData%}
    {%FILTER.simpleSort.filterSimpleSort.tableDetailData%}
    @php
        $simple_search_value = isset($dataSearch['raw_' . $simpleSearch->name]) && $dataSearch['raw_' . $simpleSearch->name] !== 'raw_' . $simpleSearch->name ? $dataSearch['raw_' . $simpleSearch->name] : '';
    @endphp
    <form id="frmsearch" action="{{ $admincp }}/search/{{ $tableData->get('table_map', '') }}"
        class="">
        <div class="filter-table__top">
            <div class="group-filter--left">
                @if(isset($history_table_name) || ($tableData->get('table_map', '') == 'h_histories' && isset(request()->raw_table_name) && in_array(request()->raw_table_name,$arrayIncludesHistory) ))
                    <input type="hidden" name="raw_table_name" value="{{$history_table_name ?? request()->raw_table_name}}">
                @endif
                @if ($simpleSearch !== null)
                    <div class="filter-group">
                        <select name="raw_{{ $simpleSearch->name }}_type_filter" id="">
                            <option value="~=" {{request()->input("raw_".$simpleSearch->name."_type_filter") == '!=' ? 'selected' : ''}}>~=</option>
                            <option value="==" {{request()->input("raw_".$simpleSearch->name."_type_filter") == '==' ? 'selected' : ''}}>=</option>
                        </select>
                        <input type="text" name="raw_{{ $simpleSearch->name }}"
                            placeholder="{{ trans('db::search') }} {{ trans('db::as') }} {{ FCHelper::ep($simpleSearch, 'note') }}"
                            value="{{ @$simple_search_value }}">
                    </div>
                @endif
                @foreach ($advanceSearchs as $search)
                    @php
                        $viewSearch = strpos(FCHelper::er($search,'type_show'),'::') ? FCHelper::er($search,'type_show').'_view' : 'tv::ctsearch.'.StringHelper::normal(FCHelper::er($search ,'type_show'));
                        $viewSearch = View::exists($viewSearch)?$viewSearch:"tv::ctsearch.base";
                    @endphp    
                    @include($viewSearch)
                @endforeach
            </div>
            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            @if(isset($history_table_name) || ($tableData->get('table_map', '') == 'h_histories' && isset(request()->raw_table_name) && in_array(request()->raw_table_name,$arrayIncludesHistory) ))
                <a class="refresh ms-2" href="{{ url('esystem/history/'.($history_table_name ?? request()->raw_table_name ).'/0')  }}"><i class="fa fa-refresh" aria-hidden="true"></i></a>
            @else
                <a class="refresh ms-2" href="{{ url('esystem/view/' . $tableData['table_map'] . (isset($key) ? '?tab=' . $key : '')) }}"><i class="fa fa-refresh" aria-hidden="true"></i></a>
            @endif
        </div>
        <div class="filter-table__bottom">
            <p class="filter-table__sort-title">Sắp xếp</p>
            <div class="filter-group">
                <select name="orderkey" class="select2" style="width:160px">
                    {%FILTER.simpleSort.filterSimpleSort.tableDetailData%}
                    @foreach ($simpleSort as $ss)
                        @if (isset(request()->orderkey) && request()->orderkey == $ss->name)
                            <option {{ request()->orderkey == $ss->name ? 'selected' : '' }}
                                value="{{ $ss->name }}">{{ $ss->note }}</option>
                        @elseif(!isset($dataSearch) || $dataSearch['orderkey'] == 'id')
                            <option {{ $ss->type_show == 'PRIMARY_KEY' ? 'selected' : '' }}
                                value="{{ $ss->name }}">{{ $ss->note }}</option>
                        @else
                            <option {{ $ss->name == $dataSearch['orderkey'] ? 'selected' : '' }}
                                value="{{ $ss->name }}">{{ $ss->note }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="ordervalue" class="select2" style="width:100px">
                    @if (isset($dataSearch['ordervalue']))
                        <option {{ $dataSearch['ordervalue'] == 'desc' ? 'selected' : '' }} value="desc">
                            {{ trans('db::from') }} Z->A</option>
                        <option {{ $dataSearch['ordervalue'] == 'asc' ? 'selected' : '' }} value="asc">
                            {{ trans('db::from') }} A->Z</option>
                    @elseif(isset(request()->ordervalue))
                        <option {{ request()->ordervalue == 'desc' ? 'selected' : '' }} value="desc">
                            {{ trans('db::from') }} Z->A</option>
                        <option {{ request()->ordervalue == 'asc' ? 'selected' : '' }} value="asc">
                            {{ trans('db::from') }} A->Z</option>
                    @else
                        <option selected value="desc">{{ trans('db::from') }} Z->A</option>
                        <option value="asc">{{ trans('db::from') }} A->Z</option>
                    @endif
                </select>
            </div>
            <div class="filter-group">
                <select name="limit" class="select2" style="width:80px">
                    <option {{isset($dataSearch) && $dataSearch['limit'] == 10 ? 'selected' : ''}} value="10">10</option>
                    <option {{ isset($dataSearch) && $dataSearch['limit'] == 20 ? 'selected' : '' }} value="20">20
                    </option>
                    <option {{ isset($dataSearch) && $dataSearch['limit'] == 50 ? 'selected' : '' }} value="50">50
                    </option>
                    <option {{ isset($dataSearch) && $dataSearch['limit'] == 100 ? 'selected' : '' }} value="100">100
                    </option>
                </select>
            </div>
            @if($has_history)
            <div class="filter-group">
                <a href="{{ url('esystem/history/'. $tableData['table_map'].'/0') }}"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử thay đổi</a>
                </div>
            @endif
            @if(isset($history_table_name) || ($tableData->get('table_map', '') == 'h_histories' && isset(request()->raw_table_name) && in_array(request()->raw_table_name,$arrayIncludesHistory)  ))
                <div class="filter-group">
                    <a href="{{ url('esystem/view/'. ($history_table_name ?? request()->raw_table_name)) }}"> <i class="fa fa-list" aria-hidden="true"></i> Quay lại danh sách</a>
                </div>
            @endif
        </div>
    </form>
</div>