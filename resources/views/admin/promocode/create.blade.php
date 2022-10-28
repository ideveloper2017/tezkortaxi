@extends('admin.layout.base')

@section('title', 'Add Promocode ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;"><i class="ti-bookmark-alt"></i>&nbsp;Add Promocode</h5><hr>

            <form class="form-horizontal" action="{{route('admin.promocode.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="promo_code" class="col-xs-2 col-form-label">Promocode</label>
					<div class="col-xs-10">
						<input class="form-control" autocomplete="off"  type="text" value="{{ old('promo_code') }}" name="promo_code" required id="promo_code" placeholder="Promocode">
					</div>
				</div>
				<div class="form-group row">
					<label for="discount" class="col-xs-2 col-form-label">Discount</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{ old('discount') }}" name="discount" required id="discount" placeholder="Discount">
					</div>
				</div>

				<div class="form-group row">
					<label for="expiration" class="col-xs-2 col-form-label">Expiration</label>
					<div class="col-xs-10">
						<input class="form-control" type="date" value="{{ old('expiration') }}" name="expiration" required id="expiration" placeholder="Expiration">
					</div>
				</div>
				<div class="form-group row">
					<label for="expiration" class="col-xs-2 col-form-label">Set Limit</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('set_limit') }}" name="set_limit" required id="set_limit" placeholder="Limit">
					</div>
				</div>
				<div class="form-group row">
					<label for="expiration" class="col-xs-2 col-form-label">Number Of Time</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('number_of_time') }}" name="number_of_time" required id="number_of_time" placeholder="Times">
					</div>
				</div>
				<div class="form-group row">
					<label for="expiration" class="col-xs-2 col-form-label">User</label>
					<div class="col-xs-10">
                    <select name="user_type" class="form-control" id="user_type" required="">
    				    <option value="">Choose User Type</option>			 
						<option value="1">First User</option>
					 
						<option value="2">General User</option>
					</div>
    				</select>
    			</div>
				<div class="form-group row">
					<label for="expiration" class="col-xs-2 col-form-label" style="padding-left: 31px;">Zone</label>
					<div class="col-xs-10" style="padding: 15px 29px 1px 26px;">
                    <select name="zone" class="form-control" id="zone" required="">
                        <option value="">Choose Zone</option>
					    @foreach($zones as $zone)
						<option value="{{$zone->id}}">{{$zone->zone_name}}</option>
						@endforeach
					</div>
    				</select>
				</div>
                
				<div class="form-group row" >
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10" style="padding-top: 10px;">
						<button type="submit" class="btn btn-success shadow-box">Add Promocode</button>
						<a href="{{route('admin.promocode.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
