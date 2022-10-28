@extends('fleet.layout.base')

@section('title', 'Dashboard ')

@section('styles')
	<link rel="stylesheet" href="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection

@section('content')

<div class="content-area py-1">
<div class="container-fluid">
    <div class="row row-md">
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-danger mb-2">
				
				<div class="t-content">
					<h6 class="text-uppercase mb-1">All Rides</h6>
					<h1 class="mb-1">{{$all_rides->count()}}</h1>
					
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-success mb-2">
				<div class="t-content">
					<h6 class="text-uppercase mb-1">Revenue</h6>
					<h1 class="mb-1">{{currency($revenue)}}</h1>
					
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-primary  mb-2">
				
				<div class="t-content">
					<h6 class="text-uppercase mb-1">All Cab</h6>
					<h1 class="mb-1">{{$service}}</h1>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-warning mb-2">
			
				<div class="t-content">
					<h6 class="text-uppercase mb-1">Cancelled Rides</h6>
					<h1 class="mb-1">{{$user_cancelled+$provider_cancelled}}</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="row row-md mb-2">
		<div class="col-md-12">
				<div class="box bg-white">
					<div class="box-block clearfix">
						<h5 class="float-xs-left">Recent Rides</h5>
						<div class="float-xs-right">
							<button class="btn btn-link btn-sm text-muted" type="button"><i class="ti-close"></i></button>
						</div>
					</div>
					<table class="table mb-md-0">
						<tbody>
						<?php $diff = ['-success','-info','-warning','-danger']; ?>
						@foreach($rides as $index => $ride)
							<tr>
								<th scope="row">{{$index + 1}}</th>
								<td>{{$ride->user->first_name}} {{$ride->user->last_name}}</td>
								<td>
									@if($ride->status != "CANCELLED")
										<a class="text-primary" href="{{route('fleet.requests.show',$ride->id)}}"><span class="underline">View Ride Details</span></a>
									@else
										<span>No Details Found </span>
									@endif									
								</td>
								<td>
									<span class="text-muted">{{$ride->created_at->diffForHumans()}}</span>
								</td>
								<td>
									@if($ride->status == "COMPLETED")
										<span class="tag tag-success">{{$ride->status}}</span>
									@elseif($ride->status == "CANCELLED")
										<span class="tag tag-danger">{{$ride->status}}</span>
									@else
										<span class="tag tag-info">{{$ride->status}}</span>
									@endif
								</td>
							</tr>
							<?php if($index==10) break; ?>
						@endforeach
							
						</tbody>
					</table>
				</div>
			</div>

		</div>

	</div>
</div>
@endsection

@section('scripts')
	<script type="text/javascript" src="{{asset('main/vendor/jvectormap/jquery-jvectormap-2.0.3.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('main/vendor/jvectormap/jquery-jvectormap-world-mill.js')}}"></script>

	<script type="text/javascript">
		$(document).ready(function(){

		        /* Vector Map */
		    $('#world').vectorMap({
		        zoomOnScroll: false,
		        map: 'world_mill',
		        markers: [
		        @foreach($rides as $ride)
		        	@if($ride->status != "CANCELLED")
		            {latLng: [{{$ride->s_latitude}}, {{$ride->s_longitude}}], name: '{{$ride->user->first_name}}'},
		            @endif
		        @endforeach

		        ],
		        normalizeFunction: 'polynomial',
		        backgroundColor: 'transparent',
		        regionsSelectable: true,
		        markersSelectable: true,
		        regionStyle: {
		            initial: {
		                fill: 'rgba(0,0,0,0.15)'
		            },
		            hover: {
		                fill: 'rgba(0,0,0,0.15)',
		            stroke: '#fff'
		            },
		        },
		        markerStyle: {
		            initial: {
		                fill: '#43b968',
		                stroke: '#fff'
		            },
		            hover: {
		                fill: '#3e70c9',
		                stroke: '#fff'
		            }
		        },
		        series: {
		            markers: [{
		                attribute: 'fill',
		                scale: ['#43b968','#a567e2', '#f44236'],
		                values: [200, 300, 600, 1000, 150, 250, 450, 500, 800, 900, 750, 650]
		            },{
		                attribute: 'r',
		                scale: [5, 15],
		                values: [200, 300, 600, 1000, 150, 250, 450, 500, 800, 900, 750, 650]
		            }]
		        }
		    });
		});
	</script>

@endsection