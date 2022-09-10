<!DOCTYPE html>
<html>
<head>
    <title>Quản trị</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="_token" content="{{csrf_token()}}">
    <?php header('X-XSS-Protection: 0'); ?>
    <meta content="{{$admincp}}" name="admincp"/>
    <base href="{{asset('/')}}" />
    <link rel="stylesheet" href="admin/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/bootstrap/css/bootstrap-theme.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/css/font-awesome.min.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/plug/scrollbar/jquery.mCustomScrollbar.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/plug/select2/select2-bootstrap.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/plug/toast/toast.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/plug/select2/select2.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/plug/contextmenu/jquery.contextMenu.css" type="text/css" media="screen" />
    {!!\vanhenry\helpers\helpers\SEOHelper::HEADER_SEO(@$currentItem?$currentItem:NULL)!!}	
    <link rel="stylesheet" type="text/css" href="admin/plug/xdsoft/jquery.datetimepicker.min.css"> 
    <script src="admin/bootstrap/js/jquery-1.11.2.min.js"></script>
    <script src="admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="admin/plug/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <link rel="stylesheet" href="admin/css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/css/order.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/css/cssloader.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/css/view_order.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="admin/media/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="{{ asset('admin/css/style_menu_vertical.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('admin/css/style_share_social.css') }}" type="text/css">
    <link type="text/javascript" src="admin/gallery_control/css/jquery.contextMenu.min.css"></link>
    <link rel="stylesheet" href="admin/plug/xdsoft/datetimepicker-master/build/jquery-ui.css" type="text/css" media="screen" />
    <script type="text/javascript" src="admin/gallery_control/js/jquery.contextMenu.min.js"></script>
    <script type="text/javascript" src="admin/gallery_control/js/script.js"></script>
    
    <script type="text/javascript">
        var SUCCESS=200;
        var ERROR=100;
        var REDIRECT=300;
        var PERMISSION=400;
        var typeNotify = "{{Session::get('typeNotify', '')}}";
        var messageNotify = "{{Session::get('messageNotify', '')}}";
    </script>
    <script src="admin/plug/select2/select2.min.js"></script>
    <script src="admin/plug/toast/toast.js"></script>
    <script type="text/javascript" src="admin/js/main.js"></script>
    <script type="text/javascript" src="admin/js/table.js"></script>
    <script type="text/javascript" src="admin/js/menu_new.js"></script>
    <script type="text/javascript" src="admin/js/cate.js"></script>
    <script type="text/javascript" src="admin/js/detail.js"></script>
    <script type="text/javascript" src="admin/js/jquery.form.js"></script>
    <script type="text/javascript" src="admin/js/jquery.techbytarun.excelexportjs.js"></script>
    <script type="text/javascript" src="admin/js/search/main.js"></script>
    <script type="text/javascript" src="admin/plug/xdsoft/jquery.datetimepicker.full.min.js"></script>
    <script type="text/javascript" src="admin/plug/select2/i18n/vi.js"></script>
    <script type="text/javascript" src="admin/plug/simple_toast/simply-toast.min.js"></script>
    <script type="text/javascript" src="admin/plug/bootbox/bootbox.js"></script>
    <script type="text/javascript" src="admin/plug/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="admin/plug/tinymce/jquery.tinymce.min.js"></script>
    <script type="text/javascript" src="admin/plug/tinymce/main.js"></script>
    <script type="text/javascript" src="admin/js/tech.js"></script>
    <script type="text/javascript" src="admin/js/valiForm.min.js"></script>
    <script type="text/javascript" src="admin/plug/checkbox/bootstrap-checkbox.min.js"></script>
    <script type="text/javascript" src="admin/plug/contextmenu/jquery.contextMenu.js"></script>
    <script type="text/javascript" src="admin/plug/jqueryui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="admin/media/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="admin/js/jquery.doubleScroll.js"></script>
    <script type="text/javascript" src="admin/js/social_share.js"></script>
    <script type="text/javascript" src="admin/js/webhooks.js"></script>
    <script type="text/javascript" src="admin/plug/xdsoft/datetimepicker-master/build/jquery-ui.multidatespicker.js"></script>
    @yield('css')
</head>
<body>
    <div class="root" >
        <div class="root-left {{ session('fix_show_menu') ? '' : 'fix-small' }}">
            @include('vh::static.menu')
        </div>
        <div class="root-right {{ session('fix_show_menu') ? '' : 'fix-small' }}">
            @include('vh::static.header_main')
            <div class="main_admin">
                <div class="container-fluid site-wrap" data-menu="<?php echo session('menu_status', 'off'); ?>">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @include('vh::loading')
    @yield('more')
    @include('vh::static.changepass')
    @yield('js')
</body>
