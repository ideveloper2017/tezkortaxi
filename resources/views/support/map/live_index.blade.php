@extends('admin.layout.base')

@section('title', 'Map View ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">Map View</h5>
            <div class="row">
                <div class="col-xs-12">
                    <div id="map"></div>
                    <div id="legend"><h3>Note: </h3></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('styles')
<style type="text/css">
    #map {
        height: 100%;
        min-height: 500px;
    }
    
    #legend {
        font-family: Arial, sans-serif;
        background: rgba(255,255,255,0.8);
        padding: 10px;
        margin: 10px;
        border: 2px solid #f3f3f3;
    }

    #legend h3 {
        margin-top: 0;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }

    #legend img {
        vertical-align: middle;
        margin-bottom: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    var current_Lat  = "<?php echo $ip_details->geoplugin_latitude; ?>" ;
    var current_long = "<?php echo $ip_details->geoplugin_longitude; ?>" ;
    var map;
    var users;
    var providers;
    var ajaxMarkers = [];
    var googleMarkers = [];
    var mapIcons = {
        user: '{{ asset("asset/img/marker-user.png") }}',
        active: '{{ asset("asset/img/car.png") }}',
        riding: '{{ asset("asset/img/car-active.png") }}',
        offline: '{{ asset("asset/img/car-offline.png") }}',
        unactivated: '{{ asset("asset/img/car-unactivated.png") }}'
    }

	
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: Number(current_Lat), lng: Number(current_long)},
            zoom: 5,
            minZoom: 1
        });

        setInterval(ajaxMapData, 3000);

        var legend = document.getElementById('legend');

        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['user'] + '"> ' + 'User';
        legend.appendChild(div);

        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['offline'] + '"> ' + 'Unavailable Provider';
        legend.appendChild(div);
        
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['active'] + '"> ' + 'Available Provider';
        legend.appendChild(div);
        
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons['unactivated'] + '"> ' + 'Unactivated Provider';
        legend.appendChild(div);
        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
        
        google.maps.Map.prototype.clearOverlays = function() {
            for (var i = 0; i < googleMarkers.length; i++ ) {
                googleMarkers[i].setMap(null);
            }
            googleMarkers.length = 0;
        }

    }

    function ajaxMapData() {
        map.clearOverlays();
        $.ajax({
            url: '/admin/map/ajax',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: "GET",
            success: function(data) {
                console.log('Ajax Response', data);
                ajaxMarkers = data;
            }
        });
		
        ajaxMarkers ? ajaxMarkers.forEach(addMarkerToMap) : '';
		
		

    }

    function addMarkerToMap(element, index) {
          //console.log('Ajax Response', element.latitude);
        marker = new google.maps.Marker({
            position: {
                lat : parseFloat( element.latitude ),
                lng : parseFloat( element.longitude )
            },
            id: element.id,
            map: map,
            title: element.first_name + " " +element.last_name,
            icon : mapIcons[element.service ? element.service.status : element.status],
        });

        googleMarkers.push(marker);

        google.maps.event.addListener(marker, 'click', function() {
            window.location.href = '/admin/' + element.service ? 'provider' : 'user' + '/' +element.user_id;
        });
		
    }
</script>
<script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endsection