
@section('styles')

<style type="text/css">


.book_now_wrap  .btn.btn-default.sid_tg {
    text-transform: uppercase;
    font-family: 'open_sansbold';
    font-size: 16px;
    color: #333333;
    background-color: #2bb673;
    border-color: #2bb673;
    border-radius: 0;
    width: 100%;
    text-align: left;
    height: 40px;
    line-height: 28px;
    position: relative;
}

.book_now_wrap figure {
	float: right;
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
@endsection
@extends('website.app')@section('content')
<div class="pricing">
   <div class="container">
      <div class="row">
         <div class="col-md-4">
            <div class="pricing_left">
               <h5>{{ trans('index.pricing') }}</h5>
               <h4>{{ trans('index.get_fare') }}</h4>

               <form  method="GET"  action="{{ url('/PassengerSignin') }}"  onkeypress="return disableEnterKey(event);" style="width: 100%">

                   @if(Setting::get('map_type', 1) == 2)
                       <input type="hidden" name="distance" id="distance_input">
                       <input type="hidden" name="duration" id="duration_input">
                       <input type="hidden" name="seconds" id="seconds_input">
                       <input type="hidden" name="s_address">
                       <input type="hidden" name="d_address">
                       <div id="input_address_box">

                       </div>
                       <input type="button" id="yandex_estimated_btn" value="CALCULATE"/>
                   @else
                       <div class="pickup_location">
                           <input class="form-control" type="text" id="origin-input" required name="s_address"  placeholder="{{ trans('index.enter_pickup_location') }}">
                       </div>
                       <div class="destination">
                           <input class="form-control"  type="text"  id="destination-input" required  name="d_address"  placeholder="{{ trans('index.enter_destination') }}">
                           <figure><img src="../../../public/asset/front_dashboard/img/destination.png" id="estimated_btn" alt="img"/></figure>
                       </div>
                       <input type="hidden" name="s_latitude" id="origin_latitude">
                       <input type="hidden" name="s_longitude" id="origin_longitude">
                       <input type="hidden" name="d_latitude" id="destination_latitude">
                       <input type="hidden" name="d_longitude" id="destination_longitude">
                       <input type="hidden" name="current_longitude" id="long">
                       <input type="hidden" name="current_latitude" id="lat">
                       <input type="hidden" name="promo_code" id="promo_code" />
                   @endif
                  <div class="car-detail">
                     @if( count( $services )  > 0 )
                     <?php $i = 0;  ?>
                     @foreach($services as $service )
                     <div class="car-radio">
                        <input type="radio" name="service_type" value="{{$service->id}}" id="service_{{$service->id}}"  @if ($loop->first) checked="checked" @endif>
                        <label for="service_{{$service->id}}">
                           <div class="car-radio-inner">
                              <div class="img"><img src="{{image($service->image)}}"  class="{{ $i== 0 ? 'img_color ': ''}}"></div>
                              <div class="name"><span class="{{ $i== 0 ? 'car_ser_type': ''}}">{{$service->name}}</span></div>
                           </div>
                        </label>
                     </div>
                     <?php $i++; ?>
                     @endforeach
                     @endif
                  </div>
               </form>
            </div>
         </div>
         <div class="col-md-8">
            <div id="map"></div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('asset/front/js/slick.min.js')}}"></script>
<script type="text/javascript">
   var ip_details = [];
   var current_latitude =  43.653908 ;
   var current_longitude = -79.384293;

   $(document).ready(function() {
     $("#owl-demo2").owlCarousel({
       autoPlay: 3000,
       items :3,
       autoPlay:true,
       navigation:true,
       navigationText:true,
       pagination:true,
       itemsDesktop : [1350,3],
       itemsDesktop : [1199,2],
       itemsDesktopSmall : [991,1],
       itemsTablet: [767,1],
       itemsMobile : [560,1]
     });

   });

   function disableEnterKey(e) {
       var key;
       if(window.e) {
           key = window.e.keyCode; // IE
       } else {
           key = e.which; // Firefox
       }

       if(key == 13)
           return e.preventDefault();
   }

    $('.pricing_left .car-radio').on('click', function() {
   var detail = $('.car-detail');
   detail.find('input[type=radio]').attr('checked');
   detail.find('.car_ser_type').removeClass('car_ser_type');
   detail.find('.img_color').removeClass('img_color');
   $(this).find('img').addClass('img_color');
   $(this).find('span').addClass('car_ser_type');
   $(this).find('input[type=radio]').attr('checked', 'checked');

   });

   $('.car-detail').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		autoplay: false,
		swipeToSlide: true,
		infinite: false
	});

   $(function() {
   $('#estimated_btn').click(function(e) {
    e.preventDefault();
    form = $(this).closest('form');
    form.find('.help-block, .final_estimated, .book_now_wrap').remove();

    var origion                 = form.find('#origin-input');
    var destinated              = form.find('#destination-input');

    var origin_latitude		    =	form.find('#origin_latitude').val();
    var origin_longitude	    =	form.find('#origin_longitude').val();
    var destination_latitude    =   form.find('#destination_latitude').val();
    var destination_longitude   =   form.find('#destination_longitude').val();
    var formData = form.serializeArray();

    if( origion.val().length === 0 ) {
        form.find('.pickup_location').append('<span class="help-block text-danger">Please add a pick up location! </span>');
        return false;
    }

    if( destinated.val().length === 0 ) {
        form.find('.destination').append('<span class="help-block text-danger">Please add a final location! </span>');
        return false;
    }


    if( origin_latitude.length !== 0 &&  origin_latitude.length !== 0  && origin_latitude.length !== 0  && origin_latitude.length !==  0 ) {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            type	:	'POST',
            url	    : 	'{{ url("/get_fare") }}',
            data	: 	formData,

            success	: 	function(json) {
                form.find('.destination').after('<div class="final_estimated" style="font-weight: bold; color:#00bfff"><span class="pull-left">Estimated Fare</span><span class="pull-right">'+json.estimated_fare+'</span></div>');
                form.find('.car-detail').after('<div class="book_now_wrap"><button type="submit" class="btn btn-default sid_tg">Book Now <figure><img src="../../../public/asset/front_dashboard/img/btn_arrow.png" alt="img"></figure></button></div>');
            }

          });
   }
   });

   });

</script>

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
                            form.find('.car-detail').after('<div class="book_now_wrap"><button type="submit" class="btn btn-default sid_tg">Book Now <figure><img src="../../../public/asset/front_dashboard/img/btn_arrow.png" alt="img"></figure></button></div>');
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
@endsection
