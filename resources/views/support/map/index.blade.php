<?php 
	$total_zones =	count( $data['zones'] ); 
	$width 		 =	$total_zones * 	200;
?>

@extends('dispatcher.layout.base')

@section('title', 'Live Tracking ')

@section('content')


<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">Live Tracking</h5>
            <div class="row">
                <div class="col-xs-12">
                    <div id="map"></div>
                    <div id="legend"><h3></h3></div>
                </div>
            </div>
			@if( count( $data['zones'] ) )
				<div class="row">
					<div class="col-sm-12">
						<div class="main_inner">
							<ul class="ul" style="width:{{$width}}px">
								@foreach(  $data['zones'] as $zone )
									<li  class="zone">
										<div class="header">{{ $zone['name'] }}</div>
										<div class="driver_list zone_{{ $zone['id'] }}" data-zone_id="{{ $zone['id'] }}"  id="zone_{{ $zone['id'] }}">	
											@if( $zone['drivers'] )
												@foreach( $zone['drivers'] as $driver )
														<div class="driver <?php echo ( $driver->provider_status == 'riding' ) ? 'bg-success' : ''; ?>  driver_{{ $driver->id }}" id="position_{{ $driver->driver_position }}">
															<div class="vehicle_no">{{ strtoupper( $driver->service_number ) }}</div>
															<div class="timeOut">In Time:{{ $driver->enter_time }}</div>
														</div>
												@endforeach
											@endif
										</div>												
									</li>
								@endforeach
							</ul>
						</div>
					</div>
				</div>
			@endif
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
	//All Varirables
	var zones  = <?php echo json_encode( $data['zones'] ); ?>;
	var current_lat		 =	48.9552626;
	var current_long     =	-93.7065152;

	var mapIcons = {
		//user: '{{ asset("asset/img/marker-user.png") }}',
		active: '{{ asset("asset/img/car.png") }}',
		riding: '{{ asset("asset/img/car-active.png") }}',
		//offline: '{{ asset("asset/img/car-offline.png") }}',
		//unactivated: '{{ asset("asset/img/car-unactivated.png") }}'
	};
	
</script>
<script type="text/javascript" src="{{ asset('asset/js/dispatcher-map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=geometry&callback=LiveTrackingInitMap" async defer></script>

@endsection     