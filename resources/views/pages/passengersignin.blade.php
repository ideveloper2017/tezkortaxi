@extends('website.app')
@section('content')
<div class="signin_page">
   <div class="container">
      <div class="row">
         <div class="col-md-4" style="margin-top: 35px;">
            <h4>{{ trans('passengersignin.login') }}</h4>
            <form role="form" method="POST" action="{{ url('/login') }}" style="margin-bottom:10px;">
               {{ csrf_field() }}  
               <label>{{ trans('passengersignin.email') }}</label>
               <input id="email" name="email" class="form-control" type="text" placeholder="{{ trans('passengersignin.email') }}" value="{{ old('email') }}" required > 
               @if ($errors->has('email'))
               <span class="help-block">
               <strong>{{ $errors->first('email') }}</strong>
               </span>
               @endif
               <label>{{ trans('passengersignin.password') }}</label> 
               <input id="password" name="password" class="form-control" type="password" placeholder="{{ trans('passengersignin.password') }}" required>
               @if ($errors->has('password'))
               <span class="help-block">
               <strong>{{ $errors->first('password') }}</strong>
               </span>
               @endif
               <div class="facebook_btn">
                     <button value="submit" class="btn btn-default btn-arrow-left" style="background-color: #ec58d6;">{{ trans('passengersignin.next') }} </button>
                     <figure><img src="{{ url('asset/front_dashboard/img/btn_arrow.png') }}" alt="img"/></figure>
               </div>
               <p>{{ trans('passengersignin.dont_account') }} <a href="{{ url('/register') }}">{{ trans('passengersignin.sign_up') }}</a></p>
               <p class="helper"><a href="{{ url('/password/reset') }}">{{ trans('passengersignin.forgot_pass') }}</a></p>
			   	
        
         </form>
			<div class="">
				<a href="{{ url('/auth/facebook') }}"><button type="submit" class="btn btn-default" style="background-color:#3b61ad;">LOGIN WITH FACEBOOK</button></a>
			</div>  
			<div class="">
				<a href="{{ url('/auth/google') }}"><button type="submit" class="btn btn-default" style="background-color:#f37151">LOGIN WITH GOOGLE+</button></a>
			</div>
		@endif -->
		 </div>
         <div class="col-md-8">
            <img src="{{ url('asset/front_dashboard/img/User-Login.png') }}" alt="Dispatcher Panel"> 
         </div>
		 

      </div>
   </div>
</div>
@endsection