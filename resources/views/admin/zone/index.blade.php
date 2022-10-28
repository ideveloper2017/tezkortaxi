@extends('admin.layout.base')

@section('title', 'Locations ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1"><span class="s-icon"><i class="ti-zoom-in"></i></span> &nbsp;Zones </h5>
                <hr/>
                <a href="{{ route('admin.zone.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded w-min-sm m-b-0-25 waves-effect waves-light pull-right"><i class="fa fa-plus"></i> Add New Location</a>
                <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Zone Name</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="width:50px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($zones as $index => $zone)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$zone->zone_name}}</td>
                            <td>{{$zone->country}}</td>
                            <td>{{$zone->state}}</td>
                            <td>{{$zone->city}}</td>
                            <td>{{$zone->currency}}</td>
                            <td>{{$zone->status}}</td>
                            <td>{{ date('Y-m-d: H:i:A', strtotime( $zone->created_at ) )}}</td>
                            <td style="width: 100px;">
                                <form action="{{ route('admin.zone.destroy', $zone->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('admin.zone.edit', $zone->id) }}" class="btn shadow-box btn-black"><i class="fa fa-eye"></i></a>
                                    <button class="btn shadow-box btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Location Name</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection