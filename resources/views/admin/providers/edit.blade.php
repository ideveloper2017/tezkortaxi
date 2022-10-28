@extends('admin.layout.base')
@section('title', 'Update Provider ')
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 style="margin-bottom: 2em;">&nbsp;Driver Profile</h5><hr>
            <form class="form-horizontal" action="{{route('admin.provider.update', $provider->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">
                @include('admin.providers._form')
                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-success shadow-box">Update Driver</button>
                        <a href="{{route('admin.provider.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="box box-block bg-white">
            <h5 style="margin-bottom: 2em;">Update password</h5>
            <form class="form-horizontal" action="{{url('/admin/changeprovidorpassword')}}" method="POST" enctype="multipart/form-data" role="form">
                  {{csrf_field()}}
                <div class="form-group row">
                    <label for="mobile" class="col-xs-2 col-form-label">Password</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="" name="password" required id="password" placeholder="Password">
                        <input class="form-control" type="hidden" value="{{ $provider->id }}" name="id" required id="id">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="mobile" class="col-xs-2 col-form-label">Confirm Password</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="" name="password_confirmation" required id="password_confirmation" placeholder="Confirm Password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-success shadow-box">Update Password</button>
                        <a href="{{route('admin.provider.index')}}" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
