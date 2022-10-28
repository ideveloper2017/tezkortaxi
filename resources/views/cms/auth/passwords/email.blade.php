@extends('account.layout.auth')

<!-- Main Content -->
@section('content')
<div>
    <div class="row">
        <div class="col-md-4">
            <div class="box b-a-0" style="top: 132px; box-shadow: none;height: 448px;">
                <div class="p-2 text-xs-center">
                    <h5>Reset Password</h5>
                </div>
                <form class="form-material mb-1" role="form" method="POST" action="{{ url('/account/password/email') }}" >
                {{ csrf_field() }}
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" name="email" value="{{ old('email') }}" required="true" class="form-control" id="email" placeholder="Email">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="px-2 form-group mb-0">
                        <button type="submit" class="btn btn-success shadow-box btn-block waves-effect waves-light btn-lg">Send Password Reset Link</button>
                    </div>
                </form>
                <div class="p-2 text-xs-center text-muted">
                    <a class="text-black" href="{{ url('/account/login') }}"><span class="underline">Login Here!</span></a>
                </div>
            </div>
        </div>
 <div class="col-md-8">
        <img src="{{asset('asset/front_dashboard/img/login.jpg')}}" alt="Admin Panel" style="width: 100%;">
        </div>
    </div>
</div>
@endsection
