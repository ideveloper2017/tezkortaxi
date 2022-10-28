@extends('user.layout.base')

@section('title', 'Profile ')

@section('content')

<div class="col-md-12" style="margin-bottom: 20px;">

   <div class="dash-content">

      <div class="row no-margin">

         <div class="col-md-12">

            <h4 class="page-title">

               <!--@lang('user.profile.general_information')--><span class="s-icon"><i class="ti-user user-sidebaricon" style="color: rgb(0, 0, 0);"></i></span>&nbsp; @lang('user.profile.profile')

            </h4>

            <h4><a href="{{url('edit/profile')}}" style="float: right;margin-top: -34px;">@lang('user.profile.edit')</a></h4>

         </div>

      </div>

      @include('common.notify')

      <div class="row no-margin">

         <form>

            <div class="col-md-6 pro-form">

               <div class="img_outer">

                  <h5 class="col-md-12 no-padding">


                     <img class="profile_preview" id="profile_image_preview" src="{{img(Auth::user()->picture)}}" alt="your image" style="width: 100px;height: 100px;border-radius: 50%;"/>

                  </h5>

               </div>

            </div>

            <div class="col-md-12 pro-form">

               <h5 class="col-md-12 no-padding">

                  <strong>

                  @lang('user.profile.name')

                  </strong>

                  : {{Auth::user()->first_name}} 

               </h5>

            </div>

          
            <div class="col-md-12 pro-form">

               <h5 class="col-md-12 no-padding"><strong>@lang('user.profile.email')</strong>: {{Auth::user()->email}}</h5>

            </div>

            <div class="col-md-12 pro-form">

               <h5 class="col-md-12 no-padding"><strong>@lang('user.profile.mobile')</strong>: {{Auth::user()->mobile}}</h5>

            </div>

            <div class="col-md-6 pro-form">

               <h5 class="col-md-6 no-padding"><strong>@lang('user.profile.wallet_balance')</strong>: {{currency(Auth::user()->wallet_balance)}}</h5>

            </div>

            <div class="col-md-6 pro-form">

            </div>

         </form>

      </div>

   </div>

</div>

@endsection