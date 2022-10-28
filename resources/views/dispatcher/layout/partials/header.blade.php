<!-- Header -->

<div class="site-header" id="sid_dis_header">
	<nav class="navbar navbar-default" style="border-bottom: 1px solid #dfdfdf">
		<div class="container-fluid">
				<a class="navbar-brand"  href="{{url('/dispatcher')}}" style="padding:0;">
					<img src="{{ url(Setting::get('site_logo')) }}" style="max-width: 100%;vertical-align: top;height: 32px;margin-top: 14px;" />
				</a>
				<?php $url=url()->current();
				      $path = substr($url, strrpos($url, '/') + 1); ?>
				<div class="sid_nav_wrapper">
					<ul class="nav navbar-nav">
					  <li @if($path=='dispatcher') style='background-color: #74d274;' @endif><a href="{{ route('dispatcher.new_booking') }}">New Trip</a></li>
					  <li @if($path=='recent-trips') style='background-color: #74d274;' @endif><a  href="{{ route('dispatcher.index') }}">Recent Trips </a></li>
					  <li @if($path=='map') style='background-color: #74d274;' @endif><a  href="{{ route('dispatcher.map.index') }}">Live  Tracking </a></li>

					  <li class="dropdown">
		               <a class="nav-link" href="#" data-toggle="dropdown" aria-expanded="false">
		               <i class="ti-bell"></i>
		               <span class="hidden-md-up ml-1">Trip</span>
		               <span class="tag tag-success top">1</span>
		               </a>
		               <div class="dropdown-tasks dropdown-menu dropdown-menu-right animated fadeInUp" style="min-width: 188px;">
		                  <div class="t-item">
		                     <div class="mb-0-5">
		                        <a  href="{{ route('dispatcher.openTicket','new') }}">New Ticket </a>
		                     </div>
		                  </div>
		                  <div class="t-item">
		                     <div class="mb-0-5">
		                        <a  href="{{ route('dispatcher.openTicket','open') }}">Open Ticket</a>
		                     </div>
		                  </div>
		                  <div class="t-item">
		                     <div class="mb-0-5">
		                        <a  href="{{ route('dispatcher.closeTicket') }}">Close Ticket </a>
		                     </div>
		                  </div>
		                  
		               </div>
		            </li>
		            
			</ul>
				</div>
			<div class="sid_post_ab">
				<ul id="sid_nav">
					<li class="sid_li">
						<span class="avatar box-32 bg-img" style="background-image: url({{asset('asset/admin/avatar.jpg')}})"></span>
						
						<div class="sid_dropdown animated fadeInUp hd_div">
							<a  href="{{route('dispatcher.profile')}}"><i class="ti-user mr-0-5"></i> Profile </a>
							<a  href="{{route('dispatcher.password')}}"><i class="ti-settings mr-0-5"></i> Change Password </a>
							<div class="dropdown-divider"></div>
							<a  href="{{ url('/dispatcher/logout') }}"
								onclick="event.preventDefault();
										 document.getElementById('logout-form').submit();"><i class="ti-power-off mr-0-5"></i> Sign out</a>
							<form id="logout-form" action="{{ url('/dispatcher/logout') }}" method="POST" style="display: none;">
								{{ csrf_field() }}
							</form>
						</div>
					</li>
					
				</ul>
			</div>

		</div>
	</nav>
</div>