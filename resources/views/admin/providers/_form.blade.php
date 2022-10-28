
<div class="form-group row">
    <label for="first_name" class="col-xs-2 col-form-label">Name</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('first_name', isset($provider->first_name) ? $provider->first_name : '') }}" name="first_name" required id="first_name" placeholder="Name">
    </div>
</div>
@unless (isset($provider) && is_object($provider))
    <div class="form-group row">
        <label for="password" class="col-xs-2 col-form-label">Password</label>
        <div class="col-xs-10">
            <input class="form-control" type="password" name="password" id="password" placeholder="Password">
        </div>
    </div>

    <div class="form-group row">
        <label for="password_confirmation" class="col-xs-2 col-form-label">Password Confirmation</label>
        <div class="col-xs-10">
            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-type Password">
        </div>
    </div>
@endunless
<div class="form-group row">
    <label for="picture" class="col-xs-2 col-form-label">Picture</label>
    <div class="col-xs-10">
        @if(isset($provider->avatar))
            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{ asset('/storage/app/public/' . $provider->avatar) }}">
        @endif
        <input type="file" accept="image/*" name="avatar" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
    </div>
</div>
<div class="form-group row">
    <label for="mobile" class="col-xs-2 col-form-label">Mobile</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('mobile', isset($provider->mobile) ? $provider->mobile : '') }}" name="mobile" required id="mobile" placeholder="Mobile">
    </div>
</div>
<div class="form-group row">
    <label for="mobile" class="col-xs-2 col-form-label">Email</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('email', isset($provider->email) ? $provider->email : '') }}" name="email" required id="email" placeholder="Email">
    </div>
</div>
<div class="form-group row">
    <label for="service_type" class="col-xs-2 col-form-label">Vehicle</label>
    <div class="col-xs-10">
        <select name="service_type" class="form-control" id="service_type"  required>
            @foreach($services as $service)
                <option value="{{ $service->id }}" {{ ( old('service_type', isset($provider->service->service_type_id) ? $provider->service->service_type_id : '') == $service->id ) ? 'selected' : '' }}>{{ $service->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="service_number" class="col-xs-2 col-form-label">Vehicle Number</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('service_number', isset($provider->service->service_number) ? $provider->service->service_number : '' ) }}" name="service_number" required id="service_number" placeholder="Cab Number">
    </div>
</div>
<div class="form-group row">
    <label for="service_make" class="col-xs-2 col-form-label">Vehicle Make</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('service_make', isset($provider->service->service_make) ? $provider->service->service_make : '' ) }}" name="service_make" required id="service_make" placeholder="Cab Make">
    </div>
</div>
<div class="form-group row">
    <label for="service_model" class="col-xs-2 col-form-label">Vehicle Model</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('service_model', isset($provider->service->service_model) ? $provider->service->service_model : '' ) }}" name="service_model" required id="service_model" placeholder="Cab Model">
    </div>
</div>
<div class="form-group row">
    <label for="service_model" class="col-xs-2 col-form-label">Vehicle Year</label>
    <div class="col-xs-10">
        <select name="service_year" class="form-control" id="service_year"  required>
            @foreach(range(1940, \Carbon\Carbon::now()->format('Y')) as $year)
                <option value="{{ $year }}" {{ ( old('service_year', isset($provider->service->service_year) ? $provider->service->service_year : '') == $year ) ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label for="service_name" class="col-xs-2 col-form-label">Vehicle Name</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('service_name', isset($provider->service->service_name) ? $provider->service->service_name : '' ) }}" name="service_name" required id="service_name" placeholder="Cab Name">
    </div>
</div>
<div class="form-group row">
    <label for="service_ac" class="col-xs-2 col-form-label">Vehicle AC</label>
    <div class="col-xs-10">
        <input class="form-control" type="text" value="{{ old('service_ac', isset($provider->service->service_ac) ? $provider->service->service_ac : '' ) }}" name="service_ac" required id="service_ac" placeholder="Cab AC">
    </div>
</div>