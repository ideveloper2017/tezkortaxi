<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Title -->
    <title>@yield('title'){{ Setting::get('site_title') }} - Recent Trips</title>

    <link rel="shortcut icon" type="image/png" href="{{ asset(Setting::get('site_icon')) }}" />

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/bootstrap4/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/waves/waves.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/switchery/dist/switchery.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Buttons/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Buttons/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('asset/admin/vendor/dropify/dist/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/admin/assets/css/dispatcher-core.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <style type="text/css">
        .rating-outer span,
        .rating-symbol-background {
            color: #ffe000!important;
        }
        .rating-outer span,
        .rating-symbol-foreground {
            color: #ffe000!important;
        }
        @media (min-width: 768px)
.site-header .navbar-left {
    float: left !important;
    width: 220px !important;
}
    </style>
    @yield('styles')
</head>
<body class="fixed-sidebar fixed-header content-appear skin-default">

    <div class="wrapper">
        <div class="preloader"></div>
        <div class="site-overlay"></div>
		
        @include('dispatcher.layout.partials.header')

        <div class="site-content" style="margin-left: 0 !important;">

            @include('common.notify')

            @yield('content')

            @include('dispatcher.layout.partials.footer')
			
        </div>
    </div>
    <script type="text/javascript">
        var bases_url     =  '{{ url("/") }}';
    </script>
    <!-- Vendor JS -->
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery/jquery-2.1.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/tether/js/tether.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/bootstrap4/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/detectmobilebrowser/detectmobilebrowser.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/mwheelIntent.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery-fullscreen-plugin/jquery.fullscreen-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/waves/waves.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Responsive/js/dataTables.responsive.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/dataTables.buttons.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/JSZip/jszip.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/pdfmake/build/pdfmake.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/pdfmake/build/vfs_fonts.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.html5.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.print.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.colVis.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('asset/admin/vendor/switchery/dist/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/dropify/dist/js/dropify.min.js')}}"></script>

    <!-- pintaxi JS -->
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/demo.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/tables-datatable.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/forms-upload.js')}}"></script>
    @yield('scripts')
    <script type="text/javascript" src="{{asset('asset/front/js/rating.js')}}"></script>    
    <script type="text/javascript">
        $('.rating').rating(); 
        $(document).ready(function() {
        	$("body").removeClass("compact-sidebar");

    	});
		
		$('#sid_nav .box-32').on('click', function () {
			$('.sid_dropdown').toggleClass('hd_div');
		});

    </script>
</body>
</html>