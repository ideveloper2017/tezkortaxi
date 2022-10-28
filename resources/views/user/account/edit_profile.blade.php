@extends('user.layout.base')

@section('title', 'Profile ')

@section('content')

<div class="col-md-12" style="margin-bottom: 20px;">

   <div class="dash-content">

      <div class="row no-margin">

         <div class="col-md-12">

            <h4 class="page-title">

              <span class="s-icon"><i class="ti-user user-sidebaricon" style="color: rgb(0, 0, 0);"></i></span>&nbsp; @lang('user.profile.update_profile')

            </h4>

         </div>

      </div>

      @include('common.notify')

      <div class="row no-margin edit-pro">

         <form action="{{url('profile')}}" method="post" enctype="multipart/form-data">

            {{ csrf_field() }}

            <div class="col-md-12">

               <div class="profile-img-blk">

                  <div class="img_outer">


                     <img class="profile_preview" id="profile_image_preview" src="{{img(Auth::user()->picture)}}" alt="your image"/>

                  </div>

                  <div class="fileUpload up-btn profile-up-btn">                   

                     <input type="file" id="profile_img_upload_btn" name="picture" class="upload" accept="image/x-png, image/jpeg"/>

                  </div>

               </div>

            </div>

            <div class="form-group col-md-12">

               <label>

                  @lang('user.profile.name')

               </label>

               <input type="text" class="form-control" name="first_name" required placeholder="@lang('user.profile.first_name')" value="{{Auth::user()->first_name}}">

            </div>

          

            <div class="form-group col-md-12">

               <label>@lang('user.profile.email')</label>

               <input type="email" class="form-control" placeholder="@lang('user.profile.email')" readonly value="{{Auth::user()->email}}">

            </div>

            <div class="form-group col-md-12">

               <label>@lang('user.profile.mobile')</label>

               <input type="text" class="form-control" name="mobile" required placeholder="@lang('user.profile.mobile')" value="{{Auth::user()->mobile}}">

            </div>

            <div class="form-group col-md-12">

               <label>@lang('user.profile.password')</label>

               <input type="text" class="form-control" name="password" type="password" >

            </div>

            <div class="form-group col-md-12">

               <label>@lang('user.profile.confirm_password')</label>

               <input type="text" class="form-control" name="confirm_password" type="password" >

            </div>

            <div class="col-md-12 pull-left">

               <button type="submit" class="form-sub-btn btn-success big box-shadow">@lang('user.profile.save')</button>

            </div>

         </form>

      </div>

   </div>

</div>

@endsection