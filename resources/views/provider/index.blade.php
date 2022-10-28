@extends('provider.layout.app')

@section('content')


<div class="pro-dashboard-content">
    <div class="container-fluid">
        <div class="dash-content" id="trip-container">
            <div class="row no-margin" >
            
            </div>
        </div>
    </div>
</div>

<div class="content-area py-1" style="margin-bottom: 20px; margin-top: 20px;">
<div class="container-fluid">
    <div class="row row-md">
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box box-block bg-primary mb-2">
                <div class="t-content">
                    <h4 class="text-uppercase mb-1">@lang('provider.total_revenue')</h4>
                    <h1 class="mb-1">
                    <?php
    $fully_sum = 0; ?>
      @foreach($fully as $each)
        
        @if($each->payment != "")
            <?php $each_sum = 0;
            $each_sum = $each->payment->tax + $each->payment->fixed + $each->payment->distance + $each->payment->commision;
            $fully_sum += $each_sum;
            ?>
        @endif
          
      @endforeach
       ${{round($fully_sum,2)}}  
      </h1>
                </div>
            </div>
        </div> 
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box box-block bg-success mb-2">
                <div class="t-content">
                    <h4 class="text-uppercase mb-1">@lang('provider.today_ride')</h4>
                    <h1 class="mb-1">{{$today_rides->count()}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box box-block bg-primary mb-2">
                <div class="t-content">
                    <h4 class="text-uppercase mb-1">@lang('provider.completed_ride')</h4>
                    <h1 class="mb-1">{{$completed_rides}}</h1>
                </div>
            </div>
        </div>
    </div>

     <div class="row row-md">
            <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box box-block bg-warning mb-2">
                <div class="t-content">
                    <h4 class="text-uppercase mb-1">@lang('provider.total_ride')</h4>
                    <h1 class="mb-1">{{$rides->count()}}</h1>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box box-block bg-warning mb-2">
                <div class="t-content">
                    <h4 class="text-uppercase mb-1">@lang('provider.ride_accepted')</h4>
                    <h1 class="mb-1">{{$accepted_rides}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box box-block bg-success mb-2">
                <div class="t-content">
                    <h4 class="text-uppercase mb-1">@lang('provider.cancel_ride')</h4>
                    <h1 class="mb-1">{{$cancel_rides}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-xs-12">
            <div class="box box-block bg-primary mb-2">
                <div class="t-content">
                    <h4 class="text-uppercase mb-1">@lang('provider.scheduled_ride')</h4>
                    <h1 class="mb-1">{{$scheduled_rides}}</h1>
                </div>
            </div>
        </div>
      

    </div>
</div>
</div> 



@endsection

@section('scripts')
<script type="text/javascript" src="js/app.js"></script>

   

    <script>
       
</script>
<script type="text/javascript" src="{{asset('asset/js/rating.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places" defer></script>

<script>

	window.image_path = '<?php echo asset('/storage/app/public/'); ?>';
	
	console.log( window.image_path );

    function initMap() {
        var polylineOptionsActual = new google.maps.Polyline({
                strokeColor: '#111',
                strokeOpacity: 0.8,
                strokeWeight: 4
        });

      directionsService = new google.maps.DirectionsService;
      directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: false, polylineOptions: polylineOptionsActual});
      var center = {lat: 45.2487862, lng: -76.3606792};
    

      var map = new google.maps.Map(document.getElementById('map'), {
            mapTypeControl: false,
            zoomControl: true,
            center: center,
            zoom: 12,
            styles : [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#e4e8e9"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#7de843"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#9bd0e8"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
        });

      directionsDisplay.setMap(map);

    }

    
     function updateMap(route) {

            directionsService.route({
            origin: route.source.lat+', '+route.source.lng,
            destination: route.destination.lat+', '+route.destination.lng,
            travelMode: 'DRIVING'
        }, function(response, status) {
            if (status === 'OK') {
            directionsDisplay.setDirections(response);
            } else {
            window.alert('Directions request failed due to ' + status);
            }
        });

     }


  </script>


@endsection