@extends('website.app')

@section('content')

	<?php  $details = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($id).'&sensor=false&key='.env('GOOGLE_MAP_KEY'));
           $geo = json_decode($details, true); // Convert the JSON to an array

		if (isset($geo['status']) && ($geo['status'] == 'OK')) {
		  $latitude = $geo['results'][0]['geometry']['location']['lat']; // Latitude
		  $longitude = $geo['results'][0]['geometry']['location']['lng']; // Longitude
		}
		?>
	    <input type="hidden" name="lat" id="lat" value="{{$latitude}}">
	    <input type="hidden" name="long" id="long" value="{{$longitude}}">		
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div>
                      <div id="map" style="width: 100%"></div>
					</div>
					</div>
				</div>
			</div>
	
@endsection
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=myMap" async defer></script>
    <script>
        function myMap() {
	        var Latitudev = document.getElementById('lat').value;
        	var Longitudev = document.getElementById('long').value;
			var mapProp= {
			  center:new google.maps.LatLng(Latitudev,Longitudev),
			  zoom:5,
			};
			var map = new google.maps.Map(document.getElementById("map"),mapProp);
			var simage = '{{asset("asset/front/img/source.png")}}';
			var dimage = '{{asset("asset/front/img/destination.png")}}';
			
			var marker = new google.maps.Marker({
			    position: new google.maps.LatLng(Latitudev, Longitudev),
			    map: map,
			    icon: dimage
			});
        }
</script>

@endsection