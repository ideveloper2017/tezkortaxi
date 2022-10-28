@extends('admin.layout.base')
@section('title', 'Update User ')
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;"><i class="ti-user"></i>&nbsp;Update User</h5><hr>

            <form class="form-horizontal" action="{{route('admin.user.update', $user->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				<div class="form-group row">
					<label for="first_name" class="col-xs-2 col-form-label">Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $user->first_name }}" name="first_name" required id="first_name" placeholder="Name">
					</div>
				</div>
			
				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">Picture</label>
					<div class="col-xs-10">
						
					@if(isset($user->picture))
                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').'/'.$user->picture}}">
                    @endif
						<input type="file" accept="image/*" name="picture" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>
				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">Mobile</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ $user->mobile }}" name="mobile" required id="mobile" placeholder="Mobile">
					</div>
				</div>
				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">Email</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $user->email }}" name="email" required id="email" placeholder="Email">
					</div>
				</div>
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Update User</button>
						<a href="{{route('admin.user.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
		<div class="box box-block bg-white">
    	    <a href="{{ route('admin.user.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">Update User Password</h5>

            <form class="form-horizontal" action="http://quickrideja.com/admin/changeuserpassword" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
                    <label for="mobile" class="col-xs-2 col-form-label">Password</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="" name="password" required id="password" placeholder="Password">
                        <input class="form-control" type="hidden" value="{{ $user->id }}" name="id" required id="id">
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
