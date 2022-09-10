<!DOCTYPE html>
<html itemscope="" itemtype="http://schema.org/WebPage" lang="vi">

<head>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEOHelper::HEADER_SEO(@$currentItem ? $currentItem : null) !!}
    {{-- @php
		$css = [];
	@endphp
	{{ SEOHelper::loadCss($css) }} --}}
    @yield('css')
    <script type="text/javascript">
        var showNotify = "";
        var messageNotify = "{{ Session::get('messageNotify', '') }}";
        var typeNotify = "{{ Session::get('typeNotify', '') }}";
        var typePopup = "{{ Session::get('type', '') }}";
        var emailSocial = "{{ Session::get('emailSocial', '') }}";
        var auth = "{{ Session::get('auth', '') }}";
        var redirect = "{{ Session::get('redirect', '') }}";
    </script>
    {[CMS_HEADER]}
</head>

<body>
    @include('layouts.header')
    @yield('main')
    @include('layouts.footer')
    {[CMS_FOOTER]}
    {{-- @if (!\Support::isLightHouseSp()) --}}
    {{-- JS --}}
    {{-- @php
		$js = [];
	@endphp
	{{ SEOHelper::loadJs($js) }} --}}
    {{-- JSL --}}
    @yield('jsl')
    {{-- JS --}}
    @yield('js')
    {{-- @endif --}}
</body>

</html>
