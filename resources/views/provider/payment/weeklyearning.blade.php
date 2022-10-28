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
                            <h3 class="earning-section-tit">@lang('provider.week_erning')</h3>
                        </div>

                        <!-- Earning acc-wrapper -->
                        <div class="col-md-12 col-sm-10 col-xs-12 no-padding"">
                            <div class="earn-acc-wrapper">
                                <div class="earning-acc pad20">
                                    <!-- Earning acc head -->
                                    <div class="row no-margin">
                                       
                                                <div class="pull-left trip-left">
                                                    <h3 class="acc-tit estimate-tit">
                                                        @lang('provider.day')
                                                    </h3>
                                                </div>

                                                <div class="pull-right trip-right">
                                                    <p class="earning-cost no-margin" style="font-weight: bold;">@lang('provider.earning')</p>
                                                </div>
                                            <!--/h3-->
                                        <!--/div-->
                                    </div>
                                    <!-- End of eaning acc head -->
                                    <!-- Earning acc body -->
                                    <div class="accordian-body earning-acc-body">
                                        <table class="table table-condensed table-responsive" style="border-collapse:collapse;">
                                            <tbody>
                                            <?php $sum_weekly = 0;  ?>
                                            @foreach($weekly as $day)
                                            <?php $current_sum = 0; ?>
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
                                                   
                                                   $current_sum = $day->payment->tax + $day->payment->fixed + $day->payment->distance;
                                                    $sum_weekly += $current_sum; ?>
                                                        
                                                    @endif
                                                    {{currency($current_sum)}} 
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
                                                @lang('provider.estimated_payout')
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