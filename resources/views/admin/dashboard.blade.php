@extends('admin.layout.base')

@section('title', 'Dashboard ')

@section('styles')
	<link rel="stylesheet" href="{{asset('asset/admin/vendor/jvectormap/jquery-jvectormap-2.0.3.css')}}">
@endsection

@section('content')
<div class="content-area py-1">
<div class="container-fluid">
    <div class="row row-md">
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-success mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">All Ride</h5>
					<h5 class="mb-1">{{$rides->count()}}</h5>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-primary mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">Revenue</h5>
					<h5 class="mb-1">{{currency($revenue)}}</h5>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-warning mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">All Cab</h5>
					<h5 class="mb-1">{{$service}}</h5>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-danger mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">Cancelled Ride</h5>
					<h5 class="mb-1">{{$user_cancelled+$provider_cancelled}}</h5>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-success mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">Cancelled Ride (Passenger)</h5>
					<h5 class="mb-1">{{$user_cancelled}}</h5>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-primary mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">Cancelled Ride (Driver)</h5>
					<h5 class="mb-1">{{$provider_cancelled}}</h5>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-warning mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">Card Payment</h5>
					<h5 class="mb-1">{{$fleet}}</h5>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-danger mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">Scheduled Ride</h5>
					<h5 class="mb-1">{{$scheduled_rides}}</h5>
				</div>
			</div>
		</div>
                <div class="col-lg-4 col-md-6 col-xs-12">
			<div class="box box-block bg-success mb-2 shadow-box">
				<div class="t-content">
					<h5 class="text-uppercase mb-1">Cash Payment</h5>
					<h5 class="mb-1">{{$cash}}</h5>
				</div>
			</div>
		</div>
	</div>

 <div class="row row-md">
         <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="box box-block mb-2">
               <div class="t-content" id="total-trip-chart">
               </div>
            </div>
         </div>
         <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="box box-block mb-2">
               <div class="t-content" id="payment-mode-chart">
               </div>
            </div>
         </div>
      </div>
      <div class="row row-md">
         <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="box box-block mb-2">
               <div class="t-content" id="chartContainer">
                  Rides
               </div>
            </div>
         </div>
         <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="box box-block mb-2">
               <div class="t-content" id="chartContainer_r">
                  Revenue
               </div>
            </div>
         </div>
      </div>
      <div class="row row-md mb-2">
         <div class="col-md-12">
            <div class="box bg-white" style="box-shadow:none;">
               <div class="box-block clearfix">
                  <h5 class="float-xs-left">Recent Rides</h5>
               </div>
               <table class="table mb-md-0 table table-striped table-bordered dataTable" id="table-2"style="width:100%">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>User </th>
                        <th>Driver</th>
                        <th>Ride Details</th>
                        <th>Date &amp; Time</th>
                        <th>Total</th>
                        <th>Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php $diff = ['-success','-info','-warning','-danger']; ?>

                     @foreach($rides as $index => $ride)
                     <tr>
                        <th scope="row">{{$index + 1}}</th>

                        <td>{{$ride->user['first_name']}}</td>
                        <td>
                          {{@$ride->provider->first_name}}
                        </td>
                        <td>
                           @if($ride->status != "CANCELLED")
                           <a class="text-primary" href="{{route('admin.requests.show',$ride->id)}}"><span class="underline">Ride Details</span></a>
                           @else
                           <span>No Details Found </span>
                           @endif                 
                        </td>
                        <td>
                           <span class="text-muted">{{$ride->created_at->diffForHumans()}}</span>
                        </td>
                        <td>
                           {{ currency($ride->payment['total'])}}
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
                     </tr>
                     <?php if($index==10) break; ?>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>

	</div>	
</div>
@endsection

@section('scripts')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
   <?php
      $php_array = array('abc','def','ghi');
      $js_array = json_encode(@$last7days_rides);
     /// echo "<pre/>";
      //print_r($js_array);die();
      echo "var last7days_rides = ". $js_array . ";\n";
      
       $js_r_array = json_encode(@$last7days_rides_r);
      echo "var last7days_rides_r = ". $js_r_array . ";\n";
      ?>
</script>
<script type="text/javascript">
   // VISUALIZATION API AND THE PIE CHART PACKAGE. payment-mode-chart
      google.charts.load("visualization", "1", { packages: ["corechart"] });
      google.charts.setOnLoadCallback(DrawPieChart);
      google.charts.setOnLoadCallback(DrawPieChart1);
   
     var tt = ["Element", "Density", { role: "style" } ];
      last7days_rides.unshift(tt);
      last7days_rides_r.unshift(tt);
      console.log(last7days_rides_r);
   
      //console.log(last7days_rides);
      function DrawPieChart() {
   
        var completed_rides ='<?php echo @$completed_rides; ?>';
        var cancel_rides    ='<?php echo @$cancel_rides; ?>';
        var ongoing_rides   ='<?php echo @$ongoing_rides; ?>';
          // DEFINE AN ARRAY OF DATA. completed_rides
          var arrSales =
          [
              ['Rides', 'Total Rides'],
              ['Completed', parseInt(completed_rides)],
              ['Cancel', parseInt(cancel_rides)],
              ['Ongoing', parseInt(ongoing_rides)]
              
          ];
          //console.log(arrSales);
   
          // SET CHART OPTIONS.
          var options = {
              title: 'Rides',
              is3D: true,
              pieSliceText: 'value-and-percentage'
          };
   
          var figures = google.visualization.arrayToDataTable(arrSales);
   
          // WHERE TO SHOW THE CHART (DIV ELEMENT).
         var chart = new google.visualization.PieChart(document.getElementById('total-trip-chart'));
   
          // DRAW THE CHART.
          chart.draw(figures, options);
      }
   
   
      function DrawPieChart1() {
   
        var cash_rides ='<?php echo @$cash; ?>';
        var card_rides ='<?php echo @$fleet; ?>';
        var paypal_rides ='<?php echo @$paypal; ?>';
          // DEFINE AN ARRAY OF DATA. completed_rides
          var arrSales =
          [
              ['Payment Mode', 'Payment Mode'],
              ['Cash', parseInt(cash_rides)],
              ['Stripe', parseInt(card_rides)],
              ['Paypal', parseInt(paypal_rides)]
              
          ];
   
          //console.log(arrSales);
   
          // SET CHART OPTIONS.
          var options = {
              title: 'Payment Mode',
              is3D: true,
              pieSliceText: 'value-and-percentage'
          };
   
          var figures = google.visualization.arrayToDataTable(arrSales);
   
          // WHERE TO SHOW THE CHART (DIV ELEMENT).
         var chart = new google.visualization.PieChart(document.getElementById('payment-mode-chart'));
   
          // DRAW THE CHART.
          chart.draw(figures, options);
      }
   
      ////////////////// bar             ////////
   google.charts.setOnLoadCallback(drawChart);
   google.charts.setOnLoadCallback(drawChartR);
      function drawChart() {
        var data = google.visualization.arrayToDataTable(last7days_rides);
   
        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                         { calc: "stringify",
                           sourceColumn: 1,
                           type: "string",
                           role: "annotation" },
                         2]);
   
        var options = {
          title: "Rides",
          width: 600,
          height: 400,
          bar: {groupWidth: "95%"},
          legend: { position: "none" },
        };
        var chart = new google.visualization.BarChart(document.getElementById("chartContainer"));
        chart.draw(view, options);
    }
   
   
   
    function drawChartR() {
        var data1 = google.visualization.arrayToDataTable(last7days_rides_r);
   
        var view1 = new google.visualization.DataView(data1);
        view1.setColumns([0, 1,
                         { calc: "stringify",
                           sourceColumn: 1,
                           type: "string",
                           role: "annotation" },
                         2]);
   
        var options1 = {
          title: "Revenue",
          width: 600,
          height: 400,
          bar: {groupWidth: "95%"},
          legend: { position: "none" },
        };
        var chart1 = new google.visualization.BarChart(document.getElementById("chartContainer_r"));
        chart1.draw(view1, options1);
    }
   
   
   
   
      ////////// ed  /////////
     /* window.onload = function () {
      
    var chart = new CanvasJS.Chart("chartContainer",
       {
         title:{
           text: ""    
         },
         axisY: {
           title: "Earning"
         },
         legend: {
           verticalAlign: "bottom",
           horizontalAlign: "center"
         },
         data: [
     
         {        
           color: "#B0D0B0",
           type: "column",  
           showInLegend: true, 
           legendMarkerType: "none",
           legendText: "Timing",
           dataPoints: [      
           { x: 1, y: 14, label: "3:00 PM"},
           { x: 2, y: 12,  label: "3:30 PM" },
           { x: 3, y: 8,  label: "4:00 PM"},
           { x: 4, y: 10,  label: "4:30 PM"},
           { x: 5, y: 7,  label: "5:00 PM"},
           { x: 6, y: 6, label: "5:30 PM"},
           { x: 7, y: 19,  label: "6:00 PM"},        
           { x: 8, y: 20,  label: "6:30 PM"}
           ]
         }
         ]
       });
     
       chart.render();
     }
      */
     
     
     
     window.onload = function () {
      
    var chart = new CanvasJS.Chart("chartContainer",
       {
         title:{
           text: ""    
         },
         axisY: {
           title: "Rides"
         },
         legend: {
           verticalAlign: "bottom",
           horizontalAlign: "center"
         },
         data: [
     
         {        
           color: "#B0D0B0",
           type: "column",  
           showInLegend: true, 
           legendMarkerType: "none",
           legendText: "Timing",
           dataPoints: [      
           { x: 1, y: 14, label: "3:00 PM"},
           { x: 2, y: 12,  label: "3:30 PM" },
           { x: 3, y: 8,  label: "4:00 PM"},
           { x: 4, y: 10,  label: "4:30 PM"},
           { x: 5, y: 7,  label: "5:00 PM"},
           { x: 6, y: 6, label: "5:30 PM"},
           { x: 7, y: 19,  label: "6:00 PM"},        
           { x: 8, y: 20,  label: "6:30 PM"}
           ]
         }
         ]
       });
     
       chart.render();
     }
      
     
</script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>@endsection