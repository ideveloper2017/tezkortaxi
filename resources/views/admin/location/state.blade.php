@extends('admin.layout.base')

@section('title', 'Update State ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;"><i class="ti-map"></i>&nbsp;Update State</h5><hr>

            <form class="form-horizontal" action="{{route('admin.state.update', $state->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				<div class="form-group row">
					<label for="first_name" class="col-xs-2 col-form-label">Country Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $state->name }}" name="name" required id="name" placeholder="State Name">
					</div>
				</div>

			
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Update State</button>
						<a href="{{route('admin.state.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
