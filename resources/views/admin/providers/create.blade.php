@extends('admin.layout.base')

@section('title', 'Add Provider ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
			<h5 style="margin-bottom: 2em;"><span class="s-icon"><i class="ti-infinite"></i></span>&nbsp;Add Driver<hr></h5>

			@if( $services->count() )
            <form class="form-horizontal" action="{{route('admin.provider.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				@include('admin.providers._form')
				
	
				
				
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success shadow-box">Add Driver</button>
						<a href="{{route('admin.provider.index')}}" class="btn btn-default">Cancel</a>
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
