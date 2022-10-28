@extends('website.app')

@section('content')

<div class="signin_page">
    <div class="container">
        <div>
          <h4>{{ trans('passengersignin.sign_up') }}</h4>
        </div> 
    <div class="row">
        <div class="col-md-4">
            <form role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}
                      <label style="margin-top: inherit;">{{ trans('passengersignin.name') }}</label>
                      <input id="name" type="text" class="form-control" name="first_name" value="" placeholder="{{ trans('passengersignin.name') }}" autofocus="">
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif  
                      
                   
                      
                      <label>{{ trans('passengersignin.phone') }}</label>
                      
                      <input type="text" autofocus="" id="phone_number" class="form-control" placeholder="{{ trans('passengersignin.phone') }}" name="phone_number" value="">
                        @if ($errors->has('phone_number'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone_number') }}</strong>
                            </span>
                        @endif
                      
                      <label>{{ trans('passengersignin.email_addr') }}</label>
                      <input id="email" type="email" class="form-control" name="email" value="" placeholder="{{ trans('passengersignin.email_addr') }}">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                      
                      <label>{{ trans('passengersignin.password') }}</label>
                      <input id="password" type="password" class="form-control" name="password" placeholder="{{ trans('passengersignin.password') }}">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                      
                      <label>{{ trans('passengersignin.confirm_pass') }}</label>
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{ trans('passengersignin.confirm_pass') }}">
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                      
                    <div class="facebook_btn">
                        <button type="submit" value="submit" class="btn btn-default btn-arrow-left" style="background-color: #ec58d6;">{{ trans('passengersignin.next') }} </button>
                        <figure><img src="{{url('asset/front_dashboard/img/btn_arrow.png')}}" alt="img"></figure>
                    </div> 
          <div class="facebook_btn">
                     <h5>{{ trans('passengersignin.already_reg') }}<a class="log-blk-btn" href="{{ url('/PassengerSignin') }}">  {{ trans('passengersignin.click_here') }}</a></h5>
                    </div>           
            </form>
    </div>
         <div class="col-md-8">
            <img src="{{ url('asset/front_dashboard/img/User-Signup.png') }}" alt="User Login" style="margin-top:-47px"> 
         </div>
    </div>
    </div>
</div>
@endsection


@section('noscripts')


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.js"></script>
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


<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
<script>
  // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
    AccountKit.init(
      {
        appId: {{env('FB_APP_ID')}}, 
        state:"state", 
        version: "{{env('FB_APP_VERSION')}}",
        fbAppEventsEnabled:true
      }
    );
  };

  // login callback
  function loginCallback(response) {
    if (response.status === "PARTIALLY_AUTHENTICATED") {
      var code = response.code;
      var csrf = response.state;
      // Send code to server to exchange for access token
      $('#mobile_verfication').html("<p class='helper'> * Phone Number Verified </p>");
      $('#phone_number').attr('readonly',true);
      $('#country_code').attr('readonly',true);
      $('#second_step').fadeIn(400);

      $.post("{{route('account.kit')}}",{ code : code }, function(data){
        $('#phone_number').val(data.phone.national_number);
        $('#country_code').val('+'+data.phone.country_prefix);
      });

    }
    else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
      $('#mobile_verfication').html("<p class='helper'> * Authentication Failed </p>");
    }
    else if (response.status === "BAD_PARAMS") {
      // handle bad parameters
    }
  }

  // phone form submission handler
  function smsLogin() {
    var countryCode = document.getElementById("country_code").value;
    var phoneNumber = document.getElementById("phone_number").value;

    $('#mobile_verfication').html("<p class='helper'> Please Wait... </p>");
    $('#phone_number').attr('readonly',true);
    $('#country_code').attr('readonly',true);

    AccountKit.login(
      'PHONE', 
      {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
      loginCallback
    );
  }

</script>

@endsection














