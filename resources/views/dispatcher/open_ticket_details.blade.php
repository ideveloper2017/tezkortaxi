@extends('dispatcher.layout.base')

@section('title', 'Complaint Ticket ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('dispatcher.openTicket') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;"><i class="ti-receipt"></i>&nbsp;Complaint Details</h5><hr>

            <form class="form-horizontal" action="{{route('dispatcher.transfer', $data->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				
				<div class="form-group row">
					<label for="name" class="col-xs-3 col-form-label">Name</label>
					<div class="col-xs-8">
						<span>{{ $data->name }}</span>
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-3 col-form-label">Email</label>
					<div class="col-xs-8">
						<span>{{ $data->email }}</span>
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-3 col-form-label">Subject</label>
					<div class="col-xs-8">
						@if($data->transfer==1)
						<span>Customer Relationship</span>
						@elseif($data->transfer==2)
						<span>Dispatcher Department</span>
						@elseif($data->transfer==3)
						<span>Account Department</span>
						@else
						<span></span>
						@endif
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-3 col-form-label">Message</label>
					<div class="col-xs-8">
                     <span>{{ $data->message }}</span>
					</div>
				</div>

                <div class="form-group row">
					<label for="mobile" class="col-xs-3 col-form-label">Transfer To Other Department</label>
					<div class="col-xs-8">
						<select class="form-control" name="transfer" id="transfer" required>
							<option value=""> Please Select </option>
							<option value="1" {{($data->transfer == 1)?'selected':''}}>Customer Relationship</option>
							<option value="2" {{($data->transfer == 2)?'selected':''}}>Dispatcher Department</option>
							<option value="3" {{($data->transfer == 3)?'selected':''}}>Account Department</option>
						</select>
					</div>
				</div>

                <div class="form-group row">
					<label for="mobile" class="col-xs-3 col-form-label">Status</label>
					<div class="col-xs-8">
					<select class="form-control" name="status" required id="status">
						<option value=""> Please Select </option>
						<option value="1" {{($data->status == 1)?'selected':''}}>Active</option>
                        <option value="0" {{($data->status == 0)?'selected':''}}>Close</option>
                    </select>
					</div>
				</div>
				<div class="form-group row">
					<label for="mobile" class="col-xs-3 col-form-label">Reply</label>
					<div class="col-xs-8">
						<input class="form-control" type="text" value="{{$data->reply}}" name="reply" required id="reply" placeholder="Reply">
					</div>
				</div> 
				<div class="form-group row">
					<label for="zipcode" class="col-xs-3 col-form-label"></label>
					<div class="col-xs-8">
						<button type="submit" class="btn btn-success btn-rounded shadow-box">Reply</button>
						<a href="{{route('dispatcher.openTicket')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
