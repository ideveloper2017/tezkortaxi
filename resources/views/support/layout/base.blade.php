<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="utf-8">
    <title>{{ Setting::get('site_title') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset(Setting::get('site_icon')) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    

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
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="{{ asset('admin/vendor/dropify/dist/css/dropify.min.css') }}">
	<link rel="stylesheet" href="{{asset('asset/front/css/jasny-bootstrap.min.css')}}">
	<link href="{{ asset('asset/front_dashboard/css/hamburgers.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/admin/assets/css/core.css') }}">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

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
    </style>
	
    @yield('styles')
</head>
<body class="fixed-sidebar fixed-header content-appear skin-1">

    <div class="wrapper">
        <div class="preloader"></div>
        <div class="site-overlay"></div>
        @include('support.layout.partials.header')
		<div class="page-content dashboard-page">    
            <div class="site-content no-margin">
				@include('common.notify')
				@include('support.layout.partials.nav')
                @yield('content')
				@include('support.layout.partials.footer')
            </div>
        </div>
    </div>

    <!-- Vendor JS -->
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery/jquery-1.12.3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/tether/js/tether.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/bootstrap4/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/detectmobilebrowser/detectmobilebrowser.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/mwheelIntent.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery-fullscreen-plugin/jquery.fullscreen')}}-min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/waves/waves.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Responsive/js/dataTables.responsi')}}ve.min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Responsive/js/responsive.bootstra')}}p4.min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/dataTables.buttons')}}.min.js"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/DataTables/Buttons/js/buttons.bootstrap4')}}.min.js"></script>
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
        if(jQuery.browser.mobile == false) {
        function initScroll(){
            $('.custom-scroll').jScrollPane({
                autoReinitialise: true,
                autoReinitialiseDelay: 100
            });
        }

        initScroll();

        $(window).resize(function() {
            initScroll();
        });
    }
	
	$(".hamburger").click(function(){		
		$(".site-sidebar").toggleClass("open");	
		$('.hamburger').toggleClass("is-active");
		$(".site-content").toggleClass("no-margin");
	}); 
	var wId = null;

       $('#accountApproved').click(function(){
        
        var id = $(this).attr('data');


        $.ajax({

            url: '{{url("admin/approved_account?id=")}}'+id,
            beforeSend: function(xhr){xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');},
             success: function(result){

            
            location.reload();
            $("#msg").html('you have successfully approved');
            console.log(result);
            

        }});

    });
	var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
	
    /* Scroll - if mobile */
    if(jQuery.browser.mobile == true) {
        $('.custom-scroll').css('overflow-y','scroll');
    }
    </script>
</body>
</html>