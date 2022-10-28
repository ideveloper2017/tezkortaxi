@extends('crm.layout.base')

@section('title', 'Add Provider ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('crm.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>
			
			<h5 style="margin-bottom: 2em;"><i class="ti-basketball"></i>&nbsp;Add Driver</h5><hr>

			@if( $services->count() )
            <form class="form-horizontal" action="{{route('crm.provider.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="first_name" class="col-xs-2 col-form-label">Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('first_name') }}" name="first_name" required id="first_name" placeholder="Name">
					</div>
				</div>



				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">Email</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" required name="email" value="{{old('email')}}" id="email" placeholder="Email">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-2 col-form-label">Password</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password" id="password" placeholder="Password">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-2 col-form-label">Password Confirmation</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type Password">
					</div>
				</div>

				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">Picture</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="avatar" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">Mobile</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ old('mobile') }}" name="mobile" required id="mobile" placeholder="Mobile">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="service_type" class="col-xs-2 col-form-label">Service Type</label>
					<div class="col-xs-10">
						<select name="service_type" class="form-control" id="service_type"  required>
							@foreach($services as $service) 
								<option value="{{ $service->id }}" <?php echo ( old('service_type') == $service->id ) ? 'selected' : ''; ?> >{{ $service->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="service_number" class="col-xs-2 col-form-label">Cab Number</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('service_number') }}" name="service_number" required id="service_number" placeholder="Cab Number">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="service_model" class="col-xs-2 col-form-label">Cab Model</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('service_model') }}" name="service_model" required id="service_model" placeholder="Cab Model">
					</div>
				</div>
		
				
				
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success shadow-box">Add Driver</button>
						<a href="{{route('crm.provider.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
			@else
				<div>Please add a service type first to add a new Driver!</div>
			@endif
		</div>
    </div>
</div>

@endsection
