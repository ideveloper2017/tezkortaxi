<?php 
	$total_zones =	count( $all_zones ); 
	$width 		 =	$total_zones * 	200;
?>

@extends('dispatcher.layout.base')

@section('title', 'New Trip')

@section('content')
<div class="content-area py-1" id="dispatcher-panel">
	<div class="container-fluid">

		<div class="row">
			<div class="col-md-4">
				<div class="box card card-block" id="create-ride">
					<form id="form-create-ride"  method="POST">
						<div class="row">
							<div style="display: none" class="<?php echo ( count( $corporates ) > 0 ) ? 'col-xs-6' : 'col-xs-12'; ?>">
								<div class="form-group">
									<label class="btn btn-secondary btn_corporate active btn-block"><input type="radio" id="booking_typeq" name="booking_type" value="1" checked class="booking_type" style="float:left;"><h7>Indivisual</h7></label>
								</div>
							</div>
							@if( count( $corporates ) > 0 )
							<div class="col-xs-6" style="display: none">
								<div class="form-group">
									<label class="btn btn-secondary btn_corporate btn-block"><input type="radio" id="booking_type" name="booking_type" value="2" class="booking_type" style="float:left;"><h7>Corporate</h7></label>
								</div>
							</div>
							@endif
							<div class="col-xs-12">
								<div class="form-group">
									<label for="first_name">Name</label>
									<input type="text" class="form-control" name="first_name" id="first_name" placeholder="Name" required />
								</div>
									<input type="hidden" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="" />
						
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="email">Email</label>
									<input type="email" class="form-control" name="email" id="email" placeholder="Email" />
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="mobile">Phone</label>
									<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Phone" required />
								</div>
							</div>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="s_address">Pickup Location</label>
									<input type="text" name="s_address" class="form-control" id="s_address" placeholder="Pickup Location" required></input>
									<input type="hidden" name="s_latitude" id="s_latitude"></input>
									<input type="hidden" name="s_longitude" id="s_longitude"></input>
								</div>
								<div class="form-group">
									<label for="d_address">Drop Location</label>
									<input type="hidden" name="dispatcher_id" id="dispatcher_id" value="{{ $user_id }}">
									<input type="text"  name="d_address" class="form-control" id="d_address" placeholder="Drop Location" required></input>
									<input type="hidden" name="d_latitude" id="d_latitude"></input>
									<input type="hidden" name="d_longitude" id="d_longitude"></input>
									<input type="hidden" name="request_id" id="request_id"/>
								</div>
								<div class="form-group">
									<label for="schedule_time">Schedule Time</label>
									<input type="text" class="form-control form_datetime" name="schedule_time" id="schedule_time" placeholder="Date" autocomplete="off"/>
								</div>
								@if( $payment_methods->count() )
									<div class="form-group">
										<label for="service_types">Payment Method</label>
										<select name="payment_method" id="payment_method" class="form-control">	
											@foreach( $payment_methods as $method )
												<option value="{{ $method->id }}">{{ $method->name }}</option>
											@endforeach
										</select>
									</div> 
								@endif
								
								@if( count( $services) > 0 )
									<div class="form-group">
										<label for="service_types">Cab Type</label>
										<select name="service_type" id="service_type" class="form-control">
											@foreach( $services as $service )
												<option value="{{ $service->id }}">{{ $service->name }}</option>
											@endforeach
										</select>
									</div> 
								@else
									<div class="form-group">
										<div  class="bg-danger">Service type not added by admin. Please contact to admin first to add a service type then create a request</div>
									</div>								
								@endif
							</div>
				
							<div class="col-xs-12">
								<div class="form-group">
									<label for="distance">Distance(KM)</label>
									<input type="text" class="form-control" readonly name="distance" id="distance"></input>
								</div>
							</div>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="distance">Add Note</label>
									<textarea class="form-control" rows="4" id="special_note" name="special_note"></textarea>
								</div>
							</div>
							@if( count( $corporates ) > 0 )
								<div id="corporate_list" class="hide">
									<div class="col-xs-12">
										<div class="form-group">
											<label htmlfor="provider_id">Corporate Name</label>
											<select name="corporate_id" class="form-control">
												@foreach( $corporates as $corporate )
													<option value="{{ $corporate->id }}">{{ $corporate->corporate_name }}</option>
												@endforeach
											</select>
										</div> 
									</div>
									<div class="col-xs-12">
										<div class="form-group">
											<label htmlfor="provider_id">Fare (Driver)</label>
											<input type="text" readonly class="form-control" name="estimated_price" id="estimated_price"></input>
										</div> 
									</div>
									<div class="col-xs-12">
										<div class="form-group">
											<label htmlfor="provider_id">Fare (Customer)</label>
											<input type="text"  class="form-control" name="amount_customer" id="amount_customer"></input>
										</div> 
									</div>
								</div>
							@endif
							<div class="col-xs-12">
								<div class="form-group">
									<label for="provider_auto_assign">Auto Assign Driver</label>
									<br />
									<input  type="checkbox" id="provider_auto_assign" name="provider_auto_assign" class="js-check-change" data-color="#f59345" checked="checked" />
								</div>
								<div class="form-group hide" id="provider_list">
									<label htmlfor="provider_id">Select Driver</label>
									<input name="provider_id" type="hidden"/>
									
									<div class="provider_fl_wrapper">
										<div class="form-control" id="provider_wrapper">
											<div class="dr_search_txt">Select Driver</div>
											<div class="dr_icon"><i class="fa fa-sort-down" aria-hidden="true"></i></div>
										</div>
										<div id="dr_list_wrapper">
											<div id="input_wrapper">
												<input type="text" placeholder="Search..." id="dr_seach_input" class="form-control" onkeyup="filterFunction()" />
											</div>
											<div id="dr_list"></div>
										</div>
									</div>
								 </div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<button type="button" class="btn btn-lg btn-danger btn-block waves-effect  waves-light form_create_ride_reset_btn">RESET</button>
							</div>
							<div class="col-xs-6">
								<button class="btn btn-lg btn-success btn-block waves-effect waves-light">Next</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-8">
				<div class="card my-card">
					<div class="box card-header">								
					<div class="col-md-3">						        
					<div style="margin-top:5px;font-size: 16px;">
						<input type="checkbox" name="vehicle1" value="ongoing" id="ongoing" class="vehicle1" onclick="rideOn()"> On Going Ride</div>
					</div>								
					<div class="col-md-3">						        
					<div style="margin-top:5px;font-size: 16px;">
						<input type="checkbox" name="vehicle1" value="driver" id="driver" class="
						vehicle1" onclick="rideOn();"> Active Driver</div>
					</div>								
								
					</div>
					<div class="card-body" style="height: 500px;">
						<div id="map"></div>
					</div>
				</div>
				<div class="main_inner">
					<ul class="ul" style="width:{{$width}}px">
						@foreach(  $all_zones as $zone )
							<li  class="zone">
								<div class="header">{{ $zone['zone_name'] }}</div>
                                <div class="driver_list zone_{{ $zone['id'] }}" data-zone_id="{{ $zone['id'] }}"  id="zone_{{ $zone['id'] }}">	
									@if( $zone['drivers'] )
										@foreach( $zone['drivers'] as $driver )
												<div class="driver <?php echo ( $driver->provider_status == 'riding' ) ? 'bg-warning' : ''; ?>  driver_{{ $driver->id }}" id="position_{{ $driver->driver_position }}">
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
	
				<div id="fareMap"></div>
				
			</div>
		</div>
	</div>
</div>
@include('dispatcher.layout.partials.wait_model')
 
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>



@php 

 //$latAndLong =getLatAndLongByLocation('Country');
 $provider_select_timeout =providerTimeout(); 
@endphp

<script type="text/javascript">
var counter =60;

var current_lat		 =	30.033333;
var current_long     =	31.233334;

var schedule_req_time =  {{ Setting::get('schedule_req_time', 0) }};

var zones  = <?php echo json_encode( $all_zones ); ?>;
var mapIcons = {
	active: '{{ asset("asset/front/img/car.png") }}',
	riding: '{{ asset("asset/front/img/car-active.png") }}',

};

<?php if( $ip_details ) { ?>

	var current_lat  =	"<?php echo $ip_details->geoplugin_latitude; ?>";
	var current_long =	"<?php echo $ip_details->geoplugin_longitude; ?>";
	
<?php }?>

var provider_select_timeout = "<?php echo $provider_select_timeout; ?>";
var dispatcher_user_id = "<?php echo $user_id; ?>" ;
var site_url           ="<?php echo  url('/'); ?>"; 
var countdown_number   = provider_select_timeout;
var countdown;

// Init Checkbox
var mySwitch = new Switchery(document.querySelector('.js-check-change'));

window.PinTaxi = {!! json_encode([
	"minDate" => \Carbon\Carbon::today()->format('Y-m-d\TH:i'),
	"maxDate" => \Carbon\Carbon::today()->addDays(30)->format('Y-m-d\TH:i'),
	"map" => false,
]) !!}


//Document ready function define here
$(function () {       
	$('#schedule_time').datetimepicker();
	
	$('#provider_wrapper').on('click', function() {
		$('#dr_list_wrapper').slideToggle('slow');
	});
	
	$(document).on('click','#dr_list .dr_item',function() {
		$('#provider_list input[name=provider_id]').val($(this).attr('pr_id'));
		$('#provider_list .dr_search_txt').text($(this).text());
		$('#dr_list_wrapper').slideUp('slow');
	});
	
});
 


function filterFunction() {
  var input, filter, ul, li, a, i;
  input = document.getElementById("dr_seach_input");
  filter = input.value.toUpperCase();
  div = document.getElementById("dr_list");
  a = div.getElementsByTagName("a");
  for (i = 0; i < a.length; i++) {
    if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places,geometry&callback=initMap" async defer></script>
<script type="text/javascript" src="{{ asset('asset/front/js/dispatcher-map.js') }}"></script>
@endsection
@section('styles')
<style type="text/css">

/* by sid */

.provider_fl_wrapper {
    position: relative;
}

div#provider_wrapper {
    position: relative;
	cursor: pointer;
}

.dr_icon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
}

#dr_list_wrapper {
	display: none;
    position: absolute;
    left: 0;
    right: 0;
    top: 100%;
    width: 100%;
    z-index: 99;
    padding: 20px;
	border: 1px solid #c1c1c1;
    border-radius: 0 0 5px 5px;
    background-color: #f1f1f1;
}

#dr_list_wrapper input {
    border-radius: 6px;
}

#dr_list .dr_item {
    display: block;
    padding: 5px 0;
    cursor: pointer;
    font-weight: bold;
}

div#dr_list {
    height: 180px;
    margin-top: 20px;
    overflow-y: scroll;
}


/* by sid end here */

html, body, #map{
  height: 100%;
  margin: 0px;
  padding: 0px
}

#form-create-ride .has-error  .help-block{
	color: red;
	font-weight: bold;
}

#infowindow{
  padding: 10px;
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
	
	#corporate_list {
		display: none;
	}
	
	#countdown {
		margin: 0 auto;
		width: 140px;
		height: 150px;
		font-size: 90px;
		line-height: 140px;
		text-align: center;
		border-radius: 50%;
	}

	.ride_fn_btn .btn-block {
		margin-bottom: 20px;
	}

	.ongoing_trips {
		margin-top: 30px;
		background-color: #fff;
		padding: 5px;
	}
	
	.main_inner {
	    background: #fff;
	}
	
	.zone .header {
		background-color: #f1f1f1;
	}

</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
@endsection