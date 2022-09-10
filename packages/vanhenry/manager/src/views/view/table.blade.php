@php
    $has_delete = $tableData->get('has_delete', '') == 1;
    $has_update = $tableData->get('has_update', '') == 1;
    $has_copy = $tableData->get('has_copy', '') == 1;
    $has_trash = $tableData->get('has_trash', '') == 1;
    $has_history = $tableData->get('has_history', '') == 1;
@endphp
<div class="pagination">
    <span class="total">{{ trans('db::number_record') }}: <strong>{{ $listData->total() }}</strong></span>
    {{ $listData->withQueryString()->links('vh::vendor.pagination.pagination') }}
</div>
<div id="no-more-tables" class="row m0">
    <div class="tablecontrol none">
        <a class="_vh_action_all btn bg-red-400 text-white" data-confirm="Bạn có thực sự muốn xóa?" href="{{ $admincp }}/deleteAll/{{ $tableData->get('table_map', '') }}" title="{{ trans('db::delete_all') }} {{ $tableData->get('name', '') }}">
        <i class="fa fa-trash" aria-hidden="true"></i> {{ trans('db::delete_all') }}
        </a>
        @if ($tableData->get('table_parent', '') != '')
            <a href="#" data-toggle="modal" data-target="#addToParent" class="_vh_add_to_parent"
                title="Thêm vào danh mục cha"><i class="fa fa-puzzle-piece" aria-hidden="true">Thêm vào danh mục cha</i>
            </a>
            <a href="#" title="Xóa khỏi danh mục cha" data-toggle="modal" data-target="#addToParent"
                class="_vh_remove_from_parent"><i class="fa fa-chain-broken" aria-hidden="true">Xóa khỏi danh mục
                    cha</i></a>
        @endif
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.double-scroll').doubleScroll();
        });
    </script>
    <div class="main_table double-scroll">
        <table class="col-md-12 table-bordered table-striped table-condensed cf p0 table-data-view">
            <thead class="cf">
                <tr>
                    @if ($has_delete)
                        <th>
                            <div class="squaredTwo">
                                <input type="checkbox" class="all" value="None"
                                    id="squaredTwoall{{ @$dataKey ?? '' }}" name="check">
                                <label for="squaredTwoall{{ @$dataKey ?? '' }}"
                                    data-tab="{{ @$dataKey ?? '' }}"></label>
                            </div>
                        </th>
                    @endif
                    {%FILTER.simpleShow.filterShow.tableDetailData%}
                    <th>STT</th>
                    @foreach ($simpleShow as $show)
                        @php
                            $urlSorts = FCHelper::buildUrlSort($show);
                        @endphp
                        @if ($show->hide !== 1)
                            <th class="{{ $urlSorts['cursor'] }}" data-href="{{ \Str::replaceFirst('/view/', '/search/', \Str::replaceFirst('?&', '?', $urlSorts['url_sort'])) }}">
                                {{ $show->note }}
                                @if ($urlSorts['ordervalue'] == 'asc')
                                    <i class="fa fa-sort-asc" aria-hidden="true"></i>
                                @elseif($urlSorts['ordervalue'] == 'desc')
                                    <i class="fa fa-sort-desc" aria-hidden="true"></i>
                                @endif
                            </th>
                        @endif
                    @endforeach
                    @if ($has_delete || $has_update || $has_copy || $has_trash || $has_history)
                        <th>Chức năng</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <?php $urlFull = base64_encode(Request::fullUrl()); ?>
                @for ($i = 0; $i < $listData->count(); $i++)
                    <?php $itemMain = $listData->get($i); ?>
                    <tr>
                        @if ($has_delete)
                            <td data-title="#">
                                <div class="squaredTwo">
                                    <input type="checkbox" class="one"
                                        dt-id="{{ FCHelper::ep($itemMain, 'id') }}"
                                        id="squaredTwo{{ FCHelper::ep($itemMain, 'id') }}" name="check">
                                    <label for="squaredTwo{{ FCHelper::ep($itemMain, 'id') }}"></label>
                                </div>
                            </td>
                        @endif
                        <td data-title="STT">{{ $i + 1 }}</td>
                        
                        @foreach ($simpleShow as $key => $show)
                            @php
                                preg_match('/(.*?)(::)(.+)/', $show->type_show, $matches);
                                $viewView = isset($matches[1], $matches[2], $matches[3]) && $matches[2] == '::' ? $matches[1].$matches[2].'ctview.'.$matches[3] : 'tv::ctview.'.StringHelper::normal(FCHelper::er($show ,'type_show'));
                                $viewView = View::exists($viewView)?$viewView:"tv::ctview.base";
                            @endphp
                            @if ($show->hide !== 1)
                                @include($viewView,array('item'=>$show,'dataItem'=>$itemMain))
                            @endif
                        @endforeach

                        @php
                            $actions = config('sys_action.'.FCHelper::ep($tableData,'table_map'));
                        @endphp
                        @if ($has_delete || $has_copy || $has_update || $has_trash || $has_history || !is_null($actions))
                            <td data-title="{{ trans('db::function') }}" style="min-width: 130px;"
                                class="action">
                                @if(!is_null($actions))
                                    @foreach($actions as $action)
                                        @include('vh::table.action_button')
                                    @endforeach
                                @endif
                                @isset($itemMain->slug)
                                    <a href="{{ $itemMain->slug }}" target="_blank"
                                        class="{{ trans('db::edit') }} tooltipx {{ $tableData->get('table_map', '') }}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <span class="tooltiptext">Xem demo</span>
                                    </a>
                                @endisset
                                @if ($has_history)
                                    <a href="{{ $admincp }}/history/{{ $tableData->get('table_map', '') }}/{{ FCHelper::ep($itemMain, 'id') }}?returnurl={{ $urlFull }}"
                                        class="{{ trans('db::history') }} tooltipx {{ $tableData->get('table_map', '') }}">
                                        <i class="fa fa-history" aria-hidden="true"></i>
                                        <span class="tooltiptext">Lịch sử thay đổi</span>
                                    </a>
                                @endif
                                @if ($has_copy)
                                    <a href="{{ $admincp }}/copy/{{ $tableData->get('table_map', '') }}/{{ FCHelper::ep($itemMain, 'id') }}?returnurl={{ $urlFull }}"
                                        class="{{ trans('db::edit') }} tooltipx {{ $tableData->get('table_map', '') }}"><i
                                            class="fa fa-copy" aria-hidden="true"></i>
                                        <span class="tooltiptext">Copy</span>
                                    </a>
                                @endif
                                @if ($has_update)
                                    <a href="{{ $admincp }}/edit/{{ $tableData->get('table_map', '') }}/{{ FCHelper::ep($itemMain, 'id') }}?returnurl={{ $urlFull }}"
                                        class="{{ trans('db::edit') }} tooltipx {{ $tableData->get('table_map', '') }}"><i
                                            class="fa fa-pencil" aria-hidden="true"></i>
                                        <span class="tooltiptext">Sửa</span>
                                    </a>
                                @endif
                                @if ($has_trash)
                                    <a href="{{ $admincp }}/{{ isset($trash) ? 'backtrash' : 'trash' }}/{{ $tableData->get('table_map', '') }}"
                                        class="_vh_{{ isset($trash) ? 'backtrash' : 'trash' }} tooltipx {{ trans('db::delete') }} {{ $tableData->get('table_map', '') }}"><i
                                            class="fa fa-{{ isset($trash) ? 'level-up' : 'trash' }}"
                                            aria-hidden="true"></i>
                                        <span
                                            class="tooltiptext">{{ isset($trash) ? 'Restore' : 'Thùng rác' }}</span>
                                    </a>
                                @endif
                                @if($has_delete)
                                    <a href="{{$admincp}}/delete/{{$tableData->get('table_map','')}}" class="_vh_delete_permanent _vh_delete tooltipx {{trans('db::delete')}} {{$tableData->get('table_map','')}}"><i class="fa fa-times-circle" aria-hidden="true"></i>
                                        <span class="tooltiptext">Xóa vĩnh viễn</span>
                                    </a>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <span class="total">{{ trans('db::number_record') }}:<strong>
                {{ $listData->total() }}</strong></span>
        {{ $listData->withQueryString()->links('vh::vendor.pagination.pagination') }}
    </div>
</div>
