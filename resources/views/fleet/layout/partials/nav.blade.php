<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li>
				<a href="{{ route('fleet.dashboard') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-control-record"></i></span>
					<span class="s-text">Dashboard</span>
				</a>
			</li>
			
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-infinite"></i></span>
					<span class="s-text">Drivers</span>
				</a>
				<ul>
					<li><a href="{{ route('fleet.provider.index') }}">List Drivers</a></li>
					<li><a href="{{ route('fleet.provider.create') }}">Add New Drivers</a></li>
				</ul>
			</li>
			<li>
				<a href="{{ route('fleet.map.index') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-map-alt"></i></span>
					<span class="s-text">Live Tracking</span>
				</a>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-star"></i></span>
					<span class="s-text">Ratings &amp; Reviews</span>
				</a>
				<ul>
					<li><a href="{{ route('fleet.provider.review') }}">Drivers Ratings</a></li>
				</ul>
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-pie-chart"></i></span>
					<span class="s-text">All Rides</span>
				</a>
				<ul>
					<li><a href="{{ url('fleet/onGoingTrip') }}">Ongoing Ride</a></li>
					
					<li><a href="{{ url('fleet/cancelTrip') }}">Cancelled Ride</a></li>
					<li><a href="{{ url('fleet/completedTrip') }}">Completed Ride</a></li>
				</ul>
			</li>
			<li>
				<a href="{{ url('fleet/scheduledTrip') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-timer"></i></span>
					<span class="s-text">Scheduled Rides</span>
				</a>
			</li>
			
			<li>
				<a href="{{ route('fleet.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-user"></i></span>
					<span class="s-text">Account Settings</span>
				</a>
			</li>
			<li>
				<a href="{{ route('fleet.password') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-exchange-vertical"></i></span>
					<span class="s-text">Change Password</span>
				</a>
			</li>
			<li class="compact-hide">
				<a href="{{ url('/fleet/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
					<span class="s-icon"><i class="ti-power-off"></i></span>
					<span class="s-text">Logout</span>
                </a>

                <form id="logout-form" action="{{ url('/fleet/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
			
		</ul>
	</div>
</div>