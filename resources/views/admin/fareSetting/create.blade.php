@extends('admin.layout.base')

@section('title', 'Site Settings ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
			<h5><i class="ti-layout-media-overlay"></i>&nbsp;Fare Settings</h5><hr>
            <form class="form-horizontal"  action="{{ route('admin.fare.settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	
            		<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">Fare Plan Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="" name="fare_plan_name" required id="fare_plan_name" placeholder="Fare Plan Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">Start KM</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="" name="from_km" required id="from_km" placeholder="enter in digit (0)">
					</div>
				</div>

			
                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Upto KM</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="" name="upto_km" id="upto_km" placeholder="enter in digit (3)">
                    </div>
                </div>

				<div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">Price Per KM</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="" name="price_per_km"  id="price_per_km" >
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Waiting Price Per Minute</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="" name="waiting_price_per_min"  id="waiting_price_per_min" placeholder="4">
					</div>
				</div>
				
				
				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Apply Peak Fare</label>
				     	<div class="col-xs-10">
						<select class="form-control" id="peak_hour" name="peak_hour">
                        <option value="NO">NO</option>
                         <option value="YES">YES</option>
                         </select>
					</div>
				</div>

                       
                <span class="peakHide" style="display: none">   
                     
                <div class="form-group row" style="margin-left: -32px;font-size: 15px;font-weight: bold;">
					<label for="store_link_ios" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Peak Fare Details</label>
					</div>
				</div>
                    
                <div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label"></label>
					 
					<div class="col-xs-2">
						<select  class="form-control peak_day validation" name="peak_day[]" id="peak_day">
                        <option value="">Day</option>
                        @foreach(explode(',', env('DAY')) as $d=>$dv)
                         <option value="{{$dv}}">{{$dv}} </option>
                         @endforeach
                         </select>
					</div>
						<div class="col-xs-2">
						
                         <input type="time" class="form-control validation" name="peak_start_time[]" id="peak_start_time">
					</div>
						<div class="col-xs-2">
						
                         <input type="time" class="form-control validation" name="peak_end_time[]" id="peak_end_time">
					</div>
						<div class="col-xs-2">
						<input class="form-control validation" type="text" value="" name="peak_fare[]" id="peak_fare" placeholder="Peak Fare(%)">
					</div>
					
						<div class="col-xs-2">
					<button type="button" class="btn btn-primary" id="peak_data">+</button>
					</div>
				</div>
			
					<div  id="peakAdded">
						
					</div>
					
                     </span>
                     <br/>
                     
                     	<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Apply Night Fare</label>
				     	<div class="col-xs-10">
					
						
						<select class="form-control" id="night_hour" name="late_night">
                        <option selected="selected" value="NO">NO</option>
                         <option value="YES">YES</option>
    
                         </select>
					</div>
				</div>

                       
                           <span class="nightHide" style="display: none;">   
                     
                    	<div class="form-group row" style="margin-left: -32px;font-size: 15px;font-weight: bold;">
					<label for="store_link_ios" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Night Fare Details</label>
					</div>
				</div>
                    
                	<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label"></label>
					
						<div class="col-xs-2">
						
                         <input type="time" class="form-control validation1" id="night_start_time" name="night_start_time">
					</div>
						<div class="col-xs-2">
						
                         <input type="time" class="form-control validation1" id="night_end_time" name="night_end_time">
					</div>
					 
						<div class="col-xs-2">
						<input class="form-control validation1" type="text" value="" id="night_fare" name="night_fare" placeholder="Night Fare(%)">
					</div>
					
						<div class="col-xs-2">
					</div>
				</div>
			         
		        <div  id="nightAdded">
					
				</div>
                  
                </span>
                     
				<div class="form-group row">
                    <div class="col-xs-12 col-sm-6 offset-md-2 col-md-3">
                    	<button type="submit" class="btn btn-success shadow-box" id="alertbox">Save</button>
                    	<a href="{{route('admin.fare_settings')}}" class="btn btn-danger shadow-box">Cancel</a>
						
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection

