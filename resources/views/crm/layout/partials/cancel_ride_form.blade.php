<div class="modal fade" id="cancel_ride_form-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="cancel_ride_form">
			  <!-- Modal Header -->
			  <div class="modal-header">
					<h4 class="modal-title pull-left">Please enter a reason type</h4>
					<span class="btn close  cancel_ride_form_close pull-right" data-dismiss="modal"><i class="fa fa-times"></i></span>
			  </div>
			  <!-- Modal body -->
				<div class="modal-body">
					<div class="form-group">
						<input type="hidden"  name="user_id" />
						<input type="hidden" name="request_id" />
						<input type="hidden" name="cancel_status"/>
						<input type="hidden" name="provider_id" value="0"/>
						<textarea class="form-control" rows="10" name="cancel_reason"></textarea>
					</div>
				</div>	
				<div class="modal-footer">
					<a class="btn btn-block" id="submit_reason_btn" style="background-color: #b01d23;color: #fff !important; font-weight: bold;">SUBMIT</a>
				</div>
			</form>
		</div>
	</div>
</div>
	