@extends('admin.layout.base')

@section('title', 'Drivers')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1"><span class="s-icon"><i class="ti-infinite"></i></span>&nbsp; Drivers Info</h5>
            <hr/>
            <a href="{{ route('admin.provider.create') }}" style="margin-left: 1em;" class="btn btn-success shadow-box btn-rounded pull-right"><i class="fa fa-plus"></i> Add New Driver</a>
            <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                 
                        <th>Documents</th>
                        <th>Online</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($providers as $index => $provider)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $provider->first_name }}</td>
                        <td>{{ $provider->email }}</td>
                        <td>{{ $provider->mobile }}</td>
                     
                        <td>
                            @if($provider->pending_documents() > 0 || $provider->service == null)
                                <a class="btn shadow-box btn-danger" href="{{route('admin.provider.document.index', $provider->id )}}"><span>{{ $provider->pending_documents() }} Doc! </span></a>
                            @else
                                <a class="btn shadow-box btn-success" href="{{route('admin.provider.document.index', $provider->id )}}">All Set!</a>
                            @endif
                        </td>
                        <td>
                            @if($provider->service)
                                @if($provider->service->status == 'active')
                                    <label class="btn shadow-box btn-primary">Yes</label>
                                @else
                                    <label class="btn shadow-box btn-warning">No</label>
                                @endif
                            @else
                                <label class="btn shadow-box btn-danger">N/A</label>
                            @endif
                        </td>
                        <td>
                            <div class="input-group-btn">
                                @if($provider->status == 'approved')
                                <a class="btn shadow-box btn-danger btn-block" href="{{ route('admin.provider.disapprove', $provider->id ) }}"><i class="fa fa-ban"></i></a>
                                @else
                                <a class="btn shadow-box btn-success btn-block" href="{{ route('admin.provider.approve', $provider->id ) }}"><i class="fa fa-check"></i></a>
                                @endif
                                <button type="button" 
                                    class="btn shadow-box btn-black btn-block dropdown-toggle"
                                    data-toggle="dropdown">Action
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.provider.request', $provider->id) }}" class="btn btn-default"><i class="fa fa-search"></i> Details</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.provider.statement', $provider->id) }}" class="btn btn-default"><i class="fa fa-sticky-note-o"></i> History</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.provider.edit', $provider->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> Edit Profile</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.provider.destroy', $provider->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                
                        <th>Documents</th>
                        <th>Online</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection