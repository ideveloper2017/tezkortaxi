@extends('admin.layout.base')

@section('title', 'Service Types ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><i class="ti-car"></i>&nbsp;Vehicle Category</h5><hr>
            <a href="{{ route('admin.service.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add New Vehicle</a>
            <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle Name</th>
                        <th>Capacity</th>
                        <th>Base Fare</th>
                        <th>Base Distance</th>
                        <th>Description</th>
                        <th>Icon</th>
                        <th>Upload Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($services as $index => $service)
				
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->capacity }}</td>
                        <td>{{ currency($service->fixed) }}</td>
						<td>{{ $service->distance }} KM</td>
                        <td>{{ $service->description }}</td>
                      
                        <td>
                            @if($service->image) 
                                <img src="{{   url('/'. $service->image ) }}" style="height: 50px" >
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            @if($service->vehicle_image) 
                                <img src="{{   url('/'. $service->vehicle_image ) }}" style="height: 50px">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.service.destroy', $service->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="{{ route('admin.service.edit', $service->id) }}" class="btn btn-success shadow-box">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Vehicle Name</th>
                        <th>Capacity</th>
                        <th>Base Fare</th>
                        <th>Base Distance</th>
                        <th>Description</th>
                    
                        <th>Icon</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection