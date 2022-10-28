<div class="modal" id="wait-modal" style="background-color: #99999952;">
	<div class="modal-dialog">
		<div class="modal-content">
		  <!-- Modal Header -->
		  <div class="modal-header">
				<h4 class="modal-title pull-left">Waitting For Driver Acceptance</h4>
				<span class="btn close wait_model_close pull-right" data-dismiss="modal" onclick="window.location.reload()"><i class="fa fa-times"></i></span>
		  </div>
		  <!-- Modal body -->
			<div class="modal-body">
				<section id="timer" style="height: 200px; width: 200px; overflow: hidden; margin: 20px auto;">
					<img src="{{ asset('asset/front/img/loading.gif') }}" style="width:100%; height: auto;"/>
				
	
				</section>
		
				  <div class="modal-footer"></div>
			</div>
		</div>
	</div>
</div>
