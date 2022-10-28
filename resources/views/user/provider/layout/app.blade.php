<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ Setting::get('site_favicon', asset('favicon.ico')) }}" type="image/x-icon">
    <link rel="icon" href="{{ Setting::get('site_favicon', asset('favicon.ico')) }}" type="image/x-icon">

    <title>InstaCab</title>
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>
    

    <!-- Styles -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/rating.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/css/dashboard-style.css') }}" rel="stylesheet" type="text/css">


    <!-- from admin -->
    <link rel="stylesheet" href="{{ asset('main/assets/css/core.css') }}">
    <link rel="stylesheet" href="{{asset('main/vendor/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/switchery/dist/switchery.min.css')}}">
    <link rel="stylesheet" href="{{asset('main/vendor/chartist/chartist.min.css')}}">

    @yield('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body class="fixed-sidebar fixed-header content-appear skin-default ">
    
    <div class="wrapper">
        <div class="preloader"></div>
        <div class="site-overlay"></div>
            @include('provider.layout.partials.header')

            <div class="page-content dashboard-page">    
            <div class="site-content">
                @include('provider.layout.partials.nav')
                <div style="margin-top: -70px;">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>        

    <div id="modal-incoming"></div>
   

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('asset/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/jquery.mousewheel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/jquery-migrate-1.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/slick.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('asset/js/rating.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/js/dashboard-scripts.js') }}"></script>
    
    <script type="text/javascript">

      
        $.ajax({
            url: '/provider/incoming',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            data: {
                latitude: 21.2050,
                longitude: 72.8408
            },
            type: "GET",
            success: function(data){
               
                $('#active_offline_hdn').val(data.service_status);
                if(data.service_status == 'offline'){
                    
                    $('#stripe_check').prop('checked', false);
                }else{
                    
                    $('#stripe_check').prop('checked', true);
                }
            }.bind(this)
        });

        $('#stripe_check').change(function(){
            if($(this).is(':checked')){
                $('#active_offline_hdn').val('active');
                 $('#stripe_check').prop('checked', true);

            }
            else
            {
                $('#active_offline_hdn').val('offline');
                  $('#stripe_check').prop('checked', false);
            }
            $('#form_online').submit();
        });
    </script>

     <!-- pintaxi JS -->
    <script type="text/javascript" src="{{asset('main/assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/assets/js/demo.js')}}"></script>
    <!--script type="text/javascript" src="{{asset('main/assets/js/tables-datatable.js')}}"></script-->
    <script type="text/javascript" src="{{asset('main/assets/js/forms-upload.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/waves/waves.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('main/vendor/switchery/dist/switchery.min.js')}}"></script>

 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react.js"></script>
    <!-- <script type="text/javascript" src="{{ asset('asset/js/react.js') }}"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react-dom.js"></script>
    <script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
    <script type="text/babel" src="{{ asset('asset/js/incoming.js') }}"></script>
    @yield('scripts')

</body>
</html>