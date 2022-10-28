@extends('provider.layout.app')

@section('content')
<div class="content-area py-1" style="margin-bottom: 20px;margin-top: -70px;">
<div class="container-fluid">

    <div class="col-md-12" style="margin-bottom: 20px">
    <div class="dash-content">
        <div class="row no-margin">
            <div class="pro-dashboard-head">
               
            </div>
        </div>    
        <!-- End of earning head -->

        <div class="dash-content">
          
            <!-- Earning Content -->
            <div class="earning-content gray-bg">
                <div class="container-fluid">

                   
                    <!-- End of earning section -->

                    <!-- Earning section -->
                    <div class="earning-section earn-main-sec pad20">
                        <!-- Earning section head -->
                        <div class="earning-section-head row no-margin">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 no-left-padding">
                                <h3 class="earning-section-tit">My Trips</h3>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <div class="daily-earn-right text-right">
                                    <div class="status-block display-inline row no-margin">
                                        <form class="form-inline status-form">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select type="password" class="form-control mx-sm-3" style="height: auto;">
                                                    <option>All Trips</option>
                                                    <option>Completed</option>
                                                    <option>Pending</option>
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- View tab -->

                                    <!-- End of view tab -->
                                </div>
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
                                            <th>Booking Id</th>
                                            <th>Vehicle</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                            <th>Distance(KM)</th>
                                            <th>Cash Collected</th>
                                            <th>Total Earnings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $fully_sum = 0; ?>
                                    @foreach($fully as $each)
                                        <tr>
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
                                                    echo $StartTime->diffInHours($EndTime). " Hours";
                                                    echo " ".$StartTime->diffInMinutes($EndTime). " Minutes";
                                                    ?>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{$each->status}}</td>
                                            <td>{{$each->distance}}Kms</td>
                                            <td>
                                            	@if($each->payment != "")
                                            		<?php $each_sum = 0;
                                            		$each_sum = $each->payment->tax + $each->payment->fixed + $each->payment->distance + $each->payment->commision;
                                            		$fully_sum += $each_sum;
                                            		?>

                                            		{{currency($each_sum)}}
                                            	@endif
                                            </td>
                                            <td>{{currency($fully_sum)}}</td>
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
</div>
@endsection

@section('scripts')
<script type="text/javascript">
	document.getElementById('set_fully_sum').textContent = "{{currency($fully_sum)}}";
</script>
@endsection