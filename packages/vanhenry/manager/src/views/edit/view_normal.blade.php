@extends('vh::master')
@section('css')
	@if ($tableData->get('has_yoast_seo', '') == 1)
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
	@php 
        $tableMap = $tableData->get('table_map', '');
        $configs = config('sys_table.edit');
    @endphp
    @foreach($configs as $config)
        @php
            $sys_config = config("sys_table.$config");
            $hasConfig = array_filter($sys_config,function($value, $key) use($tableMap){
                return $key === $tableMap;
            },ARRAY_FILTER_USE_BOTH);
        @endphp
	    {{-- Thêm các event từ config --}}
        @if(count($hasConfig) > 0)
            <span sys-config data-type="{{$config}}" data-table="{{$tableMap}}" data-action="{{$actionType}}" @if(isset($sys_config[$tableMap]['time'])) data-time="{{$sys_config[$tableMap]['time']}}" @endif></span>
        @endif
    @endforeach
	<input class="one hidden" dt-id="{{ FCHelper::er($dataItem, 'id') }}">
	<!--Lưu id để xóa-->
	<div class="header-top aclr">
		<div class="breadc pull-left">
			<ul class="aclr pull-left list-link">
				<li class="pull-left"><a href="{{ $admincp }}/view/{{ $tableMap }}">{{ $tableData->get('name', '') }}</a>
				</li>
			</ul>
			@if ($transTable != null)
				<ul class="table-lang edit">
					<?php $tableLangs = \Session::get('_table_lang'); ?>
					@foreach ($locales as $localeCode => $v)
						<li><a
								href="{{ $admincp }}/table-lang/{{ $tableData->get('table_map', '') }}/{{ $localeCode }}"
								data-lang="{{ $localeCode }}"
								class="{{ (!is_array($tableLangs) && $localeCode == Config::get('app.locale_origin')) ||(is_array($tableLangs) &&array_key_exists($tableData->get('table_map'), $tableLangs) &&$tableLangs[$tableData->get('table_map')] == $localeCode)? 'active': '' }}">{{ $v }}</a>
						</li>
					@endforeach
				</ul>
			@endif
		</div>
		<div>
			{{-- <a class="pull-right bgmain1 viewsite" target="_blank" href="{{asset('/')}}">
			<i class="fa fa-external-link" aria-hidden="true"></i>
			<span  class="clfff">Xem website</span> 
		</a> --}}

			@if ($actionType == 'edit')
				{{-- <a class="pull-right bgmain viewsite" href="#">
				<i class="fa fa-copy" aria-hidden="true"></i>
				<span  class="clfff">Copy</span> 
			</a> --}}
				{{-- <a class="pull-right bgmain viewsite _vh_delete" href="{{$admincp}}/delete/{{$tableMap}}">
				<i class="fa fa-trash" aria-hidden="true"></i>
				<span  class="clfff">Xóa</span> 
			</a> --}}
				{{-- <a class="pull-right bgmain viewsite _vh_update" href="#">
				<i class="fa fa-pencil" aria-hidden="true"></i>
				<span  class="clfff">Cập nhật</span> 
			</a> --}}
			@endif
			<a class="pull-right bgmain viewsite _vh_save" href="#">
				<i class="fa fa-save" aria-hidden="true"></i>
				<span class="clfff">Lưu</span>
			</a>
			<a class="pull-right bgmain1 viewsite"
				href="{{ base64_decode(\Request::input('returnurl')) }}">
				<i class="fa fa-backward" aria-hidden="true"></i>
				<span class="clfff">Back</span>
			</a>

		</div>
	</div>
	<?php
	if ($actionType == 'edit') {
	    $actionAjax = "$admincp/update/" . $tableMap . '/' . FCHelper::er($dataItem, 'id');
	    $actionNormal = "$admincp/save/" . $tableMap . '/' . FCHelper::er($dataItem, 'id') . '?returnurl=' . Request::input('returnurl');
	} else {
	    $actionAjax = "$admincp/storeAjax/" . $tableMap;
	    $actionNormal = "$admincp/store/" . $tableMap . '?returnurl=' . Request::input('returnurl');
	}
	?>
	<div id="maincontent">
		<form action="{{ $actionNormal }}" dt-ajax="{{ $actionAjax }}"
			dt-normal="{{ $actionNormal }}" method="post" id="frmUpdate">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="tech5s_controller"
				value="{{ $tableData->get('controller', '') }}">
			<div id="mainedit" class="row">
				<div class="col-xs-12 col-md-9 p0">
					<?php
					    $mainTable = count($tableDetailData) > 0 ? $tableDetailData[1] : [];
					?>
					@foreach ($mainTable as $mTable)
						<?php $currentGroup = count($mTable) > 0 ? $mTable[0]->group : 0; ?>
						<?php if (!isset($groupControl[$currentGroup])) {
						    continue;
						} ?>
						<div class="row m0 boxedit">
							@if ($groupControl[$currentGroup]->has_button_hide == 1)
								<div class="col-xs-12 boxtitle">
									<h1 class="col-xs-9 p0">
										{{ FCHelper::ep($groupControl[$currentGroup], 'name', 1) }}</h1>
									<div class="textright col-xs-3 p0">
										<button type="button"
											class="btn btn-primary bgmain btnshow">{{ trans('db::edit') }}</button>
									</div>
								</div>
							@else
								<h1 class="col-xs-12">
									{{ FCHelper::ep($groupControl[$currentGroup], 'name', 1) }}</h1>
							@endif
							<p class="des col-xs-12">
								{{ FCHelper::ep($groupControl[$currentGroup], 'note', 1) }}</p>
							<div
								class="col-xs-12 {{ $groupControl[$currentGroup]->has_button_hide == 1 ? 'boxhide' : '' }} {{ $groupControl[$currentGroup]->display_default == 0 ? 'none' : '' }}">
								@foreach ($mTable as $table)
									@php
                                        preg_match('/(.*?)(::)(.+)/', $table->type_show, $matches);
                                        $viewEdit = isset($matches[1], $matches[2], $matches[3]) && $matches[2] == '::' ? $matches[1].$matches[2].'ctedit.'.$matches[3] : 'tv::ctedit.' . StringHelper::normal(FCHelper::er($table, 'type_show'));
                                        $viewEdit = View::exists($viewEdit) ? $viewEdit : 'tv::ctedit.base';
									@endphp
									@include($viewEdit)
								@endforeach
							</div>
						</div>
					@endforeach
					<?php $exs = \Event::dispatch('vanhenry.manager.insert.generate_view', [$tableData]); ?>
					@foreach ($exs as $exk => $exvs)
						@if (is_array($exvs))
							@foreach ($exvs as $exvv)
								@include('vh::' . $exvv)
							@endforeach
						@endif
					@endforeach
				</div>
				<div class="col-xs-12 col-md-3">
					<?php
					$sideTable = count($tableDetailData) > 1 ? $tableDetailData[2] : [];
					?>
					@foreach ($sideTable as $side)
						<?php $currentGroup = count($side) > 0 ? $side[0]->group : 0; ?>
						<?php if (!isset($groupControl[$currentGroup])) {
						    continue;
						}
						?>
						<div class="row m0 boxedit">
							<h1 class="col-xs-12">
								{{ FCHelper::ep($groupControl[$currentGroup], 'name') }}</h1>
							<div class="col-xs-12">
								@foreach ($side as $key => $table)
									@php
										preg_match('/(.*?)(::)(.+)/', $table->type_show, $matches);
                                		$viewEdit = isset($matches[1], $matches[2], $matches[3]) && $matches[2] == '::' ? $matches[1].$matches[2].'ctedit.'.$matches[3] : 'tv::ctedit.' . StringHelper::normal(FCHelper::er($table, 'type_show'));
                                        $viewEdit = View::exists($viewEdit) ? $viewEdit : 'tv::ctedit.base';
									@endphp
									@include($viewEdit)
								@endforeach

							</div>
						</div>
					@endforeach
				</div>
			</div>
		</form>
		@include('vh::edit.view_edit_script')
		@include('vh::static.footer')
	</div>
@stop
<?php $tableYoastSeo = \Config::get('manager.table_yoast_seo'); ?>
@section('js')
	@if ($tableData->get('has_yoast_seo', '') == 1)
		<script type="text/javascript" src="admin/tech5s_yoast_seo/theme/js/yoastseo.js" defer></script>
		<script type="text/javascript" src="admin/tech5s_yoast_seo/theme/js/script.js" defer></script>
	@endif
    @php
        $scripts = config('sys_view'.'.'.$tableData->get('table_map').'.script',false);
    @endphp
    @if($scripts)
        @foreach($scripts as $script_link)
            <script src="{{$script_link}}" type="text/javascript" defer></script>
        @endforeach
    @endif
@endsection
