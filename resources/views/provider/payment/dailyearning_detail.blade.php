@extends('provider.layout.app')
@section('content')
<div class="col-md-12" style="margin-bottom: 20px">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="pro-dashboard-head">
               <h4>@lang('provider.ride_details')</h4>
            </div>
        <div class="pro-dashboard-content">
            <!-- Earning Content -->
            <div class="earning-content">
                <div class="container-fluid">
                    <!-- Earning section -->
                    <div class="earning-section earn-main-sec">
                        <div class="tab-content list-content">
                            <div class="list-view pad30 ">
                                <table class="table table-condensed" style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <th>@lang('provider.pickup_time')</th>
                                            <th>@lang('provider.booking_id')</th>
                                            <th>@lang('provider.vehicle')</th>
                                            <th>@lang('provider.duration')</th>
                                            <th>@lang('provider.status')</th>
                                            <th>@lang('provider.distance')(@lang('provider.km'))</th>
                                            <th>@lang('provider.cash_collected')</th>
                                            <th>@lang('provider.total_earnings')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($fully as $each)
                                         <?php $fully_sum = 0;$each_sum = 0; ?>
                                        <tr data-toggle="collapse" data-target="" class="accordion-toggle collapsed">
                                            <td>{{date('Y D, M d - H:i A',strtotime($each->created_at))}}</td>
                                            <td>{{ $each->booking_id }}</td>
                                            <td>
                                            	@if($each->service_type)
                                            		{{$each->service_type->name}}
                                            	@endif
                                            </td>
                                            <td>
                                            	@if($each->finished_at != null && $each->started_at != null) 
                                                    <?php 
                                                    $StartTime = \Carbon\Carbon::parse($each->started_at);
                                                    $EndTime = \Carbon\Carbon::parse($each->finished_at);
                                                    echo $StartTime->diffInHours($EndTime). " @lang('provider.hours')";
                                                    echo " ".$StartTime->diffInMinutes($EndTime). " @lang('provider.minutes')";
                                                    ?>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{$each->status}}</td>
                                            <td>{{$each->distance}}Kms</td>
                                            <td>
                                            	@if($each->payment != "")
                                            		<?php 
                                            		$each_sum = $each->payment->tax + $each->payment->fixed + $each->payment->distance + $each->payment->commision;
                                            		$fully_sum = $each_sum-$each->payment->commision;
                                            		?>
                                            	@endif
                                            	
                                            		{{currency($each_sum)}}
                                            </td>
                                            <td>{{currency($fully_sum)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="12">
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
                                                                    <h5>@lang('provider.from')</h5>
                                                                    <p>{{$each->s_address}}</p>
                                                                </div>
                                                                <div class="to">
                                                                    <h5>@lang('provider.to')</h5>
                                                                    <p>{{$each->d_address}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mytrip-right">
                                                             <h5>@lang('provider.user_details')</h5>
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
	document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
</script>
@endsection