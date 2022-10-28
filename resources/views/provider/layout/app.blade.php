<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ Setting::get('site_title') }}</title>
    <!--title>@yield('title'){{ Setting::get('site_title', 'PinTaxi') }}</title-->
    <link rel="shortcut icon" type="image/png" href="{{ asset(Setting::get('site_icon')) }}"/>
    <link href="{{asset('asset/front/css/ismael.css')}}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('asset/front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/front/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/front/css/slick.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/front/css/slick-theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/front/css/rating.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/front/css/dashboard-style.css') }}" rel="stylesheet" type="text/css">


    <!-- from admin -->
    <link rel="stylesheet" href="{{ asset('asset/admin/assets/css/core.css') }}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/DataTables/Responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/animate.css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/switchery/dist/switchery.min.css')}}">

    

    
    <link href="{{ asset('asset/front_dashboard/css/hamburgers.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.css')}}">

    @yield('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

 <?php
    $fully_sum = 0; ?>
      @foreach($fully as $each)
        
        @if($each->payment != "")
            <?php $each_sum = 0;
            //$each_sum = $each->payment->tax + $each->payment->fixed + $each->payment->distance + $each->payment->commision;
            $each_sum = $each->payment->tax + $each->payment->fixed + $each->payment->distance;
            $fully_sum += $each_sum;
            ?>
        @endif
              
      @endforeach

<!--body class="fixed-sidebar fixed-header content-appear skin-default provider-content"-->
<body class="fixed-sidebar fixed-header content-appear skin-default ">
    
    <div class="wrapper">
        <div class="preloader"></div>
        <div class="site-overlay"></div>
            @include('provider.layout.partials.header')

            <div class="page-content dashboard-page">    
            <div class="site-content no-margin">
                @include('provider.layout.partials.nav')
                <div style="margin-top: -70px;">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>        

    <div id="modal-incoming"></div>
   

    <!-- Scripts -->
    <script type="text/javascript" src="{{ asset('asset/front/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/front/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/front/js/jquery.mousewheel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/front/js/jquery-migrate-1.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/front/js/slick.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('asset/front/js/rating.js') }}"></script>
    <script type="text/javascript" src="{{ asset('asset/front/js/dashboard-scripts.js') }}"></script>

    <script type="text/javascript">
        // code for set total revenue
        $(document).ready(function() {
          document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
        });
 
        //$('#modal-incoming').modal('show');
        // $.incoming({
        //     'url': '{{ route('provider.incoming') }}',
        //     'modal': '#modal-incoming'
        // });
        var glocation_lat = 0;
        var glocation_long = 0;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                 console.log("position: ",position);
                 glocation_lat = position.coords.latitude;
                 glocation_long = position.coords.longitude;
                //this.setState({latitude: position.coords.latitude, longitude: position.coords.longitude});
            }.bind(this));
        }

        $.ajax({
            url: '{{ url("/provider/incoming") }}',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            data: {
                latitude: glocation_lat,
                longitude: glocation_long 
            },
            type: "GET",
            success: function(data){
            
                console.log('ss'+data.service_status);
                $('#active_offline_hdn').attr( 'value', data.service_status);
                if(data.service_status == 'offline'){
                  
                    $('#stripe_check input').removeAttr('checked');
                }else{
                   
                    $('#stripe_check input').attr('checked', 'checked');
                }
            }.bind(this)
        });

        $('#stripe_check input').change(function(){
            
            if($(this).is(':checked')){
            $.ajax({
            url: '{{ url("/provider/updateStatus") }}',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            data: {
                status:'active' 
            },
            type: "GET",
            success: function(data){
           
            }
        });
                $('#active_offline_hdn').val('active');
                $('#stripe_check input').attr('checked', 'checked');

            }
            else
            { 
                $('#active_offline_hdn').val('offline');
                $('#stripe_check input').removeAttr('checked');
            }
            $('#form_online').submit();
        });
    </script>

    <script type="text/javascript" src="{{asset('asset/admin/vendor/detectmobilebrowser/detectmobilebrowser.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.mousewheel.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/mwheelIntent.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/jscrollpane/jquery.jscrollpane.min.js')}}"></script>

     <!-- pintaxi JS -->
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/assets/js/demo.js')}}"></script>

    <script type="text/javascript" src="{{asset('asset/admin/vendor/waves/waves.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('asset/admin/vendor/switchery/dist/switchery.min.js')}}"></script>

 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.3.1/react-dom.js"></script>
    <script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
    <script type="text/babel" src="{{ asset('asset/front/js/incoming.js') }}"></script>
    @yield('scripts')
   
    <script type="text/javascript">
      
           
            function initScroll(){
                $('.custom-scroll').jScrollPane({
                      alert("hii");
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

    <script type="text/javascript">
        
        
        $('.rating').rating();
    
            $(".hamburger").click(function(){       
            $(".site-sidebar").toggleClass("open"); 
            $('.hamburger').toggleClass("is-active");
            $(".site-content").toggleClass("no-margin");
        }); 
        
        var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
    
    </script>
</body>
</html>