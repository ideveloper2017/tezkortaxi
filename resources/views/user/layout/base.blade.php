<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Setting::get('site_title') }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>

    <link href="{{asset('asset/front/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front/css/slick.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('asset/front/css/slick-theme.css')}}"/>
    <link href="{{asset('asset/front/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front/css/bootstrap-timepicker.css')}}" rel="stylesheet">
    
    <link href="{{asset('asset/front/css/dashboard-style.css')}}" rel="stylesheet">

    <link href="{{asset('asset/front/css/ismael.css')}}" rel="stylesheet">

<!-- from admin-->
    <link rel="stylesheet" href="{{ asset('asset/admin/assets/css/core.css') }}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.css')}}">

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
    <link rel="stylesheet" href="{{ asset('asset/admin/vendor/dropify/dist/css/dropify.min.css') }}">
   
    @yield('styles')
</head>

<body class="fixed-sidebar fixed-header content-appear skin-default">

    <div class="wrapper">
        <div class="preloader"></div>
        <div class="site-overlay"></div>
        @include('user.layout.partials.header')

        <div class="page-content dashboard-page">    
            <div class="site-content no-margin">
                
                @include('user.layout.partials.nav')
                <div style="margin-top: -70px">
                    @yield('content')
                </div>

                
            </div>
        </div>
    </div>    

    <script src="{{asset('asset/front/js/jquery.min.js')}}"></script>
    <script src="{{asset('asset/front/js/bootstrap.min.js')}}"></script>       
    <script type="text/javascript" src="{{asset('asset/front/js/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/front/js/jquery-migrate-1.2.1.min.js')}}"></script> 
    <script type="text/javascript" src="{{asset('asset/front/js/slick.min.js')}}"></script>
    <script src="{{asset('asset/front/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('asset/front/js/bootstrap-timepicker.js')}}"></script>
    <script src="{{asset('asset/front/js/dashboard-scripts.js')}}"></script>

	
    <!-- pintaxi JS -->
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/demo.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/tables-datatable.js')}}"></script> 
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/forms-upload.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/waves/waves.min.js')}}"></script>

    <!-- <script type="text/javascript" src="{{asset('admin/vendor/jscrollpane/jquery.mousewheel.js')}}"></script> -->
    <!-- <script type="text/javascript" src="{{asset('admin/vendor/jscrollpane/mwheelIntent.js')}}"></script> -->
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.min.js')}}"></script>
    <link href="{{ asset('asset/front_dashboard/css/hamburgers.css') }}" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script type="text/javascript">
       
        
    </script>
    <script type="text/javascript">
       
   //if(jQuery.browser.mobile == false) {
            
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
        //}

        /* Scroll - if mobile */
        if(jQuery.browser.mobile == true) {
            $('.custom-scroll').css('overflow-y','scroll');
        }
    </script>

    @yield('scripts')

    <script type="text/javascript" src="{{asset('asset/front/js/rating.js')}}"></script>    
    <script type="text/javascript">
        
        
        $('.rating').rating();
    
            $(".hamburger").click(function(){       
            $(".site-sidebar").toggleClass("open"); 
            $('.hamburger').toggleClass("is-active");
            $(".site-content").toggleClass("no-margin");
        }); 
        
        var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
    
    </script>
    
<script>
//     $(window).load(function() {
      
       
//     document.getElementById('paybtn').onclick = function(e){
//     rzp1.open();
//     e.preventDefault();
// }
//     // console.log( "ready!" );
// });

   
        
    

</script>

    
</body>
</html>