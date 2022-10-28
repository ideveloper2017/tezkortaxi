@extends('user.layout.app')


@section('content')
		<div style="background-color: #ddd">
			<div class="container">
					<div class="signin_page">
					<h4>@lang('passengersignin.signin')</h4> 
						<form role="form" style="margin: 40px 10px" method="POST" action="{{ url('/login') }}">
							{{ csrf_field() }}  
							<label>@lang('passengersignin.email')</label>
							<input id="email" name="email" class="form-control" type="text" placeholder="@lang('passengersignin.email')" value="{{ old('email') }}" > 

							@if ($errors->has('email'))
								<span class="help-block">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
							@endif

							<label>@lang('passengersignin.password')</label> 
							<input id="password" name="password" class="form-control" type="password" placeholder="@lang('passengersignin.password')" >

							@if ($errors->has('password'))
								<span class="help-block">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
							@endif

							<div class="facebook_btn">
								<a href="{{ url('provider/auth/facebook') }}"><button value="submit" class="btn btn-default btn-arrow-left">@lang('passengersignin.next')</button>
								<figure><img src="img/btn_arrow.png" alt="img"/></figure></a>
							</div>    
							<p>@lang('passengersignin.dont_account') <a href="{{ url('/register') }}">@lang('passengersignin.sign_up')</a></p>
							<p class="helper"><a href="{{ url('/password/reset') }}">@lang('passengersignin.forgot_pass')</a></p>
							<h4>@lang('passengersignin.sign_by')</h4>
							<hr/>
							
							<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
						<a href="{{ url('/auth/google') }}" onclick="" class="btn btn-lg waves-effect waves-light btn-block google" style="background-color:Red;color:white;">Google+</a>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6">
						<a href="{{ url('/auth/facebook') }}" class="btn btn-lg waves-effect waves-light btn-block facebook" style="background-color:blue;color:white;">Facebook</a>
					</div>
				</div>
						</form>                               
				</div>
			</div>
		</div>

	<script>
		// initialize Account Kit with CSRF protection
		AccountKit_OnInteractive = function(){
			AccountKit.init(
			{
				appId:"210224979836955", 
			        state:"provider", 
			        version:"v1.1",
			        fbAppEventsEnabled:true,
			        redirect:base_url+"/sdkloginsuccess.php"
			}
		     );
		};
		
		// login callback
		function loginCallback(response) {
			if (response.status === "PARTIALLY_AUTHENTICATED") {
			var code = response.code;
			var csrf = response.state;
			
			// Send code to server to exchange for access token
			$('#mobile_verfication').html("<p class='helper'> * Phone Number Verified </p>");
			$('#phone_number').attr('readonly',true);
			$('#country_code').attr('readonly',true);
			$('#second_step').fadeIn(400);
			
			$.post("{{route('account.kit')}}",{ code : code }, function(data){
				$('#phone_number').val(data.phone.national_number);
				$('#country_code').val('+'+data.phone.country_prefix);
			});
			
			}
			else if (response.status === "NOT_AUTHENTICATED") {
				// handle authentication failure
				$('#mobile_verfication').html("<p class='helper'> * Authentication Failed </p>");
			}
			else if (response.status === "BAD_PARAMS") {
				// handle bad parameters
			}
		}
		
		// phone form submission handler
		function smsLogin() {
			var countryCode = document.getElementById("country_code").value;
			var phoneNumber = document.getElementById("phone_number").value;
			
			$('#mobile_verfication').html("<p class='helper'> Please Wait... </p>");
			$('#phone_number').attr('readonly',true);
			$('#country_code').attr('readonly',true);
			
			AccountKit.login(
				'PHONE', 
				{countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
				loginCallback
			);
		}
	</script>
@endsection