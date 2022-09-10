@extends('vh::master')
    @section('css')
        @if($tableData->get('has_yoast_seo','') == 1)
            <link rel="stylesheet" href="admin/tech5s_yoast_seo/theme/css/yoastseo.css" type="text/css">
        @endif
        @php
            $styles = config('sys_view'.'.'.$tableData->get('table_map').'.style',false);
        @endphp
        @if($styles)
            @foreach($styles as $style_link)
                <link rel="stylesheet" href="{{$style_link}}" type="text/css">
            @endforeach
        @endif
    @endsection
@section('content')
<a href="{{$admincp}}/editableajax/{{$tableData->get('table_map','')}}" class="hidden" id="editableajax"></a>
<div class="header-top aclr">
	<div class="breadc pull-left">
		<ul class="aclr pull-left list-link">
			<li class="active">
                <a  href="{{$admincp}}/view/{{$tableData->get('table_map','')}}">
                    {{FCHelper::ep($tableData,'name')}}
                </a>
            </li>
		</ul>
	</div>
</div>
<div id="maincontent">
    @php
        $tabData = config('sys_tab'.'.'.$tableData->get('table_map',''),false);
    @endphp
	<div class="listcontent">
		<ul class="nav nav-tabs">
            @if($tabData)
				@foreach($tabData['tabs'] as $key => $detailTab)
                    @php
                        if(isset(request()->tab) && $key == request()->tab){
                            $active_tab = 'active';
                        }elseif(!isset(request()->tab)){
                            $active_tab = $loop->first ? 'active' : '';
                        }else{
                            $active_tab = '';
                        }

                         
                    @endphp
                    <li class="{{$active_tab}}">
                        <a class="pull-right bgmain" href="{{url()->current().'?tab='.$key}}">
                            {{$detailTab['label']}}
                            <span class="count">
                                {{$listData[$detailTab['name']]->total()}}
                            </span>
                        </a>
                    </li>
                    @php
                       if(!empty($active_tab)){
                            $dataList = $listData[$detailTab['name']];
                        }
                    @endphp
                @endforeach
			@endif
			@if($tableData->get("has_trash","") == 1)
				<li class=""><a  href="{{$admincp}}/trashview/{{$tableData->get('table_map','')}}">{{trans('db::trash')}}</a></li>
			@endif
			@if($transTable != null)
				<li>
					<ul class="table-lang view">
						<?php $tableLangs = \Session::get('_table_lang') ?>
						@foreach($locales as $localeCode => $v)
						<li><a href="{{$admincp}}/table-lang/{{$tableData->get('table_map','')}}/{{$localeCode}}" class="{{(isset($tableLangs[$tableData->get('table_map')]) && $tableLangs[$tableData->get('table_map')] == $localeCode) || (!isset($tableLangs[$tableData->get('table_map')]) && $localeCode == Config::get('app.locale_origin')) ? 'active' : ''}}">{{$v}}</a></li>
						@endforeach
					</ul>
				</li>
			@endif
			<div class="header-top aclr">
				<div>
					@if($tableData->get("has_import","") == 1)
						<a class="pull-right bgmain viewsite" href="{{$admincp}}/import/{{$tableData->get('table_map','')}}">
							<i class="fa fa-cloud-upload" aria-hidden="true"></i>
							<span  class="clfff">Import</span>
						</a>
					@endif
					@if($tableData->get('has_export',"") == 1)
                        <a class="pull-right bgmain viewsite" href="{{$admincp}}/export/{{$tableData->get('table_map','')}}">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                           <span  class="clfff"> Xuất file excel </span>
                        </a>
                    @endif
					@if($tableData->get('has_insert','')==1)
						<?php $urlFull = base64_encode(Request::fullUrl()); ?>
						<a class="pull-right bgmain viewsite " href="{{$admincp}}/insert/{{$tableData->get('table_map','')}}?returnurl={{$urlFull}}">
							<i class="fa fa-file-o" aria-hidden="true"></i>
							<span  class="clfff">{{trans('db::add')}}</span>
						</a>
					@endif
				</div>
			</div>
		</ul>
		<div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                @include('tv::ctview.filter.filter')
                <div id="main-table">
                    @include('vh::view.table',['listData' => $dataList ?? $listData, 'tableData'=>$tableData])
                </div>
            </div>
		</div>
	</div>
    @php
        $includes = config('sys_components'.'.'.$tableData->get('table_map').'.view',false);
    @endphp
    @if($includes)
        @foreach($includes as $include)
            @include($include['view'],$include['params'])
        @endforeach
    @endif
	@include('vh::static.footer')
</div>
@stop
@section('more')
    @if($tableData->get('table_parent','')!='')
        @include('vh::view.addToParent')
    @endif
@stop
@section('js')
    @php
        $scripts = config('sys_view'.'.'.$tableData->get('table_map').'.script',false);
    @endphp
    @if($scripts)
        @foreach($scripts as $script_link)
            <script src="{{$script_link}}" type="text/javascript" defer></script>
        @endforeach
    @endif
@endsection