@extends('user.layout.base')
@section('title', 'My Trips ')
@section('content')
<style type="text/css">
	.fontsize{
		font-size: 14px;
	}
    .car-radio{
        width: 125px !important;
    }
    #yandex_estimated_btn {
        padding: 5px 10px;
        margin: 20px;
        color: white;
        background: #000111;
        border: 1px solid #000111 !important;
        border-radius: 10px;
    }
</style>

<div class="col-md-12" style="margin-bottom: 20px;">

    <div class="dash-content">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title"><span class="s-icon"></span>&nbsp; {{ trans('user.my_rides') }}</h4>
            </div>
        </div>
        @include('common.notify')
        <div class="row no-margin pricing_left">
            <div class="col-md-6">
                <form action="{{url('confirm/ride')}}" method="GET" onkeypress="return disableEnterKey(event);" class="tripsform">
                    @if(Setting::get('map_type', 1) == 2)
                       <input type="hidden" name="distance" id="distance_input" value="{{ session('distance') }}">
                       <input type="hidden" name="duration" id="duration_input" value="{{ session('duration') }}">
                       <input type="hidden" name="seconds" id="seconds_input" value="{{ session('seconds') }}">
                       <input type="hidden" name="s_address" value="{{ session('s_address') }}">
                       <input type="hidden" name="d_address" value="{{ session('d_address') }}">
                   <div id="input_address_box">

                   </div>
                       <input type="button" id="yandex_estimated_btn" value="CALCULATE"/>
                @else
                    <div class="input-group dash-form" style="max-width:inherit">
                        <div class="input-group-addon"><span class="ti-control-record"></span></div>
                        <input type="text" class="form-control fontsize" id="origin-input" name="s_address"  placeholder="{{ trans('index.enter_pickup_location') }}" value="{{ session('s_address') }}">
                    </div>
                    <div class="input-group dash-form" style="max-width:inherit">
                        <div class="input-group-addon"><span class="ti-location-pin"></span></div>
                        <input type="text" class="form-control fontsize" id="destination-input" name="d_address"  placeholder="{{ trans('index.enter_destination') }}" value="{{ session('d_address') }}">
                    </div>
                    <input type="hidden" name="s_latitude" id="origin_latitude" value="{{ session('s_latitude') }}">
                    <input type="hidden" name="s_longitude" id="origin_longitude" value="{{ session('s_longitude') }}">
                    <input type="hidden" name="d_latitude" id="destination_latitude" value="{{ session('d_latitude') }}">
                    <input type="hidden" name="d_longitude" id="destination_longitude" value="{{ session('d_longitude') }}">
                    <input type="hidden" name="current_longitude" id="long" value="{{ @$_GET['current_longitude'] }}">
                    <input type="hidden" name="current_latitude" id="lat" value="{{ @$_GET['current_latitude'] }}">
                    <input type="hidden" name="promo_code" id="promo_code" />
                @endif

                    <div class="car-detail">
                        @foreach($services as $service)
                        <?php $i = 0;  ?>
                        <div class="car-radio">
                            <input type="radio"
                                name="service_type"
                                value="{{ $service->id }}"
                                id="service_{{ $service->id }}"
                                @if (session('service_type') == $service->id) checked="checked" @endif>
                            <label for="service_{{$service->id}}">
                                <div class="car-radio-inner">

                                    <div class="img"><img src="{{image($service->image)}}"  class="{{ $i== 0 ? 'img_color ': ''}}"></div>
                                    <div class="name"><span class="{{ $i== 0 ? 'car_ser_type': ''}}">{{$service->name}}</span></div>
                                </div>
                            </label>
                        </div>
                        <?php $i++; ?>
                        @endforeach
                    </div>
                    <button type="submit" class="half-primary-btn btn-success shadow-box fare-btn" style="width: 100% !important;">@lang('user.ride.order_now')</button>
                </form>
            </div>

            <div class="col-md-6">
                <div class="map-responsive">
                    <div id="map" style="width: 100%; height: 450px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="dash-content" style="margin-top: 20px;">
        <div class="row no-margin">
            <div class="col-md-12">
                <h4 class="page-title">Recent Ride</h4>
            </div>
        </div>

        <div class="row no-margin ride-detail">
            <div class="col-md-12">
            @if($trips->count() > 0)

                <table class="table table-condensed" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>@lang('user.booking_id')</th>
                            <th>@lang('user.date')</th>
                            <th>@lang('user.profile.name')</th>
                            <th>@lang('user.amount')</th>
                            <th>@lang('user.type')</th>
                            <th>@lang('user.payment')</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($trips as $trip)

                        <tr data-toggle="collapse" data-target="#trip_{{$trip->id}}" class="accordion-toggle collapsed">
                            <td>{{ $trip->booking_id }}</td>
                            <td>{{date('d-m-Y',strtotime($trip->assigned_at))}}</td>
                            @if($trip->provider)
                                <td>{{$trip->provider->first_name}} {{$trip->provider->last_name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($trip->payment)
                                <td>{{currency($trip->payment->total)}}</td>
                            @else
                                <td>-</td>
                            @endif
                            @if($trip->service_type)
                                <td>{{$trip->service_type->name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>@lang('user.paid_via') {{$trip->payment_mode}}</td>
                            <td>
                                <form action ="{{url('/mytrips/detail')}}">
                                    <input type="hidden" value="{{$trip->id}}" name="request_id">
                                    <button type="submit" style="margin-top: 0px;" class="full-primary-btn fare-btn">Detail</button>
                                </form>
                            </td>
                        </tr>

                        @endforeach


                    </tbody>
                </table>
                @else
                    <hr>
                    <p style="text-align: center;">No Rides Available</p>
                @endif
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
        <h4 class="modal-title">Schedule a Order</h4>
      </div>
		<form>
			<div class="modal-body">
				<label>Date</label>
				<input value="{{date('m/d/Y')}}" type="text" id="datepicker" placeholder="Date" name="schedule_date">
				<label>Time</label>
				<input value="{{date('H:i')}}" type="text" id="timepicker" placeholder="Time" name="schedule_time">
			  </div>
			  <div class="modal-footer">
				<button type="button" id="schedule_button" class="half-primary-btn btn-success shadow-box" data-dismiss="modal" style="width: 522px;margin-right: 24px;">Schedule Order</button>
			</div>
		</form>
    </div>
  </div>
</div>
@endsection

@section('scripts')

@if (Setting::get('map_type', 1) == 2)<!--YANDEX MAP-->
<script src="https://api-maps.yandex.ru/2.1/?apikey={{ env("YANDEX_MAP_KEY") }}&lang=en_US" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('asset/front/js/provider.js') }}"></script>
<script type="text/javascript" src="{{ asset('asset/front/js/yandex_map.js') }}"></script>
<script>
        $(function() {
           $(document).ready(function () {

                setTimeout(function () {
                    $("#input_address_box").append($(".ymaps-2-1-79-control-popup.ymaps-2-1-79-popup.ymaps-2-1-79-popup_direction_down"));
                }, 1000);

               $('#yandex_estimated_btn').click(function(e) {
                   e.preventDefault();
                   form = $(this).closest('form');
                   form.find('.help-block, .final_estimated, .book_now_wrap').remove();

                   let orig = '';
                   let dest = '';
                   $("#input_address_box input[type=text]").each(function (index) {
                       if(index == 0) {
                           orig = $(this).val();
                           $('input[name="s_address"]').val(orig);
                       } else {
                           dest = $(this).val();
                           $('input[name="d_address"]').val(dest);
                       }
                   });

                   var distance		    =	form.find('#distance_input').val();
                   var duration	    =	form.find('#duration_input').val();
                   var seconds    =   form.find('#seconds_input').val();
                   var formData = form.serializeArray();

                   if( distance !== 0     && seconds !==  0 ) {

                       $.ajax({
                           headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                           type	:	'POST',
                           url   : '{{ url("/get_fare_yandex") }}',
                           data	: 	formData,
                           success	: 	function(json) {
                               form.find('#yandex_estimated_btn').after('<div class="final_estimated" style="font-weight: bold; color:black"><span class="pull-left">Estimated Fare</span><span class="pull-right">'+json.estimated_fare+'</span></div>');
                               form.find('.car-detail').after('<div class="book_now_wrap"><button type="submit" class="btn btn-default sid_tg">Book Now <figure><img src="../../../../public/asset/front_dashboard/img/btn_arrow.png" alt="img"></figure></button></div>');
                           }

                       });
                   }
               });

           }) ;
        });
    </script>
@else
<script type="text/javascript" src="{{ asset('asset/front/js/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endif

<script type="text/javascript">
 $('.pricing_left .car-radio').on('click', function() {
   var detail = $('.car-detail');
   detail.find('input[type=radio]').attr('checked');
   detail.find('.car_ser_type').removeClass('car_ser_type');
   detail.find('.img_color').removeClass('img_color');
   $(this).find('img').addClass('img_color');
   $(this).find('span').addClass('car_ser_type');
   if($(':radio[name=service_type]:checked, :radio[name=service_type]:checked').length >= 1){
   $('input[name=service_type]').attr('checked', false);

   } if($(':radio[name=service_type]:checked, :radio[name=service_type]:checked').length==0){
   	   $(this).find('input[type=radio]').attr('checked', 'checked');
   }

   });

        var ip_details = <?php echo json_encode( $ip_details );  ?>;

        var current_latitude = parseFloat(ip_details.geoplugin_latitude);
        var current_longitude = parseFloat(ip_details.geoplugin_longitude);



    if( navigator.geolocation ) {
       navigator.geolocation.getCurrentPosition( success);
    } else {
        console.log('Sorry, your browser does not support geolocation services');
        initMap();
    }

    function success(position)
    {
        document.getElementById('long').value = position.coords.longitude;
        document.getElementById('lat').value = position.coords.latitude;

        if(position.coords.longitude != "" && position.coords.latitude != ""){
            current_longitude = position.coords.longitude;
            current_latitude = position.coords.latitude;

        }

        initMap();
    }
</script>

    <script type="text/javascript">
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

        $('#schedule_button').click(function(){
            alert("ride script");
            $("#datepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
            $("#timepicker").clone().attr('type','hidden').appendTo($('#create_ride'));
            alert("ride script before submit");
            document.getElementById('create_ride').submit();
        });

    </script>

<script type="text/javascript">

    function disableEnterKey(e)
    {
        var key;
        if(window.e)
            key = window.e.keyCode; // IE
        else
            key = e.which; // Firefox

        if(key == 13)
            return e.preventDefault();
    }
</script>

@endsection
