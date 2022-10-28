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
                                <h3 class="earning-section-tit">Schedule Trips</h3>
                            </div>
                        </div>
                        <!-- End of earning section head -->

                        <!-- Earning-section content -->
                        <div class="tab-content list-content">
                            <div class="list-view pad30 ">
                                
                                <table class="earning-table table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Pickup Time</th>
                                            <th>Driver Type</th>
                                            <th>Pickup Address</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $fully_sum = 0; ?>
                                    @if( count( $fully ) > 0 )
                                        @foreach($fully as $each)
                                            <tr>
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
                                                    <a href="{{route('provider.cancel')}}?id={{$each->id}}" style="margin-top: 0px;" class="full-primary-btn fare-btn">Cancel</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">No Trips Found</td>
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
	document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
</script>
@endsection