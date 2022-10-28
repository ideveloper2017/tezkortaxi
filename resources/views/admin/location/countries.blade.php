@extends('admin.layout.base')

@section('title', 'Country')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1"><i class="ti-map"></i>&nbsp;Countries <hr></h5>
                <a href="{{ route('admin.location.create') }}" style="margin-left: 1em;" class="btn shadow-box btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add Country</a>
                <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($countries as $index => $country)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$country->name}}</td>
                            <td>{{ date('Y-m-d: H:i:A', strtotime( $country->created_at ) )}}</td>
                            <td>
                                <form action="{{ route('admin.country.destroy', $country->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('admin.country.edit', $country->id) }}" class="btn shadow-box btn-success"><i class="fa fa-edit"></i></a>
                                    <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                           <th>ID</th>
                            <th>Name</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection