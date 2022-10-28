@extends('crm.layout.base')

@section('title', 'Add Driver Document')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    
            <a href="{{ url('crm/provider/'.$Provider[0]['id'].'/document') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>
			
			<h5 style="margin-bottom: 2em;"><i class="ti-basketball"></i>&nbsp;Add Driver Document</h5><r>h

		
            <form class="form-horizontal" action="{{ url('crm/provider/'.$Provider[0]['id'].'/document/'.$Document[0]['id'].'/upload') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				
                <h5 class="mb-1">Driver Name: {{ $Provider[0]['first_name'] }} {{ $Provider[0]['last_name'] }}</h5>
            <h5 class="mb-1">Document: {{ $Document[0]['name'] }}</h5>
				
				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">Picture</label>
					<div class="col-xs-10">
						<input type="file" accept="application/pdf, image/*" name="document" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="service_model" class="col-xs-2 col-form-label">Expiry Date</label>
					<div class="col-xs-10">
						<input class="form-control" type="date" value="{{ old('service_model') }}" name="expiry_date" required id="expiry_date" placeholder="Expiry Date">
					</div>
				</div>
				
				<input type="hidden" name="provider_id" value="{{ $Provider[0]['id'] }}">
				<input type="hidden" name="document_id" value="{{ $Document[0]['id'] }}">
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success shadow-box btn-rounded">Add Driver</button>
						<a href="{{ url('crm/provider/'.$Provider[0]['id'].'/document') }}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		
		</div>
    </div>
</div>

@endsection
