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

                    <!-- Earning section -->
                    <div class="earning-section pad20 row no-margin">
                        <div class="earning-section-head">
                            <h3 class="earning-section-tit">Weekly Earnings</h3>
                        </div>

                        <!-- Earning acc-wrapper -->
                        <div class="col-md-12 col-sm-10 col-xs-12 no-padding"">
                            <div class="earn-acc-wrapper">
                                <div class="earning-acc pad20">
                                    <!-- Earning acc head -->
                                    <div class="row no-margin">
                                        <!--div class="pull-left trip-left"-->
                                            <h3 data-toggle="collapse" data-target="#demo1" class="accordion-toggle collapsed acc-tit">
                                                <!--span class="arrow-icon fa fa-chevron-right"></span-->
                                                <div class="pull-left trip-left">
                                                    <h3 class="acc-tit estimate-tit">
                                                        Day
                                                    </h3>
                                                </div>

                                                <div class="pull-right trip-right">
                                                    <p class="earning-cost no-margin" style="font-weight: bold;">Earning</p>
                                                </div>
                                            </h3>
                                        <!--/div-->
                                    </div>
                                    <!-- End of eaning acc head -->
                                    <!-- Earning acc body -->
                                    <div class="accordian-body earning-acc-body collapse row" id="demo1">
                                        <table class="table table-condensed table-responsive" style="border-collapse:collapse;">
                                            <tbody>
                                            <?php $sum_weekly = 0; ?>
                                            @foreach($weekly as $day)
                                                <tr>
                                                    <td>
                                                    @if($day->created_at)
                                                        {{date('Y-m-d',strtotime($day->created_at))}} - {{$day->created_at->diffForHumans()}}
                                                    @else
                                                        -
                                                    @endif
                                                    </td>
                                                    <td class="text-right">
                                                    @if($day->payment != "")
                                                    <?php 
                                                    $current_sum = 0;
                                                    $current_sum = $day->payment->tax + $day->payment->fixed + $day->payment->distance + $day->payment->commision;
                                                    $sum_weekly += $current_sum; ?>
                                                        {{currency($current_sum)}}
                                                    @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End of earning acc-body -->
                                </div>
                                <div class="earning-acc pad20 border-top">
                                    <div class="row no-margin">
                                        <div class="pull-left trip-left">
                                            <h3 class="acc-tit estimate-tit">
                                                Estimated Payout
                                            </h3>
                                        </div>

                                        <div class="pull-right trip-right">
                                            <p class="earning-cost no-margin">{{currency($sum_weekly)}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of earning acc-wrapper -->
                    </div>
                    <!-- End of earning section -->


                  
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