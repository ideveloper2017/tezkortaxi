<div class="modal fade" id="booking-modal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<form id="booking_form" class="form-horizontal">
			  <!-- Modal Header -->
			  <div class="modal-header">
					<h4 class="modal-title pull-left">Update Ride</h4>
					<span class="btn close  pull-right" data-dismiss="modal"><i class="fa fa-times"></i></span>
			  </div>
			  <!-- Modal body -->
				<div class="modal-body">
					<div class="hidden_fields">
						<input type="hidden"  name="request_id" id="request_id" />
						<input type="hidden"  name="distance" id="distance" />
						<input type="hidden" name="s_latitude" id="s_latitude">
						<input type="hidden" name="s_longitude" id="s_longitude">
						<input type="hidden" name="d_latitude" id="d_latitude">
						<input type="hidden" name="d_longitude" id="d_longitude">
						<input type="hidden" name="service_type" id="service_type">
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Name:</label>
						<div class="col-sm-8">
							<input type="text"  id="first_name" name="first_name" class="form-control"  required/>
						</div>
					</div>
					<div class="form-group clearfix" style="display: none;">
						<label class="control-label col-sm-4">LastName:</label>
						<div class="col-sm-8">
							<input type="text" id="last_name"  name="last_name" class="form-control"  required/>
						</div>
					</div>
					
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Email:</label>
						<div class="col-sm-8">
							<input type="email" id="email" name="email" class="form-control" required />
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Phone No:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Phone" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Pickup Address:</label>
						<div class="col-sm-8">
							<input type="text" name="s_address" class="form-control" id="s_address" placeholder="Pickup Address" required>
						</div>
					</div> 
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Dropoff Address</label>
						<div class="col-sm-8">
							<input type="text" name="d_address" class="form-control" id="d_address" placeholder="Dropoff Address" required>
						</div>
					</div>
					
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Note:</label>
						<div class="col-sm-8">
							<textarea class="form-control" rows="4" id="special_note" name="special_note"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="form-group clearfix">
						<div class="col-sm-4"></div>
						<div class="col-sm-8">
							<a class="btn btn-block shadow-box" id="booking_btn" style="background-color: #b01d23;color: #fff !important; font-weight: bold;">SUBMIT</a>
						</div>
					</div>					
				</div>
			</form>
		</div>
	</div>
</div>
	