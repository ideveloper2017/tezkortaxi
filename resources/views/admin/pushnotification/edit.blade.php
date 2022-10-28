@extends('admin.layout.base')

@section('title', 'Update Push Notification ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;"><i class="ti-comments"></i>&nbsp;Update Push Notification</h5><hr>

            <form class="form-horizontal" action="{{route('admin.pushnotification.update', $notifications->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
            	<div class="form-group row">
					<label for="type" class="col-xs-12 col-form-label">Type</label>
					<div class="col-xs-10">
						<select name="zone" class="form-control" id="zone" required="">
							 
						    <option value="0">Choose One</option>
							 
							<option value="1">zone</option>
							 
					    </select>
					</div>
				</div>
				<div class="form-group row">
					<label for="type" class="col-xs-12 col-form-label">Type</label>
					<div class="col-xs-10">
						<select name="type" class="form-control" id="type" required="">
							 
						    <option value="0" >Choose One</option>
							 
							<option value="1" {{  ($notifications->type == 1)?"selected":""}}>User</option>
							  
							<option value="2" {{  ($notifications->type == 2)?"selected":""}}>Driver</option>
					    </select>
					</div>
				</div>
				
				<div class="form-group user row" @if ($notifications->type = 2) style="display:block; @else style="display:none;@endif">
					<label for="type" class="col-xs-12 col-form-label">Users</label>
					<div class="col-xs-10">
						<select name="users" class="form-control" id="users">
							 
						    <option value="0">Choose One</option>
							@foreach($users as $user)
							
							<option value="{{$user->id}}" {{  isset($user->id)?"selected":""}}>{{$user->first_name}}</option>
							 
							@endforeach
					    </select>
					</div>
				</div>
				
				<div class="form-group provider row"  @if ($notifications->type = 2) style="display:block; @else style="display:none;@endif">
					<label for="type" class="col-xs-12 col-form-label">Drivers</label>
					<div class="col-xs-10">
						<select name="providers" class="form-control" id="providers">
							 
						    <option value="0">Choose One</option>
							@foreach($providers as $provider)
							
							<option value="{{$provider->id}}" {{  isset($provider->id)?"selected":""}}>{{$provider->first_name}}</option>
							 
							@endforeach
					    </select>
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">Title</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="title" value="{{($notifications->title)?$notifications->title:old('title')}}" id="title" placeholder="Title">
					</div>
				</div>

				<div class="form-group row">
					<label for="notification_text" class="col-xs-12 col-form-label">Notification Text</label>
					<div class="col-xs-10">
						<textarea class="form-control" rows="5" id="notification_text"  name="notification_text" placeholder="Notification Text" >{{$notifications->notification_text}}</textarea>
					</div>
				</div>
                <embed src="{{ asset('storage/app/public/'.$notifications['image']) }}" width="300px" height="300px" />
				<div class="form-group row">
					<label for="picture" class="col-xs-12 col-form-label">Image</label>
					<div class="col-xs-10">
						<input type="file" accept="application/pdf, image/*" name="image" class="dropify form-control-file" id="image" aria-describedby="fileHelp">
					</div>
				</div>
				
                <div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">Url</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" name="url" value="{{old('url')}}" id="url" placeholder="Url">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">Expiration Date</label>
					<div class="col-xs-10">
						<input class="form-control" type="date" required name="expiration_date" value="{{($notifications['expiration_date'])? date('Y-m-d',strtotime($notifications['expiration_date'])) : old('expiration_date')}}" id="expiration_date" placeholder="Expiration Date">
					</div>
				</div>
				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label"><div class="col-xs-10"><input type="checkbox" class="form-check-input" id="show_in_promotion" name="show_in_promotion"  value="1" @if($notifications['show_in_promotion'] == 1)? checked="checked"' @else "" @endif>Show in promotion</div></label>
					
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Update Push Notification</button>
						<a href="{{route('admin.pushnotification.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){

 $('#type').change(function(){
     
  if($(this).val() == 1)
  {
    $('.provider').hide(); 
   $('.user').css('display','block');
   
  }else{
     $('.user').hide();   
    $('.provider').css('display','block'); 
  }
   
 });
 
});
</script>
