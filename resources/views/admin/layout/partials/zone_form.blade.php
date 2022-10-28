<?php 
	
	if( isset( $zone ) ) {
		$zone_name 	=	$zone->name;
		$zone_id	=	$zone->id;		
		
	} else {
		$zone_name = '';
		$zone_id = 0;
	
	}

?>


<div class="modal fade" id="zoneModel">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="zoneForm">
			  <!-- Modal Header -->
			  <div class="modal-header">
					<h4 class="modal-title pull-left">Add Location</h4>
					<span class="btn close  zone_close pull-right" id="zone_close" onClick="window.location.reload()"><i class="fa fa-times"></i></span>
			  </div>
			  <!-- Modal body --> 
				<div class="modal-body">
					<div class="form-group">
						
						<input type="hidden" name="country_id" id="country_id"/>
						<select class='form-control' v-model='country' @change='getStates()' name="country_name" id="country_name">
							<option value='0' >Select Country</option>
							<option v-for='data in countries' :value='data.id'>@{{ data.name }}</option>
						</select>
					</div>
					<div class="form-group">
					
						<input type="hidden" name="state_id" id="state_id"/>
					    <select class='form-control' v-model='state' @change='getCities()' name="state_name" id="state_name">
					    	<option value='0' >Select State</option>
							<option v-for='data in states' :value='data.id'>@{{ data.name }}</option>
						</select>
					</div>
					<div class="form-group">
						<input type="hidden" name="city_id" id="city_id"/>
						<select class='form-control' v-model='city' name="city_name" id="city_name" />
							<option value='0' >Select City</option>
							<option v-for='data in cities' :value='data.id'>@{{ data.name }}</option>
						</select>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Zone Name" name="zone_name" value="{{ $zone_name }}" />
						<input type="hidden" name="zone_id" id="zone_id" value="{{ $zone_id }}" />
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Currency Name" name="currency_name"/>
					</div>
					<h5 class="modal-title pull-left">Status</h5><br><br>
					<div class="form-group">
						<input type="radio" value="active" name="status_name" class="pull-left">Active
					    <input type="radio" value="inactive" name="status_name" class="align-center">Inactive
					    <input type="radio" value="banned" name="status_name">Banned
					</div>
				</div>	
				<div class="modal-footer">
					<a class="btn btn-block" id="submit_zone_btn"><i class="fa fa-save"></i> SUBMIT</a>
				</div>
			</form>
		</div>
	</div>
</div>
