<div class="modal fade" id="assign_manual-modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="assign_copatner_form" class="form-horizontal">
			  <!-- Modal Header -->
			  <div class="modal-header">
					<h4 class="modal-title pull-left">Assign To Co-partner</h4>
					<span class="btn close  pull-right" data-dismiss="modal"><i class="fa fa-times"></i></span>
			  </div>
			  <!-- Modal body -->
				<div class="modal-body">
					<div class="hidden_fields">
						<input type="hidden"  name="trip_id" id="request_id" />
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Booking Id:</label>
						<div class="col-sm-8"><span class="pull-right user_booking_id"></span></div>							
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Name:</label>
						<div class="col-sm-8"><span class="pull-right user_name"></span></div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Email:</label>
						<div class="col-sm-8"><span class="pull-right user_email"></span></div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Phone No:</label>
						<div class="col-sm-8"><span class="pull-right  user_mobile"></span></div>
					</div>
					<hr/>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">From:</label>
						<div class="col-sm-8"><span class="pull-right user_from"></span></div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">To:</label>
						<div class="col-sm-8"><span class="pull-right user_to"></span></div>
					</div>
					<div class="form-group clearfix user_sr_type_form_gp">
						<label class="control-label col-sm-4">Choosen Service Type:</label>
						<div class="col-sm-8"><span class="pull-right user_service_type"></span></div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Distance:</label>
						<div class="col-sm-8"><span class="pull-right user_distance"></span></div>
					</div>
					<div class="form-group user_et_form_gp clearfix">
						<label class="control-label col-sm-4">Estimated Price:</label>
						<div class="col-sm-8"><span class="pull-right user_estimated_price"></span></div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Choose Cab Company:</label>
						<div class="col-sm-8">
							@if($companies->count())
							<select name="cab_company_id" class="form-control">
								@foreach( $companies as $cab )
									<option value="{{ $cab->id }}">{{ $cab->name }}  <small>Company({{ $cab->company  }})<small></option>
								@endforeach
							</select>
							@endif 
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="control-label col-sm-4">Special Note:</label>
						<div class="col-sm-8">
							<textarea class="form-control special_note" rows="8" name="special_note"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="form-group clearfix">
						<div class="col-sm-4"></div>
						<div class="col-sm-8">
							<a class="btn btn-block shadow-box" id="submit_assign_btn" style="background-color: #b01d23;color: #fff !important; font-weight: bold;">SUBMIT</a>
						</div>
					</div>					
				</div>
			</form>
		</div>
	</div>
</div>
	