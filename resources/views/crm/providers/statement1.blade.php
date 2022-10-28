@extends('crm.layout.base')
@section('title', $page)
@section('content')
<div class="content-area py-1">
   <div class="container-fluid">
      <div class="box box-block bg-white">
         <h3>Ride Revenue</h3>
         </br>
         <div class="row">
            <div class="col-lg-3 col-md-6 col-xs-12">
               <div class="box box-block bg-success mb-2"">
                  <div class="t-content">
                     <h6 class="text-uppercase mb-1">Total Ride</h6>
                     <h1 class="mb-1">{{$rides->count()}}</h1>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
               <div class="box box-block bg-primary mb-2">
                  <div class="t-content">
                     <h6 class="text-uppercase mb-1">Revenue</h6>
                     <h1 class="mb-1">{{currency($revenue[0]->overall)}}</h1>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
               <div class="box box-block bg-warning mb-2">
                  <div class="t-content">
                     <h6 class="text-uppercase mb-1">Cancelled Rides</h6>
                     <h1 class="mb-1">{{$cancel_rides}}</h1>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
               <div class="box box-block bg-warning mb-2">
                  <div class="t-content">
                     <h6 class="text-uppercase mb-1">Comission</h6>
                     <h1 class="mb-1">{{currency($revenue[0]->commission)}}</h1>
                  </div>
               </div>
            </div>
            <div class="row row-md mb-2" style="padding: 15px;">
               <div class="col-md-12">
                  <div class="box bg-white">
                     <div class="box-block clearfix">
                        <h5 class="float-xs-left">Earnings</h5>
                        <div class="float-xs-right">
                        </div>
                     </div>
                     @if(count($rides) != 0)
                     <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                        <thead>
                           <tr>
                              <td>Booking ID</td>
                              <td>Pick UP</td>
                              <td>Drop</td>
                              <td>Ride Details</td>
                              <td>Commission</td>
                              <td>Date</td>
                              <td>Status</td>
                              <td>Earned</td>
                           </tr>
                        </thead>
                        <tbody>
                           <?php $diff = ['-success','-info','-warning','-danger']; ?>
                           @foreach($rides as $index => $ride)
                           <tr>
                              <td>{{$ride->booking_id}}</td>
                              <td>
                                 @if($ride->s_address != '')
                                 {{$ride->s_address}}
                                 @else
                                 Not Provided
                                 @endif
                              </td>
                              <td>
                                 @if($ride->d_address != '')
                                 {{$ride->d_address}}
                                 @else
                                 Not Provided
                                 @endif
                              </td>
                              <td>
                                 @if($ride->status != "CANCELLED")
                                 <a class="text-primary" href="{{route('crm.requests.show',$ride->id)}}"><span class="underline">View Ride Details</span></a>
                                 @else
                                 <span>No Details Found </span>
                                 @endif									
                              </td>
                              <td>{{currency($ride->payment['commision'])}}</td>
                              <td>
                                 <span class="text-muted">{{date('d M Y',strtotime($ride->created_at))}}</span>
                              </td>
                              <td>
                                 @if($ride->status == "COMPLETED")
                                 <span class="tag tag-success">{{$ride->status}}</span>
                                 @elseif($ride->status == "CANCELLED")
                                 <span class="tag tag-danger">{{$ride->status}}</span>
                                 @else
                                 <span class="tag tag-info">{{$ride->status}}</span>
                                 @endif
                              </td>
                              <td>{{currency($ride->payment['fixed'] + $ride->payment['distance'])}}</td>
                           </tr>
                           @endforeach
                        <tfoot>
                           <tr>
                              <td>Booking ID</td>
                              <td>Pick UP</td>
                              <td>Drop</td>
                              <td>Ride Details</td>
                              <td>Commission</td>
                              <td>Dated on</td>
                              <td>Status</td>
                              <td>Earned</td>
                           </tr>
                        </tfoot>
                     </table>
                     @else
                     <h6 class="no-result">No results found</h6>
                     @endif 
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection