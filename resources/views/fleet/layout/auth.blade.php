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
    <title>{{ Setting::get('site_title', 'PinTaxi') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset(Setting::get('site_icon')) }}"/>

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/bootstrap4/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('asset/admin/vendor/font-awesome/css/font-awesome.min.css')}}">

    <link rel="stylesheet" href="{{asset('asset/admin/assets/css/core.css')}}">


</head>
<body>

    <?php $background = asset('asset/admin/assets/img/photos-1/3.jpg'); ?>

    <body class="img-cover" style="background-image: url('http://gaia-int.com/admin/assets/img/daryan-shamkhali-109777-unsplash.jpg');">
    
    <div class="container-fluid">

    @yield('content')

    </div>

        <!-- Vendor JS -->
        <script type="text/javascript" src="{{asset('asset/admin/vendor/jquery/jquery-1.12.3.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('asset/admin/vendor/tether/js/tether.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('asset/admin/vendor/bootstrap4/js/bootstrap.min.js')}}"></script>
    </body>
</html>
