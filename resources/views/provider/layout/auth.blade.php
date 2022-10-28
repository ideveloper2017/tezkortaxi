<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>{{ Setting::get('site_title') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset(Setting::get('site_icon')) }}"/>

    <!-- Bootstrap -->

    <link href="{{asset('asset/front_dashboard/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front_dashboard/css/custom.css')}}" rel="stylesheet">  
    <link href="{{asset('asset/front_dashboard/css/hamburgers.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front_dashboard/css/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{asset('asset/front_dashboard/css/owl.theme.css')}}" rel="stylesheet">
</head>
<body>

    @include('user.layout.templateparts.website_header')
   <div class="driversignup sid_jangra">
   
        <div class="signin_page">
              @yield('content')
        </div>
    @include('user.layout.templateparts.website_footer')
    </div>
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('asset/front_dashboard/js/jquery.min.js')}}"></script>
    <script src="{{asset('asset/front_dashboard/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('asset/front_dashboard/js/owl.carousel.js')}}"></script>
    <script>
        $("#left_menu_open").click(function(){
            $("#left_menu").addClass("open");
        });
        $(".close").click(function(){
            $("#left_menu").removeClass("open");
        });
        
        $(".hamburger").click(function(){
            $(".side_menu").toggleClass("open");
        }); 
    </script> 
    <script>
    var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};

    var hamburgers = document.querySelectorAll(".hamburger");
    if (hamburgers.length > 0) {
      forEach(hamburgers, function(hamburger) {
        hamburger.addEventListener("click", function() {
          this.classList.toggle("is-active");
        }, false);
      });
    }
  </script> 
   
  <script>
    $(document).ready(function() {
      $("#owl-demo2").owlCarousel({
        autoPlay: 3000,
        items :3,
        autoPlay:true,
        navigation:true,
        navigationText:true,
        pagination:true,
        itemsDesktop : [1350,3],
        itemsDesktop : [1199,1],
        itemsDesktopSmall : [991,1],
        itemsTablet: [767,1], 
        itemsMobile : [560,1] 
      });

    });    
</script>   
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
        
        <script type="text/javascript">
            // When the window has finished loading create our google map below
            google.maps.event.addDomListener(window, 'load', init);
        
            function init() {
                // Basic options for a simple Google Map
                // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                var mapOptions = {
                    // How zoomed in you want the map to start at (always required)
                    zoom: 11,

                    // The latitude and longitude to center the map (always required)
                    center: new google.maps.LatLng(40.6700, -73.9400), // New York

                    // How you would like to style the map. 
                    // This is where you would paste any style found on Snazzy Maps.
                    styles: [{"featureType":"all","elementType":"all","stylers":[{"hue":"#e7ecf0"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#8ed863"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-70}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":-60}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#8abdec"}]},{"featureType":"water","elementType":"geometry.stroke","stylers":[{"color":"#9cbbf0"}]},{"featureType":"water","elementType":"labels.text","stylers":[{"visibility":"off"}]}]
                };

                // Get the HTML DOM element that will contain your map 
                // We are using a div with id="map" seen below in the <body>
                var mapElement = document.getElementById('map');

                // Create the Google Map using our element and options defined above
                var map = new google.maps.Map(mapElement, mapOptions);

                // Let's also add a marker while we're at it
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(40.6700, -73.9400),
                    map: map,
                    title: 'Snazzy!'
                });
            }
        </script>
    
</body>
</html>
