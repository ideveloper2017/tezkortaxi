<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>{{ Setting::get('site_title') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ Setting::get('site_icon') }}"/>

    <!-- Bootstrap -->

    <link href="{{asset('asset/front_dashboard/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front_dashboard/css/custom.css')}}" rel="stylesheet">  
    <link href="{{asset('asset/front_dashboard/css/hamburgers.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front_dashboard/css/owl.carousel.css')}}" rel="stylesheet">
<link href="{{asset('front_dashboard/css/owl.theme.css')}}" rel="stylesheet">

<!-- HTTPS required. HTTP will give a 403 forbidden response -->
<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>

  </head>
  <body>
    <div class="driversignup">
         <div class="signin_page">
            @yield('content')
    </div>
		<script src="{{asset('asset/front_dashboard/js/jquery.min.js')}}"></script>
		<script src="{{asset('asset/front_dashboard/js/bootstrap.min.js')}}"></script>
		<script src="{{asset('asset/front_dashboard/js/owl.carousel.js')}}"></script>

        @yield('scripts')
    
</body>
</html>