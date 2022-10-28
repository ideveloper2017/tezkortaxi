@extends('provider.layout.app')

@section('content')

 <div class="col-md-12" style="margin-bottom: 20px;">
    <div class="dash-content">
        <div class="row no-margin">
        <div class="pro-dashboard-head">

        </div>

        <div class="pro-dashboard-content">
            
            <!-- Earning Content -->
            <div class="earning-content gray-bg">
                <div class="container-fluid">


                    <!-- Earning section -->
                    <div class="earning-section earn-main-sec pad20">
                        <!-- Earning-section content -->
                        <div class="tab-content list-content">
                            <div class="list-view pad30 ">
                                
                                 <table class="table table-condensed" style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th>Pickup Time</th>
                                            <th>Cab</th>
                                            <th>Pickup Address</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $fully_sum = 0; ?>
                                    @foreach($fully as $each)

                                        <tr data-toggle="collapse" data-target="" class="accordion-toggle collapsed">
                                            <td>{{date('Y D, M d - H:i A',strtotime($each->schedule_at))}}</td>
                                            <td>
                                            	@if($each->service_type)
                                            		{{$each->service_type->name}}
                                            	@endif
                                            </td>
                                            <td>
                                                {{$each->s_address}}
                                            </td>
                                            
                                            <td>{{$each->status}}</td>
                                            <td>
                                                <form action="{{route('provider.cancel')}}?id={{$each->id}}" method="get" >
                                                    <input type="hidden" value="{{$each->id}}" name="request_id">
                                                    <button type="submit" style="margin-top: 0px;" class="full-primary-btn fare-btn detail_button">Cancel</button>
                                                </form>



                                                <!-- <a href="{{route('provider.cancel')}}?id={{$each->id}}" style="margin-top: 0px;" class="full-primary-btn fare-btn">Cancel</a> -->
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="6">
                                                <div class="accordian-body" >
                                                    <div class="col-md-6">
                                                        <div class="my-trip-left">
                                                        <?php 
                                                    $map_icon = asset('asset/img/marker-start.png');
                                                    $static_map = "https://maps.googleapis.com/maps/api/staticmap?autoscale=1&size=600x450&maptype=terrain&format=png&visual_refresh=true&markers=icon:".$map_icon."%7C".$each->s_latitude.",".$each->s_longitude."&markers=icon:".$map_icon."%7C".$each->d_latitude.",".$each->d_longitude."&path=color:0x191919|weight:8|enc:".$each->route_key."&key=".env('GOOGLE_MAP_KEY'); ?>
                                                            <div class="map-static" style="background-image: url({{$static_map}});">
                                                                
                                                            </div>
                                                            <div class="from-to row no-margin">
                                                                <div class="from">
                                                                    <h5>From</h5>
                                                                    <p>{{$each->s_address}}</p>
                                                                </div>
                                                                <div class="to">
                                                                    <h5>To</h5>
                                                                    <p>{{$each->d_address}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mytrip-right">
                                                             <h5>User Details</h5>
                                                             <div class="trip-user">
                                                                <div class="user-img" style="background-image: url({{img($each->user->avatar)}});">
                                                                </div>
                                                                <div class="user-right">
                                                                @if($each->user)
                                                                    <h5>{{$each->user->first_name}} {{$each->user->last_name}}</h5>
                                                                @endif
                                                                    <p>{{$each->status}}</p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                       
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    <!-- End of earning section -->
                </div>
            </div>
            <!-- Endd of earning content -->
        </div>  

        </div>
      </div>
    </div>
 </div>     
@endsection

@section('scripts')
<script type="text/javascript">
    $('.detail_button').click(function(){
          $(this).parent().submit();
    });
	document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
</script>
@endsection