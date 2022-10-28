@extends('admin.layout.base')

@section('title', 'Service Types ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><i class="ti-layout-media-overlay"></i>&nbsp;Cab Types</h5><hr>
            <a href="{{ route('admin.service.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add New Cab</a>
            <table class="table table-striped table-bordered dataTable" id="table-2" style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Service Name</th>
                        <th>Provider Name</th>
                        <th>Capacity</th>
                        <th>Base Price</th>
                        <th>Base Distance</th>
                        <th>Distance Price</th>
                        <th>Time Price</th>
                        <th>Price Calculation</th>
                        <th>Service Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($services as $index => $service)
				
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->provider_name }}</td>
                        <td>{{ $service->capacity }}</td>
                        <td>{{ currency($service->fixed) }}</td>
						<td>{{ $service->distance }} KM</td>
                        <td>{{ currency($service->price) }}</td>
                        <td>{{ currency($service->minute) }}</td>
                        <td>@lang('servicetypes.'.$service->calculator)</td>
                        <td>
                            @if($service->image) 
                                <img src="{{   url('/'. $service->image ) }}" style="height: 50px" >
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.service.destroy', $service->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <a href="{{ route('admin.service.edit', $service->id) }}" class="btn shadow-box btn-success">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <button type="submit" class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')">
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
                        <th>Service Name</th>
                        <th>Provider Name</th>
                        <th>Capacity</th>
                        <th>Base Price</th>
                        <th>Base Distance</th>
                        <th>Distance Price</th>
                        <th>Time Price</th>
                        <th>Price Calculation</th>
                        <th>Service Image</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection