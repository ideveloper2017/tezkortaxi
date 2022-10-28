@extends('provider.layout.auth')

@section('content')
            <div class="container">
                <h3>Sign In</h3> 
                    
                    <form role="form" method="POST" action="{{ url('/provider/login') }}">
                       {{ csrf_field() }} 
                        <label>Email</label>
                        <input id="email" type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required > 

                       @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                        <label>Password</label> 
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                       @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                        <div class="facebook_btn">
                            <button value="submit" class="btn btn-default btn-arrow-left">next </button>
                            <figure><img src="../img/btn_arrow.png" alt="img"/></figure>
                        </div>    
                        <p>Don't have an account? <a href="{{ url('/provider/register') }}">sign up</a></p>
                        <p class="helper"><a href="{{ url('/provider/password/reset') }}">Forgot Your Password?</a></p>
                        
                        <h4>or</h4>
                        <div class="facebook_btn">
                            <a href="{{ url('provider/auth/facebook') }}"><button value="submit" class="btn btn-default btn-arrow-left" style="background: #3B5998;">facebook</button></a>
                        <figure><img src="../img/btn_arrow.png" alt="img"/></figure>
                        </div>
 
                        <div class="facebook_btn">
                            <a href="{{ url('provider/auth/google') }}"><button value="submit" class="btn btn-default btn-arrow-left" style="background: #d34836;">Google+</button></a>
                        <figure><img src="../img/btn_arrow.png" alt="img"/></figure>
                        </div>
                    </form>                               
            </div>
@endsection


@section('scripts')

<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
<script>
  // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
    AccountKit.init(
      {
        appId: {{env('FB_APP_ID')}}, 
        state:"state", 
        version: "{{env('FB_APP_VERSION')}}",
        fbAppEventsEnabled:true
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
