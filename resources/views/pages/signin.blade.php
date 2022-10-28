@extends('website.app')

@section('content')

		<div class="signup">
        	<div class="signup_box">
            	<h3>{{ Setting::get('site_title') }} | Sign In</h3>
                <div class="container">
                	<div class="row">
                    	<div class="col-md-6">
                        	<div class="driver">
                            	<h5>Driver</h5>
                                <p>Find everything you need to track your success on the road.</p>
                                <div class="driver_signup">
                                	<a  href="/provider/login" class="btn btn-default">Sign in  <figure><img src="img/btn_arrow.png" alt="img"/></figure></a>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        	<div class="driver">
                            	<h5>Passenger</h5>
                                <p>Manage your payment options, review trip history, and more.</p>
                                <div class="driver_signup">
                                	<a  href="/PassengerSignin" class="btn btn-default">sign in <figure><img src="img/btn_arrow.png" alt="img"/></figure></a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection