<!-- Header -->
<div class="site-header navbar-fixed-top">
	<nav class="navbar navbar-light">
		 <div class="col-sm-1 col-xs-1">
            <div class="hamburger hamburger--3dy-r">
               <div class="hamburger-box">
                  <div class="hamburger-inner"></div>
               </div>
            </div>
         </div>
         <div class="col-sm-2 col-xs-2">
            <div class="navbar-left" style="background-color: #fff;">
               <a class="navbar-brand" href="{{url('cms/dashboard')}}" style="background:white;">
                  <div class="logo">
                     <img  style="width: 132px;height: 40px;" src=" {{ url(Setting::get('site_logo', asset('logo-black.png'))) }}">
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
                 
                  
                  <li class="nav-item dropdown hidden-sm-down">
                     <a href="#" data-toggle="dropdown" aria-expanded="false">
                     <span class="avatar box-32">
                     
                      @if(isset(Auth::guard('admin')->user()->picture))
                     <img src="{{img(Auth::guard('cms')->user()->picture)}}" alt="">
                     @else
                      <img src="{{asset('asset/admin/avatar.jpg')}}" alt="">
                     @endif
                     </span>
                     </a>
                     <div class="dropdown-menu dropdown-menu-right animated fadeInUp">
                         <a class="dropdown-item" href="{{route('cms.profile')}}">
                        <i class="ti-settings mr-0-5"></i> Profile
                        </a>
                        <a class="dropdown-item" href="{{route('cms.password')}}">
                        <i class="ti-settings mr-0-5"></i> Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('/cms/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();"><i class="ti-power-off mr-0-5"></i> Sign out</a>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
	</nav>
</div>