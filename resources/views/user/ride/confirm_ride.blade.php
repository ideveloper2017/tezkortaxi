@extends('user.layout.base')
@section('title', 'Ride Confirmation ')
@section('styles')
<style type="text/css">
    .surge-block {
        background-color: black;
        width: 50px;
        height: 50px;
        border-radius: 25px;
        margin: 0 auto;
        padding: 10px;
        padding-top: 15px;
    }
    .surge-text{
        top: 11px;
        font-weight: bold;
        color: white;
    }		.pomoc_wrap {		position: relative;		font-size: 0;	}			.form-group.promoc_wrap * {		display: inline-block;		vertical-align: top;	}
    select.form-control:not([size]):not([multiple]){
    	height: 33px !important;
    }
</style>
@endsection

@section('content')
<div class="col-md-12" style="margin-bottom: 20px;">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title"><span class="s-icon"></span>&nbsp;{{ trans('user.my_rides') }}</h4><hr>
            </div>
        </div>

        @if(Session::has('message'))
		<div class="alert alert-success alert-block">
			<button type="button" class="close" data-dismiss="alert">×</button>	
		        <span>{{ Session::get('message') }}</span>
		</div>
		@endif

        @include('common.notify')
        <div class="row no-margin">
            <div class="col-md-6">
                <form action="{{url('create/ride')}}" method="POST" id="create_ride">
                {{ csrf_field() }}

                    <dl class="dl-horizontal left-right">
                        <dt>@lang('user.vehicle')</dt>
                        <dd>{{$service->name}}</dd>
                        <dt>@lang('user.total_distance')</dt>
                        <dd>{{  $fare->distance }} {{ ( $fare->distance > 1 ) ? 'KMS' :  'KM' }}</dd>
                        <dt>@lang('user.eta')</dt>
                        <dd>{{$fare->time}}</dd>
						<dt class="discnt-txt">@lang('user.discount')</dt>
						<dd class="discnt-val"> {{ $fare->discount == 0 ? '0.00' :  currency($fare->discount) }}</dd>
                        <dt class="est_fare">@lang('user.estimated_fare')</dt>
                        <dd class="est_val">{{currency($fare->estimated_fare)}}</dd>
                        <hr>
                        @if(Auth::user()->wallet_balance > 0)
			
                        @endif
						
                    </dl>
                    <input type="hidden" name="s_address"  value="{{Request::get('s_address')}}">
                    <input type="hidden" name="d_address" value="{{Request::get('d_address')}}">
                    <input type="hidden" name="s_latitude" id="origin_latitude"  value="{{Request::get('s_latitude')}}">
                    <input type="hidden" name="s_longitude"  id="origin_longitude" value="{{Request::get('s_longitude')}}">
                    <input type="hidden" name="d_latitude"  id="destination_latitude" value="{{Request::get('d_latitude')}}">
                    <input type="hidden" name="d_longitude" id="destination_longitude"  value="{{Request::get('d_longitude')}}">
                    <input type="hidden" name="service_type" value="{{Request::get('service_type')}}">
                    <input type="hidden" name="distance" value="{{$fare->distance}}">										
                    <p>@lang('user.payment_method')</p>
                    <select class="form-control" name="payment_mode" id="payment_mode" onchange="card(this.value);">
                      <option value="CASH">@lang('user.cash')</option>

                      @if(Setting::get('CARD') == 1 )

						  @if($cards->count() > 0 && in_array('card',explode(',',Auth::user()->multiple_payment_method)))
							<option value="CARD">Card</option>
							@endif
						  @if(Auth::user()->paypal_id != '' && in_array('paypal',explode(',',Auth::user()->multiple_payment_method)))
						  <option value="PAYPAL">@lang('user.paypal')</option>
						  @endif
                          @if(Auth::user()->id != '' && in_array('razorpay',explode(',',Auth::user()->multiple_payment_method)))
						  <option value="RAZORPAY">@lang('user.razorpay')</option>
						  
						  
						  @endif
                      @endif
                    </select>
                    <br>
                  
                    @if(Setting::get('CARD') == 1)
                       
                        @if($cards->count() > 0 && in_array('card',explode(',',Auth::user()->multiple_payment_method)))
                        <select class="form-control" name="card_id" id="card_id" style="display: none;" >
                          @foreach($cards as $card)
                            <option value="{{$card->card_id}}" selected>{{$card->brand}} **** **** **** {{$card->last_four}}</option>
                          @endforeach
                        </select>
                        @endif
                    @endif
                    
                   
                    @if(@$fare->surge == 1)
                        <span><em>@lang('user.note')</em></span>
                        <div class="surge-block"><span class="surge-text">{{$fare->surge_value}}</span>
                        </div>
                    @endif
                    <p>@lang('user.add_coupon')</p>									
                    <div class="form-group promoc_wrap">    						
                        <input type="text" style="width: 68%;" class="form-control" name="promo_code" id="promo_code_input">						
                        <button  data-loading-text="Applying..."  style="width:30%; height: 34px!important" class="btn btn-success shadow-box" id="promo_code_btn" type="button">@lang('user.apply')</button>					
                    </div>
					<input type="hidden" value="{{ $fare->estimated_fare }}" name="estimated_fare">
		            <button type="submit" class="half-primary-btn btn-success shadow-box fare-btn">@lang('user.ride.order_now')</button>
		            <button type="button" class="half-secondary-btn fare-btn shadow-box" data-toggle="modal" data-target="#schedule_modal">@lang('user.ride.schedule_order')</button>
		            <a class="full-primary-btn btn-danger shadow-box" style="width: 647px;"  href="{{url('/trips')}}">@lang('user.ride.cancel_request')</a>
		            
		          </form>
            </div>
            <div class="col-md-6">
                <div class="user-request-map">
                    <div class="map-responsive">
						<div id="map" style="width: 100%; height: 450px;"></div>
						<div class="from-to row no-margin">
							<div class="from">
								<h5>@lang('user.from')</h5>
								<p>{{$request->s_address}}</p>
							</div>
							<div class="to">
								<h5>@lang('user.to')</h5>
								<p>{{$request->d_address}}</p>
							</div>
						</div>
					</div>
                </div> 
            </div>
        </div>
    </div>
</div>
<!-- Schedule Modal -->
<div id="schedule_modal" class="modal fade schedule-modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('user.ride.schedule_order')</h4>
      </div>
		<form>
			<div class="modal-body">
				<label>@lang('user.date')</label>
				<input value="{{date('m/d/Y')}}" type="text" id="datepicker" placeholder="Date" name="schedule_date">
				<label>@lang('user.time')</label>
				<input value="{{date('H:i')}}" type="text" id="timepicker" placeholder="Time" name="schedule_time">
			  </div>
			  <div class="modal-footer">
				<button type="button" id="schedule_button" class="half-primary-btn btn-success shadow-box" data-dismiss="modal" style="width: 522px;margin-right: 24px;">@lang('user.ride.schedule_order')</button>
			</div>
		</form>
    </div>
  </div>
</div>
<!-- Cancel Modal -->
<div id="cancel_modal" id="cancel-reason" class="modal fade schedule-modal" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('user.ride.cancel_reason')</h4>
      </div>
        <form action="{{url('cancel/ride')}}" method="POST" >
			<div class="modal-body">
		
				<textarea class="form-control" name="cancel_reason" placeholder="@lang('user.ride.cancel_reason')" row="5" height="100px !important;"></textarea>
			  </div>
			  <div class="modal-footer">
				<button type="submit" class="full-primary-btn btn-danger fare-btn">@lang('user.ride.cancel_request')</button>
			</div>
		</form>	
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap" async defer></script>
    
    <script type="text/javascript">
	
	var ip_details = <?php echo json_encode( $ip_details );  ?>;
    var current_latitude = parseFloat(ip_details.geoplugin_latitude);
    var current_longitude = parseFloat(ip_details.geoplugin_longitude);
	var oLatitudev = document.getElementById('origin_latitude').value;
	var oLongitudev = document.getElementById('origin_longitude').value;
	var dLatitudev = document.getElementById('destination_latitude').value;
	var dLongitudev = document.getElementById('destination_longitude').value;
	
	
	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			mapTypeControl: false,
			zoomControl: true,
			center: {lat: current_latitude, lng: current_longitude},
			zoom: 12,
			styles : [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#e4e8e9"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#7de843"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#9bd0e8"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
		});
		
		var polylineOptionsActual = new google.maps.Polyline({ strokeColor: '#111', strokeOpacity: 0.8, strokeWeight: 4 });

		var directionsService = new google.maps.DirectionsService;
		var simage = '{{asset("asset/front/img/source.png")}}';
		var dimage = '{{asset("asset/front/img/destination.png")}}';
		
		var marker = new google.maps.Marker({
		    position: new google.maps.LatLng(oLatitudev, oLongitudev),
		    map: map,
		    icon: simage
		  });
        var marker = new google.maps.Marker({
		    position: new google.maps.LatLng(dLatitudev, dLongitudev),
		    map: map,
		    icon: dimage
		  });
		var directionsDisplay = new google.maps.DirectionsRenderer({map: map, suppressMarkers: true, polylineOptions: polylineOptionsActual });
	

		if( oLatitudev.length && oLongitudev.length &&  dLatitudev.length && dLongitudev.length ) {
			
			directionsService.route({
				origin: new google.maps.LatLng(Number(oLatitudev), Number(oLongitudev)),
				destination: new google.maps.LatLng(Number(dLatitudev), Number(dLongitudev)),
				avoidTolls: true,
				avoidHighways: false,
				travelMode: google.maps.TravelMode.DRIVING
			}, function (response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(response);
				} else {
					window.alert('Directions request failed due to ' + status);
				}
			});
			

		}
	}
	
	////////////////////////////////////////////////////////////////
	
            $('#schedule_button').click(function(){
                 //alert("ride script");
                $("#datepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
                $("#timepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
                //alert("ride script before submit");
                document.getElementById('create_ride').submit();
            }); 		


			/////////////////////////////////////////////////
			$('#promo_code_btn').on('click', function (e) {
				
				e.preventDefault();
				var btn = $('#promo_code_btn');
				form = btn.closest('form');
				$.ajax({
					headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
					type		:	'POST',
					url			:	'{{URL::to('/')}}/apply_promo_code',
					data		: 	form.serialize(),  
					beforeSend	: 	function () {
						btn.button('loading');
					},
					
					success		: function(json) {
						btn.button('reset');
						var dl = $('.dl-horizontal');
						$('.alert, .help-block, .alert-success, .alert-danger').remove();
						$('.form-group').removeClass('has-error');
					
						var html = '';
						var html1 = '';
						
						if(json.code == 'promocode_applied') {
							html  =  '<div class="alert alert-success">'+json.message+'!<button type="button" class="close" data-dismiss="alert">&times;</button></div>';						
						
							dl.find('.discnt-val').text( json.fare.discount );
							dl.find('.est_val').text(json.fare.estimated_fare);
							
							setTimeout(function() {
								btn.prop('disabled', true ); // Disables the button correctly.
							}, 0);
							
							$('#promo_code_input').attr('readonly', true);
							
							
							
						} else if( json.code == 'request_error' ) {
							
							html += '<div class="alert alert-danger"><ul>';	
							var i = 0;
							$.each(json.errors, function(ind, value ) {	
							
									html += '<li>'+value[i]+'</li>';
									i++;
								}); 
								
							html += '</ul></div>';							
							$('#promo_code_input').val('');
							
							
						} else {
						
							html  =  '<div class="alert alert-danger">'+json.message+'!<button type="button" class="close" data-dismiss="alert">&times;</button></div>';							
							$('#promo_code_input').val('');
						}
						
						$('.dash-content').prepend( html );
					}
				}); 
			});    
			
	</script>	
	<script type="text/javascript">
	$(document).ready(function () {
            $("#create_ride").validate(
		      {
		        rules: 
		        {
		          card_id: 
		          {
		            required: true
		          }
		        }
		    });	
	    });		
        var date = new Date();
        date.setDate(date.getDate()-1);
        $('#datepicker').datepicker({  
            startDate: date
        });	

		
        $('#timepicker').timepicker({showMeridian : false});		
        function card(value){
        	
            if(value == 'CARD'){
                $('#card_id').fadeIn(300);

            }else{
                $('#card_id').fadeOut(300);
            }

        }


		    
		
    </script>
@endsection