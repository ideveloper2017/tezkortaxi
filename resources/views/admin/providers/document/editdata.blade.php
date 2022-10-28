@extends('admin.layout.base')

@section('title', 'Add Driver Document')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ url('admin/provider/'.$Document['provider_id'].'/document') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>
			
			<h5 style="margin-bottom: 2em;">Add Driver Document</h5>

		
            <form class="form-horizontal" action="{{ url('admin/provider/'.$Document['provider_id'].'/document/'.$Document['document_id'].'/update') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				
                <h5 class="mb-1">Driver Name: {{ $Document['provider']['first_name'] }} {{ $Document['provider']['last_name'] }}</h5>
            <h5 class="mb-1">Document: {{ $Document['document']['name'] }}</h5>
				<embed src="{{ asset('storage/app/public/'.$Document['url']) }}" width="300px" height="300px" />
				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">Picture</label>
					<div class="col-xs-10">
						<input type="file" accept="application/pdf, image/*" name="document" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>
			
				<div class="form-group row">
					<label for="service_model" class="col-xs-2 col-form-label">Expiry Date</label>
					<div class="col-xs-10">
						<input class="form-control" type="date" name="expiry_date" required id="expiry_date" placeholder="Expiry Date" value="{{ date('Y-m-d',strtotime($Document["expires_at"])) }}">
					</div>
				</div>
				
				<input type="hidden" name="provider_id" value="{{ $Document['provider_id'] }}">
				<input type="hidden" name="document_id" value="{{ $Document['document_id'] }}">
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Add Driver</button>
						<a href="{{ url('admin/provider/'.$Document['provider_id'].'/document') }}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		
		</div>
    </div>
</div>

@endsection
