@extends('provider.layout.app')

@section('content')

 <div class="col-md-12" style="margin-bottom: 20px;">
    <div class="">
        <div class="row no-margin">
        <div class="pro-dashboard-head">

        </div>

        <div class="pro-dashboard-content">
            
            <!-- Earning Content -->
            <div class="earning-content gray-bg">
                <div class="container-fluid">


                    <!-- Earning section -->
                    <div class="earning-section earn-main-sec pad20">
                        <!-- Earning section head -->
                        <div class="earning-section-head row no-margin">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 no-left-padding">
                                <h3 class="earning-section-tit">@lang('provider.scheduled_rides')</h3>
                            </div>
                        </div>
                        <!-- End of earning section head -->

                        <!-- Earning-section content -->
                        <div class="tab-content list-content">
                            <div class="list-view pad30 ">
                                
                                 <table class="table table-condensed" style="border-collapse:collapse;">
                                    <thead>
                                        <tr>
                                            <!--th>&nbsp;</th-->
                                            <th>@lang('provider.pickup_time')</th>
                                            <th>@lang('provider.driver_type')</th>
                                            <th>@lang('provider.pickup_address')</th>
                                            <th>@lang('provider.status')</th>
                                            <th>@lang('provider.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $fully_sum = 0;?>
                                    @if( count( $fully ) > 0 )
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
                                                    <form action="{{url('provider/upcoming/detail')}}">
                                                        <input type="hidden" value="{{$each->id}}" name="request_id">
                                                        <button type="submit" style="margin-top: 0px;" class="full-primary-btn fare-btn detail_button">@lang('provider.detail')</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else 
                                        <tr>
                                            <td colspan="5" class="text-center" >@lang('provider.no_ride_found')</td>
                                        </tr>
                                    @endif

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