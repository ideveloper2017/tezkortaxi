@extends('website.app')

@section('content')

<?php  $login_user = asset('asset/img/login-user-bg.jpg'); ?>
<div class="container-fluid" style="margin-bottom: 50px;">
    <div class="col-md-6 log-left">
        <!-- <span class="login-logo"><img src="{{asset('asset/img/logo.png')}}"></span> -->
        <h2>Create your account and get moving in minutes</h2>
        <p>Welcome to {{ Setting::get('site_title', 'Ilyft')  }}, the easiest way to get around at the tap of a button.</p>
    </div>
    <div class="col-md-12">
        
        <h3>Reset Password</h3>
    </div>
        @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
       @endif
    <form role="form" method="POST" action="{{ url('/password/update') }}">
        {{ csrf_field() }}
            <div class="col-md-12" style="margin-top: 10px;">
            <input type="email" class="form-control" name="email" placeholder="Email Address" value="{{ old('email') }}" style="width: 312px;">
            <br/>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif                        
            </div>
            <div class="col-md-12"  style="margin-top: 10px;">
            <input type="password" class="form-control" name="password" placeholder="Password" style="width: 312px;">

                @if ($errors->has('password'))
                 <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="col-md-12" style="margin-top: 10px;">
            <input type="password" placeholder="Re-type Password" class="form-control" name="password_confirmation" style="width: 312px;">

                @if ($errors->has('password_confirmation'))
                 <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                 </span>
                @endif
            </div>   
            <div class="col-md-12" style="margin-top: 10px;">   
            <div class="facebook_btn">
                <button value="submit" class="btn btn-default btn-arrow-left" style="width: 312px;border-radius: 0px;background-color: #5dce5d">RESET PASSWORD</button>
            </div>  
             <h5>Already have account?<a class="log-blk-btn" href="{{url('PassengerSignin')}}">Click Here</a></h5>
        </div>  
    
    </form>     
 </div>




@endsection
