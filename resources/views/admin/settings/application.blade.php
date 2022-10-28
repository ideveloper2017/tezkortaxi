@extends('admin.layout.base')

@section('title', 'Site Settings ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
			<h5 style="margin-bottom: 2em;"><span class="s-icon"><i class="ti-settings"></i></span> Business Settings</h5>
            <hr/>
            <form class="form-horizontal" action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">Business Name</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('site_title', 'iLyft') }}" name="site_title" required id="site_title" placeholder="Business Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_logo" class="col-xs-2 col-form-label">Business Logo</label>
					<div class="col-xs-12">
						@if(Setting::get('site_logo')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ url(Setting::get('site_logo', asset('logo-black.png'))) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_logo" class="dropify form-control-file" id="site_logo" aria-describedby="fileHelp">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="site_icon" class="col-xs-2 col-form-label">Business Icon</label>
					<div class="col-xs-12">
						@if(Setting::get('site_icon')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{url (Setting::get('site_icon',asset('icon.png'))) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_icon" class="dropify form-control-file" id="site_icon" aria-describedby="fileHelp">
					</div>
				</div>

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">Copyright Content</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" value="{{ Setting::get('site_copyright', '&copy; '.date('Y').' rommoz') }}" name="site_copyright" id="site_copyright" placeholder="Site Copyright">
                    </div>
                </div>

                <div class="form-group row">
					<label for="store_link_user" class="col-xs-2 col-form-label">Playstore User</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_user', '') }}" name="store_link_user"  id="store_link_user" placeholder="Playstore User">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_provider" class="col-xs-2 col-form-label">Playstore Driver</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_provider', '') }}" name="store_link_provider"  id="store_link_provider" placeholder="Playstore Driver">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">Appstore User</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android', '') }}" name="store_link_android"  id="store_link_android" placeholder="Appstore User">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Appstore Driver</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_ios', '') }}" name="store_link_ios"  id="store_link_ios" placeholder="Appstore Driver">
					</div>
				</div>
				
				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Distance Unit</label>
					<div class="col-xs-12">
					    
				<select class="form-control" id="sel1" name="unit">
				 <option value="none">---- Select distance unit----</option>
                 <option value="KM" <?php if(Setting::get('unit') == 'KM' ) echo 'selected' ; ?> >KM</option>
                 <option value="MILES" <?php if(Setting::get('unit') == 'MILES' ) echo 'selected' ; ?>>MILES</option>
                
                </select>
              	</div>
				</div>
				
				<div class="form-group row">
					<label for="provider_select_timeout" class="col-xs-4 col-form-label">Driver Acceptence Timeout</label>
					<div class="col-xs-12">
						<input class="form-control" type="number" value="{{ Setting::get('provider_select_timeout', '60') }}" name="provider_select_timeout" required id="provider_select_timeout" placeholder="Provider Timout">
					</div>
				</div>
				
					<div class="form-group row">
					<label for="contact_email" class="col-xs-2 col-form-label">Waiting Time Out</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('waiting_time_out', '') }}" name="waiting_time_out" required id="waiting_time_out" placeholder="Schedule Request Time">
					</div>
				</div>

				<div class="form-group row">
					<label for="provider_search_radius" class="col-xs-2 col-form-label">Provider Search Radius</label>
					<div class="col-xs-12">
						<input class="form-control" type="number" value="{{ Setting::get('provider_search_radius', '100') }}" name="provider_search_radius" required id="provider_search_radius" placeholder="Provider Search Radius">
					</div>
				</div>

				<div class="form-group row">
					<label for="sos_number" class="col-xs-2 col-form-label">Emergency Number</label>
					<div class="col-xs-12">
						<input class="form-control" type="number" value="{{ Setting::get('sos_number', '911') }}" name="sos_number" required id="sos_number" placeholder="SOS Number">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_number" class="col-xs-2 col-form-label">Contact Number</label>
					<div class="col-xs-12">
						<input class="form-control" type="number" value="{{ Setting::get('contact_number', '911') }}" name="contact_number" required id="contact_number" placeholder="Contact Number">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_email" class="col-xs-2 col-form-label"> Email</label>
					<div class="col-xs-12">
						<input class="form-control" type="email" value="{{ Setting::get('contact_email', '') }}" name="contact_email" required id="contact_email" placeholder="Contact Email">
					</div>
				</div>
				<div class="form-group row">
					<label for="contact_email" class="col-xs-4 col-form-label">Schedule Trigger Time</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('schedule_req_time', '') }}" name="schedule_req_time" required id="schedule_req_time" placeholder="Schedule Request Time">
					</div>
				</div>
				
			   <div class="form-group row">
			   <label for="store_link_ios" class="col-xs-4 col-form-label">Driver Phone Validation</label>
				<div class="col-xs-12">
				<select class="form-control" id="driver_phone" name="driver_phone">
				 <option value="none">---- Select distance unit----</option>
                 <option value="Y" <?php if(Setting::get('driver_phone') == 'Y' ) echo 'selected' ; ?> >Y</option>
                 <option value="N" <?php if(Setting::get('driver_phone') == 'N' ) echo 'selected' ; ?>>N</option>
                </select>
              	</div>
				</div>
				<div class="form-group row">
			   <label for="store_link_ios" class="col-xs-4 col-form-label">Driver Email Validation</label>
				<div class="col-xs-12">
				<select class="form-control" id="driver_email" name="driver_email">
				 <option value="none">---- Select distance unit----</option>
                 <option value="Y" <?php if(Setting::get('driver_email') == 'Y' ) echo 'selected' ; ?> >Y</option>
                 <option value="N" <?php if(Setting::get('driver_email') == 'N' ) echo 'selected' ; ?>>N</option>
                </select>
              	</div>
				</div>
				<div class="form-group row">
			   <label for="store_link_ios" class="col-xs-4 col-form-label">User Phone Validation</label>
				<div class="col-xs-12">
				<select class="form-control" id="user_phone" name="user_phone">
				 <option value="none">---- Select distance unit----</option>
                 <option value="Y" <?php if(Setting::get('user_phone') == 'Y' ) echo 'selected' ; ?> >Y</option>
                 <option value="N" <?php if(Setting::get('user_phone') == 'N' ) echo 'selected' ; ?>>N</option>
                </select>
              	</div>
				</div>
				<div class="form-group row">
			   <label for="store_link_ios" class="col-xs-4 col-form-label">User Email Validation</label>
				<div class="col-xs-12">
				<select class="form-control" id="user_email" name="user_email">
				 <option value="none">---- Select distance unit----</option>
                 <option value="Y" <?php if(Setting::get('user_email') == 'Y' ) echo 'selected' ; ?> >Y</option>
                 <option value="N" <?php if(Setting::get('user_email') == 'N' ) echo 'selected' ; ?>>N</option>
                </select>
              	</div>
				</div>

				<div class="form-group row">
					<label for="contact_email" class="col-xs-4 col-form-label">Ride Cancellation Minute(Second)</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('ride_cancal_min', '') }}" name="ride_cancal_min" required id="ride_cancal_min" placeholder="In Second">
					</div>
				</div>

				<div class="form-group row">
					<label for="contact_email" class="col-xs-4 col-form-label">Ride Cancellation Charges($)</label>
					<div class="col-xs-12">
						<input class="form-control" type="text" value="{{ Setting::get('ride_cancal_chage', '') }}" name="ride_cancal_chage" required id="ride_cancal_chage" placeholder="In $">
					</div>
				</div>
             
				<div class="form-group row">
					<label for="social_login" class="col-xs-2 col-form-label">Chat</label>
					<div class="col-xs-12">
						<select class="form-control" id="chat" name="chat">
							<option value="1" @if(Setting::get('chat', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('chat', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="social_login" class="col-xs-2 col-form-label">Social Login</label>
					<div class="col-xs-12">
						<select class="form-control" id="social_login" name="social_login">
							<option value="1" @if(Setting::get('social_login', 0) == 1) selected @endif>Enable</option>
							<option value="0" @if(Setting::get('social_login', 0) == 0) selected @endif>Disable</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="map_type" class="col-xs-2 col-form-label">Map Type</label>
					<div class="col-xs-12">
						<select class="form-control" id="map_type" name="map_type">
							<option value="1" @if(Setting::get('map_type', 1) == 1) selected @endif>Google</option>
							<option value="2" @if(Setting::get('map_type', 1) == 2) selected @endif>Yandex</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="ad_setting" class="col-xs-2 col-form-label">Advertisement</label>
					<div class="col-xs-12">
						<select class="form-control" id="ad_setting" name="ad_setting">
							<option value="1" @if(Setting::get('ad_setting', 1) == 1) selected @endif>Show</option>
							<option value="2" @if(Setting::get('ad_setting', 1) == 2) selected @endif>Hidden</option>
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="personal_ad" class="col-xs-2 col-form-label">Personal Advertisement</label>
					<div class="col-xs-12">
						@if(Setting::get('personal_ad')!='')
							<img style="height: 90px; margin-bottom: 15px;" src="{{ url(Setting::get('personal_ad')) }}">
						@endif
						<input type="file" accept="image/*" name="personal_ad" class="dropify form-control-file" id="personal_ad" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-12">
						<button type="submit" class="btn btn-success shadow-box">Update</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection
