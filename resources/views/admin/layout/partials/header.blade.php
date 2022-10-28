<!-- Header -->
<?php
$users = get_new_user();
$providers = get_new_provider();?>
<div class="site-header navbar-fixed-top">
   <nav class="navbar navbar-light">
      <div class="container-fluid">
         <div class="col-sm-1 col-xs-1">
            <div class="hamburger hamburger--3dy-r">
               <div class="hamburger-box">
                  <div class="hamburger-inner"></div>
               </div>
            </div>
         </div>
         <div class="col-sm-2 col-xs-2">
            <div class="navbar-left" style="background-color: #fff;">
                <a class="navbar-brand" href="{{url('admin/dashboard')}}" style="background:white;">
                  <div class="logo">
                     <img  style="width: 132px;height: 40px;" src=" {{ asset(Setting::get('site_logo')) }}">
                  </div>
                </a>
                <div class="toggle-button-second dark float-xs-right hidden-md-up">
                  <i class="ti-arrow-left"></i>
                </div>
               <div class="toggle-button dark float-xs-right hidden-md-up" data-toggle="collapse" data-target="#collapse-1">
                  <span class="more"></span>
               </div>
            </div>
         </div>
         <div class="col-sm-9 col-xs-9">
            <div class="navbar-right navbar-toggleable-sm collapse" id="collapse-1">
               <ul class="nav navbar-nav float-md-right">
                  <li class="nav-item dropdown">
                    <a class="nav-link" href="{{ url('/') }}"  target="_blank">
                        Website
                    </a>
                  </li>
               <li class="nav-item dropdown">
               <a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
               <i class="ti-bell"></i>
               <span class="hidden-md-up ml-1">Trip</span>
               <span class="tag tag-success top">1</span>
               </a>
               <div class="dropdown-tasks dropdown-menu dropdown-menu-right animated fadeInUp">
                  
                  <div class="t-item">
                     <div class="mb-0-5">
                        <a class="text-black" href="#">New User Signup</a>
                     </div>
                     <progress class="progress progress-success progress-sm" value="100" max="100">100%</progress>
                     <a class="text-black" href="#">{{ucwords($providers[0]['first_name'])}}</a>
                  </div>
                  </a>
               </div>
            </li>
            <li class="nav-item dropdown">
                
               <a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
               <i class="ti-basketball"></i>
               <span class="hidden-md-up ml-1">Notifications</span>
               <span class="tag tag-danger top">{{$users->count()+$providers->count() }}</span>
               </a>
               
                  
               <div class="dropdown-messages dropdown-tasks dropdown-menu dropdown-menu-right animated fadeInUp">
                   @foreach($users as $user)
                   
                  <div class="m-item">
                     <div class="mi-icon bg-info"><span class="avatar box-32">
               <img src="{{img($user->picture)}}" alt="">
               </span></div>
                     <div class="mi-text"><a class="text-black" href="#">{{ucwords($user->first_name)}}</a> <span class="text-muted">does sign up</span> <a class="text-black" href="#"></a></div>
                     <div class="mi-time">{{$user->created_at->diffForHumans()}}</div>
                  </div>
                  @endforeach
                  @foreach($providers as $provider)
                   
                  <div class="m-item">
                     <div class="mi-icon bg-info"><span class="avatar box-32">
               <img src="{{img($provider->avatar)}}" alt="">
               </span></div>
                     <div class="mi-text"><a class="text-black" href="#">{{ucwords($provider->first_name)}}</a> <span class="text-muted">does sign up</span> <a class="text-black" href="#"></a></div>
                     <div class="mi-time">{{$provider->created_at->diffForHumans()}}</div>
                  </div>
                  @endforeach
            
               </div>
               
            </li>
                  <li class="nav-item dropdown hidden-sm-down">
                     <a href="#" data-toggle="dropdown" aria-expanded="false">
                     <span class="avatar box-32">
                     @if(isset(Auth::guard('admin')->user()->picture))
                     <img src="{{img(Auth::guard('admin')->user()->picture)}}" alt="">
                     @else
                      <img src="{{asset('asset/admin/avatar.jpg')}}" alt="">
                     @endif
                     </span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right animated fadeInUp">
                        <a class="dropdown-item" href="{{route('admin.profile')}}">
                        <i class="ti-user mr-0-5"></i> Profile
                        </a>
                        <a class="dropdown-item" href="{{route('admin.password')}}">
                        <i class="ti-settings mr-0-5"></i> Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        
                        <a class="dropdown-item" href="{{ url('/admin/logout') }}"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();"><i class="ti-power-off mr-0-5"></i> Sign out</a>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </nav>
</div>