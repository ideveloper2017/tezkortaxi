@extends('admin.layout.base')

@section('title', 'Service Types ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><i class="ti-layout-media-overlay"></i>&nbsp;Fare Settings</h5><hr>
            <a href="{{ route('admin.fare.settings.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add New Plan</a>
            <table class="table table-striped table-bordered dataTable" id="table-2"  style="width: 100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fare Plan Name</th>
                        <th>From Km</th>
                        <th>UPTO KM</th>
                        <th>price_per_km</th>
                        <th>waiting_price_per_min</th>
                        <th>peak_hour</th>
                        <th>late_night</th>
                        <th>Action</th>
                    </tr>
                 
                </thead>
                <tbody>
                @foreach($data as $index => $service)
				
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->fare_plan_name }}</td>
                        <td>{{ $service->from_km }}</td>
                        <td>{{ $service->upto_km }}</td>
                        <td>{{ $service->price_per_km }}</td>
						<td>{{ $service->waiting_price_per_min }}</td>
                        <td>{{ $service->peak_hour }}</td>
                        <td>{{ $service->late_night }}</td>
                       
                        <td>
                            <form action="{{ route('admin.fare.settings.destroy') }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="hidden" value="{{$service->id}}" name="id" />
                                <a href="{{ route('admin.fare.settings.edit',$service->id) }}" class="btn btn-success shadow-box">
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
                        <th>Fare Plan Name</th>
                        <th>From Km</th>
                        <th>UPTO KM</th>
                        <th>price_per_km</th>
                        <th>waiting_price_per_min</th>
                        <th>peak_hour</th>
                        <th>late_night</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection