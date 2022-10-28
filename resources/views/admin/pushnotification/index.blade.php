@extends('admin.layout.base')

@section('title', 'Push Notification ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            
            <div class="box box-block bg-white">
                <h5 class="mb-1"><i class="ti-comments"></i>&nbsp; Push Notification <hr></h5>
                <a href="{{ route('admin.pushnotification.create') }}" style="margin-left: 1em;" class="btn btn-success btn-rounded pull-right"><i class="fa fa-plus"></i> Add New Push Notification</a>
                <table class="table table-striped table-bordered dataTable" id="table-2" style="width:100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Notification Text</th>
                            <th>Expiration Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($notifications as $index => $notification)
                        <tr>
                            <td>{{$index + 1}}</td>
                            
                            <td><img src="{{ asset('public/user/profile/'.$notification->image) }}" width="80px" height="80px" /></td>
                            <td>{{($notification->type==1)?'User':'Provider'}}</td>
                            <td>{{$notification->title}}</td>
                            <td>{{$notification->notification_text}}</td>
                            <td>{{ date('Y-m-d: H:i:A', strtotime( $notification->expiration_date ) )}}</td>
                            <td>
                                <form action="{{ route('admin.pushnotification.destroy', $notification->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-danger shadow-box" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
                                </form>
                            
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Notification Text</th>
                            <th>Expiration Date</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
@endsection