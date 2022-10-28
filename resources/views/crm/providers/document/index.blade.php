@extends('crm.layout.base')

@section('title', 'Driver Documents ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><i class="ti-basketball"></i>&nbsp;Vehicle Allocation</h5>
            <div class="row">
                <div class="col-xs-12">
                    
                    @if($ProviderService->count() > 0)
                    
                    <hr><h6>Allocated Services :  </h6>
                    <table class="table table-striped table-bordered dataTable">
                        <thead>
                            <tr>
                                <th>Vehicle Name</th>
                                <th>Number Plate</th>
                                <th>Vehicle Model</th>
                              
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ProviderService as $service)
                             
                            <tr>
                                <td>{{ $service['service_type']['name'] }}</td>
                                <td>{{ $service['service_number'] }}</td>
                                <td>{{ $service['service_model'] }}</td>
                              
                                <td>
                                    <form action="{{ route('crm.provider.document.service', [$Provider->id, $service->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button class="btn btn-danger btn-large btn-block shadow-box">Delete</a>
                                    </form>
                                </td>
                            </tr>
                            
                            @endforeach
                           
                        </tbody>
                       
                    </table>
                    
                    @else
                    
                    <div>Data Not Found</div>
                    @endif
                    <hr>
                </div>
                <form action="{{ route('crm.provider.document.store', $Provider->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="col-xs-3">
                        <select class="form-control input" name="service_type" required>
                            @forelse($ServiceTypes as $Type)
                            <option value="{{ $Type->id }}">{{ $Type->name }}</option>
                            @empty
                            <option>- Please Create a Service Type -</option>
                            @endforelse
                        </select>
                    </div>
                     <div class="col-xs-3">
                        <input type="text" required name="service_number" class="form-control" placeholder="Vehicle Number">
                    </div>
                    <div class="col-xs-3" style="width: 17%;">
                        <input type="text" required name="service_model" class="form-control" placeholder="Vehicle Model" >
                    </div>

                    

                    <div class="col-xs-3" style="width: 12%;">
                        <button class="btn btn-success btn-block shadow-box btn-rounded" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
<div class="content-area py-1">
    <div class="">
        <div class="box box-block bg-white">
            <h5 class="mb-1">Drivers Documents</h5>
			
			@if( $pr_documents )
				
				<table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
					<thead>
						<tr>
							<th>Document</th>
							<th>Name</th>
							<th>status</th>
							<th>Expiry Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					    
						@foreach($pr_documents as $document )
						
						<tr>
							<td>{{ $document['type'] }}</td>
							<td>{{ $document['name'] }}</td>
							<td>{{ $document['status'] }}</td>
						
							<td>{{ $document['expires_at'] }}</td>
							<td>
								
									@if( $document['status'] == 'MISSING' )
									
									<a href="{{ url('crm/provider/'.$document['provider_id'].'/document/'.$document['id'].'/upload') }}" style="margin-left: 1em;" class="btn btn-success shadow-box pull-right"><i class="fa fa-plus"></i> Add Doccument</a>
									
									@else
										<a href="{{ route('crm.provider.document.edit', [$document['provider_id'], $document['id']]) }}"><span class="btn btn-success btn-large shadow-box">View</span></a>
										<a href="{{ url('crm/provider/'.$document['provider_id'].'/document/'.$document['id'].'/update') }}"><span class="btn btn-success btn-large shadow-box">Edit</span></a>
										<form style="display:inline-block; margin-left:20px;" action="{{ route('crm.provider.document.destroy', [$document['provider_id'], $document['id']]) }}" method="POST" id="form-delete">
											<button class="btn btn-danger btn-large shadow-box" form="form-delete">Decline</button>
											{{ csrf_field() }}
											{{ method_field('DELETE') }}
										</form>
										
									@endif
							
							</td>
						</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th>Document</th>
							<th>Name</th>
							<th>status</th>
							<th>Expiry Date</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>
				@else
				<div>Data Not Found</div>
			@endif
        </div>
    </div>
    
</div>

@endsection