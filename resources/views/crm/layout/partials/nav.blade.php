<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li class="active">
				<a href="{{ route('crm.dashboard') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-control-record"></i></span>
					<span class="s-text">Dashboard</span>
				</a>
			</li> 
			
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-user"></i></span>
					<span class="s-text">Users</span>
				</a>
				<ul>
					<li><a href="{{ route('crm.user.index') }}">All User</a></li>
					<li><a href="{{ route('crm.user.create') }}">Add New</a></li>
				</ul>
			</li>
			    
            <li class="with-sub">
				<a href="{{ route('crm.contact') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-comment-alt"></i></span>
					<span class="s-text">Query</span>
				</a>
			</li>
            <li>
				<a href="{{ route('crm.complaint') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-receipt"></i></span>
					<span class="s-text">Complaint</span>
				</a>  
			</li>
			<li>

				<a href="{{ route('crm.openTicket') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-receipt"></i></span>
					<span class="s-text">Open Ticket</span>
				</a>  
			</li>
			<li>
			    <a href="{{ route('crm.closeTicket') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-agenda"></i></span>
					<span class="s-text">Close Ticket</span>
				</a>  
			</li>
			<!-- <li>
				<a href="{{ route('crm.lost-management') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-receipt"></i></span>
					<span class="s-text">Lost Management</span>
				</a>  
			</li> -->
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-basketball"></i></span>
					<span class="s-text">Driver</span>
				</a>
				<ul>
					<li><a href="{{ route('crm.provider.index') }}">All Driver</a></li>
					<li><a href="{{ route('crm.provider.create') }}">Add New</a></li>
				</ul>
			</li>
			
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-pie-chart"></i></span>
					<span class="s-text">All Rides</span>
				</a>
				<ul>
					<li><a href="{{ url('crm/onGoingTrip') }}">Ongoing Ride</a></li>
					<!-- <li><a href="{{ url('crm/scheduledTrip') }}">Scheduled Ride</a></li> -->
					<li><a href="{{ url('crm/cancelTrip') }}">Cancelled Ride</a></li>
					<li><a href="{{ url('crm/completedTrip') }}">Completed Ride</a></li>
				</ul>
			</li>
			<li>
				<a href="{{ url('crm/scheduledTrip') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-write"></i></span>
					<span class="s-text">Scheduled Ride</span>
				</a>  
			</li>
			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-files"></i></span>
					<span class="s-text">Report</span>
				</a> 
				<ul>

               <li><a href="{{ route('crm.ride.statement') }}">Trip Revenue</a></li>

               <li><a href="{{ route('crm.payment') }}">Trip History</a></li>

               <li><a href="{{ route('crm.ride.statement.provider') }}">Driver Earning</a></li>

               <li><a href="{{ route('crm.ride.statement.today') }}">Daily Revenue</a></li>

               <li><a href="{{ route('crm.ride.statement.monthly') }}">Monthly Revenue</a></li>

               <li><a href="{{ route('crm.ride.statement.yearly') }}">Yearly Revenue</a></li>

            </ul> 
			</li>
			
			<li>
				<a href="{{ route('crm.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-write"></i></span>
					<span class="s-text">Profile</span>
				</a>  
			</li>
			
			<li class="compact-hide" style="margin-bottom: -16px;">
				<a href="{{ url('/crm/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
					<span class="s-icon"><i class="ti-power-off"></i></span>
					<span class="s-text">Logout</span>
                </a>

                <form id="logout-form" action="{{ url('/crm/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
			</li>
			
		</ul>
	</div>
</div>