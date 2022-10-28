@extends('dispatcher.layout.base')

@section('title', 'Recent Trips ')

@section('content')
<div class="content-area py-1" id="dispatcher-panel">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h4>Recent Trips</h4>
			</div>
		</div> 
		<div class="row">
			<div class="col-md-12">
				 <nav class="navbar navbar-light bg-white b-a mb-2">
					<button class="navbar-toggler hidden-md-up" data-toggle="collapse" data-target="#process-filters" aria-controls="process-filters" aria-expanded="false" aria-label="Toggle Navigation"></button>

					<div class="collapse navbar-toggleable-sm" id="process-filters">
						<ul class="nav navbar-nav dispatcher-nav">
							<li class="nav-item dispatcher-tab active" id="dispatch-map" onClick="getUpdateFilterData('');" >
								<span class="nav-link dispatcher-link">ALL</span>
							</li>
							<li class="nav-item dispatcher-tab" id="dispatch-new" onClick="getUpdateFilterData('dispatch-new');">
								<span class="nav-link dispatcher-link">PENDING</span>
							</li>
							<li class="nav-item dispatcher-tab" id="dispatch-dispatching" onClick="getUpdateFilterData('dispatch-dispatching');">
								<span class="nav-link dispatcher-link">DISPATCHING</span>
							</li>
							<li class="nav-item dispatcher-tab" id="dispatch-completed" onClick="getUpdateFilterData('dispatch-completed');">
								<span class="nav-link dispatcher-link">COMPLETED</span>
							</li>
							<li class="nav-item dispatcher-tab" id="dispatch-cancelled" onClick="getUpdateFilterData('dispatch-cancelled');">
								<span class="nav-link dispatcher-link" >CANCELLED</span>
							</li>
							<li class="nav-item dispatcher-tab" id="dispatch-scheduled" onClick="getUpdateFilterData('dispatch-scheduled');">
								<span class="nav-link dispatcher-link">SCHEDULED</span>
							</li>
							<li class="nav-item dispatcher-tab" id="dispatcher-dead" onClick="getUpdateFilterData('dispatcher-dead');">
								<span class="nav-link dispatcher-link">DEAD</span>
							</li>
							<li class="nav-item dispatcher-tab" id="dispatcher-log" onClick="getLogsData();">
								<span class="nav-link dispatcher-link">LOGS</span>
							</li>
							
						</ul>
					</div>
				</nav>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="card card-block" id="create-ride">
					<h3 class="card-title text-uppercase">List</h3>
					<div class="items-list">
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="card my-card">
					<div class="card-header text-uppercase"><strong>MAP</strong></div>
					<div class="card-body">
						<div id="map" style="height: 480px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
    @include('dispatcher.layout.partials.cancel_ride_form')
     
    @if( $companies->count() )
    	@include('dispatcher.layout.partials.assign_form')
    @endif
    
    @include('dispatcher.layout.partials.booking')
    
    @endsection
@section('scripts')

<script type="text/javascript">
    window.mylift = {!! json_encode([
    	"minDate" => \Carbon\Carbon::today()->format('Y-m-d\TH:i'),
    	"maxDate" => \Carbon\Carbon::today()->addDays(30)->format('Y-m-d\TH:i'),
    	"map" => false,
    ]) !!}
    
    var base_url	 =	'{{ url("/") }}';
    
    var zones  = <?php echo json_encode( $all_zones ); ?>;
    var services = {!!  json_encode($services) !!};
    var mapIcons = {
    	active: '{{ asset("asset/front/img/car.png") }}',
    	riding: '{{ asset("asset/front/img/car-active.png") }}',
  
    };
    
    var current_lat		 = 28.57427;
    var current_long     = 77.3558;

    <?php if( $ip_details ) { ?>
    
    	var current_lat  =	"<?php echo $ip_details->geoplugin_latitude; ?>";
    	var current_long =	"<?php echo $ip_details->geoplugin_longitude; ?>";
    	
    <?php } ?>
    $(document).ready(function () {
            getUpdateFilterData('');
    });
</script>
 
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places,geometry&callback=initMap" async defer></script>
<script type="text/javascript" src="{{ asset('asset/front/js/dispatcher-map.js') }}"></script>
<script type="text/babel" src="{{ asset('asset/front/js/dispatcher.js') }}"></script>
@endsection

@section('styles')
<style type="text/css">
	.pac-container {
        z-index: 10000 !important;
    }
	
	.items-list .media {
		position: relative;
		height: 250px;
	}
	
	div#logs {
		background-color: #000;
		color: #fff;
		padding: 1em;
		font-weight: bold;
		font-size: 14px;
	}

	ul.log_ul li {
		padding: 10px 0;
		border-bottom: 1px solid gray;
	}
	
	.has-error .form-control {
		border-color: red;
	}
	
	.help-block {
		font-weight: bold;
		color: red;
	}
	
	#create-ride  {
		padding: 0;
	}

	.items-list .il-item {
		padding:  0.8rem;
	}


	.create_ride_li_btn {
		position: absolute;
		bottom: 0;
		left: 0;
		z-index: 1;
	
	}
	
	
	.create_ride_li_btn .btn {
		margin-bottom: 10px;
	}

	
	#assign_manual-modal .form-group  {
		margin-bottom: 0.5rem;
	}
	
	#assign_manual-modal .form-group {
		font-weight: bold;
	}

	#assign_manual-modal  select, #assign_manual-modal  .control-label {
		text-transform: uppercase;
	}
	
	.user_detail ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	
	.user_detail ul li {
		display: block;
		padding: 5px 20px;
		font-weight: bold;
		font-size: 14pX;
	}
	
	.user_detail ul li span:first-child {
		text-transform: uppercase;
	}


	#create-ride .card-title.text-uppercase {
		padding: 1.25rem;
		border-bottom: 1px solid whitesmoke;
	}

	.no_data {
		padding: 20px;
		text-align: center;
		font-weight: bold;
	}
	
	
    .my-card input{
        margin-bottom: 10px;
    }
    .my-card label.checkbox-inline{
        margin-top: 10px;
        margin-right: 5px;
        margin-bottom: 0;
    }
    .my-card label.checkbox-inline input{
        position: relative;
        top: 3px;
        margin-right: 3px;
    }
    .my-card .card-header .btn{
        font-size: 10px;
        padding: 3px 7px;   
    }
    .tag.my-tag{
        padding: 10px 15px;
        font-size: 11px;
    }

    .add-nav-btn{
        padding: 5px 15px;
        min-width: 0;
    }

    .dispatcher-nav li span {
        background-color: transparent;
        color: #000!important;
        padding: 5px 12px;
    }

    .dispatcher-nav li span:hover,
    .dispatcher-nav li span:focus,
    .dispatcher-nav li span:active {
        background-color: #20b9ae;
        color: #fff!important;
        padding: 5px 12px;
    }

    .dispatcher-nav li.active span,
    .dispatcher-nav li span:hover,
    .dispatcher-nav li span:focus,
    .dispatcher-nav li span:active {
        background-color: #20b9ae;
        color: #fff!important;
        padding: 5px 12px;
    }

    @media (max-width:767px){
        .navbar-nav {
            display: inline-block;
            float: none!important;
            margin-top: 10px;
            width: 100%;
        }
        .navbar-nav .nav-item {
            display: block;
            width: 100%;
            float: none;
        }
        .navbar-nav .nav-item .btn {
            display: block;
            width: 100%;
        }
        .navbar .navbar-toggleable-sm {
            padding-top: 0;
        }
    }

    .items-list {
        height: 450px;
        overflow-y: scroll;
    }
	
</style>
@endsection